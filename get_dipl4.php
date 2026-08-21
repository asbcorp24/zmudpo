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
  `ts_arh_stud`.`fam`,
  `ts_arh_stud`.`name`,
  `ts_arh_stud`.`otch`,
  `ts_arh_stud`.`num`,
  `ts_arh_stud`.`itog_rab`,
  `ts_arh_stud`.`crasn_reg`,
  `ts_arh_stud`.`inn`,
  `ts_arh_stud`.`nreg`,
  `ts_arh_stud`.`datav`
FROM
  `ts_arh_stud`
WHERE
  `ts_arh_stud`.`num` =".$_GET['stud'];
$stud =  /* fixed MMiC */ DB::Query($query_spec, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_stud =  /* fixed MMiC */ mysqli_fetch_assoc($stud);

	
	$query_spec = "SELECT 
  `tm_arh_ball`.`nazv`,
  `tm_arh_ball`.`ball`,
  `tm_arh_ball`.`chas`
FROM
  `tm_arh_ball`
WHERE
  `tm_arh_ball`.`inn` =".$_GET['stud'];
$ball =  /* fixed MMiC */ DB::Query($query_spec, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_ball =  /* fixed MMiC */ mysqli_fetch_assoc($ball);
$totalRows_ball =  /* fixed MMiC */ mysqli_num_rows($ball);

	$query_spec = "SELECT * FROM `tm_arh_dop_sv` WHERE typ=1 and `tm_arh_dop_sv`.`inn`=".$_GET['stud'];
$dop =  /* fixed MMiC */ DB::Query($query_spec, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_dop =  /* fixed MMiC */ mysqli_fetch_assoc($dop);
$totalRowsdop =  /* fixed MMiC */ mysqli_num_rows($dop);

//////////////////////
	$query_spec = "SELECT * FROM `tm_arh_dop_sv` WHERE typ=2 and `tm_arh_dop_sv`.`inn`=".$_GET['stud'];
$dop2 =  /* fixed MMiC */ DB::Query($query_spec, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_dop2 =  /* fixed MMiC */ mysqli_fetch_assoc($dop2);
$totalRowsdop2 =  /* fixed MMiC */ mysqli_num_rows($dop2);

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

$ttfImg = new ttfTextOnImage('pril.jpg');
      
// Пишем шрифтом Scrawn размером 64 пункта бордовым цветом с 80%-ой прозрачностью 

//$ttfImg->setFont('times.ttf', 25, "#e6878b", 0);      

//$ttfImg->text_center("771800986".$row_stud['num'],180,830,800); 


 $ttfImg->setFont('timesbd.ttf', 20, "#000000", 0);
$ttfImg->text_center($row_stud['nreg'],700,610,800);
$ttfImg->setFont('times.ttf', 20, "#000000", 0); 
//$ttfImg->text_center("город",180,1150,800);
// $ttfImg->setFont('timesbd.ttf', 20, "#000000", 0);
//$ttfImg->text_center("Зеленодольск",180,1200,800);


 $ttfImg->setFont('timesbd.ttf', 20, "#000000", 0);
$ttfImg->text_center($row_stud['datav'],700,690,800);

 $ttfImg->setFont('timesbd.ttf', 20, "#000000", 0);
$ttfImg->text_center($row_stud['datav'],700,870,800);

$ttfImg->setFont('times.ttf', 30, "#000000", 0);      
$ttfImg->text_center($row_stud['fam']." ".$row_stud['name']." ".$row_stud['otch'],100,380,800);
// Шрифтом Constantin размером 15 пунктов оранжевым цветом с 90%-ой прозрачностью 
$ttfImg->setFont('times.ttf', 25, "#000000", 0);      
$ttfImg->text_center($row_dop['valdat'],100,428,800);
 
 
$message = $ttfImg->textFormat(800, 200, 
 $row_dop2['valstr']); 
$ttfImg->setFont('times.ttf', 25, "#000000", 0);      
$ttfImg->text_center($message,100,518,800);



$ttfImg->setFont('times.ttf', 20, "#000000", 0);      

$message = $ttfImg->textFormat(200, 200, 
 'ГОСУДАРСТВЕННОE АВТОНОМНОE ПРОФЕССИОНАЛЬНОE ОБРАЗОВАТЕЛЬНОE УЧРЕЖДЕНИE "ЗЕЛЕНОДОЛЬСКОЕ МЕДИЦИНСКОЕ УЧИЛИЩЕ"');
$ttfImg->text_center($message,700,220,800);

//$message = $ttfImg->textFormat(800, 200, 
// 'ГОСУДАРСТВЕННОЕ АВТОНОМНОЕ ПРОФЕССИОНАЛЬНОЕ ОБРАЗОВАТЕЛЬНОЕ УЧРЕЖДЕНИЕ "ЗЕЛЕНОДОЛЬСКОЕ МЕДИЦИНСКОЕ УЧИЛИЩЕ"');
//$ttfImg->text_center($message,20,450,800);


$ttfImg->setFont('times.ttf', 20, "#000000", 0); 
$ttfImg->text_center("c ".$row_spec0['din']." по ".$row_spec0['dout'],1300,680,800);


$ttfImg->setFont('times.ttf', 20, "#000000", 0); 
$message = $ttfImg->textFormat(200, 200, 
 $row_spec0['specs']);

$ttfImg->text_center($message,720,980,800);
// $ttfImg->text_center("в объеме ".$row_spec0['chas']." часов",1300,900,800);
if ($totalRows_ball>0){


$dd=0;
 do { 
	 $dd+=70;$message = $ttfImg->textFormat(500, 200, 
 $row_ball['nazv']);
    $ttfImg->writeText(100,785+$dd,$message,0);
//	 imagerectangle($ttfImg->hImage, 1280, 900+$dd-20, 1800, 900+$dd+70-20, 0);
	 $ttfImg->writeText(630,785+$dd,$row_ball['chas'],0); //imagerectangle($ttfImg->hImage, 1800, 900+$dd-20, 1950, 900+$dd+70-20, 0);
	 if ($row_ball['ball']==6) 	 $ttfImg->writeText(750,785+$dd,"Зачтено",0); 
	 	 if ($row_ball['ball']==5) 	 $ttfImg->writeText(750,785+$dd,"Отлично",0); 
	 	 if ($row_ball['ball']==4) 	 $ttfImg->writeText(750,785+$dd,"Хорошо",0); 
	 	 if ($row_ball['ball']==3) 	 $ttfImg->writeText(750,785+$dd,"Удовлетворительно",0); 
	 
	// imagerectangle($ttfImg->hImage, 1950, 900+$dd-20, 2150, 900+$dd+70-20, 0);
      } while ($row_ball =  /* fixed MMiC */ mysqli_fetch_assoc($ball)); 
}
$ttfImg->setFont('timesi.ttf', 20, "#000000", 0);
$ttfImg->writeText(1500,1400,"Руководитель",0); 
$ttfImg->writeText(1500,1450,"Секретарь",0); 

// накладываем копия верна
$stamp = imagecreatefrompng('Stamp-09.png');
$xx=rand(100,100);
$yy=rand(100,100);
//$stamp = imagerotate($stamp, 20, 0);
imagecopy($ttfImg->hImage, $stamp, $xx, $yy, 0, 0, imagesx($stamp), imagesy($stamp));
$ttfImg->setFont('alix2_regular.ttf', 20, "#000000", 0);
$ttfImg->writeText($xx+70,$yy+120, date('j'),0); 
$ttfImg->writeText($xx+160,$yy+120, date('m'),0); 
$ttfImg->writeText($xx+275,$yy+120, date('y'),0); 





$stamp = imagecreatefrompng('podp2.png');
$xx=1100;
$yy=1180;
imagecopy($ttfImg->hImage, $stamp, $xx, $yy, 0, 0, imagesx($stamp), imagesy($stamp));
$stamp = imagecreatefrompng('podp2.png');
$xx=1100;
$yy=1260;
//$stamp = imagerotate($stamp, 20, 0);
imagecopy($ttfImg->hImage, $stamp, $xx, $yy, 0, 0, imagesx($stamp), imagesy($stamp));
$stamp = imagecreatefrompng('p_d.png');
$xx=1100;
$yy=1350;
//$stamp = imagerotate($stamp, 20, 0);
imagecopy($ttfImg->hImage, $stamp, $xx, $yy, 0, 0, imagesx($stamp), imagesy($stamp));

$stamp = imagecreatefrompng('pech.png');
$xx=890;
$yy=1350;
//$stamp = imagerotate($stamp, 20, 0);
imagecopy($ttfImg->hImage, $stamp, $xx, $yy, 0, 0, imagesx($stamp), imagesy($stamp));
header('Content-Type: image/jpeg');
if (isset($_GET['pr'])){ header('Content-Disposition: attachment; filename=квалификация.jpg' );}
$ttfImg->output2();

//imagejpeg($img);
//imagedestroy($img);
?>