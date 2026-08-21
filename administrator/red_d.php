<?php require_once('Connections/testmed.php'); ?>
<?php

 $ref= $_SERVER['HTTP_REFERER'];
ini_set("display_errors",1);
error_reporting(E_ALL);
 function GoBack() {
           
    }

if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

 // $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

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

/* fixed MMiC */// mysqli_select_db(DB::$link, $$testmed);



$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {


	if (isset($_POST['nums'])){
	$bl=$_POST['nums'];
//	print_r($bl);
	}
	if (isset($_POST['ball'])){
	$bla=$_POST['ball'];

	}


	
	
	
		$delsql="delete from tm_user_test where inn=".$_POST['user'];
		
		 $user =  /* fixed MMiC */ DB::Query($delsql) or die( /* fixed MMiC */ mysqli_error(DB::$link));
		
	for($ax=0;$ax<=count($bl)-1;$ax++){
  $insertSQL = sprintf("INSERT INTO tm_user_test (test, res,inn) VALUES (%s, %s,%s)",
                       GetSQLValueString($bl[$ax], "int"),
                       GetSQLValueString($bla[$ax], "int"),
					    GetSQLValueString($_POST['user'], "int"));

 $user =  /* fixed MMiC */ DB::Query($insertSQL) or die( /* fixed MMiC */ mysqli_error(DB::$link));
		}
	 header("Location: ".$_POST['ref']);exit();
}

//$tuser_ress = "39";
//if (isset(39)) {
//  $tuser_ress = 39;
//}

if (isset($_GET['us'])) {
  $tuser_ress = $_GET['us'];
}
if (isset($_POST['us'])) {
  $tuser_ress = $_POST['us'];
}

$query_user = sprintf("SELECT fio FROM tm_user WHERE num = %s", GetSQLValueString( $tuser_ress, "int"));
 $user =  /* fixed MMiC */ DB::Query($query_user) or die( /* fixed MMiC */ mysqli_error(DB::$link));




$row_user = mysqli_fetch_assoc($user);




//$query_ress = sprintf("SELECT    `tm_user_test`.`res`,   `tm_user_test`.`inn`,   `tm_spec_test`.`nazvanie`,   `tm_spec_test`.`num`,
//  `tm_user_test`.`num` as unum FROM   `tm_user_test`   RIGHT OUTER JOIN `tm_spec_test` ON (`tm_user_test`.`test` = `tm_spec_test`.`num`) WHERE   `tm_spec_test`.`inn` = %s AND    (`tm_user_test`.`inn` =  %s OR    `tm_user_test`.`inn` IS NULL)", GetSQLValueString($_GET['num'], "int"), GetSQLValueString($tuser_ress, "int"));

$query_ress = sprintf("SELECT    `tm_user_test`.`res`,   `tm_user_test`.`inn`,   `tm_spec_test`.`nazvanie`,   `tm_spec_test`.`num`,
  `tm_user_test`.`num` as unum,
  `tm_test`.`col_v` FROM   `tm_user_test`   RIGHT OUTER JOIN `tm_spec_test` ON (`tm_user_test`.`test` = `tm_spec_test`.`num`) 
  INNER JOIN `tm_test` ON (`tm_spec_test`.`tm_test` = `tm_test`.`num`)
  WHERE   `tm_spec_test`.`inn` = %s AND    (`tm_user_test`.`inn` =  %s OR    `tm_user_test`.`inn` IS NULL)", GetSQLValueString($_GET['num'], "int"), GetSQLValueString($tuser_ress, "int"));

  $ress =  /* fixed MMiC */ DB::Query($query_ress) or die( /* fixed MMiC */ mysqli_error(DB::$link));
//$ress = mysql_query($query_ress, $d) or die(mysql_error());
$row_ress = mysqli_fetch_assoc($ress);
$totalRows_ress = mysqli_num_rows($ress);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Документ без названия</title>
</head>

<body>
<h1><?php echo $row_user['fio']; ?>
</h1>
<form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
  <table border="1">
  <tr>
    <td>Название</td>
    <td>inn</td>
    <td>Балл</td>
    <td>МАКС</td>
  </tr>
  <?php do { ?>
    <tr>
      <td><label for="ball"><?php echo $row_ress['nazvanie']; ?></label></td>
        <td></td>
      <td><input name="nums[]" type="hidden" id="nums" value="<?php echo $row_ress['num']; ?>" />
        <input name="ball[]" type="text" id="ball" value="<?php echo $row_ress['res']; ?>" /></td>
      <input name="ref" type="hidden" id="ref" value="<?php echo $ref; ?>" />
      <td>из <?php echo $row_ress['col_v']; ?></td>
    </tr>
    <?php } while ($row_ress = mysqli_fetch_assoc($ress)); ?>
</table>
<?php echo $row_ress['inn']; ?>
<input type="submit" name="button" id="button" value="Отправить" />
<input name="us" type="hidden" id="us" value="<?php echo $$_GET['us']; ?>" />
<input name="user" type="hidden" id="user" value="<?php echo $tuser_ress ?>" />
<input type="hidden" name="MM_insert" value="form1" />
</form>
</body>
</html>
<?php
mysqli_free_result($user);

mysqli_free_result($ress);
?>
