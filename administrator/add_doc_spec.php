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
  $insertSQL = sprintf("INSERT INTO tm_doc_spec (doc, spec) VALUES (%s, %s)",
                       GetSQLValueString($_POST['doc'], "int"),
                       GetSQLValueString($_POST['spec'], "int"));

 // //mysql_select_db($database_loc, $loc);
  $Result1 =  /* fixed MMiC */ DB::Query($insertSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
 // $ = mysql_query($insertSQL, $loc) or die(mysql_error());
}

if ((isset($_GET['del'])) && ($_GET['del'] != "")) {
  $deleteSQL = sprintf("DELETE FROM tm_doc_spec WHERE num=%s",
                       GetSQLValueString($_GET['del'], "int"));

//  //mysql_select_db($database_loc, $loc);
 $Result1 =  /* fixed MMiC */ DB::Query($deleteSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
 //// $Result1 = mysql_query($deleteSQL, $loc) or die(mysql_error());
}

//mysql_select_db($database_loc, $loc);
$query_doc = "SELECT * FROM tm_docs";
 $doc =  /* fixed MMiC */ DB::Query($query_doc, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));

//$doc = mysql_query($query_doc, $loc) or die(mysql_error());
$row_doc = mysqli_fetch_assoc($doc);
$totalRows_doc = mysqli_num_rows($doc);

//mysql_select_db($database_loc, $loc);
$query_spec = "SELECT * FROM tm_spec  where actiiv=1 ORDER BY dat DESC";
 $spec =  /* fixed MMiC */ DB::Query($query_spec, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));

//$spec = mysql_query($query_spec, $loc) or die(mysql_error());
$row_spec = mysqli_fetch_assoc($spec);
$totalRows_spec = mysqli_num_rows($spec);

//mysql_select_db($database_loc, $loc);
$query_doc_spec = "SELECT    `tm_spec`.`nazv` AS `specnazv`,   `tm_doc_spec`.`num`,   `tm_docs`.`nazv` AS `docnazv` FROM   `tm_doc_spec`   INNER JOIN `tm_docs` ON (`tm_doc_spec`.`doc` = `tm_docs`.`num`)   INNER JOIN `tm_spec` ON (`tm_doc_spec`.`spec` = `tm_spec`.`num`)";
 $doc_spec =  /* fixed MMiC */ DB::Query($query_doc_spec, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));

//$doc_spec = mysql_query($query_doc_spec, $loc) or die(mysql_error());
$row_doc_spec = mysqli_fetch_assoc($doc_spec);
$totalRows_doc_spec = mysqli_num_rows($doc_spec);

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

$currentPage = $_SERVER["PHP_SELF"];


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

function tstFile(val){
  var v = val.value;
  var v = v.search(/^.*\.(?:pdf|docx)\s*$/ig)
  if(v!=0){
     alert("Загружаем только pdf и word");
     var pk = document.getElementById("path");
	 pk.value="";
	 
//	 $('#Reset').click();
  }
}
</script>

</head>

<body>

<div class="container">
  <div class="sidebar1">
 <?php include("menu.php")?>
    <p>&nbsp;</p>
    <!-- end .sidebar1 --></div>
  <div class="content">
    <h1>Добавление документов к специальности</h1>
    <p>&nbsp;</p>
    <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
      <table align="center">
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Doc:</td>
          <td><select name="doc">
            <?php 
do {  
?>
            <option value="<?php echo $row_doc['num']?>" ><?php echo $row_doc['nazv']?> </option>
            <?php
} while ($row_doc = mysqli_fetch_assoc($doc));
?>
          </select></td>
        </tr>
        <tr> </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Spec:</td>
          <td><select name="spec">
            <?php 
do {  
?>
            <option value="<?php echo $row_spec['num']?>" <?php if (!(strcmp($row_spec['num'],$_POST['spec']))) {echo "SELECTED";} ?>><?php echo $row_spec['nazv']?>[<?php echo $row_spec['dat']?>]</option>
            <?php
} while ($row_spec = mysqli_fetch_assoc($spec));
?>
          </select></td>
        </tr>
        <tr> </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">&nbsp;</td>
          <td><input type="submit" value="Вставить запись" /></td>
        </tr>
      </table>
      <input type="hidden" name="MM_insert" value="form1" />
    </form>
    <p>&nbsp;</p>
<p>&nbsp;</p>
    <p>&nbsp;</p>
    <table width="99%" border="1" style="max-width: 800px">
      <tr>
        <th><strong>specnazv</strong></th>
        <th><strong>num</strong></th>
        <th><strong>docnazv</strong></th>
      </tr>
      <?php do { ?>
        <tr>
          <td><?php echo $row_doc_spec['specnazv']; ?></td>
          <td><a href="?del=<?php echo $row_doc_spec['num']; ?>">Удалить</a></td>
          <td><?php echo $row_doc_spec['docnazv']; ?></td>
        </tr>
        <?php } while ($row_doc_spec = mysqli_fetch_assoc($doc_spec)); ?>
    </table>
<!-- end .content --></div>
  <div class="sidebar2">
    <h4>Фоны</h4>
    <p><a href="add_doc.php">Документы</a></p>
    <!-- end .sidebar2 --></div>
  <!-- end .container --></div>
</body>
</html>
<?php
mysqli_free_result($doc);

mysqli_free_result($spec);

mysqli_free_result($doc_spec);


?>
