<?php


 require_once('Connections/testmed.php'); 
include('gdim.php');
?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists(" /* fixed MMiC */ DB::escape") ?  /* fixed MMiC */ DB::escape($theValue) :  /* fixed MMiC */ DB::escape($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

if (isset($_GET['spec'])){
$query_spec = "SELECT * FROM tm_arh_spec WHERE num=".$_GET['spec'];
$spec2 =  /* fixed MMiC */ DB::Query($query_spec, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_spec0 =  /* fixed MMiC */ mysqli_fetch_assoc($spec2);
	
	
}


if (isset($_GET['stud'])){

	
	$query_spec = "SELECT 
  `tm_user`.`fio`,
  `tm_spec`.`nazv`,`tm_spec`.`chas`
FROM
  `tm_user`
  INNER JOIN `tm_spec` ON (`tm_user`.`spec` = `tm_spec`.`num`)
WHERE
  `tm_user`.`num` =".$_GET['stud'];
$stud =  /* fixed MMiC */ DB::Query($query_spec, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_stud =  /* fixed MMiC */ mysqli_fetch_assoc($stud);

	

	
}

function imagestringcutted($img,$fonts,$y,$x1,$x2,$text,$color,$align="center") {
    $fontwidth = imagefontwidth($font);
    $fullwidth = strlen($text) * $fontwidth;
    $maxwidth = $x2-$x1;
    $targetwidth = $fullwidth-(4*$fontwidth);
    $font="times.ttf";
     //imagestring($img,$font,$x1+($x2-$x1)/ 2 - (strlen($text) * $fontwidth / 2),$y,$text,$color);
	imagettftext($img, $fonts, 0, $x1+($x2-$x1)/ 2 - (strlen($text) * $fontwidth / 2), $y, $color, $font, $text);
}

//$textcolor = imagecolorallocate($im, 0, 0, 255);

// Надпись в левом верхнем углу
//imagestringcutted($img,30,250,800,1500,$row_stud['fam']." ".$row_stud['name']." ".$row_stud['otch'],0);
//imagestring($img, 5, 990,194, $row_stud['fam']."dddd", $textcolor);
//imagestringcutted($img,20,450,800,1500,textFormat(700,300,' ГОСУДАРСТВЕННОЕ АВТОНОМНОЕ ПРОФЕССИОНАЛЬНОЕ ОБРАЗОВАТЕЛЬНОЕ УЧРЕЖДЕНИЕ "ЗЕЛЕНОДОЛЬСКОЕ МЕДИЦИНСКОЕ УЧИЛИЩЕ"'),0);

$ttfImg = new ttfTextOnImage('log3.jpg');
      
// Пишем шрифтом Scrawn размером 64 пункта бордовым цветом с 80%-ой прозрачностью 
$ttfImg->setFont('./timesbd.ttf', 125, "#576860", 0);      
$ttfImg->text_center("Сертификат".$row_stud['num'],350,700,680); 



$ttfImg->setFont('./times.ttf', 40, "#576860", 0);      
$ttfImg->text_center("Настоящий сертификат свидетельствует о том, что",130,900,1200);
$ttfImg->setFont('./times.ttf', 50, "#576860", 0);    
$message = $ttfImg->textFormat(1200, 500, 
 $row_stud['fio']);
$ttfImg->text_center($message,1,1000,1500);
// Шрифтом Constantin размером 15 пунктов оранжевым цветом с 90%-ой прозрачностью 

//$ttfImg->setFont('times.ttf', 20, "#000000", 0);      
//$ttfImg->text_center("Прошел(а) повышение квалификации в(на)",1300,400,800);


$ttfImg->setFont('./times.ttf', 20, "#576860", 0); 
$ttfImg->text_center($row_stud['protocol'],600,480,900);


$ttfImg->setFont('./times.ttf', 40, "#576860", 0);      
$ttfImg->text_center("Прошел(ла) обучение по программе",170,1200,1200);

$ttfImg->setFont('./times.ttf',50, "#576860", 0); 
$message = $ttfImg->textFormat(1200, 500, 
 $row_stud['nazv']);
$ttfImg->text_center($message,1,1300,1570);


$ttfImg->setFont('./times.ttf',30, "#576860", 0); 
$message = $ttfImg->textFormat(400, 500, 
 'Директор ГАОУ СПО "Зеленодольское медицинское училище"');
$ttfImg->text_center($message,80,1600,500);


$ttfImg->setFont('./times.ttf',30, "#576860", 0); 
$message = $ttfImg->textFormat(500, 500, 
 'Р.В. Латыпов');
$ttfImg->text_center($message,900,1600,500);


$ttfImg->setFont('./times.ttf', 40, "#576860", 0); 
$ttfImg->text_center('В объеме '.$row_stud['chas'].' ч.',10,1400,1500);

//$ttfImg->setFont('timesi.ttf', 20, "#000000", 0);
//$ttfImg->writeText(600,1400,"Руководитель",0); 
//$ttfImg->writeText(600,1450,"Секретарь",0); 

// накладываем копия верна
$stamp = imagecreatefrompng('Stamp-10.png');
$xx=rand(100,400);
$yy=rand(300,400);
//$stamp = imagerotate($stamp, 20, 0);
imagecopy($ttfImg->hImage, $stamp, $xx, $yy, 0, 0, imagesx($stamp), imagesy($stamp));
$ttfImg->setFont('alix2_regular.ttf', 15, "#576860", 0);
$ttfImg->writeText($xx+45,$yy+80, date('j'),0); 
$ttfImg->writeText($xx+100,$yy+80, date('m'),0); 
$ttfImg->writeText($xx+185,$yy+80, date('y'),0); 

$stamp = imagecreatefrompng('pech.png');
$xx=500;
$yy=1500;
//$stamp = imagerotate($stamp, 20, 0);
imagecopy($ttfImg->hImage, $stamp, $xx, $yy, 0, 0, imagesx($stamp), imagesy($stamp));



$stamp = imagecreatefrompng('podp2.png');
$xx=800;
$yy=1500;
//$stamp = imagerotate($stamp, 20, 0);
imagecopy($ttfImg->hImage, $stamp, $xx, $yy, 0, 0, imagesx($stamp), imagesy($stamp));


$ttfImg->setFont('timesbd.ttf', 35, "#bf0528", 0);      
$ttfImg->text_center("771800986".$row_stud['num'],350,800,680); 


header('Content-Type: image/jpeg');
if (isset($_GET['pr'])){ header('Content-Disposition: attachment; filename=квалификация.jpg' );}
$ttfImg->output2();

//imagejpeg($img);
//imagedestroy($img);
?>