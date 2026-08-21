<?php require_once('Connections/testmed.php'); ?>
﻿<?php
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

  $theValue = function_exists(" /* fixed MMiC */ DB::escape") ?  /* fixed MMiC */ DB::escape($theValue) :  /* fixed MMiC */ DB::escape($theValue);

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

 /* fixed MMiC */ mysqli_select_db(DB::$link, $database_testmed);
$query_spec = "SELECT num, concat(dat,' ',nazv) as nazv FROM tm_spec where actiiv=1 ORDER BY nazv ASC ";
$spec =  /* fixed MMiC */ DB::Query($query_spec, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_spec =  /* fixed MMiC */ mysqli_fetch_assoc($spec);
$totalRows_spec =  /* fixed MMiC */ mysqli_num_rows($spec);

$tspec_resu = "0";
if (isset($_GET['num'])) {
  $tspec_resu = $_GET['num'];
}
 /* fixed MMiC */ mysqli_select_db(DB::$link, $database_testmed);

//`tm_spec_test`.`nazvanie
$query_Recordset1 = "SELECT CONCAT_WS('-',`tm_spec_test`.`nazvanie`,`tm_test`.`nazv`) as nazvanie  FROM   `tm_spec_test`  
 INNER JOIN `tm_test` ON (`tm_spec_test`.`tm_test` = `tm_test`.`num`)
WHERE   `tm_spec_test`.`inn` = $tspec_resu ";
$Recordset5 =  /* fixed MMiC */ DB::Query($query_Recordset1, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));



$query_Recordset1 = "SELECT `tm_user`.`num`,`tm_user`.`fio` FROM  `tm_user` WHERE   `tm_user`.`spec` =  $tspec_resu";
$Recordset1 =  /* fixed MMiC */ DB::Query($query_Recordset1, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$totalRows_Recordset1 =  /* fixed MMiC */ mysqli_num_rows($Recordset1);
 $ax=1;

do {  

$mas[0][$row_spec5["nazvanie"]]="";
$ms[]=$row_spec5["nazvanie"];
} while ($row_spec5 =  /* fixed MMiC */ mysqli_fetch_assoc($Recordset5));
$mas[0]['name']="";
$mas[0]['itog']="";
$mas[0]['path']="";
$ax=1;

do { 
$query_resu = sprintf("SELECT 
  CONCAT_WS('-',`tm_spec_test`.`nazvanie`,`tm_test`.`nazv`) as nazvanie,
  `tm_spec_test`.`num`,
  `tm_user_test`.`res`,
  `tm_user`.`fio`,
   `tm_test`.`col_v`,`tm_test`.`num`
FROM
  `tm_spec_test`
  LEFT OUTER JOIN `tm_user_test` ON (`tm_spec_test`.`num` = `tm_user_test`.`test`)
  LEFT OUTER JOIN `tm_user` ON (`tm_user_test`.`inn` = `tm_user`.`num`)
  INNER JOIN `tm_test` ON (`tm_spec_test`.`tm_test` = `tm_test`.`num`)
WHERE
  `tm_spec_test`.`inn` = %s AND 
  (`tm_user_test`.`inn` = %s OR 
  `tm_user_test`.`inn` IS NULL) ", GetSQLValueString($tspec_resu, "int"),GetSQLValueString($row_spec2['num'], "int"));
$resu =  /* fixed MMiC */ DB::Query($query_resu, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));

FOR ($aa=0;$aa<count($ms);$aa++){
	$mas[$ax][$ms[$aa]]="0";
	
}
$mas[$ax]['name']=$row_spec2['fio'];

$itog=0;
$st=0;
do {  
if (!isset($row_spec3["nazvanie"])) continue;
$st++;
$mas[$ax][$row_spec3["nazvanie"]]=$row_spec3['res']/$row_spec3['col_v']*100;
$itog=$itog+$row_spec3['res']/$row_spec3['col_v']*100;
} while ($row_spec3 =  /* fixed MMiC */ mysqli_fetch_assoc($resu));
$mas[$ax]['itog']=ceil($itog/$st);
$mas[$ax]['path']=$row_spec2['num'];
$ax++;

} while ($row_spec2 =  /* fixed MMiC */ mysqli_fetch_assoc($Recordset1));


mysqli_data_seek($Recordset5, 0);

//print_r($mas)	;
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
    <p>&nbsp;</p>
    <!-- end .sidebar1 --></div>
  <div class="content">
    <h1>Инструкции</h1>
   


    <form id="form1" name="form1" method="post" action="">
      <label for="spec"></label>
      <select name="spec" id="spec" style="size:landscape" onchange="MM_goToURL('parent','spec_otch.php?num='+form1.spec.value+'&naz='+form1.spec.text);return document.MM_returnValue" >
        <?php
do {  
?>
        <option value="<?php echo $row_spec['num']?>"<?php if (!(strcmp($row_spec['num'], $_GET['num']))) {echo "selected=\"selected\"";} ?>><?php echo $row_spec['nazv']?></option>
        <?php
} while ($row_spec =  /* fixed MMiC */ mysqli_fetch_assoc($spec));
  $rows =  /* fixed MMiC */ mysqli_num_rows($spec);
  if($rows > 0) {
       /* fixed MMiC */ mysqli_data_seek($spec, 0);
	  $row_spec =  /* fixed MMiC */ mysqli_fetch_assoc($spec);
  }
?>
      </select>
    </form>
    <p></p>
    <h2>Отчет по специальности</h2>
    <table width="100%" border="1" class="mini">
    <tr>
  <?php foreach($mas[0] as $key=>$value)
{
 ?>
<td style="max-width:30px; alignment-baseline:central"><?php 	echo "$key";?></td>
<?php } ?>
      </tr>

    <? for($x=1;$x<=count($mas);$x++){ ?>
      <tr>
       <?php foreach($mas[$x] as $key=>$value)
{
 ?>
<td>
	
	<?php 
	if ($key=="path"){?><a href="red_d.php?us=<?php echo $value; ?>&num=<?php echo $_GET['num']; ?>">исправить </a>  <?php } else {?>
	
	<?php echo "$value ";;?>
<?php } ?>
</td>
<?php } ?>
      </tr>
<?php } ?>
    </table>
    <p></p>
    <p> </p>
    <!-- end .content --></div>
  <div class="sidebar2">
        <h4>Печать</h4>
    <p><a href="pechat_user.php?spec=<?php echo $_GET['num']?>">Отчет по пользователям</a></p>
    <p><a href="spec_otch_pech.php?num=<?php echo $_GET['num']?>">Отчет по успеваемости в баллах</a></p>
    <p><a href="spec_otch_pech.php?num=<?php echo $_GET['num']?>&b">Отчет по успеваемости в оценках</a></p>
    <p></p>
    <!-- end .sidebar2 --></div>
  <!-- end .container --></div>
</body>
</html>
<?php
 /* fixed MMiC */ mysqli_free_result($spec);

 /* fixed MMiC */ mysqli_free_result($resu);

 /* fixed MMiC */ mysqli_free_result($Recordset1);
?>
