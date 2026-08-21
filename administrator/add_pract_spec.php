<?php require_once('Connections/testmed.php'); ?>
<?php
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
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

 //. $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

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

if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

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
  $insertSQL = sprintf("INSERT INTO tm_spec_pract (spec, pract, d_in, d_out) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($_POST['spec'], "int"),
                       GetSQLValueString($_POST['pract'], "int"),
                       GetSQLValueString($_POST['d_in'], "date"),
                       GetSQLValueString($_POST['d_out'], "date"));

 
  $Result1 =  /* fixed MMiC */ DB::Query($insertSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
}

if ((isset($_GET['del'])) && ($_GET['del'] != "")) {
  $deleteSQL = sprintf("DELETE FROM tm_spec_pract WHERE num=%s",
                       GetSQLValueString($_GET['del'], "int"));

 
  $Result1 =/* fixed MMiC */ DB::Query($deleteSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
}


$query_spec = "SELECT * FROM tm_spec WHERE actiiv = 1 ORDER BY dat DESC";
$spec = /* fixed MMiC */ DB::Query($query_spec, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_spec = mysqli_fetch_assoc($spec);
$totalRows_spec = mysqli_num_rows($spec);


$query_pract = "SELECT * FROM tm_pract ORDER BY nazv ASC";
$pract = /* fixed MMiC */ DB::Query($query_pract, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_pract = mysqli_fetch_assoc($pract);
$totalRows_pract = mysqli_num_rows($pract);

$mdf_Recordset1 = "0";
if (isset($_GET['pnum'])) {
  $mdf_Recordset1 = $_GET['pnum'];
}

$query_Recordset1 = sprintf("SELECT    `tm_pract`.`nazv` AS `pnazv`,   `tm_spec`.`nazv` AS `snazv`,   `tm_spec_pract`.`num`,   `tm_spec_pract`.`d_in`,   `tm_spec_pract`.`d_out` FROM   `tm_spec_pract`   INNER JOIN `tm_pract` ON (`tm_spec_pract`.`pract` = `tm_pract`.`num`)   INNER JOIN `tm_spec` ON (`tm_spec_pract`.`spec` = `tm_spec`.`num`) where  `tm_spec_pract`.`spec`=%s", GetSQLValueString($mdf_Recordset1, "int"));
$Recordset1 = /* fixed MMiC */ DB::Query($query_Recordset1, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Документ без названия</title>
<link href="thrColLiq.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
function MM_jumpMenuGo(objId,targ,restore){ //v9.0
  var selObj = null;  with (document) { 
  if (getElementById) selObj = getElementById(objId);
  if (selObj) eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0; }
}
</script>
</head>

<body>

<div class="container">
  <label for="fileField"></label>
  <div class="sidebar1">
    <?php include("menu.php"); ?>
    <p>&nbsp;</p>
  <!-- end .sidebar1 --></div>
  <div class="content">
    <h1>Добавление Практических  к специальности</h1>
    <div align="center">
      <form name="form" id="form">
        <select name="jumpMenu" id="jumpMenu">

          <?php
do {  
?>
          <option value="?pnum=<?php echo $row_spec['num']?>"<?php if (!(strcmp($row_spec['num'], $_GET['pnum']))) {echo "selected=\"selected\"";} ?>><?php echo $row_spec['nazv']?></option>
          <?php
} while ($row_spec = mysqli_fetch_assoc($spec));
  $rows = mysqli_num_rows($spec);
  if($rows > 0) {
      mysqli_data_seek($spec, 0);
	  $row_spec = mysqli_fetch_assoc($spec);
  }
?>
        </select>
        <input type="button" name="go_button" id= "go_button" value="Перейти" onclick="MM_jumpMenuGo('jumpMenu','parent',0)" />
      </form>
    </div>
    <p>&nbsp;</p>
<hr />
<?php if ($mdf_Recordset1  > 0) { // Show if recordset not empty ?>
  
  
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
      <table align="center">
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Практическая:</td>
          <td><select name="pract">
            <?php 
do {  
?>
            <option value="<?php echo $row_pract['num']?>" ><?php echo $row_pract['nazv']?></option>
            <?php
} while ($row_pract = mysqli_fetch_assoc($pract));
?>
          </select></td>
        </tr>
        <tr> </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Начало</td>
          <td><input type="date" name="d_in" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Окончание</td>
          <td><input type="date" name="d_out" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">&nbsp;</td>
          <td><input type="submit" value="Вставить запись" /></td>
        </tr>
      </table>
      <input type="hidden" name="spec" value="<?php echo $_GET['pnum']; ?>" />
      <input type="hidden" name="MM_insert" value="form1" />
    </form>
    <hr />
    <p>&nbsp;</p>
    <table border="1" class="mini">
      <tr>
        <td>&nbsp;</td>
        <td>pnazv</td>
        <td>snazv</td>
      
        <td>d_in</td>
        <td>d_out</td>
      </tr>
      <?php do { ?>
        <tr>
          <td><a href="?del=<?php echo $row_Recordset1['num']; ?>&pnum=<?php echo $_GET['pnum'];?>">Удалить</a></td>
          <td><?php echo $row_Recordset1['pnazv']; ?></td>
          <td><?php echo $row_Recordset1['snazv']; ?></td>
      
          <td><?php echo $row_Recordset1['d_in']; ?></td>
          <td><?php echo $row_Recordset1['d_out']; ?></td>
        </tr>
        <?php } while ($row_Recordset1 = mysqli_fetch_assoc($Recordset1)); ?>
    </table>
	
	<?php } // Show if recordset not empty ?>
<!-- end .content --></div>
  <div class="sidebar2">
    <h4>&nbsp;</h4>
    <!-- end .sidebar2 --></div>
<!-- end .container --></div>
</body>
</html>
<?php
mysqli_free_result($spec);

mysqli_free_result($pract);

mysqli_free_result($Recordset1);

mysqli_free_result($pr_temy);


?>
