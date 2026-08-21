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

$grupp=-1;
$razdel=-1;
if (isset($_POST['grupp']))$_SESSION['grupp']=$_POST['grupp'];
if (isset($_POST['razdel']))$_SESSION['razdel']=$_POST['razdel'];
if ((isset($_POST["upd"])) && ($_POST["upd"] == "1")) {
$sql="update `tm_string_array_grupp` set ".$_POST["name"]."='".$_POST["val"]."' where id=".$_POST["num"];	
DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link)); exit(0);	
	
}
var_dump($_POST);
if ((isset($_POST["del"])) && ($_POST["del"] == "1")) {
$sql="delete from `tm_string_array`  where id=".$_POST["num"];	
DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link)); exit(0);	
	
}
?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
  //  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
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
//print_r($_POST);
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
	
	  $ss=$_POST['fio'];
	  $res=explode(PHP_EOL,$ss);
	  echo count($res);
	 for ($i=0;$i<=count($res);$i++){
	
  $insertSQL = sprintf("INSERT INTO `tm_string_array` (`id`, `value`, `razd`, `grupp`) VALUES (NULL, %s, %s,%s)",
                       GetSQLValueString($res[$i], "text"),GetSQLValueString($_SESSION['razdel'], "int"),GetSQLValueString($_SESSION['grupp'], "int") );
		
   /* fixed MMiC */ mysqli_select_db(DB::$link, $database_testmed);
  $Result1 =  /* fixed MMiC */ DB::Query($insertSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));

	} 

}


$query_menu = "SELECT * FROM tm_string_array_razdel ORDER BY nazv ASC";
$query_menu = "SELECT * FROM tm_string_array_razdel ORDER BY nazv ASC";
$menu2 =  DB::Query($query_menu, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_menu2 = mysqli_fetch_assoc($menu2);
$totalRows_menu2 = mysqli_num_rows($menu2);



$query_menu = "SELECT * FROM tm_string_array_grupp ORDER BY nazv ASC";
$menu1 =  DB::Query($query_menu, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_menu1 = mysqli_fetch_assoc($menu1);
$totalRows_menu1 = mysqli_num_rows($menu1);



$query_menu = "SELECT * FROM tm_string_array where razd=".$razdel." and grupp=".$grupp." ORDER BY value ASC";
$menu3 =  DB::Query($query_menu, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_menu3 = mysqli_fetch_assoc($menu3);
$totalRows_menu3 = mysqli_num_rows($menu3);
?>
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

<div class="container">
  <div class="sidebar1">
<?php include("menu.php"); ?>
    <p>&nbsp;</p>
    <!-- end .sidebar1 --></div>
  <div class="content">
    <h1>Добавление строк в Array String</h1>
    <p>&nbsp;</p>
    <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
      <table align="center">
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">группа:</td>
          <td><select name="grupp">
			  <?php do{ ?>
			  <option value="<?php echo $row_menu1['id'] ?>" <?php if($_SESSION['grupp']==$row_menu1['id'] ) echo "selected"; ?>><?php echo $row_menu1['nazv'] ?></option>
			            <?php
} while ($row_menu1 =  /* fixed MMiC */ mysqli_fetch_assoc($menu1));
			  mysqli_data_seek($menu1, 0);
			  
			  ?>
			  </select>&nbsp;</td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Раздел</td>
          <td><select name="razdel">
			  <?php do{ ?>
			  <option value="<?php echo $row_menu2['id'] ?>" <?php if($_SESSION['razdel']==$row_menu2['id'] ) echo "selected"; ?>><?php echo $row_menu2['nazv'] ?></option>
			            <?php
} while ($row_menu2 =  /* fixed MMiC */ mysqli_fetch_assoc($menu2));
			  mysqli_data_seek($menu2, 0);
			  
			  ?>
			  </select></td>
        </tr>
        <tr valign="baseline">
          <td colspan="2" align="right" nowrap="nowrap">&nbsp;</td>
        </tr>
        <tr valign="baseline">
          <td colspan="2" align="right" nowrap="nowrap"><input type="submit" value="Выбрать" /></td>
        </tr>
      </table>
      <input type="hidden" name="MM_insert2" value="form1" />
    </form>
    <p>&nbsp;</p>
<hr />
    <form id="form2" name="form2" method="post" action="">
		    <table width="319" border="1">
      <tbody>
        <tr>
          <th colspan="2" scope="col">Строки</th>
        </tr>
        <tr>
          <td colspan="2"><textarea name="fio" id="fio" cols="45" rows="5"></textarea></td>
        </tr>
        <tr>
          <td width="256"><input type="submit" name="submit" id="submit" value="Отправить" /></td>
          <td width="47">&nbsp;</td>
			<input type="hidden" name="MM_insert" value="form1" />
        </tr>
      </tbody>
    </table>
    </form>

    <p>&nbsp;</p>
    <table width="99%" border="1" class="mini">
      <tr>
        <td>num</td>
        <td>name</td>
		  
        </tr>
      <?php do { ?>
        <tr>
          <td><button class="del" data-id="<?php echo $row_menu3['id']; ?>">Удалить</button></td>
          <td  data-num="<?php echo $row_menu3['id']; ?>" data-name="nazv"><?php echo $row_menu3['value']; ?></td>
            
        </tr>
        <?php } while ($row_menu3 = mysqli_fetch_assoc($menu3)); ?>
    </table>
<!-- end .content --></div>
  <div class="sidebar2">
    <h4><a href="add_array_nazv.php">Раздел</a></h4>
    <p><a href="add_array_grupp.php">Группа</a></p>
    <p><a href="add_array_string.php">Содержимое</a></p>
    <!-- end .sidebar2 --></div>
  <!-- end .container --></div>
	   <script src="../js/jquery-1.11.3.min.js"></script>
	    <script src="printThis.js"></script>
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
$('.del').on('click',function(){
	num=$(this).data('id')
	nn=$(this);
		$.post('add_array_string.php', {'del':'1', 'num' :num},
		function(data) {
			$(nn).parent().parent().remove();
			
	
		});
	
});

	$('body').on('click', '#btg',function(){
			
			num=$(this).parent().data('num')
				name=$(this).parent().data('name');
				val=$(this).parent().children().first().val();
			par=	$(this).parent();fl=0;
			$(this).parent().children().remove();$(par).append(val)
			$.post('add_array_string.php', {'upd':'1', 'num' :num,'name':name,'val':val},
		function(data) {
			
	
		});});});
	
	
	</script>
</body>
</html>
<?php
mysqli_free_result($menu);

 
?>
