<?php 
include('gdim.php');
if (!isset($_SESSION)) {
  session_start();
}





if (isset($_GET['num'])){
require_once('Connections/testmed.php'); 
 //mysqli_select_db(DB::$link, $testmed);
	$query_spec = "SELECT 
  `tm_nmo_sert_test`.`nazv`,
  `tm_nmo_sert_test`.`text`,
  `tm_nmo_sert_test`.`chas`,`tm_user`.`num`,
  `tm_user`.`fio`,
  `tm_nmo_razd_media`.`num` as rnum,`tm_nmo_razd_user`.`dat`,`tm_nmo_razd_media`.`dop_file` as dpf
FROM
  `tm_nmo_sert_test`
  INNER JOIN `tm_nmo_razd_media` ON (`tm_nmo_sert_test`.`media` = `tm_nmo_razd_media`.`id`)
  INNER JOIN `tm_nmo_razd_user` ON (`tm_nmo_razd_media`.`id` = `tm_nmo_razd_user`.`razdel`)
  INNER JOIN `tm_user` ON (`tm_nmo_razd_user`.`user` = `tm_user`.`num`)

WHERE
  `tm_nmo_sert_test`.`media` = ".intval($_GET['num'])." AND 
  `tm_nmo_razd_user`.`user` =".$_SESSION['MM_Username1'];
  
  
$stud =   DB::Query($query_spec) or die(  mysqli_error(DB::$link));
$row_stud =  mysqli_fetch_assoc($stud);


}
// /$ttfImg = new ttfTextOnImage('bg2020.jpg');

if (strlen ($row_stud['dpf'])>2) $ttfImg = new ttfTextOnImage('nmo/'.$row_stud['dpf']); else 
//if (!is_null($row_stud['dpf'])) $ttfImg = new ttfTextOnImage('nmo/'.$row_stud['dpf']); else 

$ttfImg = new ttfTextOnImage('bg2020.jpg');

 function addwatemark($x,$y,$string){

  }	
  
/*
echo $row_stud['nazv'];exit;
function imagestringcutted($img,$fonts,$y,$x1,$x2,$text,$color,$align="center") {
    $fontwidth = imagefontwidth($font);
    $fullwidth = strlen($text) * $fontwidth;
    $maxwidth = $x2-$x1;
    $targetwidth = $fullwidth-(4*$fontwidth);
    $font="times.ttf";
     //imagestring($img,$font,$x1+($x2-$x1)/ 2 - (strlen($text) * $fontwidth / 2),$y,$text,$color);
	imagettftext($img, $fonts, 0, $x1+($x2-$x1)/ 2 - (strlen($text) * $fontwidth / 2), $y, $color, $font, $text);
}*/

//$textcolor = imagecolorallocate($im, 0, 0, 255);

// Надпись в левом верхнем углу
//imagestringcutted($img,30,250,800,1500,$row_stud['fam']." ".$row_stud['name']." ".$row_stud['otch'],0);
//imagestring($img, 5, 990,194, $row_stud['fam']."dddd", $textcolor);
//imagestringcutted($img,20,450,800,1500,textFormat(700,300,' ГОСУДАРСТВЕННОЕ АВТОНОМНОЕ ПРОФЕССИОНАЛЬНОЕ ОБРАЗОВАТЕЛЬНОЕ УЧРЕЖДЕНИЕ "ЗЕЛЕНОДОЛЬСКОЕ МЕДИЦИНСКОЕ УЧИЛИЩЕ"'),0);
$ttfImg->setFont('./timesbd.ttf', 25, "#576860", 0);      
$message = $ttfImg->textFormat(1000, 300, 
'Государственное автономное профессиональное образовательное учреждение "Зеленодольское медицинское училище"');
$ttfImg->text_center($message,10,190,1920); 

    
// Пишем шрифтом Scrawn размером 64 пункта бордовым цветом с 80%-ой прозрачностью 
$ttfImg->setFont('./timesbd.ttf', 70, "#576860", 0);      
$ttfImg->text_center($row_stud['nazv'],10,350,1920); 

$ttfImg->setFont('timesbd.ttf', 35, "#bf0528", 0);      
$ttfImg->text_center("2020".$row_stud['num'].$row_stud['num'],10,430,1900); 
$ttfImg->setFont('timesbd.ttf', 16, "#576860", 0);      
$ttfImg->text_center("Регистрационный номер",10,455,1900); 

$ttfImg->setFont('./times.ttf', 40, "#576860", 0);      
$ttfImg->text_center("подтверждает что",10,500,1920); 
$ttfImg->setFont('./timesbd.ttf', 40, "#576860", 0);      
$ttfImg->text_center($row_stud['fio'],10,600,1920); 
$ttfImg->setFont('./times.ttf', 35, "#576860", 0);      
$ttfImg->text_center( date("Y.m.d", strtotime($row_stud['dat'])),10,1050,1920); 
//////////.


$sz1=300;	
//imagettftext ( $this->image, $sz, 0, $x, $sz, $white2, "times.ttf",$sz1 );  //imagesx($logoImage),imagesy($logoImage)
	 // sleep(3);
	  $logoImage = ImageCreateFromPNG("http://chart.apis.google.com/chart?choe=UTF-8&child=H&cht=qr&chs=".round($sz1)."x".round($sz1)."&chl=".urlencode("http://zmudpo.ru/get_sert.php?sert=".$row_stud['num'].$row_stud['num']));
	  imagecopymerge($ttfImg->hImage, $logoImage, 10, 20, 0, 0,   $sz1+1, $sz1+1,80);
////////////

$ttfImg->setFont('./times.ttf', 40, "#576860", 0);    
$message = $ttfImg->textFormat(1550, 500, 
 $row_stud['text']);
$ttfImg->text_center($message,10,670,1920);


$ttfImg->text_center("В количестве ".$row_stud['chas']." часов",10,950,1920); 

$ttfImg->setFont('./times.ttf',30, "#576860", 0); 
$message = $ttfImg->textFormat(500, 500, 
 'Семенова В.С.');
$ttfImg->text_center($message,900,1600,500);


$ttfImg->setFont('./times.ttf',30, "#576860", 0); 
$message = $ttfImg->textFormat(500, 300, 
 'Директор ГАПОУ  "Зеленодольское медицинское училище"');
$ttfImg->text_center($message,180,1100,500);

$stamp = imagecreatefrompng('podp2.png');
$xx=900;
$yy=1000;
//$stamp = imagerotate($stamp, 20, 0);
imagecopy($ttfImg->hImage, $stamp, $xx, $yy, 0, 0, imagesx($stamp), imagesy($stamp));


$stamp = imagecreatefrompng('pech.png');
$xx=700;
$yy=950;
//$stamp = imagerotate($stamp, 20, 0);
imagecopy($ttfImg->hImage, $stamp, $xx, $yy, 0, 0, imagesx($stamp), imagesy($stamp));

$ttfImg->setFont('./times.ttf',30, "#576860", 0); 
$message = $ttfImg->textFormat(500, 500, 
 'Семенова В.С. ');
$ttfImg->text_center($message,1200,1100,500);




// Шрифтом Constantin размером 15 пунктов оранжевым цветом с 90%-ой прозрачностью 
/*  
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




*/
header('Content-Type: image/jpeg');
{ header('Content-Disposition: attachment; filename=квалификация.jpg' );}
$ttfImg->output('./arh/'.$row_stud['num'].$row_stud['num'].'.webp',false);
$ttfImg->output2();

//imagejpeg($img);
//imagedestroy($img);
?>