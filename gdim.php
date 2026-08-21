<?php

class ttfTextOnImage
{  
  // Качество jpg по-умолчанияю
  public   $jpegQuality = 70;      
  
  // Каталог шрифтов
  public   $ttfFontDir   = 'ttf';  
  
  private $ttfFont    = false;
  private $ttfFontSize  = false;
    
  public $hImage      = false;
  private $hColor      = false;

  public function __construct($imagePath)
  {
    if (!is_file($imagePath) || !list(,,$type) = @getimagesize($imagePath)) return false;
        
    switch ($type) 
    {      
      case 1:  $this->hImage = @imagecreatefromgif($imagePath);  break;
      case 2:  $this->hImage = @imagecreatefromjpeg($imagePath);  break;
      case 3:  $this->hImage = @imagecreatefrompng($imagePath);  break;        
      default: $this->hImage = false;
    }
  }
  
  public function __destruct()
  {
    if ($this->hImage) imagedestroy($this->hImage);
  }
  
  /**
   * Устанавливает шрифт
   *
   */  
  public function setFont($font, $size = 14, $color = false, $alpha = false)
  {
    if (!is_file($font) && !is_file($font = $this->ttfFontDir.'/'.$font))
    return false;
    
    $this->ttfFont     = $font;
    $this->ttfFontSize   = $size;
    
    if ($color) $this->setColor($color, $alpha);
  }
  
  /**
   * Пишет текст
   *
   */    
  public function writeText ($x, $y, $text, $angle = 0)
  {
    if (!$this->ttfFont || !$this->hImage || !$this->hColor) return false;
    
    imagettftext(
      $this->hImage, 
      $this->ttfFontSize, $angle, $x, $y + $this->ttfFontSize, 
      $this->hColor, $this->ttfFont, $text);  
  }
	 public function writeText2 ($x1,$x2, $y, $text, $angle = 0)
  {
    if (!$this->ttfFont || !$this->hImage || !$this->hColor) return false;
    @$fontwidth = imagefontwidth($this->ttfFont);
    $fullwidth = strlen($text) * $fontwidth;
    $maxwidth = $x2-$x1;
    $targetwidth = $fullwidth-(4*$fontwidth);
	  
    imagettftext(
      $this->hImage, 
      $this->ttfFontSize, $angle, $x1+($x2-$x1)/ 2 - (strlen($text) * $fontwidth / 2), $y + $this->ttfFontSize, 
      $this->hColor, $this->ttfFont, $text);  
  }
 public function text_center ( $str,  $pad_left, $padTop, $width_text ) 
{	$im=$this->hImage;$font=$this->ttfFont; $font_size=$this->ttfFontSize;
 $textColor=$this->hColor;
    $arr = explode(' ', $str); $ret = "";
    foreach($arr as $word){ 
        $tmp_string = $ret.' '.$word;
        $testbox = imagettfbbox($font_size, 0, $font, $tmp_string);
        if($testbox[2] > $width_text) $ret.=($ret==""?"":"\n").$word; 
        else $ret.=($ret==""?"":" ").$word;
     }
    $arr = explode("\n", $ret);
foreach($arr as $str){
    $testbox = imagettfbbox($font_size, 0, $font, $str);// Размер строки 
    $left_x = round(($width_text - ($testbox[2] - $testbox[0]))/2);
    imagettftext($im, $font_size, 0, $left_x +$pad_left, ($padTop), $textColor, $font, $str);
    $padTop=$padTop+ $font_size*1.5;
    }
}
  /**
   * Форматирует текст (согласно текущему установленному шрифту), 
   * что бы он не вылезал за рамки ($bWidth, $bHeight)
   * Убирает слишком длинные слова
   */
  public function textFormat($bWidth, $bHeight, $text)
  {
    // Если в строке есть длинные слова, разбиваем их на более короткие
    // Разбиваем текст по строкам
    
    $strings   = explode("\n", 
      preg_replace('!([^\s]{24})[^\s]!su', '\\1 ', 
        str_replace(array("\r", "\t"),array("\n", ' '), $text)));        
        
    $textOut   = array(0 => ''); 
    $i = 0;
          
    foreach ($strings as $str)
    {
      // Уничтожаем совокупности пробелов, разбиваем по словам
      $words = array_filter(explode(' ', $str)); 
      
      foreach ($words as $word) 
      {
        // Какие параметры у текста в строке?
        $sizes = imagettfbbox($this->ttfFontSize, 0, $this->ttfFont, $textOut[$i].$word.' ');  
        
        // Если размер линии превышает заданный, принудительно 
        // перескакиваем на следующую строку
        // Иначе пишем на этой же строке
        if ($sizes[2] > $bWidth) $textOut[++$i] = $word.' '; else $textOut[$i].= $word.' '; 
        
        // Если вышли за границы текста по вертикали, то заканчиваем
        if ($i*$this->ttfFontSize >= $bHeight) break(2);
      }
      
      // "Естественный" переход на новую строку 
      $textOut[++$i] = ''; if ($i*$this->ttfFontSize >= $bHeight) break; 
    }
    
    return implode ("\n", $textOut);
  }
   public function textFormat2($bWidth, $bHeight, $text)
  {
    // Если в строке есть длинные слова, разбиваем их на более короткие
    // Разбиваем текст по строкам
    
    $strings   = explode("\n", 
      preg_replace('!([^\s]{24})[^\s]!su', '\\1 ', 
        str_replace(array("\r", "\t"),array("\n", ' '), $text)));        
        
    $textOut   = array(0 => ''); 
    $i = 0;
          
    foreach ($strings as $str)
    {
      // Уничтожаем совокупности пробелов, разбиваем по словам
      $words = array_filter(explode(' ', $str)); 
      
      foreach ($words as $word) 
      {
        // Какие параметры у текста в строке?
        $sizes = imagettfbbox($this->ttfFontSize, 0, $this->ttfFont, $textOut[$i].$word.' ');  
        
        // Если размер линии превышает заданный, принудительно 
        // перескакиваем на следующую строку
        // Иначе пишем на этой же строке
        if ($sizes[2] > $bWidth) $textOut[++$i] = $word.' '; else $textOut[$i].= $word.' '; 
        
        // Если вышли за границы текста по вертикали, то заканчиваем
        if ($i*$this->ttfFontSize >= $bHeight) break(2);
      }
      
      // "Естественный" переход на новую строку 
      $textOut[++$i] = ''; if ($i*$this->ttfFontSize >= $bHeight) break; 
    }
    
    return implode ("|", $textOut);
  }
  /**
   * Устанваливет цвет вида #34dc12
   *
   */
  public function setColor($color, $alpha = false)
  {
    if (!$this->hImage) return false; 
    
    list($r, $g, $b) = array_map('hexdec', str_split(ltrim($color, '#'), 2));
    
    return $alpha === false ? 
      $this->hColor = imagecolorallocate($this->hImage, $r+1, $g+1, $b+1) :
      $this->hColor = imagecolorallocatealpha($this->hImage, $r+1, $g+1, $b+1, $alpha);    
  }
  
  /**
   * Выводит картинку в файл. Тип вывода определяется из расширения.
   *
   */
  public function output ($target, $replace = true)
  {
    if (is_file ($target) && !$replace) return false;
      
    imagewebp($this->hImage, $target,null,80);
    return true;     
  }
	 public function output2 ()
  {
  
            imagejpeg($this->hImage);        
      
  }
	 public function output3 ()
  {
  
            imagepng($this->hImage);        
      
  }
}
?>
