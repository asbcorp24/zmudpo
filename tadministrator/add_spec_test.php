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
<?php require_once('Connections/testmed.php'); ?>
<?php require_once('Connections/testmed.php'); ?>
<?php
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

if ((isset($_GET['del'])) && ($_GET['del'] != "")) {
  $deleteSQL = sprintf("DELETE FROM tm_spec_test WHERE num=%s",
                       GetSQLValueString($_GET['del'], "int"));

  mysql_select_db($database_testmed, $testmed);
  $Result1 = mysql_query($deleteSQL, $testmed) or die(mysql_error());
}

if (isset($_GET["upd"])) {
if ($_GET["zn"]==1) $tm=0;else $tm=1;
  $updateSQL = sprintf("UPDATE tm_spec_test SET activ=%s WHERE num=%s",
                       GetSQLValueString($tm, "int"),
                       GetSQLValueString($_GET['upd'], "int"));

  mysql_select_db($database_testmed, $testmed);
  $Result1 = mysql_query($updateSQL, $testmed) or die(mysql_error());
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form2")) {
if (!isset($_POST['nazv'])) $namv="Базовый"; else $namv=$_POST['nazv'];
if ($_POST["act"]=="on") $val=1;else $val=0;
  $insertSQL = sprintf("INSERT INTO tm_spec_test (inn, tm_test,nazvanie,otv_col,activ) VALUES (%s, %s,%s,%s,%s)",
                       GetSQLValueString($_POST['inn'], "int"),
                       GetSQLValueString($_POST['tm_test'], "int"),
					                          GetSQLValueString($namv, "text"),
											  GetSQLValueString($_POST["nazv2"], "text"),  GetSQLValueString($val, "int"));
//echo   $insertSQL ;
  mysql_select_db($database_testmed, $testmed);
  $Result1 = mysql_query($insertSQL, $testmed) or die(mysql_error());
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

mysql_select_db($database_testmed, $testmed);
$query_spec = "SELECT num, concat(dat,' ',nazv) as nazv FROM tm_spec ORDER BY nazv ASC";
$spec = mysql_query($query_spec, $testmed) or die(mysql_error());
$row_spec = mysql_fetch_assoc($spec);
$totalRows_spec = mysql_num_rows($spec);

mysql_select_db($database_testmed, $testmed);
$query_testy = "SELECT * FROM tm_test ORDER BY nazv ASC";
$testy = mysql_query($query_testy, $testmed) or die(mysql_error());
$row_testy = mysql_fetch_assoc($testy);
$totalRows_testy = mysql_num_rows($testy);

$nnm_tsp = "0";
if (isset($_GET['num'])) {
  $nnm_tsp = $_GET['num'];
}
mysql_select_db($database_testmed, $testmed);
$query_tsp = sprintf("SELECT    `tm_test`.`path`,   `tm_test`.`nazv`,   `tm_spec_test`.`num`,`tm_spec_test`.`otv_col`, `tm_spec_test`.`activ`,  `tm_spec_test`.`nazvanie` FROM   `tm_spec_test`   INNER JOIN `tm_test` ON (`tm_spec_test`.`tm_test` = `tm_test`.`num`) WHERE   `tm_spec_test`.`inn` = %s", GetSQLValueString($nnm_tsp, "int"));

$tsp = mysql_query($query_tsp, $testmed) or die(mysql_error());
$row_tsp = mysql_fetch_assoc($tsp);
$totalRows_tsp = mysql_num_rows($tsp);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Документ без названия</title>
<link href="thrColLiq.css" rel="stylesheet" type="text/css" /><!--[if lte IE 7]>
<style>
.content { margin-right: -1px; } /* это отрицательное поле в 1 пиксел можно поместить в любом столбце данного макета с таким же корректирующим эффектом. */
ul.nav a { zoom: 1; }  /* свойство масштабирования предоставляет IE триггер hasLayout, необходимый для удаления лишнего пустого пространства между ссылками */
</style>
<![endif]-->
<script type="text/javascript">
function MM_goToURL() { //v3.0
  var i, args=MM_goToURL.arguments; document.MM_returnValue = false;
  for (i=0; i<(args.length-1); i+=2) eval(args[i]+".location='"+args[i+1]+"'");
}
function MM_showHideLayers() { //v9.0
  var i,p,v,obj,args=MM_showHideLayers.arguments;
  for (i=0; i<(args.length-2); i+=3) 
  with (document) if (getElementById && ((obj=getElementById(args[i]))!=null)) { v=args[i+2];
    if (obj.style) { obj=obj.style; v=(v=='show')?'visible':(v=='hide')?'hidden':v; }
    obj.visibility=v; }
}
</script>
</head>

<body>
<p>&nbsp;</p>
<div class="container">
  <div class="sidebar1">
    <?php include("menu.php"); ?>
    <p>&nbsp;</p>
    <!-- end .sidebar1 --></div>
  <div class="content">
    <h1>Добавить тест к специальности</h1>
    <div><form id="form1" name="form1" method="post" action="">
        <label for="spec"></label>
        <select name="spec" id="spec" style="size:landscape" onchange="MM_goToURL('parent','add_spec_test.php?num='+form1.spec.value);return document.MM_returnValue">
          <?php
do {  
?>
          <option value="<?php echo $row_spec['num']?>"<?php if (!(strcmp($row_spec['num'], $_GET['num']))) {echo "selected=\"selected\"";} ?>><?php echo $row_spec['nazv']?></option>
          <?php
} while ($row_spec = mysql_fetch_assoc($spec));
  $rows = mysql_num_rows($spec);
  if($rows > 0) {
      mysql_data_seek($spec, 0);
	  $row_spec = mysql_fetch_assoc($spec);
  }
?>
        </select>
      </form>
    </div>
<hr />
<form action="<?php echo $editFormAction; ?>" method="post" name="form2" id="form2">
  <table align="center">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Тест:</td>
      <td><select name="tm_test">
        <?php
do {  
?>
        <option value="<?php echo $row_testy['num']?>"><?php echo $row_testy['nazv']?></option>
        <?php
} while ($row_testy = mysql_fetch_assoc($testy));
  $rows = mysql_num_rows($testy);
  if($rows > 0) {
      mysql_data_seek($testy, 0);
	  $row_testy = mysql_fetch_assoc($testy);
  }
?>
      </select></td>
    </tr>
    <tr> </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Название</td>
      <td><label for="nazv"></label>
        <input type="text" name="nazv" id="nazv" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Количество раз</td>
      <td><input type="text" name="nazv2" id="nazv2" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Активна</td>
      <td><input name="act" type="checkbox" id="act" checked="checked" />
        <label for="act"></label></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Вставить запись" /></td>
    </tr>
  </table>
  <input type="hidden" name="inn" value="<?php echo $_GET['num']; ?>" />
  <input type="hidden" name="MM_insert" value="form2" />
</form>
<hr />
<p>&nbsp;</p>
<?php if ($totalRows_tsp > 0) { // Show if recordset not empty ?>
  <table width="95%" border="0" class="tbl">
    <tr>
      <td bgcolor="#000000">num</td>
      <td bgcolor="#000000">Коммент</td>
      <td bgcolor="#000000">Название</td>
      <td bgcolor="#000000">Путь</td>
      <td bgcolor="#000000">Кол</td>
        <td bgcolor="#000000">актив</td>
    </tr>
    <?php do { ?>
      <tr bgcolor="#FFFFFF" class="mini">
        <td><p><a href="?num=<?php echo $_GET['num'];?>&del=<?php echo $row_tsp['num']; ?>">Удалить</a></p></td>
        <td><?php echo $row_tsp['nazvanie']; ?></td>
        <td><?php echo $row_tsp['nazv']; ?></td>
        <td><?php echo $row_tsp['path']; ?></td>
        <td><?php echo $row_tsp['otv_col']; ?></td>
                <td><input name="checkbox" type="checkbox" id="checkbox" onchange="MM_goToURL('parent','add_spec_test.php?upd=<?php echo $row_tsp['num']; ?>&zn=<?php echo $row_tsp['activ']; ?>&num=<?php echo $_GET['num']; ?>');return document.MM_returnValue" <?php if (!(strcmp($row_tsp['activ'],1))) {echo "checked=\"checked\"";} ?> />
                  <label for="checkbox"></label>
                <?php echo $row_tsp['activ']; ?></td>

      </tr>
      <?php } while ($row_tsp = mysql_fetch_assoc($tsp)); ?>
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
mysql_free_result($spec);

mysql_free_result($testy);

mysql_free_result($tsp);
?>
