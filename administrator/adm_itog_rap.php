
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

//mysql_select_db($database_loc, $loc);
$query_Recordset1 = "SELECT * FROM tm_spec WHERE actiiv = 1";
$Recordset1 =  /* fixed MMiC */ DB::Query($query_Recordset1, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
//$Recordset1 = mysql_query($query_Recordset1, $loc) or die(mysql_error());
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);

$spec_irab = "0";
if (isset($_GET['spec'])) {
  $spec_irab = $_GET['spec'];
}
mysql_select_db($database_loc, $loc);
$query_irab = sprintf("SELECT 
  `tm_irab_tem`.`nazv`,
  `tm_irab_stud`.`student`,
  `tm_irab_stud`.`path`,
  `tm_irab_stud`.`antiplagiat`,
  `tm_irab_stud`.`result`,
  `tm_irab_stud`.`num`,
  `tm_user`.`fio`
FROM
  `tm_irab_def_sp`
  INNER JOIN `tm_irab_spec` ON (`tm_irab_def_sp`.`irab_spec` = `tm_irab_spec`.`num`)
  INNER JOIN `tm_irab_tem` ON (`tm_irab_spec`.`num` = `tm_irab_tem`.`inn`)
  INNER JOIN `tm_irab_stud` ON (`tm_irab_tem`.`num` = `tm_irab_stud`.`itog_rab`)
  INNER JOIN `tm_user` ON (`tm_irab_stud`.`student` = `tm_user`.`num`)
  AND (`tm_irab_def_sp`.`spec` = `tm_user`.`spec`)
WHERE
  `tm_irab_def_sp`.`spec` =  %s", GetSQLValueString($spec_irab, "int"));
$irab =  /* fixed MMiC */ DB::Query($query_irab, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));

//$irab = mysql_query($query_irab, $loc) or die(mysql_error());
$row_irab = mysqli_fetch_assoc($irab);
$totalRows_irab = mysqli_num_rows($irab);
//echo $query_irab;
?>



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Документ без названия</title>
<link href="thrColLiq.css" rel="stylesheet" type="text/css" />
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
   <?php include("menu.php"); ?>
    <p>&nbsp;</p>
    <!-- end .sidebar1 --></div>
  <div class="content">
    <h1>Итоговые работы</h1>
    <p>
      <label for="select"></label>
      <select name="select" id="select" onchange="MM_goToURL('parent','adm_itog_rap.php?spec='+this.value);return document.MM_returnValue">
        <?php
do {  
?>
        <option value="<?php echo $row_Recordset1['num']?>"<?php if (!(strcmp($row_Recordset1['num'], $_GET['spec']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Recordset1['nazv']?></option>
        <?php
} while ($row_Recordset1 = mysqli_fetch_assoc($Recordset1));
  $rows = mysqli_num_rows($Recordset1);
  if($rows > 0) {
      mysqli_data_seek($Recordset1, 0);
	  $row_Recordset1 = mysqli_fetch_assoc($Recordset1);
  }
?>
      </select>
    </p>
    <hr />
    <p>&nbsp;</p>
    <table border="1" class="mini">
      <tr>
        <td>num</td>
        <td>fio</td>
        <td>result</td>
        <td>antiplagiat</td>
        <td>path</td>
        <td>spec</td>
      </tr>
      <?php do { ?>
        <tr>
          <td><?php echo $row_irab['num']; ?></td>
          <td><?php echo $row_irab['fio']; ?></td>
          <td class="reda" id="<?php echo $row_irab['num']; ?>"><?php echo $row_irab['result']; ?></td>
          <td><?php echo $row_irab['antiplagiat']; ?></td>
          <td><a href="../resdoc/<?php echo $row_irab['path']; ?>" target="blank_"><?php echo $row_irab['path']; ?></a></td>
          <td><?php echo $row_irab['nazv']; ?></td>
        </tr>
        <?php } while ($row_irab = mysqli_fetch_assoc($irab)); ?>
    </table>
<!-- end .content --></div>
  <div class="sidebar2">
    <h4>&nbsp;</h4>
    <!-- end .sidebar2 --></div>
  <!-- end .container --></div>
  <script src="../js/jquery-1.11.3.min.js"></script>
<script type="application/javascript">
$(function() {
	$('.reda').click(function(){
	tm=$(this)[0].innerHTML;
		$(this)[0].innerHTML='';
		$(this).append('<input name="'+$(this)[0].id+'" type="number" id="num" max="5" min="2" value="'+tm+'" /><input type="button" name="button" id="button" value="отпр" class="otpr" />');
		$(this).unbind();
	});
	
	$('body').on('click','input',function(e){
		if ($(this)[0].className=="otpr"){
			
			$.get('upd_itog.php?num='+$(this)[0].parentElement.children[0].name+'&result='+$(this)[0].parentElement.children[0].value)
				$(this)[0].parentElement.innerHTML=$(this)[0].parentElement.children[0].value;
			
		}
		
	});
	});
</script>
</body>
</html>
<?php
mysqli_free_result($Recordset1);

mysqli_free_result($irab);


?>
