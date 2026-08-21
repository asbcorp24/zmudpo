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
print_r($_POST);
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO `tm_typsv_name` (`num`, `name`) VALUES (NULL, %s)",
                       GetSQLValueString($_POST['name'], "text"));

 
  $Result1 =  DB::Query($insertSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
}

if ((isset($_GET['del'])) && ($_GET['del'] != "")) {
  $deleteSQL = sprintf("DELETE FROM tm_typsv_konf WHERE num=%s",
                       GetSQLValueString($_GET['del'], "int"));

   /* fixed MMiC */ mysqli_select_db(DB::$link, $database_testmed);
  $Result1 =  /* fixed MMiC */ DB::Query($deleteSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
}

if ((isset($_POST["upd"])) && ($_POST["upd"] == "1")) {
$sql="update `tm_typsv_konf` set ".$_POST["name"]."='".$_POST["val"]."' where num=".$_POST["num"];	
DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link)); exit(0);	
	
}
if ((isset($_POST["konf"]))) {
	$poi=0;
	if (isset($_POST['poi']))$poi=1;
  $insertSQL = sprintf("INSERT INTO `tm_typsv_konf` (`num`, `nazv`, `typ`, `poi`, `konf`, `polosh`,`list`) VALUES (NULL, %s,  %s,  %s, %s, %s,%s)",
                       GetSQLValueString($_POST['nazv'], "text"),
					   GetSQLValueString($_POST['typ'], "int"),
					    GetSQLValueString($poi, "int"),
					   GetSQLValueString($_POST["konf"], "int"),
					    GetSQLValueString($_POST["polosh"], "int"),
					    GetSQLValueString($_POST["list"], "text")
					  
					  );

 
  $Result1 =  DB::Query($insertSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
}
//INSERT INTO `tm_typsv_konf` (`num`, `nazv`, `typ`, `poi`, `konf`, `polosh`) VALUES (NULL, 'цуцу', 1, 0, 1, NULL)

$query_menu = "SELECT * FROM tm_typsv_name ORDER BY name ASC";
$menu =  DB::Query($query_menu, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_menu = mysqli_fetch_assoc($menu);
$totalRows_menu = mysqli_num_rows($menu);


$ntyp=-1;
if(isset($_GET['ntyp']))$ntyp=(int)$_GET['ntyp'];

$query_menu = "SELECT * FROM `tm_typsv_konf` WHERE `konf` = ".$ntyp." ORDER BY polosh ASC";
$menu2 =  DB::Query($query_menu, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_menu2 = mysqli_fetch_assoc($menu2);
$totalRows_menu2 = mysqli_num_rows($menu2);
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
    <h1>Добавление типов сведений для анкет нмо</h1>
    <p>&nbsp;</p>
	  <details>
			<summary>Новая группа</summary>
			
    <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
      <table align="center">
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">название:</td>
          <td><input type="text" name="name" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">&nbsp;</td>
          <td><input type="submit" value="Вставить запись" /></td>
        </tr>
      </table>
      <input type="hidden" name="MM_insert" value="form1" />
    </form>
		</details>

  
<hr />
    <form action="" method="get" name="form2" id="form2" title="Выбрать">
	<select name="ntyp">
		 
            <?php 
do {  
?>
            <option value="<?php echo $row_menu['num']?>"<?php if (!(strcmp($row_menu['num'], $_GET['ntyp']))) {echo "selected=\"selected\"";} ?> ><?php echo $row_menu['name']?></option>
            <?php
} while ($row_menu = mysqli_fetch_assoc($menu));
?>
		
		
		</select>
      <input type="submit" name="submit" id="submit" value="Выбрать" />
    </form>
    <form id="form3" name="form3" method="post" action="">
      <table align="center">
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">название:</td>
          <td><input name="nazv" type="text" id="nazv" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Тип</td>
          <td><select name="typ" id="typ">
           
            <option value="0" >Текст</option>
			      <option value="1" >Дата</option>
			      <option value="2" >Число</option>
			      <option value="3" >Файл</option>
			    <option value="4" >Список</option>
			      

          </select></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">будет поиск</td>
          <td><input name="poi" type="checkbox" id="poi" value="1" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">положение в тексте:</td>
          <td><input name="polosh" type="number" id="polosh" max="1000" min="0" />
          <input name="konf" type="hidden" id="konf" value="<?php echo $ntyp ?>" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Список значений</td>
          <td><textarea name="list"></textarea></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">&nbsp;</td>
          <td><input type="submit" value="Вставить запись" /></td>
        </tr>
      </table>
    </form>
    <p>&nbsp;</p>
	  <details align="left">
			<summary>? подсказка</summary>
			<ul>
				<li>Тип: 0- текст,1-дата,2-Число,3-Файл</li>
				<li>поиск: 1-будет поиск</li>
				
			</ul>
		</details>

    <table width="99%" border="1">
      <tr>
        <td>num</td>
        <td>Название</td>
        <td>Тип</td>
		    <td>Поиск</td>
		    <td>Положение</td>
      </tr>
      <?php do { ?>
        <tr>
          <td><a href="?del=<?php echo $row_menu2['num']; ?>&ntyp=<?php echo $ntyp; ?>">Удалить</a></td>
          <td  data-num="<?php echo $row_menu2['num']; ?>" data-name="nazv"><?php echo $row_menu2['nazv']; ?></td>
          <td  data-num="<?php echo $row_menu2['num']; ?>" data-name="typ"><?php echo $row_menu2['typ']; ?></td>
			<td  data-num="<?php echo $row_menu2['num']; ?>" data-name="poi"><?php echo $row_menu2['poi']; ?></td>
			<td  data-num="<?php echo $row_menu2['num']; ?>" data-name="polosh"><?php echo $row_menu2['polosh']; ?></td>
        </tr>
        <?php } while ($row_menu2 = mysqli_fetch_assoc($menu2)); ?>
    </table>
<!-- end .content --></div>
  <div class="sidebar2">
    <h4>&nbsp;</h4>
    <!-- end .sidebar2 --></div>
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
			$.post('add_typsv_nmo.php', {'upd':'1', 'num' :num,'name':name,'val':val},
		function(data) {
			
	
		});});});
	
	
	</script>
	
	
</body>
</html>
<?php
mysqli_free_result($menu);

 
?>
