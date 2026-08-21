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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO tm_media (`path`, nazv, spec) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['path'], "text"),
                       GetSQLValueString($_POST['nazv'], "text"),
                       GetSQLValueString($_POST['spec'], "int"));

  $Result1 =  /* fixed MMiC */ DB::Query($insertSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
  ///$Result1 = mysql_query($insertSQL, $loc) or die(mysql_error());
}

if ((isset($_GET['del'])) && ($_GET['del'] != "")) {
  $deleteSQL = sprintf("DELETE FROM tm_media WHERE num=%s",
                       GetSQLValueString($_GET['del'], "int"));

  $Result1 =  /* fixed MMiC */ DB::Query($deleteSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
  //$Result1 = mysql_query($deleteSQL, $loc) or die(mysql_error());
}


$query_spec = "SELECT * FROM tm_spec";
  $cpec =  /* fixed MMiC */ DB::Query($query_spec, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
//$spec = mysql_query($query_spec, $loc) or die(mysql_error());
$row_spec = mysqli_fetch_assoc($spec);
$totalRows_spec = mysqli_num_rows($spec);


$query_media = "SELECT * FROM tm_media ORDER BY num DESC";

$media =  /* fixed MMiC */ DB::Query($query_media, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
//$media = mysql_query($query_media, $loc) or die(mysql_error());
$row_media = mysqli_fetch_assoc($media);
$totalRows_media = mysqli_num_rows($media);
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
</head>

<body>

<div class="container">
  <div class="sidebar1">
  <?php include("menu.php"); ?>
    <p>&nbsp;</p>
    <!-- end .sidebar1 --></div>
  <div class="content">
    <h1>Добавление медиа</h1>
    <p>&nbsp;</p>
    <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
      <table align="center">
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">УРЛ</td>
          <td><input type="text" name="path" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Название:</td>
          <td><input type="text" name="nazv" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Тип</td>
          <td><label for="spec"></label>
            <select name="spec" id="spec">
              <option value="0">YOUTUBE</option>
              <option value="1">Аудио</option>
              <option value="2">Книга</option>
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
    <table width="99%" border="1" class="mini">
      <tr>
        <td>num</td>
        <td>inn</td>
        <td>path</td>
        <td>nazv</td>
        <td>spec</td>
      </tr>
      <?php do { ?>
        <tr>
          <td><a href="?del=<?php echo $row_media['num']; ?>">Удалить</a></td>
          <td><?php echo $row_media['inn']; ?></td>
          <td><a href="<?php  echo $row_media['path']; ?>" target="blank_" >просмотр</td>
          <td><?php echo $row_media['nazv']; ?></td>
          <td><?php echo $row_media['spec']; ?></td>
        </tr>
        <?php } while ($row_media = mysqli_fetch_assoc($media)); ?>
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

mysqli_free_result($media);
?>
