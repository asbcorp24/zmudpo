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



if ((isset($_GET["dat"])) && ($_GET["dat"] == 1)) {
	$num=(int)$_GET["num"];
$sql="SELECT 
  `tm_login_dat`.`num`,
  `tm_login_dat`.`user`,
  `tm_login_dat`.`dat`,
  `tm_login_dat`.`dop`
FROM
  `tm_login_dat`
WHERE
  `tm_login_dat`.`user` = $num";	
	//echo $sql;
$spec =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_spec =  /* fixed MMiC */ mysqli_fetch_assoc($spec);
	echo '<table width="100%" border="0">';
do{	echo '
    <tr>
      <td style="font-size: 10px"">'.$row_spec['dat'].'</td>
    
    </tr>
	';
		  } while ($row_spec =  /* fixed MMiC */ mysqli_fetch_assoc($spec));
	echo ' </table>';
	exit();
}


 /* fixed MMiC */ mysqli_select_db(DB::$link, $database_testmed);
$query_spec = "SELECT num, concat(dat,' ',nazv) as nazv FROM tm_spec ORDER BY nazv ASC";
$spec =  /* fixed MMiC */ DB::Query($query_spec, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_spec =  /* fixed MMiC */ mysqli_fetch_assoc($spec);
$totalRows_spec =  /* fixed MMiC */ mysqli_num_rows($spec);

$colname_Recordset1 = "-1";
if (isset($_GET['num'])) {
  $colname_Recordset1 = $_GET['num'];
}
 /* fixed MMiC */ mysqli_select_db(DB::$link, $database_testmed);
$stm='';

$query_Recordset1 = sprintf("SELECT 
  `tm_user`.`fio`,
  MAX(`tm_login_dat`.`dat`) AS dat,
  `tm_login_dat`.`dop`,
  `tm_user`.`num`
FROM
  `tm_login_dat`
  RIGHT OUTER JOIN `tm_user` ON (`tm_login_dat`.`user` = `tm_user`.`num`)
WHERE
  `tm_user`.`spec` = %s
GROUP BY
  `tm_user`.`fio`,
  `tm_login_dat`.`dop`,
  `tm_user`.`num`  ORDER BY fio ASC", GetSQLValueString($colname_Recordset1, "int"));
$Recordset1 =  /* fixed MMiC */ DB::Query($query_Recordset1, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_Recordset1 =  /* fixed MMiC */ mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 =  /* fixed MMiC */ mysqli_num_rows($Recordset1);





?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Учащиеся</title>
<link href="thrColLiq.css" rel="stylesheet" type="text/css" /><!--[if lte IE 7]>

<style>
.content { margin-right: -1px; } /* это отрицательное поле в 1 пиксел можно поместить в любом столбце данного макета с таким же корректирующим эффектом. */
ul.nav a { zoom: 1; }  /* свойство масштабирования предоставляет IE триггер hasLayout, необходимый для удаления лишнего пустого пространства между ссылками */
</style>
<![endif]-->
	<link href="../css/icofont.css" rel="stylesheet" type="text/css"/>
<style>
	table {
border-spacing: 0 0px;
border-color: #787878;
}

	</style>
</head>

<body>

<div class="container">
  <div class="sidebar1">
<?php include("menu.php"); ?>
    <p>&nbsp;</p>
    <!-- end .sidebar1 --></div>
  <div class="content" style="max-width: 400px;
">
    <h1>Просмотр статистики входов</h1>
   
    <div><form id="form1" name="form1" method="get" action="">
        <p>&nbsp;        </p>
        <table width="100%" border="0">
          <tbody>
            <tr>
              <td>Специальность</td>
              <td><select name="num" id="spec" style="size:landscape" >
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
              </select></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td><input type="submit" name="submit" id="submit" value="Выбрать" /></td>
            </tr>
          </tbody>
        </table>
        <p>
          <label for="spec"></label>
        </p>
        <p>&nbsp;</p>
      </form>
    </div>
    <hr />
    <div id="odn">    </div>
  
    
    <p>&nbsp;</p>
    <hr />
    <p>     <button id="pch">печать</button><button id="pch2">печать адресов доставки</button></p>
    <?php if ($totalRows_Recordset1 > 0) { // Show if recordset not empty ?>
    
    
   

  <table width="95%" border="1" cellpadding="2" cellspacing="2" id="pepe">
    <tr bgcolor="#FFFFFF">
	
   
      <td>Фамилия:</td>
      <td>дата</td>
       <td>Доп</td>
	<td>просм</td>
        
    </tr>
    <?php do { ?>
      <tr bgcolor="#FFFFFF" align="center">
     
		     <td ><?php echo $row_Recordset1['fio']; ?></td>
		      <td ><?php echo $row_Recordset1['dat']; ?></td>
		  	    <td id="dp<?php echo $row_Recordset1['num']; ?>" ><?php echo $row_Recordset1['dop']; ?></td>
		<td  ><button class="sho" data-num="<?php echo $row_Recordset1['num']; ?>" >Подробнее</button></td>
		  
      </tr>
      <?php } while ($row_Recordset1 =  /* fixed MMiC */ mysqli_fetch_assoc($Recordset1)); ?>
  </table>
  <?php } // Show if recordset not empty ?>
	  
	  
	  <hr>
	  
	  
	  <div id="addra"></div>
<!-- end .content --></div>
  
  <!-- end .container --></div>
   <script src="../js/jquery-1.11.3.min.js"></script>
	    <script src="printThis.js"></script>
  <script type="text/javascript">
	$(function() {
		
		$('#pch').on('click',function(){
		
		$('.npc').hide();
			$('#pepe').printThis({afterPrint:function(){	$('.npc').show();}});
		//	$('.npc').show();
	});	
				$('#pch2').on('click',function(){
		
		
			$('#addra').printThis({afterPrint:function(){}});
		//	$('.npc').show();
	});	
					
		
		
		fl=0;
		
		

$('.sho').on('click',function(){
num=$(this).data('num')	;
$.get( "add_dat_user.php",{'num':num,'dat':1}, function(data) {
$('#dp'+num).html(data);
 // alert( "success" );
});
	
			});
});
	
	
	</script>
</body>
</html>
<?php mysqli_free_result($spec); mysqli_free_result($Recordset1);?>
