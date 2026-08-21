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

if ((isset($_POST["upd"])) && ($_POST["upd"] == "1")) {
$sql="update `tm_spec` set ".$_POST["name"]."='".$_POST["val"]."' where num=".$_POST["num"];	
DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
	echo $sql;
	exit(0);	
	
}


if ((isset($_GET['del'])) && ($_GET['del'] != "")) {
  $deleteSQL = sprintf("DELETE FROM tm_spec WHERE num=%s",
                       GetSQLValueString($_GET['del'], "int"));

   /* fixed MMiC */ mysqli_select_db(DB::$link, $database_testmed);
  $Result1 =  /* fixed MMiC */ DB::Query($deleteSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
}

if (isset($_GET["upd"])) {
if (isset($_GET["zn"]) and ($_GET["zn"]!='')){//$tm=1;
if ($_GET["zn"]==1) $tm=0;else $tm=1;
  $updateSQL = sprintf("UPDATE tm_spec SET actiiv=%s WHERE num=%s",
                       GetSQLValueString($tm, "int"),
                       GetSQLValueString($_GET['upd'], "int"));
   /* fixed MMiC */ mysqli_select_db(DB::$link, $database_testmed);
  $Result1 =  /* fixed MMiC */ DB::Query($updateSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
						 
}else
if (isset($_GET["zp"])and ($_GET["zp"]!='')){//$tm=1;
if ($_GET["zp"]==1) $tm=0;else $tm=1;
  $updateSQL = sprintf("UPDATE tm_spec SET zap=%s WHERE num=%s",
                       GetSQLValueString($tm, "int"),
                       GetSQLValueString($_GET['upd'], "int"));
   /* fixed MMiC */ mysqli_select_db(DB::$link, $database_testmed);
  $Result1 =  /* fixed MMiC */ DB::Query($updateSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));


}
if (isset($_GET["kr"])and ($_GET["kr"]!='')){//$tm=1;
if ($_GET["kr"]==1) $tm=0;else $tm=1;
  $updateSQL = sprintf("UPDATE tm_spec SET kr=%s WHERE num=%s",
                       GetSQLValueString($tm, "int"),
                       GetSQLValueString($_GET['upd'], "int"));
   /* fixed MMiC */ mysqli_select_db(DB::$link, $database_testmed);
  $Result1 =  /* fixed MMiC */ DB::Query($updateSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
}	
	if (isset($_GET["gl"])and ($_GET["gl"]!='')){//$tm=1;
if ($_GET["gl"]==1) $tm=0;else $tm=1;
  $updateSQL = sprintf("UPDATE tm_spec SET gl=%s WHERE num=%s",
                       GetSQLValueString($tm, "int"),
                       GetSQLValueString($_GET['upd'], "int"));
   /* fixed MMiC */ mysqli_select_db(DB::$link, $database_testmed);
  $Result1 =  /* fixed MMiC */ DB::Query($updateSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
}
	echo $updateSQL;
exit(0);	
}
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
 	$filenameimg =uniqid().'.jpg';
		
   //move_uploaded_file($_FILES['fimg']['tmp_name'],'timg/'.$filename);
	
$image = new Imagick($_FILES['img']['tmp_name']);

	$image->adaptiveResizeImage(700,400);

	$data = $image->getImageBlob(); 
file_put_contents ('../timg/'.$filenameimg, $data); 
	
 
  $insertSQL = sprintf("INSERT INTO tm_spec (nazv, dat, img, actiiv,kr,about,sert) VALUES (%s, %s, %s, %s,%s,%s,%s)",
                       GetSQLValueString($_POST['nazv'], "text"),
                       GetSQLValueString($_POST['dat'], "date"),
                       GetSQLValueString($filenameimg, "text"),
                       GetSQLValueString(($_POST['actiiv']=="on") ? "1" : "0","int" ),
					   GetSQLValueString(($_POST['kr']=="on") ? "1" : "0","int" ),
					    GetSQLValueString($_POST['about'], "text"),  GetSQLValueString($_POST['sert'], "int")
					   );

   /* fixed MMiC */ mysqli_select_db(DB::$link, $database_testmed);
  $Result1 =  /* fixed MMiC */ DB::Query($insertSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
}

 /* fixed MMiC */ mysqli_select_db(DB::$link, $database_testmed);
$query_spec = "SELECT * FROM tm_spec ORDER BY dat ASC";

if ((isset($_GET["nmo"])) ) {$kr=(int)$_GET["nmo"];; $query_spec = "SELECT * FROM tm_spec  where kr=$kr ORDER BY dat ASC";}

$spec =  /* fixed MMiC */ DB::Query($query_spec, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_spec =  /* fixed MMiC */ mysqli_fetch_assoc($spec);
$totalRows_spec =  /* fixed MMiC */ mysqli_num_rows($spec);

$query_spec = "SELECT * FROM tm_spec_type ";

$spectyp =  /* fixed MMiC */ DB::Query($query_spec, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_spectyp =  /* fixed MMiC */ mysqli_fetch_assoc($spectyp);
$totalRows_spectyp =  /* fixed MMiC */ mysqli_num_rows($spectyp);


$ssql="SELECT 
  `tm_sert`.`num`,
  `tm_sert`.`nazv`,
  `tm_sert`.`path`
FROM
  `tm_sert`";
$spectyps =  /* fixed MMiC */ DB::Query($ssql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_spectyps =  /* fixed MMiC */ mysqli_fetch_assoc($spectyps);
$totalRows_spectyps =  /* fixed MMiC */ mysqli_num_rows($spectyps);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Специальности</title>
<link href="thrColLiq.css" rel="stylesheet" type="text/css" /><!--[if lte IE 7]>
<style>
.content { margin-right: -1px; } /* это отрицательное поле в 1 пиксел можно поместить в любом столбце данного макета с таким же корректирующим эффектом. */
ul.nav a { zoom: 1; }  /* свойство масштабирования предоставляет IE триггер hasLayout, необходимый для удаления лишнего пустого пространства между ссылками */
</style>
<![endif]-->
 <style>
   .size {
    white-space: nowrap; /* Отменяем перенос текста */
    overflow: hidden; /* Обрезаем содержимое */
    background: #fc0; /* Цвет фона */
    padding: 5px; /* Поля */
   }
   .cope_text {
	overflow: hidden;
	line-height: 20px;
}
.cope_text p {
	margin: 0 0 0 0;
}
.line-clamp {
	display: -webkit-box;
	-webkit-line-clamp: 4;
	-webkit-box-orient: vertical;
}
  </style>
<script type="text/javascript">
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
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
<?php include("menu.php"); ?>
    <p>&nbsp;</p>
    <!-- end .sidebar1 --></div>
  <div class="content" style="max-width: 400px;
">
    <h1>Добавление специальности</h1>
    <p><a href="add_typ_spec.php">Типы специальностей</a></p>
    <form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data" name="form1" id="form1">
      <table align="center" cellpadding="2" cellspacing="2">
        <tr valign="baseline">
          <td align="right" valign="top" nowrap="nowrap">Название:</td>
          <td align="left"><input type="text" name="nazv" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td align="right" valign="top" nowrap="nowrap">Дата:</td>
          <td align="left"><input type="date" id="dat" name="dat"/></td>
        </tr>
        <tr valign="baseline">
          <td align="right" valign="top" nowrap="nowrap">Рисунок:</td>
          <td align="left"><label for="img"></label>
          <input type="file" name="img" id="img" /></td>
        </tr>
        <tr valign="baseline">
          <td align="right" valign="top" nowrap="nowrap">Активна:</td>
          <td align="left"><input type="checkbox" name="actiiv" value="" /></td>
        </tr>
        <tr valign="baseline">
          <td align="right" valign="top" nowrap="nowrap">НМО</td>
          <td align="left"><select name="kr" id="nmo">
			  <?php do { ?>
		  <option value="<?php echo $row_spectyp['num'];?>"><?php echo $row_spectyp['nazv'];?></option>
		 <?php } while ($row_spectyp =  /* fixed MMiC */ mysqli_fetch_assoc($spectyp));
			  mysqli_data_seek($spectyp,0);
			  
			  ?>
		</select></td>
        </tr>
        
          <tr valign="baseline">
          <td align="right" valign="top" nowrap="nowrap">Сертификат</td>
          <td align="left"><select name="sert" id="sert">
            <?php do { ?>
            <option value="<?php echo $row_spectyps['num'];?>"><?php echo $row_spectyps['nazv'];?></option>
            <?php } while ($row_spectyps =  /* fixed MMiC */ mysqli_fetch_assoc($spectyps));
			  mysqli_data_seek($spectyps,0);
			  
			  ?>
          </select>          </tr>
        
        <tr valign="baseline">
          <td align="right" valign="top" nowrap="nowrap">О специальности</td>
          <td align="left"><textarea name="about"></textarea></td>
        </tr>
        <tr valign="baseline">
          <td align="right" valign="top" nowrap="nowrap">&nbsp;</td>
          <td align="left"><input type="submit" value="Вставить запись" /></td>
        </tr>
      </table>
      <input type="hidden" name="MM_insert" value="form1" />
    </form>
    <hr />
    <form id="form2" name="form2" method="get" action="">
		<select name="nmo" id="nmo">
			 <?php do { ?>
		  <option value="<?php echo $row_spectyp['num'];?>" <?php if (!(strcmp($row_spectyp['num'], $_GET['nmo']))) {echo "selected=\"selected\"";} ?>><?php echo $row_spectyp['nazv'];?></option>
		 <?php } while ($row_spectyp =  /* fixed MMiC */ mysqli_fetch_assoc($spectyp));
			  mysqli_data_seek($spectyp,0);
			  
			  ?>
		
		</select>
	
      <input type="submit" name="submit" id="submit" value="Выбрать" />
      
    </form>
    <p><details align="left">
			<summary>Подсказки нмо</summary>
			<ul>
				
				 <?php do { ?>
				<li><?php echo $row_spectyp['num'];?>-<?php echo $row_spectyp['nazv'];?></li>
			 <?php } while ($row_spectyp =  /* fixed MMiC */ mysqli_fetch_assoc($spectyp));?>
			</ul>
		</details>
		<details align="left">
			<summary>Подсказки сертификаты</summary>
			<ul>
				
				 <?php do { ?>
				<li><?php echo $row_spectyps['num'];?>-<?php echo $row_spectyps['nazv'];?></li>
			 <?php } while ($row_spectyps =  /* fixed MMiC */ mysqli_fetch_assoc($spectyps));?>
			</ul>
		</details>
</p>
    <table width="95%" border="1">
      <tr>
        <td >Действие</td>
        <td>nazv</td>
        <td>dat</td>
        <td>о специальности</td>
        <td>actiiv</td>
           <td>Раздел</td>
              <td>Категории</td>
        <td>Идет запись</td>
		     <td>НМО</td>
		       <td>часы</td>
		         <td>цена</td>
		  <td>На главной</td>
		    <td>Серт</td>
        <td>слушатели</td>
      </tr>
      <?php do { ?>
        <tr class="mini">
          <td><a href="?del=<?php echo $row_spec['num']; ?>">Удалить</a></td>
         
          <td data-num="<?php echo $row_spec['num']; ?>" data-name="nazv"><?php echo $row_spec['nazv']; ?></td>
          <td class="mini" data-num="<?php echo $row_spec['num']; ?>" data-name="dat"><?php echo $row_spec['dat']; ?></td>
              <td class="mini" data-num="<?php echo $row_spec['num']; ?>" data-name="about" width="100px"><p class="cope_text line-clamp"><?php echo $row_spec['about']; ?></p></td>
          <td align="center"><input name="act" type="checkbox" id="act" class="cha" data-p="zn" data-num="<?php echo $_GET['num'] ?>" data-upd="<?php echo $row_spec['num']; ?>" data-zn="<?php echo $row_spec['actiiv']; ?>" data-nmo="<?php echo $_GET['nmo']; ?>"   <?php if (!(strcmp($row_spec['actiiv'],1))) {echo "checked=\"checked\"";} ?> />
         </td>
         
          <td class="mini" data-num="<?php echo $row_spec['num']; ?>" data-name="razdel"><?php echo $row_spec['razdel']; ?></td>
           <td class="mini" data-num="<?php echo $row_spec['num']; ?>" data-name="kategor"><?php echo $row_spec['kategor']; ?></td>
          <td align="center"> <input name="act" type="checkbox"   class="cha" id="act"  data-p="zp" data-upd="<?php echo $row_spec['num']; ?>" data-nmo="=<?php echo $_GET['nmo']; ?>" data-zp="<?php echo $row_spec['zap']; ?>"
									 
									 <?php if (!(strcmp($row_spec['zap'],1))) {echo "checked=\"checked\"";} ?> /></td>
	 <td class="mini" data-num="<?php echo $row_spec['num']; ?>" data-name="kr"><?php echo $row_spec['kr']; ?></td>
			     
          <td data-num="<?php echo $row_spec['num']; ?>" data-name="chas"><?php echo $row_spec['chas']; ?></td>
           <td data-num="<?php echo $row_spec['num']; ?>" data-name="cena"><?php echo $row_spec['cena']; ?></td>
	 <td align="center"> <input name="kr" type="checkbox"   class="cha" id="act"  data-p="gl" data-upd="<?php echo $row_spec['num']; ?>" data-nmo="=<?php echo $_GET['nmo']; ?>" data-gl="<?php echo $row_spec['gl']; ?>" <?php if (!(strcmp($row_spec['gl'],1))) {echo "checked=\"checked\"";} ?> /></td>
			     <td data-num="<?php echo $row_spec['num']; ?>" data-name="sert"><?php echo $row_spec['sert']; ?></td>
          <td><a href="add_spec_user.php?num=<?php echo $row_spec['num']; ?>&spec=<?php echo $row_spec['nazv']; ?>">Настроить</a></td>
        </tr>
        <?php } while ($row_spec =  /* fixed MMiC */ mysqli_fetch_assoc($spec)); ?>
    </table>
<!-- end .content --></div>
 
  <!-- end .container --></div>
   <script src="../js/jquery-1.11.3.min.js"></script>
  <script type="text/javascript">
	$(function() {
		fl=0;
		$('.cha').on('click',function(){
	
	num=$(this).data('num')	;
			z=$(this).data('p')	;
	upd=$(this).data('upd')	;
			zn=null;zp=null;kr=null;gl=null;
			console.log(z);
			if (z=="zn")if ($(this).prop('checked')!=true)zn=1 ; else zn=0;
			if (z=="kr")if ($(this).prop('checked')!=true)kr=1 ; else kr=0;
				if (z=="zp")if ($(this).prop('checked')!=true)zp=1 ; else zp=0;
			if (z=="gl")if ($(this).prop('checked')!=true)gl=1 ; else gl=0;
	nmo=$(this).data('nmo')	;
	 $.get( "add_spec.php",{'num':num,'upd':upd,'zn':zn,'nmo':nmo,'zp':zp,'kr':kr,'gl':gl}, function() {
 // alert( "success" );
});
		});
		
		
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
			$.post('add_spec.php', {'upd':'1', 'num' :num,'name':name,'val':val},
		function(data) {
			
	
		});});});
	
	
	</script>
</body>
</html>
<?php
 /* fixed MMiC */ mysqli_free_result($spec);
?>
