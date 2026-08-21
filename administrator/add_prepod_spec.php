<?php require_once('Connections/testmed.php'); ?><?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "index.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
 

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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO tm_prepod_spec (num, spec, prepod) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['num'], "int"),
                       GetSQLValueString($_POST['spec'], "int"),
                       GetSQLValueString($_POST['prepod'], "int"));

 
$prepod =  /* fixed MMiC */ DB::Query($insertSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
//	$Result1 = mysql_query($insertSQL, $loc) or die(mysql_error());
}


$query_spec = "SELECT * FROM tm_spec";
$spec =  /* fixed MMiC */ DB::Query($query_spec, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
//$spec = mysql_query($query_spec, $loc) or die(mysql_error());
$row_spec = mysqli_fetch_assoc($spec);
$totalRows_spec = mysqli_num_rows($spec);

$query_prepod = "SELECT num, fio FROM tm_prepod";
$prepod =  /* fixed MMiC */ DB::Query($query_prepod, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
//$prepod = mysql_query($query_prepod, $loc) or die(mysql_error());
$row_prepod = mysqli_fetch_assoc($prepod);
$totalRows_prepod = mysqli_num_rows($prepod);


$query_ito = "SELECT    `tm_spec`.`nazv`,   `tm_prepod`.`fio`,   `tm_prepod_spec`.`num` FROM   `tm_prepod_spec`   INNER JOIN `tm_spec` ON (`tm_prepod_spec`.`spec` = `tm_spec`.`num`)  RIGHT OUTER JOIN  `tm_prepod` ON (`tm_prepod_spec`.`prepod` = `tm_prepod`.`num`)";
$ito =  /* fixed MMiC */ DB::Query($query_ito, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
//$ito = mysql_query($query_ito, $loc) or die(mysql_error());
$row_ito = mysqli_fetch_assoc($ito);
$totalRows_ito = mysqli_num_rows($ito);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Документ без названия</title>
<link href="thrColLiq.css" rel="stylesheet" type="text/css" />
</head>

<body>

<div class="container">
  <div class="sidebar1">
   <?php include("menu.php"); ?>
    <p>&nbsp;</p>
    <!-- end .sidebar1 --></div>
  <div class="content">
    <h1>Добавление преподов к специальности</h1>
    <p>&nbsp;</p>
   
    
    <p>&nbsp;</p>
    <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
      <table align="center">
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Spec:</td>
          <td><select name="spec">
          	 <option value="-1" >Везде</option>
            <?php 
do {  
?>
            <option value="<?php echo $row_spec['num']?>" ><?php echo $row_spec['nazv']?></option>
            <?php
} while ($row_spec = mysqli_fetch_assoc($spec));
?>
          </select></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Prepod:</td>
          <td><select name="prepod">
            <?php 
do {  
?>
            <option value="<?php echo $row_prepod['num']?>" ><?php echo $row_prepod['fio']?></option>
            <?php
} while ($row_prepod = mysqli_fetch_assoc($prepod));
?>
          </select></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">&nbsp;</td>
          <td><input type="submit" value="Вставить запись" /></td>
        </tr>
      </table>
      <input type="hidden" name="MM_insert" value="form1" />
    </form>
    <p>&nbsp;</p>
    <table width="100%" border="1" class="mini">
      <tr>
        <td>nazv</td>
        <td>fio</td>
        <td>num</td>
      </tr>
      <?php do { ?>
        <tr>
          <td><?php echo $row_ito['nazv']; ?></td>
          <td><?php echo $row_ito['fio']; ?></td>
          <td><?php echo $row_ito['num']; ?></td>
        </tr>
        <?php } while ($row_ito = mysqli_fetch_assoc($ito)); ?>
    </table>
<hr />
    <p>&nbsp;</p>
    <table border="1" class="mini">
      <tr>
        <td>num</td>
        <td>fio</td>
        <td>text</td>
        <td>foto</td>
        <td>tel</td>
        <td>mail</td>
      </tr>
     
    </table>
<!-- end .content --></div>
  <div class="sidebar2">
    <h4>&nbsp;</h4>
    <!-- end .sidebar2 --></div>
  <!-- end .container --></div>
</body>
</html>
<?php
mysqli_free_result($spec);

mysqli_free_result($prepod);

mysqli_free_result($ito);
?>
