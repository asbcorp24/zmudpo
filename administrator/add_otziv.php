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

$currentPage = $_SERVER["PHP_SELF"];


if ((isset($_POST["upd"])) && ($_POST["upd"] == "1")) {
$sql="update `tm_otziv` set ".$_POST["name"]."='".$_POST["val"]."' where num=".$_POST["num"];	
DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link)); exit(0);	
	
}


//   /* fixed MMiC */ mysqli_select_db(DB::$link, $database_testmed);
if ((isset($_GET['del'])) && ($_GET['del'] != "")) {
if (file_exists('../docs/'.$_GET['file'])) unlink('../docs/'.$_GET['file']);
  $deleteSQL = sprintf("DELETE FROM tm_otziv WHERE num=%s",
                       GetSQLValueString($_GET['del'], "int"));

   $Result1 =  /* fixed MMiC */ DB::Query($deleteSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
	}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}




if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {



	$filenameimgf =uniqid().'.jpg';
if (($_FILES['fimg'][error]==0)){
$image = new Imagick($_FILES['fimg']['tmp_name']);

	$image->adaptiveResizeImage(270,340);

	$data = $image->getImageBlob(); 
file_put_contents ('../timg/'.$filenameimgf, $data); } else $filenameimgf="null";



  $insertSQL = sprintf("INSERT INTO `tm_otziv` (`num`, `dat`, `nazv`, `img`, `comment`) VALUES (NULL, %s, %s, %s, %s)",
                     
                       GetSQLValueString($_POST['dat'], "date"),
                       GetSQLValueString($_POST['nazv'], "text"),
					     GetSQLValueString($filenameimgf, "text"),
					  GetSQLValueString($_POST['comm'], "text") );

  $Result1 =  /* fixed MMiC */ DB::Query($insertSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
	echo $insertSQL;
 // $Result1 = mysql_query($insertSQL, $loc) or die(mysql_error());
}

$query_spec = "SELECT * FROM tm_otziv ORDER BY dat ASC";
$spec = /* fixed MMiC */ DB::Query($query_spec, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
//	mysql_query($query_spec, $loc) or die(mysql_error());
$row_spec = mysqli_fetch_assoc($spec);
$totalRows_spec = mysqli_num_rows($spec);


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>документы</title>
<link href="thrColLiq.css" rel="stylesheet" type="text/css" /><!--[if lte IE 7]>
<style>
.content { margin-right: -1px; } /* это отрицательное поле в 1 пиксел можно поместить в любом столбце данного макета с таким же корректирующим эффектом. */
ul.nav a { zoom: 1; }  /* свойство масштабирования предоставляет IE триггер hasLayout, необходимый для удаления лишнего пустого пространства между ссылками */
</style>
<![endif]-->
<script type="text/javascript">

function tstFile(val){
  var v = val.value;
  var v = v.search(/^.*\.(?:pdf|docx)\s*$/ig)
  if(v!=0){
     alert("Загружаем только pdf и word");
     var pk = document.getElementById("path");
	 pk.value="";
	 
//	 $('#Reset').click();
  }
}
</script>

</head>

<body>
<style>


</style>
<div class="container">
  <div class="sidebar1">
 <?php include("menu.php")?>
    <p>&nbsp;</p>
    <!-- end .sidebar1 --></div>
  <div class="content">
    <h1>Добавление отзывов</h1>
    <p>&nbsp;</p>
    <form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data" name="form1" id="form1">
      <table width="382" border="0" align="center">
        <tr valign="baseline">
          <td width="122" align="right" nowrap="nowrap">Dat:</td>
          <td width="150" align="left"><input type="date" name="dat" value="" size="32" /></td>
        </tr>
        
        <tr valign="baseline">
          <td width="122" align="right" nowrap="nowrap">Рисунок: 270*340</td>
          <td width="150" align="left"><input type="file" name="fimg" id="fimg" /></td>
        </tr>
        <tr valign="baseline">
          <td width="122" align="right" nowrap="nowrap">ФИО:</td>
          <td width="150" align="left"><input type="text" name="nazv" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td width="122" align="right" nowrap="nowrap">коммент</td>
		  <td width="150" align="left"><textarea name="comm"></textarea></td>
        </tr>
        <tr valign="baseline">
          <td width="122" align="right" nowrap="nowrap">&nbsp;</td>
          <td width="150" align="left"><input id="Reset" type="reset" />
          <input type="submit" value="Вставить запись" /></td>
        </tr>
      </table>
      <input type="hidden" name="MM_insert" value="form1" />
    </form>
    <p>&nbsp;</p>
    <table border="1" style="max-width: 800px">
      <tr>
        <td>num</td>
        <td>spec</td>
        <td>path</td>
        <td>dat</td>
        <td>коммент</td>
      </tr>
      <?php do { ?>
        <tr>
          <td><a href="?del=<?php echo $row_spec['num']; ?>&file=<?php echo $row_spec['path']; ?>">удалить</a></td>
          <td data-num="<?php echo $row_spec['num']; ?>" data-name="dat"><?php echo $row_spec['dat']; ?></td>
          <td data-num="<?php echo $row_spec['num']; ?>" data-name="nazv"><?php echo $row_spec['nazv']; ?></td>
          <td><?php echo $row_spec['img']; ?></td>
          <td class="mini" data-num="<?php echo $row_spec['num']; ?>" data-name="comment"><?php echo $row_spec['comment']; ?></td>
			  
        </tr>
        <?php } while ($row_spec = mysqli_fetch_assoc($spec)); ?>
    </table>
<!-- end .content --></div>
  <div class="sidebar2">
    <h4>&nbsp;</h4>
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
			
			num=$(this).parent().data('num')
				name=$(this).parent().data('name');
				val=$(this).parent().children().first().val();
			par=	$(this).parent();fl=0;
			$(this).parent().children().remove();$(par).append(val)
			$.post('add_otziv.php', {'upd':'1', 'num' :num,'name':name,'val':val},
		function(data) {
			
	
		});});});
  	
  </script>
</body>
</html>
<?php
mysqli_free_result($spec);

mysqli_free_result($dosc);
?>
