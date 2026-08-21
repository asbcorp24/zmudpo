<?php
//ini_set("display_errors",1);
//error_reporting(E_ALL);
$ball=array();
if (file_exists('ball.txt')){
$data = file_get_contents('ball.txt');
//$bookshelf = json_decode($data);
$ball = unserialize($data);}
 if (ob_get_level()) {
      ob_end_clean();
    }
    // заставляем браузер показать окно сохранения файла
 //   header('Content-Description: File Transfer');
 //   header('Content-Type: application/octet-stream');
 //   header('Content-Transfer-Encoding: binary');
//    header('Expires: 0');
//    header('Cache-Control: must-revalidate');
//    header('Pragma: public');
  
 ?>
<?php


require_once('Connections/testmed.php'); 
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

 /* fixed MMiC */ mysqli_select_db(DB::$link, $database_testmed);
$tspec_resu = "0";
if (isset($_GET['num'])) {
  $tspec_resu = $_GET['num'];
}


$query_spec = "SELECT num, concat(dat,' ',nazv) as nazv FROM tm_spec WHERE   `tm_spec`.`num` = $tspec_resu ";
$spec =  /* fixed MMiC */ DB::Query($query_spec, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_spec =  /* fixed MMiC */ mysqli_fetch_assoc($spec);
$totalRows_spec =  /* fixed MMiC */ mysqli_num_rows($spec);

 /* fixed MMiC */ mysqli_select_db(DB::$link, $database_testmed);


$query_Recordset1 = "SELECT CONCAT_WS('-',`tm_spec_test`.`nazvanie`,`tm_test`.`nazv`) as nazvanie  FROM   `tm_spec_test`  
 INNER JOIN `tm_test` ON (`tm_spec_test`.`tm_test` = `tm_test`.`num`) WHERE   `tm_spec_test`.`inn` = $tspec_resu ";
$Recordset5 =  /* fixed MMiC */ DB::Query($query_Recordset1, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));



$query_Recordset1 = "SELECT `tm_user`.`num`,`tm_user`.`fio` FROM  `tm_user` WHERE   `tm_user`.`spec` =  $tspec_resu";
$Recordset1 =  /* fixed MMiC */ DB::Query($query_Recordset1, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$totalRows_Recordset1 =  /* fixed MMiC */ mysqli_num_rows($Recordset1);
 $ax=1;

do {  

$mas[0][$row_spec5["nazvanie"]]="";
$ms[]=$row_spec5["nazvanie"];
} while ($row_spec5 =  /* fixed MMiC */ mysqli_fetch_assoc($Recordset5));
$mas[0]['name']="";
$mas[0]['itog']="";

$ax=1;

do { 
$query_resu = sprintf("SELECT 
  CONCAT_WS('-',`tm_spec_test`.`nazvanie`,`tm_test`.`nazv`) as nazvanie,
  `tm_spec_test`.`num`,
  `tm_user_test`.`res`,
  `tm_user`.`fio`,
   `tm_test`.`col_v`,`tm_test`.`num`
FROM
  `tm_spec_test`
  LEFT OUTER JOIN `tm_user_test` ON (`tm_spec_test`.`num` = `tm_user_test`.`test`)
  LEFT OUTER JOIN `tm_user` ON (`tm_user_test`.`inn` = `tm_user`.`num`)
  INNER JOIN `tm_test` ON (`tm_spec_test`.`tm_test` = `tm_test`.`num`)
WHERE
  `tm_spec_test`.`inn` = %s AND 
  (`tm_user_test`.`inn` = %s OR 
  `tm_user_test`.`inn` IS NULL) ", GetSQLValueString($tspec_resu, "int"),GetSQLValueString($row_spec2['num'], "int"));
$resu =  /* fixed MMiC */ DB::Query($query_resu, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));

FOR ($aa=0;$aa<count($ms);$aa++){
	$mas[$ax][$ms[$aa]]="0";
	
}
$mas[$ax]['name']=$row_spec2['fio'];

$itog=0;
$st=0;
do {  
if (!isset($row_spec3["nazvanie"])) continue;
$st++;
@$mas[$ax][$row_spec3["nazvanie"]]=$row_spec3['res']/$row_spec3['col_v']*100;
@$itog=$itog+$row_spec3['res']/$row_spec3['col_v']*100;
//$itog=$itog+$row_spec3['res']/$row_spec3['col_v']*100;
} while ($row_spec3 =  /* fixed MMiC */ mysqli_fetch_assoc($resu));
$mas[$ax]['itog']=ceil($itog/($st));

$ax++;

} while ($row_spec2 =  /* fixed MMiC */ mysqli_fetch_assoc($Recordset1));


mysqli_data_seek($Recordset5, 0);
 header('Content-type: application/msword');
 header('Content-Disposition: attachment; filename="успеваемость_'.$row_spec['nazv'].'.doc"' );
//print_r($mas)	;
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Документ без названия</title>

<![endif]-->

</head>

<body>

<div class="container">

  <div class="content">

   


    <h1>         
     
     <?php 

echo $row_spec['nazv']?>
        <?php

  $rows =  /* fixed MMiC */ mysqli_num_rows($spec);
  if($rows > 0) {
       /* fixed MMiC */ mysqli_data_seek($spec, 0);
	  $row_spec =  /* fixed MMiC */ mysqli_fetch_assoc($spec);
  }
?></h1>
      
    
    <p></p>
    <h2>Отчет по специальности</h2>
    <table width="100%" border="1" class="mini">
    <tr>
  <?php foreach($mas[0] as $key=>$value)
{
 ?>
<td style="max-width:30px; alignment-baseline:central"><?php 	echo "$key";?></td>
<?php } ?>
      </tr>

    <? for($x=1;$x<=count($mas)-1;$x++){ ?>
      <tr>
       <?php  foreach($mas[$x] as $key=>$value)
{
 ?>
<td>
	
	
	
	<?php

	if (isset($_GET['b'])){
			if (is_string($value)){echo "$value ";} else{
				 if (($value<$ball[3])and ($value<$ball[4])) echo "2";
		 if (($value>=$ball[3])and ($value<$ball[4])) echo "3";
		 if (($value>=$ball[4])and ($value<$ball[5])) echo "4";
		 if ($value>=$ball[5]) echo "5";}
		 
		 } else echo "$value ";
	 ?>

</td>
<?php } ?>
      </tr>
<?php } ?>
    </table>
    <p></p>
    <p> (c) Балабанов А.С.</p>
    <!-- end .content --></div>

  <!-- end .container --></div>
</body>
</html>
<?php
 /* fixed MMiC */ mysqli_free_result($spec);

 /* fixed MMiC */ mysqli_free_result($resu);

 /* fixed MMiC */ mysqli_free_result($Recordset1);
?>
