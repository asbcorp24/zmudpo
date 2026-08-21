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
function generate_password($number)
  {
    $arr = array('1','2','3','4','5','6',
                 '7','8','9','0');
    // Генерируем пароль
    $pass = "";
    for($i = 0; $i < $number; $i++)
    {
      // Вычисляем случайный индекс массива
      $index = rand(0, count($arr) - 1);
      $pass .= $arr[$index];
    }
    return $pass;
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
  $deleteSQL = sprintf("DELETE FROM tm_user WHERE num=%s",
                       GetSQLValueString($_GET['del'], "int"));

  mysql_select_db($database_testmed, $testmed);
  $Result1 = mysql_query($deleteSQL, $testmed) or die(mysql_error());
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form2")) {
  if ($_POST["tp"]=="m"){
	  $ss=$_POST['fio'];
	  $res=explode(PHP_EOL,$ss);
	  echo count($res);
	 for ($i=0;$i<=count($res);$i++){
  $insertSQL = sprintf("INSERT INTO tm_user (fio, spec,passw) VALUES (%s, %s,%s)",
                       GetSQLValueString($res[$i], "text"),
                       GetSQLValueString($_POST['spec'], "int"),
					   GetSQLValueString(generate_password(8), "int")
					  );	
  mysql_select_db($database_testmed, $testmed);
  $Result1 = mysql_query($insertSQL, $testmed) or die(mysql_error());

	} 
	
	  
	  }else{
  $insertSQL = sprintf("INSERT INTO tm_user (fio, spec,passw) VALUES (%s, %s,%s)",
                       GetSQLValueString($_POST['fio'], "text"),
                       GetSQLValueString($_POST['spec'], "int"),
	                   GetSQLValueString(generate_password(8), "int"));

  mysql_select_db($database_testmed, $testmed);
  $Result1 = mysql_query($insertSQL, $testmed) or die(mysql_error());}
}

mysql_select_db($database_testmed, $testmed);
$query_spec = "SELECT num, concat(dat,' ',nazv) as nazv FROM tm_spec ORDER BY nazv ASC";
$spec = mysql_query($query_spec, $testmed) or die(mysql_error());
$row_spec = mysql_fetch_assoc($spec);
$totalRows_spec = mysql_num_rows($spec);

$colname_Recordset1 = "-1";
if (isset($_GET['num'])) {
  $colname_Recordset1 = $_GET['num'];
}
mysql_select_db($database_testmed, $testmed);
$query_Recordset1 = sprintf("SELECT * FROM tm_user WHERE spec = %s ORDER BY fio ASC", GetSQLValueString($colname_Recordset1, "int"));
$Recordset1 = mysql_query($query_Recordset1, $testmed) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);
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

<div class="container">
  <div class="sidebar1">
<?php include("menu.php"); ?>
    <p>&nbsp;</p>
    <!-- end .sidebar1 --></div>
  <div class="content">
    <h1>Добавление пользователя в группу по специальности</h1>
    <div><form id="form1" name="form1" method="post" action="">
        <label for="spec"></label>
        <select name="spec" id="spec" style="size:landscape" onchange="MM_goToURL('parent','add_spec_user.php?num='+form1.spec.value);return document.MM_returnValue">
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

      <input name="radio2" type="radio" id="radio" onclick="MM_showHideLayers('mass','','hide','odn','','show')" value="radio" checked="checked" />
      <label for="radio">по одному</label>
      <input name="radio2" type="radio" id="radio2" onclick="MM_showHideLayers('mass','','show','odn','','hide')" value="radio2" />
      <label for="radio2">массово</label>

    <div id="mass" style="visibility:hidden; position:relative">
    <form action="<?php echo $editFormAction; ?>" method="post" name="form2" id="form4">
      <table align="center">
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Фамилии:</td>
          <td><textarea name="fio" cols="32"></textarea></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">&nbsp;</td>
          <td><input type="submit" value="Вставить запись" /></td>
        </tr>
      </table>
      <input type="hidden" name="spec" value="<?php echo $_GET['num']; ?>" />
      <input type="hidden" name="MM_insert" value="form2" />
      <input name="tp" type="hidden" id="tp" value="m" />
    </form>
    </div>
    <div id="odn">
      <form action="<?php echo $editFormAction; ?>" method="post" name="form2" id="form2">
        <table align="center">
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">Фамилия:</td>
            <td><input type="text" name="fio" value="" size="32" /></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">&nbsp;</td>
            <td><input type="submit" value="Вставить запись" /></td>
          </tr>
        </table>
        <input type="hidden" name="spec" value="<?php echo $_GET['num']; ?>" />
        <input type="hidden" name="MM_insert" value="form2" />
        <input name="tp2" type="hidden" id="tp2" value="o" />
      </form>
    </div>
    <p>&nbsp;</p>
    <hr />
    <p>&nbsp;</p>
    <?php if ($totalRows_Recordset1 > 0) { // Show if recordset not empty ?>
  <table width="95%" border="0" cellpadding="2" cellspacing="2" class="tbl">
    <tr bgcolor="#FFFFFF" class="nav">
      <td bgcolor="#FFFFFF">Действия</td>
      <td bgcolor="#FFFFFF">Фамилия:</td>
      <td bgcolor="#FFFFFF">Gf</td>
    </tr>
    <?php do { ?>
      <tr bgcolor="#FFFFFF">
        <td class="mini"><a href="?del=<?php echo $row_Recordset1['num']; ?>&num=<?php echo $_GET['num']; ?>">Удалить</a></td>
        <td class="mini"><?php echo $row_Recordset1['fio']; ?></td>
        <td class="mini"><?php echo $row_Recordset1['passw']; ?></td>
      </tr>
      <?php } while ($row_Recordset1 = mysql_fetch_assoc($Recordset1)); ?>
  </table>
  <?php } // Show if recordset not empty ?>
<!-- end .content --></div>
  <div class="sidebar2">
    <h4>&nbsp;</h4>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
  </div>
  <!-- end .container --></div>
</body>
</html>
<?php
mysql_free_result($spec);

mysql_free_result($Recordset1);
?>
