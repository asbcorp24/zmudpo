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
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['del'])) && ($_GET['del'] != "")) {
  $deleteSQL = sprintf("DELETE FROM tm_test WHERE num=%s",
                       GetSQLValueString($_GET['del'], "int"));

  mysql_select_db($database_testmed, $testmed);
  $Result1 = mysql_query($deleteSQL, $testmed) or die(mysql_error());
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
 $filename =uniqid().'.html';
 $filotv =uniqid().'.pdf';
	$filenameimg =uniqid().'.jpg';
		
	move_uploaded_file($_FILES['test_file']['tmp_name'],'../testy/'.$filename);
		move_uploaded_file($_FILES['fotv']['tmp_name'],'../otv/'.$filotv);
    //move_uploaded_file($_FILES['fimg']['tmp_name'],'timg/'.$filename);
	
$image = new Imagick($_FILES['fimg']['tmp_name']);

	$image->adaptiveResizeImage(140,140);

	$data = $image->getImageBlob(); 
file_put_contents ('../timg/'.$filenameimg, $data); 
	
	
	$insertSQL = sprintf("INSERT INTO tm_test (`path`, dat, nazv,img,col_v,tex) VALUES (%s, %s, %s,%s,%s,%s)",
                       GetSQLValueString($filename, "text"),
                       GetSQLValueString($_POST['test_date'], "date"),
                       GetSQLValueString($_POST['nazv'], "text"),
						GetSQLValueString($filenameimg, "text"),
						GetSQLValueString($_POST['cv'], "text"),
						GetSQLValueString($filotv, "text"));
  mysql_select_db($database_testmed, $testmed);
  $Result1 = mysql_query($insertSQL, $testmed) or die(mysql_error());
  
  
}

mysql_select_db($database_testmed, $testmed);
$query_testy = "SELECT * FROM tm_test ORDER BY num ASC";
$testy = mysql_query($query_testy, $testmed) or die(mysql_error());
$row_testy = mysql_fetch_assoc($testy);
$totalRows_testy = mysql_num_rows($testy);



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
    <h1>Добавление тестов</h1>
    <p>&nbsp;</p>
    <form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data" name="form1" id="form1">
      <table align="center">
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Файл тестов:</td>
          <td><label for="f"></label>
          <input type="file" name="test_file" id="test_file" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Дата:</td>
          <td><input type="date" name="dat" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Название теста:</td>
          <td><input type="text" name="nazv" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Рисунок:</td>
          <td><input type="file" name="fimg" id="fimg" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">&nbsp;</td>
          <td><input type="number" step="1" min="1" max="500" value="10" id="cv" name="cv"/>
</td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Файл с ответами</td>
          <td><input type="file" name="fotv" id="fotv" /></td>
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
    <table border="1" class="mini">
      <tr>
        <td bgcolor="#FFFFFF">num</td>
        <td bgcolor="#FFFFFF">inn</td>
        <td bgcolor="#FFFFFF">path</td>
        <td bgcolor="#FFFFFF">dat</td>
        <td bgcolor="#FFFFFF">nazv</td>
        <td bgcolor="#FFFFFF">img</td>
        <td bgcolor="#FFFFFF">Ответы</td>
      </tr>
      <?php do { ?>
        <tr>
          <td bgcolor="#FFFFFF"><a href="?del=<?php echo $row_testy['num']; ?>">Удалить</a></td>
          <td bgcolor="#FFFFFF"><?php echo $row_testy['inn']; ?></td>
          <td bgcolor="#FFFFFF"><a href="../testy/<?php echo $row_testy['path']; ?>" target="_blank"><?php echo $row_testy['path']; ?></a></td>
          <td bgcolor="#FFFFFF"><?php echo $row_testy['dat']; ?></td>
          <td bgcolor="#FFFFFF"><?php echo $row_testy['nazv']; ?></td>
          <td bgcolor="#FFFFFF"><?php echo $row_testy['img']; ?></td>
          <td bgcolor="#FFFFFF"><a href="../otv/<?php echo $row_testy['tex']; ?>" target="_blank"><?php echo $row_testy['tex']; ?></td>
        </tr>
        <?php } while ($row_testy = mysql_fetch_assoc($testy)); ?>
    </table>
<!-- end .content --></div>
  <div class="sidebar2">
    <h4>&nbsp;</h4>
    <!-- end .sidebar2 --></div>
  <!-- end .container --></div>
</body>
</html>
<?php
mysql_free_result($testy);
?>
