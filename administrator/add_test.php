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
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["upd"])) && ($_POST["upd"] == "1")) {
$sql="update `tm_test` set ".$_POST["name"]."='".$_POST["val"]."' where num=".$_POST["num"];	

DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));

exit(0);	
	
}




if ((isset($_GET['del'])) && ($_GET['del'] != "")) {
  $deleteSQL = sprintf("DELETE FROM tm_test WHERE num=%s",
                       GetSQLValueString($_GET['del'], "int"));

   /* fixed MMiC */ mysqli_select_db(DB::$link, $database_testmed);
  $Result1 =  /* fixed MMiC */ DB::Query($deleteSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
	$filenamep="";$filotv =uniqid().'.pdf';
	$filenameimg =uniqid().'.jpg';
if (getExtension1($_FILES['test_file']['name'])=="zip")	{
	
	$filenamep=uniqid().'/';
	$zip = new ZipArchive; 
   $zip->open($_FILES['test_file']['tmp_name']); 
   $zip->extractTo('../testy/'.$filenamep); 
	 $filename =$filenamep.'index.html';
    $zip->close(); 
} else {
	
 $filename =uniqid().'.html';
 
	move_uploaded_file($_FILES['test_file']['tmp_name'],'../testy/'.$filename);
}
	
	if (isset($_FILES['fotv']))
		move_uploaded_file($_FILES['fotv']['tmp_name'],'../otv/'.$filotv);
    //move_uploaded_file($_FILES['fimg']['tmp_name'],'timg/'.$filename);
	
$image = new Imagick($_FILES['fimg']['tmp_name']);

	$image->adaptiveResizeImage(140,140);

	$data = $image->getImageBlob(); 
file_put_contents ('../timg/'.$filenameimg, $data); 
	
	
	$insertSQL = sprintf("INSERT INTO tm_test (`path`, dat, nazv,img,col_v,tex) VALUES (%s, %s, %s,%s,%s,%s)",
                       GetSQLValueString($filename, "text"),
                       GetSQLValueString($_POST['test_date'], "date"),
                       GetSQLValueString($_POST['nazv'], "text"),
						GetSQLValueString($filenameimg, "text"),
						GetSQLValueString($_POST['cv'], "text"),
						GetSQLValueString($filotv, "text"));
   /* fixed MMiC */ mysqli_select_db(DB::$link, $database_testmed);
  $Result1 =  /* fixed MMiC */ DB::Query($insertSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
  
  
}

 /* fixed MMiC */ mysqli_select_db(DB::$link, $database_testmed);
$query_testy = "SELECT * FROM tm_test ORDER BY num ASC";
$testy =  /* fixed MMiC */ DB::Query($query_testy, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_testy =  /* fixed MMiC */ mysqli_fetch_assoc($testy);
$totalRows_testy =  /* fixed MMiC */ mysqli_num_rows($testy);



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
    <h1>Добавление тестов</h1>
    <p>&nbsp;</p>
    <form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data" name="form1" id="form1">
      <table align="center">
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Файл тестов:</td>
          <td><label for="f"></label>
          <input type="file" name="test_file" id="test_file" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Дата:</td>
          <td><input type="date" name="dat" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Название теста:</td>
          <td><input type="text" name="nazv" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Рисунок:</td>
          <td><input type="file" name="fimg" id="fimg" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">&nbsp;</td>
          <td><input type="number" step="1" min="1" max="500" value="10" id="cv" name="cv"/>
</td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Файл с ответами</td>
          <td><input type="file" name="fotv" id="fotv" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">&nbsp;</td>
          <td><input type="submit" value="Вставить запись" /></td>
        </tr>
      </table>
      <input type="hidden" name="MM_insert" value="form1" />
    </form>
    <hr />
    <p>&nbsp;</p>
    <table border="1" class="mini">
      <tr>
        <td bgcolor="#FFFFFF">num</td>
        <td bgcolor="#FFFFFF">inn</td>
        <td bgcolor="#FFFFFF">path</td>
        <td bgcolor="#FFFFFF">dat</td>
        <td bgcolor="#FFFFFF">nazv</td>
        <td bgcolor="#FFFFFF">img</td>
        <td bgcolor="#FFFFFF">Ответы</td>
        <td bgcolor="#FFFFFF">Кол вопр</td>
      </tr>
      <?php do { ?>
        <tr>
          <td bgcolor="#FFFFFF"><a href="?del=<?php echo $row_testy['num']; ?>">Удалить</a></td>
          <td bgcolor="#FFFFFF"><?php echo $row_testy['inn']; ?></td>
          <td bgcolor="#FFFFFF"><a href="../testy/<?php echo $row_testy['path']; ?>" target="_blank"><?php echo $row_testy['path']; ?></a></td>
          <td bgcolor="#FFFFFF"><?php echo $row_testy['dat']; ?></td>
          <td bgcolor="#FFFFFF"  data-num="<?php echo $row_testy['num']; ?>" data-name="nazv"><?php echo $row_testy['nazv']; ?></td>
          <td bgcolor="#FFFFFF"><?php echo $row_testy['img']; ?></td>
          <td bgcolor="#FFFFFF"><a href="../otv/<?php echo $row_testy['tex']; ?>" target="_blank"><?php echo $row_testy['tex']; ?></td>
          <td bgcolor="#FFFFFF"  data-num="<?php echo $row_testy['num']; ?>" data-name="col_v"><?php echo $row_testy['col_v']; ?></td>
        </tr>
        <?php } while ($row_testy =  /* fixed MMiC */ mysqli_fetch_assoc($testy)); ?>
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
			$.post('add_test.php', {'upd':'1', 'num' :num,'name':name,'val':val},
		function(data) {
			
	
		});});});
	
	
	</script>
</body>
</html>
<?php
 /* fixed MMiC */ mysqli_free_result($testy);
?>
