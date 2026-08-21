<?php require_once('Connections/testmed.php'); ?>
<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  //$theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

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
echo "приступаем к загрузке"."<br/>";;
if (isset($_FILES['fil'])){
$str = file_get_contents($_FILES['fil']['tmp_name']);
$docs = new SimpleXMLElement($str);
echo "Обработали как xml"."<br/>";;
 echo $_FILES['fil']['tmp_name']."<br/>";; 
 $delsql="delete from tm_arh_spec where num=".$docs->spec['num'];
	$Result1 =  /* fixed MMiC */ DB::Query($delsql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
    $insertSQL = sprintf("INSERT INTO tm_arh_spec (num, naz, din, dout, chas, god,specs) VALUES (%s, %s, %s, %s, %s, %s,%s)",
                       GetSQLValueString($docs->spec['num'], "int"),
                       GetSQLValueString($docs->spec['name'], "text"),
                       GetSQLValueString($docs->spec['din'], "date"),
                       GetSQLValueString($docs->spec['dout'], "date"),
                       GetSQLValueString($docs->spec['specchas'], "int"),
                       GetSQLValueString(0, "int"), GetSQLValueString($docs->spec['specs'], "text"));

  
  $Result1 =  /* fixed MMiC */ DB::Query($insertSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
  
 
  echo $insertSQL."<br/>";
 $delsql="delete from ts_arh_stud where inn=".$docs->spec['num'];
   echo $delsql."<br/>";
	$Result1 =  /* fixed MMiC */ DB::Query($delsql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));

foreach ($docs->spec->student as $stud) {
	
  
   $insertSQL = sprintf("INSERT INTO ts_arh_stud (num, fam, name, otch, itog_rab, crasn_reg, inn, nreg, datav,protocol) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s,%s)",
                       GetSQLValueString($stud['num'], "int"),
                       GetSQLValueString($stud['fam'], "text"),
                       GetSQLValueString($stud['name'], "text"),
                       GetSQLValueString($stud['otch'], "text"),
                       GetSQLValueString($stud['itog_rab'], "text"),
                       GetSQLValueString($stud['krasn_reg'], "text"),
                       GetSQLValueString($stud['inn'], "int"),
                       GetSQLValueString($stud['nreg'], "text"),
                       GetSQLValueString($stud['datav'], "date"), GetSQLValueString($stud['protocol'], "text"));
	  echo $insertSQL."<br/>";				 
 $Result1 =  /* fixed MMiC */ DB::Query($insertSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
  $delsql="delete from tm_arh_ball where inn=".$stud['num'];
  $Result1 =  /* fixed MMiC */ DB::Query($delsql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));

foreach ($stud->ball as $ball){
	
  $insertSQL = sprintf("INSERT INTO tm_arh_ball (inn, nazv, ball,chas) VALUES (%s, %s, %s,%s)",
                       GetSQLValueString($ball['inn'], "int"),
                       GetSQLValueString($ball['predm'], "text"),
                       GetSQLValueString($ball['otc'], "int"),
					  GetSQLValueString($ball['chas'], "int"));
  echo $insertSQL."<br/>";
 
  $Result1 =  /* fixed MMiC */ DB::Query($insertSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
	}
	 $delsql="delete from tm_arh_dop_sv where inn=".$stud['num'];
  $Result1 =  /* fixed MMiC */ DB::Query($delsql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
foreach ($stud->dopsv as $ball){
	
  $insertSQL = sprintf("INSERT INTO `tm_arh_dop_sv` (`valstr`, `valint`, `inn`, `valdat`, `typ`, `nazv`) VALUES (%s, %s,%s,%s,%s,%s);",
                      GetSQLValueString($ball['valstr'], "text"),
                      GetSQLValueString($ball['valint'], "text"),
                      GetSQLValueString($ball['inn'], "int"),
                      GetSQLValueString($ball['valdat'], "text"),
                      GetSQLValueString($ball['typ'], "int"),
                      GetSQLValueString($ball['nazv'], "text")
                      );
  echo $insertSQL."<br/>";
 
  $Result1 =  /* fixed MMiC */ DB::Query($insertSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
	}
  
	
}}
echo "1";
?>
