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
  $insertSQL = sprintf("INSERT INTO tm_irab_spec (spec, urov) VALUES (%s, %s)",
                       GetSQLValueString($_POST['spec'], "text"),
                       GetSQLValueString($_POST['urov'], "int"));

$Result1 =  /* fixed MMiC */ DB::Query($insertSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
//  $Result1 = mysql_query($insertSQL, $loc) or die(mysql_error());
}
if (isset($_GET["del"])) {
  $insertSQL = sprintf("delete from `tm_irab_tem` where `tm_irab_tem`.`num`=%s",
                       GetSQLValueString($_GET['del'], "int")
                                              );
 echo  $insertSQL;

$Result1 =  /* fixed MMiC */ DB::Query($insertSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
//  $Result1 = mysql_query($insertSQL, $loc) or die(mysql_error());
}


if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form2")) {
  if ($_POST["tp"]=="m"){
  	  $ss=$_POST['nazv'];
	  $res=explode(PHP_EOL,$ss);
	  echo count($res);
	 for ($i=0;$i<=count($res);$i++){
  $insertSQL = sprintf("INSERT INTO tm_irab_tem (inn, nazv) VALUES (%s, %s)",
                       GetSQLValueString($_POST['inn'], "int"),
                       GetSQLValueString($res[$i], "text"));
  	 $Result1 =  /* fixed MMiC */ DB::Query($insertSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));}
  }
                       else {
                       	$insertSQL = sprintf("INSERT INTO tm_irab_tem (inn, nazv) VALUES (%s, %s)",
                       GetSQLValueString($_POST['inn'], "int"),
                       GetSQLValueString($_POST['nazv'], "text"));
  	 $Result1 =  /* fixed MMiC */ DB::Query($insertSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
                       	
                       }

//  mysql_select_db($database_loc, $loc);
 $Result1 =  /* fixed MMiC */ DB::Query($insertSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
  //Result1 = mysql_query($insertSQL, $loc) or die(mysql_error());
}

//mysql_select_db($database_loc, $loc);
$query_spec = "SELECT * FROM tm_irab_spec ORDER BY num DESC";
$spec =  /* fixed MMiC */ DB::Query($query_spec, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
//$spec = mysql_query($query_spec, $loc) or die(mysql_error());
$row_spec = mysqli_fetch_assoc($spec);
$totalRows_spec = mysqli_num_rows($spec);

$colname_itog_rab = "-1";
if (isset($_GET['spec'])) {
  $colname_itog_rab = $_GET['spec'];
}
//mysql_select_db($database_loc, $loc);
$query_itog_rab = sprintf("SELECT * FROM tm_irab_tem WHERE inn = %s", GetSQLValueString($colname_itog_rab, "int"));
$itog_rab =  /* fixed MMiC */ DB::Query($query_itog_rab, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
//$itog_rab = mysql_query($query_itog_rab, $loc) or die(mysql_error());
$row_itog_rab = mysqli_fetch_assoc($itog_rab);
$totalRows_itog_rab = mysqli_num_rows($itog_rab);
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
function MM_showHideLayers() { //v9.0
  var i,p,v,obj,args=MM_showHideLayers.arguments;
  for (i=0; i<(args.length-2); i+=3) 
  with (document) if (getElementById && ((obj=getElementById(args[i]))!=null)) { v=args[i+2];
    if (obj.style) { obj=obj.style; v=(v=='show')?'visible':(v=='hide')?'hidden':v; }
    obj.visibility=v; }
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
 <?php include("menu.php")?>
    <p>&nbsp;</p>
    <!-- end .sidebar1 --></div>
  <div class="content">
    <h1>Добавление итоговых работ по специальностям</h1>
    <hr />
<p>Специальности</p>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Название:</td>
      <td><input type="text" name="spec" value="" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Уровень:</td>
      <td><select name="urov">
        <option value="0" <?php if (!(strcmp(0, ""))) {echo "SELECTED";} ?>>промежуточный</option>
        <option value="1" <?php if (!(strcmp(1, ""))) {echo "SELECTED";} ?>>Итоговый</option>
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
<hr />
<p>Темы работ</p>
<p>&nbsp;</p>

<div id="mass" style="visibility:hidden; position:relative">
	<form action="<?php echo $editFormAction; ?>" method="post" name="form2" id="form2">
  <table align="center">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Inn:</td>
      <td><select name="inn">
        <?php
do {  
?>
        <option value="<?php echo $row_spec['num']?>"<?php if (!(strcmp($row_spec['num'], $_POST['inn']))) {echo "selected=\"selected\"";} ?>><?php echo $row_spec['spec']?></option>
        <?php
} while ($row_spec = mysqli_fetch_assoc($spec));
  $rows = mysqli_num_rows($spec);
  if($rows > 0) {
      mysqli_data_seek($spec, 0);
	  $row_spec = mysqli_fetch_assoc($spec);
  }
?>
      </select></td>
    </tr>
    <tr> </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Nazv:</td>
      <td><textarea name="nazv" cols="32"></textarea></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Вставить запись" /></td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form2" />
   <input name="tp" type="hidden" id="tp" value="m" />
</form>
	</div>
	  <div id="odn">
<form action="<?php echo $editFormAction; ?>" method="post" name="form2" id="form2">
  <table align="center">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Inn:</td>
      <td><select name="inn">
        <?php
do {  
?>
        <option value="<?php echo $row_spec['num']?>"<?php if (!(strcmp($row_spec['num'], $_POST['inn']))) {echo "selected=\"selected\"";} ?>><?php echo $row_spec['spec']?></option>
        <?php
} while ($row_spec = mysqli_fetch_assoc($spec));
  $rows = mysqli_num_rows($spec);
  if($rows > 0) {
      mysqli_data_seek($spec, 0);
	  $row_spec = mysqli_fetch_assoc($spec);
  }
?>
      </select></td>
    </tr>
    <tr> </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Nazv:</td>
      <td><input type="text" name="nazv" value="" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Вставить запись" /></td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form2" />
   <input name="tp" type="hidden" id="tp" value="o" />
</form></div>
 <input name="radio2" type="radio" id="radio" onclick="MM_showHideLayers('mass','','hide','odn','','show')" value="radio" checked="checked" />
      <label for="radio">по одному</label>
      <input name="radio2" type="radio" id="radio2" onclick="MM_showHideLayers('mass','','show','odn','','hide')" value="radio2" />
      <label for="radio2">массово</label>
<p>&nbsp;</p>
<p>
  <label for="select"></label>
  <select name="select" id="select" onchange="MM_goToURL('parent','add_itog_rab.php?spec='+this.value);return document.MM_returnValue">
    <?php
do {  
?>
    <option value="<?php echo $row_spec['num']?>"<?php if (!(strcmp($row_spec['num'], $_GET['spec']))) {echo "selected=\"selected\"";} ?>><?php echo $row_spec['spec']?></option>
    <?php
} while ($row_spec = mysqli_fetch_assoc($spec));
  $rows = mysqli_num_rows($spec);
  if($rows > 0) {
      mysql_data_seek($spec, 0);
	  $row_spec = mysqli_fetch_assoc($spec);
  }
?>
  </select>
</p>
<p>&nbsp;</p>
<table width="99%" border="1" class="mini">
  <tr>
    <th>num</th>
    <th>inn</th>
    <th>nazv</th>
  </tr>
  <?php do { ?>
    <tr>
      <td><a href="?del=<?php echo $row_itog_rab['num']; ?>&spec=<?php echo $_GET['spec']; ?>">Удалить</a></td>
      <td><?php echo $row_itog_rab['inn']; ?></td>
      <td><?php echo $row_itog_rab['nazv']; ?></td>
    </tr>
    <?php } while ($row_itog_rab = mysqli_fetch_assoc($itog_rab)); ?>
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

mysqli_free_result($itog_rab);
?>
