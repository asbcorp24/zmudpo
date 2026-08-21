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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO tm_irab_def_sp (irab_spec, spec) VALUES (%s, %s)",
                       GetSQLValueString($_POST['irab_spec'], "int"),
                       GetSQLValueString($_POST['spec'], "int"));

 // mysql_select_db($database_loc, $loc);
  	 $Result1 =  /* fixed MMiC */ DB::Query($insertSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
 // $Result1 = mysql_query($insertSQL, $loc) or die(mysql_error());
}

//mysql_select_db($database_loc, $loc);
$query_it_rab = "SELECT * FROM tm_irab_spec";
//require_once('Connections/testmed.php'); 
 $it_rab =  /* fixed MMiC */ DB::Query($query_it_rab, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
//$it_rab = mysql_query($query_it_rab, $loc) or die(mysql_error());
$row_it_rab = mysqli_fetch_assoc($it_rab);
$totalRows_it_rab = mysqli_num_rows($it_rab);

//mysql_select_db($database_loc, $loc);
$query_spec = "SELECT * FROM tm_spec";
 $spec =  /* fixed MMiC */ DB::Query($query_spec, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
//$spec = mysql_query($query_spec, $loc) or die(mysql_error());
$row_spec = mysqli_fetch_assoc($spec);
$totalRows_spec = mysqli_num_rows($spec);

//mysql_select_db($database_loc, $loc);
$query_specrab = "SELECT    `tm_irab_spec`.`spec`,   `tm_spec`.`nazv`,   `tm_irab_def_sp`.`num` FROM   `tm_irab_def_sp`   INNER JOIN `tm_irab_spec` ON (`tm_irab_def_sp`.`irab_spec` = `tm_irab_spec`.`num`)   INNER JOIN `tm_spec` ON (`tm_irab_def_sp`.`spec` = `tm_spec`.`num`)";
 $specrab =  /* fixed MMiC */ DB::Query($query_specrab, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
//$specrab = mysql_query($query_specrab, $loc) or die(mysql_error());
$row_specrab = mysqli_fetch_assoc($specrab);
$totalRows_specrab = mysqli_num_rows($specrab);
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
    <h1>Добавление итоговых работ к текущей специальности</h1>
    <h1>
      <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
        <table align="center">
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">Итоговая работа:</td>
            <td><select name="irab_spec">
              <?php 
do {  
?>
              <option value="<?php echo $row_it_rab['num']?>" ><?php echo $row_it_rab['spec']?></option>
              <?php
} while ($row_it_rab = mysqli_fetch_assoc($it_rab));
?>
            </select></td>
          </tr>
          <tr> </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">Специальность:</td>
            <td><select name="spec" style="width:300px">
              <?php 
do {  
?>
              <option value="<?php echo $row_spec['num']?>" ><?php echo $row_spec['nazv']?></option>
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
    </h1>
    <hr />
    <h1>
  <p>&nbsp;</p>
  <table width="100%" border="1" class="mini">
    <tr>
      <td>Итоговая работа</td>
      <td>Специальность</td>
      <td>num</td>
    </tr>
    <?php do { ?>
      <tr>
        <td><?php echo $row_specrab['spec']; ?></td>
        <td><?php echo $row_specrab['nazv']; ?></td>
        <td><?php echo $row_specrab['num']; ?></td>
      </tr>
      <?php } while ($row_specrab = mysqli_fetch_assoc($specrab)); ?>
  </table>
<!-- end .content --></h1>
  </div>
  <div class="sidebar2">
    <h4>Фоны</h4>
    <p>&nbsp;</p>
    <!-- end .sidebar2 --></div>
  <!-- end .container --></div>
</body>
</html>
<?php
mysqli_free_result($it_rab);

mysqli_free_result($spec);

mysqli_free_result($specrab);

//mysql_free_result($itog_rab);
?>
