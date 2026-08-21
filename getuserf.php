<?php require_once('Connections/testmed.php'); 
include('gdim.php');

?>
<?php
  function getExtension1($filename) {
    return end(explode(".", $filename));
  }

$num=(int)$_GET['num'];
$query_test="
  SELECT 
  `tm_nmo_user_file`.`num`,
  `tm_nmo_user_file`.`user`,
  `tm_nmo_user_file`.`tip`,
  `tm_nmo_user_file`.`path`,
  `tm_nmo_user_file`.`comment`,
  `tm_nmo_user_file`.`inn`
FROM
  `tm_nmo_user_file`
WHERE
   `tm_nmo_user_file`.`num` = $num";
//echo $query_test;
$test =  /* fixed MMiC */ DB::Query($query_test, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_test =  /* fixed MMiC */ mysqli_fetch_assoc($test);
$totalRows_test =  /* fixed MMiC */ mysqli_num_rows($test);




/**
 * @version 0.1
 * @author recens
 * @license GPL
 * @copyright Гельтищева Нина (http://recens.ru)
 */
 
/**
* Масштабирование изображения
*
* Функция работает с PNG, GIF и JPEG изображениями.
* Масштабирование возможно как с указаниями одной стороны, так и двух, в процентах или пикселях.
*
* @param string Расположение исходного файла
* @param string Расположение конечного файла
* @param integer Ширина конечного файла
* @param integer Высота конечного файла
* @param bool Размеры даны в пискелях или в процентах
* @return bool
*/
$file_input = $_GET['fname'];

$w_o = "650";
function resize($file_input, $w_o, $h_o, $percent = false) {
//		echo $file_input;
   // list($w_i, $h_i, $type) = getimagesize($file_input);
    
   // if (!$w_i || !$h_i) {
  //      echo 'Невозможно получить длину и ширину изображения';
  //      return;
 //   }
 //   $types = array('','gif','jpeg','png','webp');
 //   $ext = $types[$type];
//    if ($ext) {
  //      $func = 'imagecreatefrom'.$ext;
  ///      $img = $func($file_input);
   // } else {
   //     echo 'Некорректный формат файла';
   //     return;
   // }
   if (getExtension1($file_input)=='jpg')  $img = imagecreatefromjpeg($file_input); else
     $img = imagecreatefromwebp($file_input);
   
     $w_i = imagesx($img);
$h_i = imagesy($img);
    if ($percent) {
        $w_o *= $w_i / 100;
        $h_o *= $h_i / 100;
    }
     
    if (!$h_o) $h_o = $w_o/($w_i/$h_i);
    if (!$w_o) $w_o = $h_o/($h_i/$w_i);
  
    $img_o = imagecreatetruecolor($w_o, $h_o);
    imagecopyresampled($img_o, $img, 0, 0, 0, 0, $w_o, $h_o, $w_i, $h_i);
    return imagejpeg($img_o);
} 
   
 
/**
* Обрезка изображения
*
* Функция работает с PNG, GIF и JPEG изображениями.
* Обрезка идёт как с указанием абсоютной длины, так и относительной (отрицательной).
*
* @param string Расположение исходного файла
* @param string Расположение конечного файла
* @param array Координаты обрезки
* @param bool Размеры даны в пискелях или в процентах
* @return bool
*/
function crop($file_input, $file_output, $crop = 'square',$percent = false) {

    list($w_i, $h_i, $type) = getimagesize($file_input);
    if (!$w_i || !$h_i) {
        echo 'Невозможно получить длину и ширину изображения';
        return;
    }
    $types = array('','gif','jpeg','png');
    $ext = $types[$type];
    if ($ext) {
        $func = 'imagecreatefrom'.$ext;
        $img = $func($file_input);
    } else {
        echo 'Некорректный формат файла';
        return;
    }
    if ($crop == 'square') {
        $min = $w_i;
        if ($w_i > $h_i) $min = $h_i;
        $w_o = $h_o = $min;
    } else {
        list($x_o, $y_o, $w_o, $h_o) = $crop;
        if ($percent) {
            $w_o *= $w_i / 100;
            $h_o *= $h_i / 100;
            $x_o *= $w_i / 100;
            $y_o *= $h_i / 100;
        }
        if ($w_o < 0) $w_o += $w_i;
        $w_o -= $x_o;
        if ($h_o < 0) $h_o += $h_i;
        $h_o -= $y_o;
    }
    $img_o = imagecreatetruecolor($w_o, $h_o);
    imagecopy($img_o, $img, 0, 0, $x_o, $y_o, $w_o, $h_o);
    if ($type == 2) {
        return imagejpeg($img_o);
    } else {
        $func = 'image'.$ext;
        return $func($img_o,$file_output);
    }
}
 
 
 
 
 
?>
<?php
if ($row_test['tip']==2){
	header('Content-type:image/jpg');
resize('usrimg/'.$row_test['path'],600,null)	;
	
} else
{
header('Content-type:image/png');
$string=$row_test['comment']; // строка
	//echo $string;
//$string=urldecode($_SERVER***91;'QUERY_STRING'***93;);
$ttfImg = new ttfTextOnImage('bgblack.png');



$ttfImg->setFont('./times.ttf',10, "#0F0F0D", 0); 
$message = $ttfImg->textFormat2(800, 800, 
$string);
	$message = str_replace("||", "|",$message);	$message = str_replace("||", "|",$message);
	$message = str_replace("|", "\n",$message);
//echo '<pre>'.$message.'</pre>';exit;	
$ttfImg->writeText(10,10,$message);
$ttfImg->output3();
	
}

?>