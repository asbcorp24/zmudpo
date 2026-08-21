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
 function getExtension1($filename) {
    return end(explode(".", $filename));
  }
  
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO tm_pract (nazv) VALUES (%s)",
                       GetSQLValueString($_POST['nazv'], "text"));

 $Result1 =  /* fixed MMiC */ DB::Query($insertSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
}

if ((isset($_GET['del'])) && ($_GET['del'] != "")) {
	if (file_exists('../pract_temy/'.$_GET['file']))unlink('../pract_temy/'.$_GET['file']);
  $deleteSQL = sprintf("DELETE FROM tm_pract_temy WHERE num=%s",
                       GetSQLValueString($_GET['del'], "int"));

  $Result1 =  /* fixed MMiC */ DB::Query($deleteSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
}

if ((isset($_GET['fdd'])) && ($_GET['fdd'] != "")) {
	if (file_exists('../pract_temy/'.$_GET['file']))unlink('../pract_temy/'.$_GET['file']);
  $deleteSQL = sprintf("DELETE FROM tm_pract_temy_file WHERE num=%s",
                       GetSQLValueString($_GET['fdd'], "int"));

  $Result1 =  /* fixed MMiC */ DB::Query($deleteSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form2")) {

	
  $insertSQL = sprintf("INSERT INTO tm_pract_temy (inn, zadanie, nazv_zad, `file`, ball) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['inn'], "int"),
                       GetSQLValueString($_POST['zadanie'], "text"),
                       GetSQLValueString($_POST['nazv_zad'], "text"),
                       GetSQLValueString("NULL", "text"),
                       GetSQLValueString($_POST['ball'], "int"));

    $Result1 =  /* fixed MMiC */ DB::Query($insertSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
	$zap="select max(num) as num from tm_pract_temy";
	$Result1 =  /* fixed MMiC */ DB::Query($zap, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
	$lastid=mysqli_fetch_assoc($Result1);
	$lastid=$lastid['num'];
	
	if($_FILES)
{
	foreach ($_FILES["filen"]["error"] as $key => $error) {
		if ($error == UPLOAD_ERR_OK) {
			$filenameimg =uniqid().'.'.getExtension1($_FILES['filen']['name'][$key]);
		move_uploaded_file($_FILES['filen']['tmp_name'][$key],'../pract_temy/'.$filenameimg);
		$sql="INSERT INTO `tm_pract_temy_file` (`inn`, `path`) VALUES ( $lastid, ". GetSQLValueString($filenameimg, "text").")";	
			$Result1 =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
		//echo($sql);	
		}
	}
}

	
	
}


$query_pract = "SELECT * FROM tm_pract ORDER BY nazv ASC";
$pract =    /* fixed MMiC */ DB::Query($query_pract, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_pract = mysqli_fetch_assoc($pract);
$totalRows_pract = mysqli_num_rows($pract);

$colname_pr_temy = "-1";
if (isset($_GET['pnum'])) {
  $colname_pr_temy = $_GET['pnum'];
}

$query_pr_temy = sprintf("SELECT * FROM tm_pract_temy WHERE inn = %s ORDER BY num DESC", GetSQLValueString($colname_pr_temy, "int"));
$pr_temy   =/* fixed MMiC */ DB::Query($query_pr_temy, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_pr_temy = mysqli_fetch_assoc($pr_temy);
$totalRows_pr_temy = mysqli_num_rows($pr_temy);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Документ без названия</title>
<link href="thrColLiq.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
function MM_jumpMenuGo(objId,targ,restore){ //v9.0
  var selObj = null;  with (document) { 
  if (getElementById) selObj = getElementById(objId);
  if (selObj) eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0; }
}
</script>
</head>

<body>

<div class="container">
  <label for="fileField"></label>
  <div class="sidebar1">
    <?php include("menu.php"); ?>
    <p>&nbsp;</p>
  <!-- end .sidebar1 --></div>
  <div class="content">
    <h1>Добавление Практических</h1>
    <p>&nbsp;</p>
    <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
      <table align="center">
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Название цикла практических</td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right"><input type="text" name="nazv" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right"><input type="submit" value="Вставить запись" /></td>
        </tr>
      </table>
      <input type="hidden" name="MM_insert" value="form1" />
    </form>
    <hr />
    <div align="center">
      <form name="form" id="form">
        <select name="jumpMenu" id="jumpMenu">
          <?php
do {  
?>
          <option value="?pnum=<?php echo $row_pract['num']?>"<?php if (!(strcmp($row_pract['num'], $_GET['pnum']))) {echo "selected=\"selected\"";} ?>><?php echo $row_pract['nazv']?></option>
          <?php
} while ($row_pract = mysqli_fetch_assoc($pract));
  $rows = mysqli_num_rows($pract);
  if($rows > 0) {
      mysqli_data_seek($pract, 0);
	  $row_pract = mysqli_fetch_assoc($pract);
  }
?>
        </select>
        <input type="button" name="go_button" id= "go_button" value="Перейти" onclick="MM_jumpMenuGo('jumpMenu','parent',0)" />
      </form>
    </div>
    <p>&nbsp;</p>
<hr />
    <p>
      <?php if ($colname_pr_temy > 0) { // Show if recordset not empty ?>
    Практические для цикла</p>
    <p>&nbsp;</p>
    <form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data" name="form2" id="form2">
      <table align="center">
        <tr valign="baseline">
          <td nowrap="nowrap" align="right" valign="top">Название задания:</td>
          <td><input type="text" name="nazv_zad" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Задание:</td>
          <td><textarea name="zadanie" cols="50" rows="5"></textarea></td>
        </tr>
       
         <tr valign="baseline">
          <td colspan="2" align="right" nowrap="nowrap">
           <table width="99%" border="1" id="tin">
       
              <tr>
                <td>Файл с заданием 1:</td>
                <td><input type="file" name="filen[]" id="filen" /></td>
              </tr>
             
         
          </table></td>
          </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right"><input type="button" name="button" id="but" value="Добавить файл"/></td>
          <td>&nbsp;</td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Максимальный балл:</td>
          <td><select name="ball" id="ball">
            <option value="0" <?php if (!(strcmp(0, "ц"))) {echo "selected=\"selected\"";} ?>>Текст</option>
            <option value="1" <?php if (!(strcmp(1, "ц"))) {echo "selected=\"selected\"";} ?>>Файл</option>
            <option value="2" <?php if (!(strcmp(2, "ц"))) {echo "selected=\"selected\"";} ?>>Скриншот</option>
            <option value="3" <?php if (!(strcmp(3, "ц"))) {echo "selected=\"selected\"";} ?>>Текст+файл</option>
            <option value="4" <?php if (!(strcmp(4, "ц"))) {echo "selected=\"selected\"";} ?>>Текст+скриншот</option>
            <option value="5" <?php if (!(strcmp(5, "ц"))) {echo "selected=\"selected\"";} ?>>файл+ скриншот</option>
            <option value="6" <?php if (!(strcmp(6, "ц"))) {echo "selected=\"selected\"";} ?>>файл+текст+скриншот</option>
          </select></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">&nbsp;</td>
          <td><input type="submit" value="Вставить запись" /></td>
        </tr>
      </table>
      <input type="hidden" name="inn" value="<?php echo $_GET['pnum']; ?>" />
      <input type="hidden" name="MM_insert" value="form2" />
    </form>
    <hr />
    <p>&nbsp;</p>
    <table width="99%" border="1">
      <?php do { ?>
        <tr>
            <td bgcolor="#93A5C4"><a href="?del=<?php echo $row_pr_temy['num']; ?>&file=<?php echo $row_pr_temy['file']; ?>&pnum=<?php echo $_GET['pnum']; ?>">Удалить</a></td>
            <td class="mini">&nbsp;</td>
        </tr>
        <tr>
          <td bgcolor="#93A5C4">№</td>
          <td ><?php echo $row_pr_temy['num']; ?></td>
        </tr>
        <tr>
          <td bgcolor="#93A5C4" >Название</td>
          <td><?php echo $row_pr_temy['nazv_zad']; ?></td>
        </tr>
        <tr>
          <td bgcolor="#93A5C4">Задание</td>
          <td ><?php echo $row_pr_temy['zadanie']; ?></td>
        </tr>
        <tr>
          <td bgcolor="#93A5C4">Файлы</td>
          <td>
             <table width="100%" border="0" >
            <tbody>
          <?php 
				$prin=0;
				if (isset($row_pr_temy['num']))$prin=$row_pr_temy['num'];
				
				
				$sql="SELECT  `tm_pract_temy_file`.`path`, `tm_pract_temy_file`.`num`,  `tm_pract_temy_file`.`inn` FROM  `tm_pract_temy_file` WHERE   `tm_pract_temy_file`.`inn` = ".$prin;
		$pr_temy_f   =/* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
				$row_pr_temy_f = mysqli_fetch_assoc($pr_temy_f);	  
			  do { ?>
       
              <tr>
                <td><a href="?fdd=<?php echo $row_pr_temy_f['num']; ?>&file=<?php echo $row_pr_temy_f['path']; ?>&pnum=<?php echo $_GET['pnum']; ?>">Удалить</a></td>
                <td>          <a href="../pract_temy/<?php echo $row_pr_temy_f['path']; ?>" target="_new"><?php echo $row_pr_temy_f['path']; ?>   </a></td>
              </tr>
                <?php } while ($row_pr_temy_f = mysqli_fetch_assoc($pr_temy_f)); ?>
            </tbody>
          </table></td>
        </tr>
        <tr>
          <td bgcolor="#93A5C4">Тип</td>
          <td ><?php echo $row_pr_temy['ball']; ?></td>
        </tr>
        <tr class="mini">
          <td colspan="2"><hr /></td>
        </tr>
        <?php } while ($row_pr_temy = mysqli_fetch_assoc($pr_temy)); ?>
    </table>  <?php } // Show if recordset not empty ?>
<!-- end .content --></div>
  <div class="sidebar2">
    <h4>&nbsp;</h4>
    <!-- end .sidebar2 --></div>
<!-- end .container --></div>

<script src="../js/jquery-1.11.3.min.js"></script> 
<script type="text/javascript">
nm=1;
$(function() {
	 $('#but').click(function(){
		 nm++;
		$('#tin').append('<tr valign="baseline"><td nowrap="nowrap" align="right">Файл с заданием '+nm+':</td><td><input type="file" name="filen[]" id="filen" /></td></tr>') 
		 
	 })


});
	</script>
</body>
</html>
<?php
mysqli_free_result($pract);

mysqli_free_result($pr_temy);


?>
