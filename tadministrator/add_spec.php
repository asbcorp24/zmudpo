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
  $deleteSQL = sprintf("DELETE FROM tm_spec WHERE num=%s",
                       GetSQLValueString($_GET['del'], "int"));

  mysql_select_db($database_testmed, $testmed);
  $Result1 = mysql_query($deleteSQL, $testmed) or die(mysql_error());
}

if (isset($_GET["upd"])) {
if (!isset($_GET["zn"]))$tm=1;
if ($_GET["zn"]==1) $tm=0;else $tm=1;
  $updateSQL = sprintf("UPDATE tm_spec SET actiiv=%s WHERE num=%s",
                       GetSQLValueString($tm, "int"),
                       GetSQLValueString($_GET['upd'], "int"));
  mysql_select_db($database_testmed, $testmed);
  $Result1 = mysql_query($updateSQL, $testmed) or die(mysql_error());
}
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
 	$filenameimg =uniqid().'.jpg';
		
   //move_uploaded_file($_FILES['fimg']['tmp_name'],'timg/'.$filename);
	
$image = new Imagick($_FILES['img']['tmp_name']);

	$image->adaptiveResizeImage(140,140);

	$data = $image->getImageBlob(); 
file_put_contents ('../timg/'.$filenameimg, $data); 
	
 
  $insertSQL = sprintf("INSERT INTO tm_spec (nazv, dat, img, actiiv) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($_POST['nazv'], "text"),
                       GetSQLValueString($_POST['dat'], "date"),
                       GetSQLValueString($filenameimg, "text"),
                       GetSQLValueString(($_POST['actiiv']=="on") ? "1" : "0","int" ));

  mysql_select_db($database_testmed, $testmed);
  $Result1 = mysql_query($insertSQL, $testmed) or die(mysql_error());
}

mysql_select_db($database_testmed, $testmed);
$query_spec = "SELECT * FROM tm_spec ORDER BY dat ASC";
$spec = mysql_query($query_spec, $testmed) or die(mysql_error());
$row_spec = mysql_fetch_assoc($spec);
$totalRows_spec = mysql_num_rows($spec);
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
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
function MM_goToURL() { //v3.0
  var i, args=MM_goToURL.arguments; document.MM_returnValue = false;
  for (i=0; i<(args.length-1); i+=2) eval(args[i]+".location='"+args[i+1]+"'");
}
</script>
</head>

<body>

<div class="container">
  <div class="sidebar1">
<?php include("menu.php"); ?>
    <p>&nbsp;</p>
    <!-- end .sidebar1 --></div>
  <div class="content">
    <h1>Добавление специальности</h1>
    <p>&nbsp;</p>
    <form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data" name="form1" id="form1">
      <table align="center">
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Название:</td>
          <td><input type="text" name="nazv" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Дата:</td>
          <td><input type="date" id="dat" name="dat"/></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Рисунок:</td>
          <td><label for="img"></label>
          <input type="file" name="img" id="img" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Активна:</td>
          <td><input type="checkbox" name="actiiv" value="" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">&nbsp;</td>
          <td><input type="submit" value="Вставить запись" /></td>
        </tr>
      </table>
      <input type="hidden" name="MM_insert" value="form1" />
    </form>
    <hr />
    <p>&nbsp;</p>
    <table width="95%" border="1">
      <tr>
        <td colspan="2">Действие</td>
        <td>nazv</td>
        <td>dat</td>
        <td>img</td>
        <td>actiiv</td>
        <td>слушатели</td>
      </tr>
      <?php do { ?>
        <tr class="mini">
          <td><a href="?del=<?php echo $row_spec['num']; ?>">Удалить</a></td>
          <td><a href="" onclick="MM_openBrWindow('add_spec_edit.php?num=<?php echo $row_spec['num']; ?>','Редактирование','width=500,height=300')">Изменить</a></td>
          <td><?php echo $row_spec['nazv']; ?></td>
          <td class="mini"><?php echo $row_spec['dat']; ?></td>
          <td><a href="../timg/<?php echo $row_spec['img']; ?>" target="_blank"><?php echo $row_spec['img']; ?></a></td>
          <td><?php echo $row_spec['actiiv']; ?>
            <input name="act" type="checkbox" id="act" onchange="MM_goToURL('parent','add_spec.php?upd=<?php echo $row_spec['num']; ?>&zn=<?php echo $row_spec['actiiv']; ?>');return document.MM_returnValue" <?php if (!(strcmp($row_spec['actiiv'],1))) {echo "checked=\"checked\"";} ?> />
          <label for="act"></label></td>
          <td><a href="add_spec_user.php?num=<?php echo $row_spec['num']; ?>&spec=<?php echo $row_spec['nazv']; ?>">Настроить</a></td>
        </tr>
        <?php } while ($row_spec = mysql_fetch_assoc($spec)); ?>
    </table>
<!-- end .content --></div>
  <div class="sidebar2">
    <h4>&nbsp;</h4>
    <!-- end .sidebar2 --></div>
  <!-- end .container --></div>
</body>
</html>
<?php
mysql_free_result($spec);
?>
