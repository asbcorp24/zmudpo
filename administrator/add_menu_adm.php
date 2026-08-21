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
   // $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
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
  $insertSQL = sprintf("INSERT INTO tm_menu_adm (inn, menu) VALUES (%s, %s)",
                       GetSQLValueString($_POST['menus'], "int"),
                       GetSQLValueString($_POST['mens'], "int"));

  
  $Result1 = DB::Query($insertSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
}

if ((isset($_GET['del'])) && ($_GET['del'] != "")) {
  $deleteSQL = sprintf("DELETE FROM tm_menu_adm WHERE num=%s",
                       GetSQLValueString($_GET['del'], "int"));


  $Result1 = DB::Query($deleteSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
}


$query_addm = "SELECT * FROM tm_admin";
$addm = DB::Query($query_addm, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_addm = mysqli_fetch_assoc($addm);
$totalRows_addm = mysqli_num_rows($addm);


$query_pmen = "SELECT num, name FROM tm_menu ORDER BY name ASC";

$pmen = DB::Query($query_pmen, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_pmen = mysqli_fetch_assoc($pmen);
$totalRows_pmen = mysqli_num_rows($pmen);

$colname_menu = "-1";
if (isset($_GET['num'])) {
  $colname_menu = $_GET['num'];
}
$query_menu = sprintf("SELECT 
  `tm_menu`.`name`,
  `tm_menu_adm`.`num`,
  `tm_menu_adm`.`inn`
FROM
  `tm_menu_adm`
  INNER JOIN `tm_menu` ON (`tm_menu_adm`.`menu` = `tm_menu`.`num`)
WHERE
  `tm_menu_adm`.`inn` = %s", GetSQLValueString($colname_menu, "int"));
$menu = DB::Query($query_menu, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_menu = mysqli_fetch_assoc($menu);
$totalRows_menu = mysqli_num_rows($menu);
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
  <div class="content" style="text-align:center">
    <h1>Добавление доступа к меню</h1>
    <p>&nbsp;</p>
    <form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
      <label for="menus">Пользователи</label>
      <select name="menus" id="menus" style="width:90%" onchange="MM_goToURL('parent','add_menu_adm.php?num='+form1.menus.value);return document.MM_returnValue">
        <?php
do {  
?>
        <option value="<?php echo $row_addm['num']?>" <?php if (!(strcmp($row_addm['num'], $_GET['num']))) {echo "selected=\"selected\"";} ?>><?php echo $row_addm['user']?></option>
        <?php
} while ($row_addm = mysqli_fetch_assoc($addm));
  $rows = mysqli_num_rows($addm);
  if($rows > 0) {
      mysqli_data_seek($addm, 0);
	  $row_addm = mysqli_fetch_assoc($addm);
  }
?>
      </select>
      <hr />

        <p>
          <label for="mens">Пункты меню</label>
          <select name="mens" id="mens" style="width:90%">
            <?php
do {  
?>
            <option value="<?php echo $row_pmen['num']?>"><?php echo $row_pmen['name']?></option>
            <?php
} while ($row_pmen = mysqli_fetch_assoc($pmen));
  $rows = mysqli_num_rows($pmen);
  if($rows > 0) {
      mysqli_data_seek($pmen, 0);
	  $row_pmen = mysqli_fetch_assoc($pmen);
  }
?>
          </select>
        </p>
        <p>
          <input type="submit" name="send" id="send" value="Отправить" />
        </p>
        <input type="hidden" name="MM_insert" value="form1" />
    </form>
    <p>&nbsp;</p>
    <table width="99%" border="1" class="mini">
  <tr>
    <td>num</td>
    <td>inn</td>
    <td>menu</td>
  </tr>
  <?php do { ?>
    <tr>
      <td><a href="?num=<?php echo $_GET['num'];?>&del=<?php echo $row_menu['num']; ?>">Удалить</a></td>
      <td><?php echo $row_menu['inn']; ?></td>
      <td><?php echo $row_menu['name']; ?></td>
    </tr>
    <?php } while ($row_menu = mysqli_fetch_assoc($menu)); ?>
</table>
<!-- end .content --></div>
  <div class="sidebar2">
    <h4>&nbsp;</h4>
    <!-- end .sidebar2 --></div>
  <!-- end .container --></div>
</body>
</html>
<?php
mysqli_free_result($addm);

mysqli_free_result($pmen);

mysqli_free_result($menu);
?>
