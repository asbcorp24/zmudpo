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

mysql_select_db($database_testmed, $testmed);
$query_spec = "SELECT num, concat(dat,' ',nazv) as nazv FROM tm_spec ORDER BY nazv ASC";
$spec = mysql_query($query_spec, $testmed) or die(mysql_error());
$row_spec = mysql_fetch_assoc($spec);
$totalRows_spec = mysql_num_rows($spec);

$tspec_resu = "0";
if (isset($_GET['num'])) {
  $tspec_resu = $_GET['num'];
}


mysql_select_db($database_testmed, $testmed);
$query_Recordset1 = "SELECT    `tm_spec_test`.`num` FROM   `tm_spec_test` WHERE   `tm_spec_test`.`inn` = $tspec_resu";
$Recordset1 = mysql_query($query_Recordset1, $testmed) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);


mysql_select_db($database_testmed, $testmed);
$query_resu = sprintf("SELECT    `tm_user`.`fio`,   `tm_spec_test`.`nazvanie`,   `tm_spec_test`.`num`,   `tm_user_test`.`res` FROM   `tm_user`   INNER JOIN `tm_spec_test` ON (`tm_user`.`spec` = `tm_spec_test`.`inn`)   LEFT OUTER JOIN `tm_user_test` ON (`tm_user`.`num` = `tm_user_test`.`inn`) WHERE   `tm_user`.`spec` = %s AND    `tm_spec_test`.`num` =%s", GetSQLValueString($tspec_resu, "int"),GetSQLValueString($ttest_resu, "int"));
$resu = mysql_query($query_resu, $testmed) or die(mysql_error());
$row_resu = mysql_fetch_assoc($resu);
$totalRows_resu = mysql_num_rows($resu);


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
</script>
</head>

<body>

<div class="container">
  <div class="sidebar1">
<?php include("menu.php");?>
    <p> Вышеуказанные ссылки демонстрируют базовую структуру навигации с использованием неупорядоченного списка, стилизованного при помощи CSS. Взяв ее за отправную точку и изменяя свойства, можно создать свой неповторимый дизайн. Если нужны выпадающие меню, их можно создать при помощи a Spry menu — мини-приложения menu из Adobe Exchange или ряда других инструментов javascript или CSS.</p>
    <p>Если нужна навигация вдоль верха, просто перенесите ul.nav в верх страницы и заново создайте стиль.</p>
    <!-- end .sidebar1 --></div>
  <div class="content">
    <h1>Инструкции</h1>
   


    <form id="form1" name="form1" method="post" action="">
      <label for="spec"></label>
      <select name="spec" id="spec" style="size:landscape" onchange="MM_goToURL('parent','spec_otch.php?num='+form1.spec.value);return document.MM_returnValue" >
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
    <h2>
    </h2>
    <!-- end .content --></div>
  <div class="sidebar2">
    <h4>Фоны</h4>
    <p>По своей сути фоновый цвет отображается в любом DIV только по длине содержимого. Если вы предпочитаете цвету разделительную линию, поместите границу сбоку DIV .content (но только если в нем всегда будет больше содержимого).</p>
    <!-- end .sidebar2 --></div>
  <!-- end .container --></div>
</body>
</html>
<?php
mysql_free_result($spec);

mysql_free_result($resu);

mysql_free_result($Recordset1);
?>
