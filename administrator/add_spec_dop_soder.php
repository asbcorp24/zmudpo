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
if ($_POST["num"]==0)	{ $sql="INSERT INTO `tm_spec_zn` (`num`, `spec_dop`, `znach`, `spec`) VALUES (NULL,".$_POST["dnum"].", '".$_POST["val"]."', ".$_POST["spec"].")";}
	
	else $sql="update `tm_spec_zn` set ".$_POST["name"]."='".$_POST["val"]."' where num=".$_POST["num"];	
DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
	echo $sql;
	exit(0);	
	
}

$currentPage = $_SERVER["PHP_SELF"];
//   /* fixed MMiC */ mysqli_select_db(DB::$link, $database_testmed);
if ((isset($_GET['del'])) && ($_GET['del'] != "")) {

  $deleteSQL = sprintf("DELETE FROM tm_spec_dop WHERE num=%s",
                       GetSQLValueString($_GET['del'], "int"));

   $Result1 =  /* fixed MMiC */ DB::Query($deleteSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
	}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}


$special=-1;
if (isset($_GET['spec']))$special=$_GET['spec'];
$query_spec = "SELECT 
  0 as num,
   `tm_spec_dop`.`num` as dnum,
  `tm_spec_dop`.`nazv`,
  '' as znach,0 as spec, `tm_spec_dop`.`type`
FROM
  `tm_spec_dop`
  where  `tm_spec_dop`.`num` not in (SELECT 
  `tm_spec_zn`.`spec_dop`
FROM
  `tm_spec_zn`
WHERE
  `tm_spec_zn`.`spec` =  $special)

union 
SELECT 
  `tm_spec_zn`.`num`,
    `tm_spec_dop`.`num` as dnum,
  `tm_spec_dop`.`nazv`,
  `tm_spec_zn`.`znach`, `tm_spec_zn`.`spec`, `tm_spec_dop`.`type`
FROM
  `tm_spec_zn`
  INNER JOIN `tm_spec_dop` ON (`tm_spec_zn`.`spec_dop` = `tm_spec_dop`.`num`)
WHERE
  `tm_spec_zn`.`spec` = $special ";
$spec = /* fixed MMiC */ DB::Query($query_spec, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
//	mysql_query($query_spec, $loc) or die(mysql_error());
$row_spec = mysqli_fetch_assoc($spec);
$totalRows_spec = mysqli_num_rows($spec);


$query_spec1 = "SELECT num, nazv FROM tm_spec ORDER BY nazv ASC";
$spec1 = /* fixed MMiC */ DB::Query($query_spec1, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
//	mysql_query($query_spec, $loc) or die(mysql_error());
$row_spec1 = mysqli_fetch_assoc($spec1);
$totalRows_spec = mysqli_num_rows($spec1);
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
<style>
	#dropZone {    
    color: #555;
    font-size: 18px;
    text-align: center;    
    
    width: 400px;
    padding: 50px 0;
    margin: 50px auto;
    
    background: #eee;
    border: 1px solid #ccc;
    
    -webkit-border-radius: 5px;
    -moz-border-radius: 5px;
    border-radius: 5px;
}

#dropZone.hover {
    background: #ddd;
    border-color: #aaa;
}

#dropZone.error {
    background: #faa;
    border-color: #f00;
}

#dropZone.drop {
    background: #afa;
    border-color: #0f0;
}
	
</style>
<div class="container">
  <div class="sidebar1">
 <?php include("menu.php")?>
    <p>&nbsp;</p>
    <!-- end .sidebar1 --></div>
  <div class="content">
    <h1>Добавление доп сведений к специальности</h1>
    <p>&nbsp;</p>
	  
	  <form action="<?php echo $editFormAction; ?>" method="get" enctype="multipart/form-data" name="form1" id="form1">
      <table align="center">
        <tr> <td colspan="2" nowrap="nowrap" align="right"><select name="spec">
            <option value="-1" >Всем</option>
            <?php 
do {  
?>
            <option value="<?php echo $row_spec1['num']?>" <?php if (!(strcmp($row_spec1['num'], $_GET['spec']))) {echo "selected=\"selected\"";} ?>><?php echo $row_spec1['nazv']?></option>
            <?php
} while ($row_spec1 = mysqli_fetch_assoc($spec1));
?>
          </select></td></tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">&nbsp;</td>
          <td><input type="submit" value="выбрать" /></td>
        </tr>
      </table>
      <input type="hidden" name="MM_insert" value="form1" />
    </form>
	  <hr>
   
    <p>&nbsp;</p>
    <table width="95%" border="1" class="mini">
      <tr>
        <td>num</td>
        
        <td>nazv</td>
		  <td>значение</td>
      </tr>
      <?php do { ?>
        <tr>
          <td><a href="?del=<?php echo $row_dosc['num']; ?>&file=<?php echo $row_dosc['path']; ?>">удалить</a></td>
          
          <td><?php echo $row_spec['nazv']; ?></td>
	      <td width="300" data-spec="<?php echo $_GET['spec']; ?>" data-num="<?php echo $row_spec['num']; ?>" data-dnum="<?php echo $row_spec['dnum']; ?>" data-name="znach"><?php echo $row_spec['znach']; ?></td>
        </tr>
        <?php } while ($row_spec = mysqli_fetch_assoc($spec)); ?>
    </table>
<!-- end .content --></div>
  <div class="sidebar2">
    <h4>меню</h4>
    <p><a href="add_spec_dop.php">Доп сведения спец</a></p>
    <p><a href="add_spec_dop_soder.php">Значения сведений</a></p>
    <p>&nbsp;</p>
    <!-- end .sidebar2 --></div>
  <!-- end .container --></div>
  <script src="../js/jquery-1.11.3.min.js"></script>
  <script type="text/javascript">
	$(function() {
		fl=0;
		$('td').on('click',function(){
		if ($(this).data('num')==undefined) return;
			if (fl==0){
			console.log($(this));fl=1;
	//	console.log($('#p'+$(this).prop('name')));
				if ($(this).data('tip')==undefined)tip="text"; else tip= $(this).data('tip');
$(this)[0].innerHTML='<input type="'+tip+'" name="textfield" id="textfield" value="'+$(this)[0].innerText+'"><input type="button" name="button" id="btg" value="V">';}
		});

	$('body').on('click', '#btg',function(){
			spec=$(this).parent().data('spec');
			num=$(this).parent().data('num');
		dnum=$(this).parent().data('dnum');
				name=$(this).parent().data('name');
				val=$(this).parent().children().first().val();
			par=	$(this).parent();fl=0;
			$(this).parent().children().remove();$(par).append(val)
			$.post('add_spec_dop_soder.php', {'upd':'1', 'num' :num,'name':name,'val':val,'dnum':dnum,'spec':spec},
		function(data) {
			
	
		});});});
	
	
	</script>
</body>
</html>
<?php
mysqli_free_result($spec);

mysqli_free_result($dosc);
?>
