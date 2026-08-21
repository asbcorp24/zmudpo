<?php




$sql="SELECT 
  `tm_arh_diplom`.`num`,
  `tm_spec`.`sert`,
  `tm_sert`.`path`
FROM
  `tm_spec`
  INNER JOIN `tm_sert` ON (`tm_spec`.`sert` = `tm_sert`.`num`)
  INNER JOIN `tm_user` ON (`tm_user`.`spec` = `tm_spec`.`num`)
  INNER JOIN `tm_arh_diplom` ON (`tm_arh_diplom`.`sfio` = `tm_user`.`num`)
WHERE
  `tm_arh_diplom`.`sfio` = $username_test";

$sps=DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_tsps =  /* fixed MMiC */ mysqli_fetch_assoc($sps);
$tsps =  /* fixed MMiC */ mysqli_num_rows($sps);

if ($tsps<1){
$sql="SELECT 
 
  `tm_spec`.`sert`,
  `tm_sert`.`path`
FROM
  `tm_spec`
  INNER JOIN `tm_sert` ON (`tm_spec`.`sert` = `tm_sert`.`num`)
  
WHERE
  `tm_spec`.`num` = $colname_test";
$spsa=DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_tspsa =  /* fixed MMiC */ mysqli_fetch_assoc($spsa);
$tspsa =  /* fixed MMiC */ mysqli_num_rows($spsa);	
if ($tspsa<1)$row_tspsa['path']='get_dipl2.php';
	
	$url='http://'.$_SERVER['SERVER_NAME']."/sert/".$row_tspsa['path']."?stud=$username_test";//./sert/get_dipl2.php?stud=$username_test
//echo $url;
 $im = imagecreatefromjpeg($url);
//header('Content-Type: image/jpeg');

$filenameimg =uniqid().'.webp';
$sql="INSERT INTO tm_arh_diplom (spec,fio,path,god,sfio) SELECT 
   `tm_spec`.`nazv` as spec ,
  `tm_user`.`fio` as fio,
  '$filenameimg' as path,
   DATE_FORMAT(`tm_spec`.`dat`, '%Y') as god, `tm_user`.`num` as sfio
   
FROM
  `tm_user`
  INNER JOIN `tm_spec` ON (`tm_user`.`spec` = `tm_spec`.`num`)
WHERE
  `tm_user`.`num` = $username_test ";

DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));

//echo $sql;imagewebp($im,'./arh/'.$filenameimg,30);

//imagejpg($im);
imagewebp($im,'./arh/'.$filenameimg,30);
//imagedestroy($im);
//echo $filenameimg;
}


?>