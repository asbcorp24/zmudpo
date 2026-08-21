<?php

class SimpleImage {

  /**
   * @var image
   */
  public $image;

  /**
   * @var string image_type
   */
  public $image_type;

  /**
   * Load image from file.
   */
  public function load($filename) {
    $image_info = getimagesize($filename);
    $this->image_type = $image_info[2];
    if ($this->image_type == IMAGETYPE_JPEG) {
      $this->image = imagecreatefromjpeg($filename);
    }
    elseif ($this->image_type == IMAGETYPE_GIF) {
      $this->image = imagecreatefromgif($filename);
    }
    elseif ($this->image_type == IMAGETYPE_PNG) {
      $this->image = imagecreatefrompng($filename);
    }
     else {
      $this->image = imagecreatefromwebp($filename);
    }
     
  }

  /**
   * Save image to file.
   */
  public function save(
    $filename,
    $image_type = IMAGETYPE_JPEG,
    $compression =40,
    $permissions = NULL
  ) {
    if ($image_type == IMAGETYPE_JPEG) {
      imagejpeg($this->image, $filename, $compression);
    }
    elseif ($image_type == IMAGETYPE_GIF) {
      imagegif($this->image, $filename);
    }
    elseif ($image_type == IMAGETYPE_PNG) {
      imagepng($this->image, $filename);
    }
    if ($permissions != NULL) {
      chmod($filename, $permissions);
    }
  }
  
    public function savewebp(
    $filename) {
    imagewebp($this->image, $filename,80);
  }
    public function savewebpmin($filename) {
    imagewebp($this->image, $filename,60);
  }

  /**
   * Output image to browser.
   */
  public function output($image_type = IMAGETYPE_JPEG) {
    if ($image_type == IMAGETYPE_JPEG) {
      imagejpeg($this->image);
    }
    elseif ($image_type == IMAGETYPE_GIF) {
      imagegif($this->image);
    }
    elseif ($image_type == IMAGETYPE_PNG) {
      imagepng($this->image);
    }
  }

  /**
   * Performs resizing to certain height.
   */
  public function resizeToHeight($height) {
    $ratio = $height / $this->getHeight();
    $width = $this->getWidth() * $ratio;
    $this->resize($width, $height);
  }

  /**
   * Returns image height.
   */
  public function getHeight() {
    return imagesy($this->image);
  }

  /**
   * Returns image width.
   */
  public function getWidth() {
    return imagesx($this->image);
  }

  /**
   * Perform resizing to certain width and height.
   */
  public function resize($width, $height) {
    $new_image = imagecreatetruecolor($width, $height);
    imagecopyresampled(
      $new_image,
      $this->image,
      0,
      0,
      0,
      0,
      $width,
      $height,
      $this->getWidth(),
      $this->getHeight()
    );
    $this->image = $new_image;
  }

  /**
   * Performs resizing to certain width.
   */
  public function addwatemark($x,$y,$string){
	$white2  = imagecolorallocatealpha( $this->image, 255,255, 255,70);
	 $sz= $this->getHeight()/20;
	  $sz1= $this->getHeight()/10;
//imagettftext ( $this->image, $sz, 0, $x, $sz, $white2, "times.ttf",$sz1 );  //imagesx($logoImage),imagesy($logoImage)
	 // sleep(3);
	  $logoImage = ImageCreateFromPNG("http://chart.apis.google.com/chart?cht=qr&chs=".round($sz1)."x".round($sz1)."&chl=".urlencode($string));
	  imagecopymerge($this->image, $logoImage, 10, 20, 0, 0,   $sz1+1, $sz1+1,80);
  }
	
	public function resizeToWidth($width) {
	  if ( $this->getWidth()<=$width)$width=$this->getWidth();
    $ratio = $width / $this->getWidth();
    $height = $this->getheight() * $ratio;
    $this->resize($width, $height);
  }
	
	
	 public function addtext($string){
		$white = imagecolorallocate($this->image, 255, 255, 255);

$text = ($string);;
// Замена пути к шрифту на пользовательский
$font = '17253.ttf';

// Тень
imagettftext($this->image, 30, 0, 10,$this->getheight()-80,$white, $font, $text);
  }
	
public function adddata(){
	
	$white = imagecolorallocate($this->image, 255, 255, 255);

$text = date("Y-m-d H:i:s");;
// Замена пути к шрифту на пользовательский
$font = '17253.ttf';

// Тень
imagettftext($this->image, 30, 0, 11,  $this->getheight()-20,$white, $font, $text);
	
}
  /**
   * Scale image with coefficient.
   */
  public function scale($scale) {
    $width = $this->getWidth() * $scale / 100;
    $height = $this->getheight() * $scale / 100;
    $this->resize($width, $height);
  }
}

