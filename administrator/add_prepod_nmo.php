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


if ((isset($_POST["upd"])) && ($_POST["upd"] == "1")) {
$sql="update `tm_nmo_prepod_spec` set ".$_POST["name"]."='".$_POST["val"]."' where num=".$_POST["num"];	
DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link)); exit(0);	
	
}

	if ((isset($_GET["del"])) ) {
	$sql="delete from `tm_nmo_prepod_spec` where num=".GetSQLValueString($_GET["del"],"int");	
$Result1 =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));

	
	
}
if (isset($_POST["spec"])){ 

	$insertSQL = sprintf(" INSERT INTO `tm_nmo_prepod_spec` (`num`, `prepod`, `predmet`, `spec`,kol_raz,comment) VALUES (NULL, %s, %s, %s, %s,%s)",
                       GetSQLValueString($_POST['prepod'], "int"),
                       GetSQLValueString($_POST['predmet'], "text"),
                       GetSQLValueString($_POST['spec'], "int"),  GetSQLValueString($_POST['kol_raz'], "int"), GetSQLValueString($_POST['comment'], "text"));

echo $insertSQL;
$Result1 =  DB::Query($insertSQL, $testmed) or die(  mysqli_error(DB::$link));
	
//  $Result1 = mysql_query($insertSQL, $loc) or die(mysql_error());

					}

$query_prepod = "SELECT * FROM tm_prepod ORDER BY fio ASC";

$prepod =  /* fixed MMiC */ DB::Query($query_prepod, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
//$prepod = mysql_query($query_prepod, $loc) or die(mysql_error());
$row_prepod = mysqli_fetch_assoc($prepod);
$totalRows_prepod = mysqli_num_rows($prepod);




$sqlp="SELECT `tm_spec`.`num`,`tm_spec`.`nazv` FROM  `tm_spec` WHERE  `tm_spec`.`kr` = 1 AND `tm_spec`.`actiiv` = 1";
$sqlp =  /* fixed MMiC */ DB::Query($sqlp, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
//$prepod = mysql_query($query_prepod, $loc) or die(mysql_error());
$row_sqlp = mysqli_fetch_assoc($sqlp);
$totalRows_sqlp = mysqli_num_rows($sqlp);

$sql="SELECT 
  `tm_prepod`.`fio`,
  `tm_spec`.`nazv`,
  `tm_nmo_prepod_spec`.`num`, `tm_nmo_prepod_spec`.`predmet`, `tm_nmo_prepod_spec`.`kol_raz`, `tm_nmo_prepod_spec`.`comment`
FROM
  `tm_nmo_prepod_spec`
  INNER JOIN `tm_prepod` ON (`tm_nmo_prepod_spec`.`prepod` = `tm_prepod`.`num`)
  INNER JOIN `tm_spec` ON (`tm_nmo_prepod_spec`.`spec` = `tm_spec`.`num`)";
$sqlp2 =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
//$prepod = mysql_query($query_prepod, $loc) or die(mysql_error());
$row_sqlp2 = mysqli_fetch_assoc($sqlp2);
$totalRows_sqlp2 = mysqli_num_rows($sqlp2);

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
    <h1>Добавление преподов к НМО</h1>
    <p>&nbsp;</p>
    <form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data" name="form1" id="form1">
      <table align="center">
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Специальность</td>
          <td align="left"><select name="spec" id="spec">
			   <?php
do {  
?>
          <option value="<?php echo $row_sqlp['num']?>"><?php echo $row_sqlp['nazv']?></option>
          <?php
} while ($row_sqlp =  /* fixed MMiC */ mysqli_fetch_assoc($sqlp));?>
			  
            
          </select></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">препод:</td>
          <td align="left"><select name="prepod" id="prepod">
			   <?php
do {  
?>
          <option value="<?php echo $row_prepod['num']?>"><?php echo $row_prepod['fio']?></option>
          <?php
} while ($row_prepod =  /* fixed MMiC */ mysqli_fetch_assoc($prepod));?>
			  
            
          </select></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Предмет:</td>
          <td align="left"><input name="predmet" type="text" id="predmet" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Занятий на человека</td>
          <td align="left"><input name="kol_raz" type=number id="kol_raz" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">&nbsp;</td>
          <td align="left"><textarea name="comment"></textarea></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">&nbsp;</td>
          <td align="left"><input type="submit" value="Вставить запись" /></td>
        </tr>
      </table>
      <input type="hidden" name="MM_insert" value="form1" />
    </form>
    <p>&nbsp;</p>
<hr />
    <p>&nbsp;</p>
    <table width="99%" border="1">
      <tr>
        <td>num</td>
        <td>Фио</td>
        <td>text</td>
        <td>предметм</td>
		   <td>Занятий </td>
		     <td>Настроить даты </td>
		     <td>Комментарий </td>
      </tr>
      <?php do { ?>
        <tr>
          <td><a href="?del=<?php echo $row_sqlp2['num']; ?>&ph=<?php echo $row_sqlp2['num']; ?>">удалить</a></td>
            
          <td><?php echo $row_sqlp2['fio']; ?></td>
          <td><?php echo $row_sqlp2['nazv']; ?></td>
			          <td><?php echo $row_sqlp2['predmet']; ?></td>
		  <td data-num="<?php echo $row_sqlp2['num']; ?>" data-name="kol_raz"><?php echo $row_sqlp2['kol_raz']; ?></td>
			   <td><a href="add_prepod_nmo_dat.php?spec=<?php echo $row_sqlp2['num']; ?>">Настроить даты </a></td>
			 <td data-num="<?php echo $row_sqlp2['num']; ?>" data-name="comment"><?php echo $row_sqlp2['comment']; ?></td>
        </tr>
        <?php } while ($row_sqlp2 = mysqli_fetch_assoc($sqlp2)); ?>
    </table>
<!-- end .content --></div>

  <!-- end .container --></div>
</body>
	 <script src="../js/jquery-1.11.3.min.js"></script>
  <script type="text/javascript">
	$(function() {
		fl=0;
		
	
		
		///////////
		$('td').on('click',function(){
		if ($(this).data('num')==undefined) return;
			if (fl==0){
			console.log($(this));fl=1;
	//	console.log($('#p'+$(this).prop('name')));
				if ($(this).data('tip')==undefined)tip="text"; else tip= $(this).data('tip');
$(this)[0].innerHTML='<input type="'+tip+'" name="textfield" id="textfield" value="'+$(this)[0].innerText+'"><input type="button" name="button" id="btg" value="V">';}
		});



	$('body').on('click', '#btg',function(){
			
			num=$(this).parent().data('num')
				name=$(this).parent().data('name');
				val=$(this).parent().children().first().val();
			par=	$(this).parent();fl=0;
			$(this).parent().children().remove();$(par).append(val)
			$.post('add_prepod_nmo.php', {'upd':'1', 'num' :num,'name':name,'val':val},
		function(data) {
			
	
		});});});
	
	
	</script>
</html>
<?php
mysqli_free_result($prepod);

?>
