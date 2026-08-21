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
<?php
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if (isset($_GET["zn"])){//$tm=1;
if ($_GET["zn"]==1) $tm=0;else $tm=1;
  $updateSQL = sprintf("UPDATE tm_typsv SET poi=%s WHERE num=%s",
                       GetSQLValueString($tm, "int"),
                       GetSQLValueString($_GET['upd'], "int"));
   /* fixed MMiC */ mysqli_select_db(DB::$link, $database_testmed);
  $Result1 =  /* fixed MMiC */ DB::Query($updateSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
						 
}

if ((isset($_GET['del'])) && ($_GET['del'] != "")) {
  $deleteSQL = sprintf("DELETE FROM tm_typsv WHERE num=%s",
                       GetSQLValueString($_GET['del'], "int"));

   /* fixed MMiC */ mysqli_select_db(DB::$link, $database_testmed);
  $Result1 =  /* fixed MMiC */ DB::Query($deleteSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
}


if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO tm_typsv (nazv, typ) VALUES (%s, %s)",
                       GetSQLValueString($_POST['nazv'], "text"),
                       GetSQLValueString($_POST['typ'], "int"));

  $Result1 = DB::Query($insertSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
// mysql_query($insertSQL, $loc) or die(mysql_error());
}

//mysql_select_db($database_loc, $loc);
$query_tpsv = "SELECT * FROM tm_typsv";
 $tpsv = DB::Query($query_tpsv, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
//$tpsv = mysql_query($query_tpsv, $loc) or die(mysql_error());
$row_tpsv = mysqli_fetch_assoc($tpsv);
$totalRows_tpsv = mysqli_num_rows($tpsv);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Документ без названия</title>
<link href="thrColLiq.css" rel="stylesheet" type="text/css" />
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
    <h1>Добавление Сведений</h1>
    <p><!-- end .content --></p>
    <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
      <table align="center">
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Nazv:</td>
          <td><input type="text" name="nazv" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Typ:</td>
          <td><select name="typ">
            <option value="0" <?php if (!(strcmp(0, ""))) {echo "SELECTED";} ?>>текст</option>
            <option value="1" <?php if (!(strcmp(1, ""))) {echo "SELECTED";} ?>>число</option>
            <option value="2" <?php if (!(strcmp(2, ""))) {echo "SELECTED";} ?>>дата</option>
            <option value="3" <?php if (!(strcmp(3, ""))) {echo "SELECTED";} ?>>файл</option>
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
    <table width="95%" border="1" class="mini">
      <tr>
        <td>num</td>
        <td>nazv</td>
        <td>typ</td>
        <td>подсказка</td>
      </tr>
      <?php do { ?>
        <tr>
          <td><?php echo $row_tpsv['num']; ?><a href="?del=<?php echo $row_tpsv['num']; ?>">удалить</a></td>
          <td><?php echo $row_tpsv['nazv']; ?></td>
          <td><?php echo $row_tpsv['typ']; ?></td>
          <td>
          <?php if($row_tpsv['typ']!=3){ ?>
          <input name="act" type="checkbox" id="act" onchange="MM_goToURL('parent','add_tpsv.php?upd=<?php echo $row_tpsv['num']; ?>&zn=<?php echo $row_tpsv['poi']; ?>');return document.MM_returnValue" <?php if (!(strcmp($row_tpsv['poi'],1))) {echo "checked=\"checked\"";} ?> /><?php } ?></td>
        </tr>
        <?php } while ($row_tpsv = mysqli_fetch_assoc($tpsv)); ?>
    </table>
  </div>
  <div class="sidebar2">
    <h4>&nbsp;</h4>
    <!-- end .sidebar2 --></div>
  <!-- end .container --></div>
</body>
</html>
<?php
mysqli_free_result($tpsv);
?>
