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

$currentPage = $_SERVER["PHP_SELF"];
//   /* fixed MMiC */ mysqli_select_db(DB::$link, $database_testmed);
if ((isset($_GET['del'])) && ($_GET['del'] != "")) {
if (file_exists('../docs/'.$_GET['file'])) unlink('../docs/'.$_GET['file']);
  $deleteSQL = sprintf("DELETE FROM tm_docs WHERE num=%s",
                       GetSQLValueString($_GET['del'], "int"));

   $Result1 =  /* fixed MMiC */ DB::Query($deleteSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
	}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {

	$filenameimg =uniqid().'.pdf';
		
	move_uploaded_file($_FILES['path']['tmp_name'],'../docs/'.$filenameimg);
  $insertSQL = sprintf("INSERT INTO tm_docs (spec, `path`, dat, nazv,comm) VALUES (%s, %s, %s, %s,%s)",
                       GetSQLValueString($_POST['spec'], "int"),
                       GetSQLValueString($filenameimg, "text"),
                       GetSQLValueString($_POST['dat'], "date"),
                       GetSQLValueString($_POST['nazv'], "text"),
					  GetSQLValueString($_POST['comm'], "text"));

  $Result1 =  /* fixed MMiC */ DB::Query($insertSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
	
 // $Result1 = mysql_query($insertSQL, $loc) or die(mysql_error());
}

$query_spec = "SELECT num, nazv FROM tm_spec ORDER BY dat ASC";
$spec = /* fixed MMiC */ DB::Query($query_spec, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
//	mysql_query($query_spec, $loc) or die(mysql_error());
$row_spec = mysqli_fetch_assoc($spec);
$totalRows_spec = mysqli_num_rows($spec);

$maxRows_dosc = 10;
$pageNum_dosc = 0;
if (isset($_GET['pageNum_dosc'])) {
  $pageNum_dosc = $_GET['pageNum_dosc'];
}
$startRow_dosc = $pageNum_dosc * $maxRows_dosc;


$query_dosc = "SELECT * FROM tm_docs ORDER BY dat DESC";
$query_limit_dosc = sprintf("%s LIMIT %d, %d", $query_dosc, $startRow_dosc, $maxRows_dosc);
$dosc = /* fixed MMiC */ DB::Query($query_limit_dosc, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
//	mysql_query($query_limit_dosc, $loc) or die(mysql_error());
$row_dosc = mysqli_fetch_assoc($dosc);

if (isset($_GET['totalRows_dosc'])) {
  $totalRows_dosc = $_GET['totalRows_dosc'];
} else {
  $all_dosc =  DB::Query($query_dosc, $testmed);
//	  mysql_query($query_dosc);
  $totalRows_dosc = mysqli_num_rows($all_dosc);
}
$totalPages_dosc = ceil($totalRows_dosc/$maxRows_dosc)-1;

$queryString_dosc = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_dosc") == false && 
        stristr($param, "totalRows_dosc") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_dosc = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_dosc = sprintf("&totalRows_dosc=%d%s", $totalRows_dosc, $queryString_dosc);
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
    <h1>Добавление документов</h1>
    <p>&nbsp;</p>
    <form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data" name="form1" id="form1">
      <table align="center">
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Spec:</td>
          <td><select name="spec">
            <option value="-1" >Всем</option>
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
          <td nowrap="nowrap" align="right">Path:</td>
          <td><label for="path"></label>
          <input type="file" name="path" id="path" onchange="tstFile(this)" multiple="false" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Dat:</td>
          <td><input type="date" name="dat" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Nazv:</td>
          <td><input type="text" name="nazv" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">коммент</td>
          <td><input name="comm" type="text" id="comm" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">&nbsp;</td>
          <td><input type="submit" value="Вставить запись" />
          <input id="Reset" type="reset" /></td>
        </tr>
      </table>
      <input type="hidden" name="MM_insert" value="form1" />
    </form>
    <table width="100%" border="1" class="mini">
      <tr>
        <td>&nbsp;
          <table border="0">
            <tr>
              <td><?php if ($pageNum_dosc > 0) { // Show if not first page ?>
                  <a href="<?php printf("%s?pageNum_dosc=%d%s", $currentPage, 0, $queryString_dosc); ?>">Первый</a>
              <?php } // Show if not first page ?></td>
              <td><?php if ($pageNum_dosc > 0) { // Show if not first page ?>
                  <a href="<?php printf("%s?pageNum_dosc=%d%s", $currentPage, max(0, $pageNum_dosc - 1), $queryString_dosc); ?>">Вернуть</a>
              <?php } // Show if not first page ?></td>
              <td><?php if ($pageNum_dosc < $totalPages_dosc) { // Show if not last page ?>
                  <a href="<?php printf("%s?pageNum_dosc=%d%s", $currentPage, min($totalPages_dosc, $pageNum_dosc + 1), $queryString_dosc); ?>">Далее</a>
              <?php } // Show if not last page ?></td>
              <td><?php if ($pageNum_dosc < $totalPages_dosc) { // Show if not last page ?>
                  <a href="<?php printf("%s?pageNum_dosc=%d%s", $currentPage, $totalPages_dosc, $queryString_dosc); ?>">Последний</a>
              <?php } // Show if not last page ?></td>
            </tr>
        </table></td>
        <td>&nbsp;
Записи с <?php echo ($startRow_dosc + 1) ?> по <?php echo min($startRow_dosc + $maxRows_dosc, $totalRows_dosc) ?> из <?php echo $totalRows_dosc ?></td>
      </tr>
    </table>
    <p>&nbsp;</p>
    <table border="1" class="mini">
      <tr>
        <td>num</td>
        <td>spec</td>
        <td>path</td>
        <td>dat</td>
        <td>nazv</td>
      </tr>
      <?php do { ?>
        <tr>
          <td><a href="?del=<?php echo $row_dosc['num']; ?>&file=<?php echo $row_dosc['path']; ?>">удалить</a></td>
          <td><?php echo $row_dosc['spec']; ?></td>
          <td><?php echo $row_dosc['path']; ?></td>
          <td><?php echo $row_dosc['dat']; ?></td>
          <td><?php echo $row_dosc['nazv']; ?></td>
        </tr>
        <?php } while ($row_dosc = mysqli_fetch_assoc($dosc)); ?>
    </table>
<!-- end .content --></div>
  <div class="sidebar2">
    <h4>Фоны</h4>
    <p>&nbsp;</p>
    <!-- end .sidebar2 --></div>
  <!-- end .container --></div>
</body>
</html>
<?php
mysqli_free_result($spec);

mysqli_free_result($dosc);
?>
