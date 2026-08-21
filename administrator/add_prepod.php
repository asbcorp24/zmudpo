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
$sql="update `tm_prepod` set ".$_POST["name"]."='".$_POST["val"]."' where num=".$_POST["num"];	
DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link)); exit(0);	
	
}
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

if ((isset($_GET['del'])) && ($_GET['del'] != "")) {
if (file_exists('../pimg/'.$_GET['ph'])){unlink('../pimg/'.$_GET['ph']);}
  $deleteSQL = sprintf("DELETE FROM tm_prepod WHERE num=%s",
                       GetSQLValueString($_GET['del'], "int"));

 // $Result1 = mysql_query($deleteSQL, $loc) or die(mysql_error());
$Result1 =  /* fixed MMiC */ DB::Query($deleteSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
	$filenameimg =uniqid().'.jpg';  	
if (isset($_FILES['foto'])){

$image = new Imagick($_FILES['foto']['tmp_name']);

	$image->adaptiveResizeImage(140,140);

	$data = $image->getImageBlob(); 
file_put_contents ('../pimg/'.$filenameimg, $data); 
}
  
  $insertSQL = sprintf("INSERT INTO tm_prepod (fio, text, foto, tel, mail,predmet) VALUES (%s, %s, %s, %s, %s,%s)",
                       GetSQLValueString($_POST['fio'], "text"),
                       GetSQLValueString($_POST['text'], "text"),
                       GetSQLValueString($filenameimg, "text"),
                       GetSQLValueString($_POST['tel'], "text"),
                       GetSQLValueString($_POST['mail'], "text"),
					   GetSQLValueString($_POST['predm'], "text"));


$Result1 =  /* fixed MMiC */ DB::Query($insertSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
	
//  $Result1 = mysql_query($insertSQL, $loc) or die(mysql_error());
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form2")) {
	
    $ss=$_POST['text'];
	  $res=explode(PHP_EOL,$ss);
	 // echo count($res);
	 for ($i=0;$i<=count($res);$i++){
 $insertSQL = sprintf("INSERT INTO tm_prepod (fio, text, foto, tel, mail,predmet) VALUES (%s, null, null, null, null,%s)",
                       GetSQLValueString($res[$i], "text"),
                       GetSQLValueString(generate_password(8), "text"));
//echo $insertSQL."<br>";

$Result1 =  /* fixed MMiC */ DB::Query($insertSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));

	} 
  
	
//  $Result1 = mysql_query($insertSQL, $loc) or die(mysql_error());
}

$query_prepod = "SELECT * FROM tm_prepod ORDER BY fio ASC";

$prepod =  /* fixed MMiC */ DB::Query($query_prepod, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
//$prepod = mysql_query($query_prepod, $loc) or die(mysql_error());
$row_prepod = mysqli_fetch_assoc($prepod);
$totalRows_prepod = mysqli_num_rows($prepod);
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

    <!-- end .sidebar1 --></div>
  <div class="content">
    <h1>Добавление преподов</h1>
    <p>&nbsp;</p>
    <form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data" name="form1" id="form1">
      <table align="center" >
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Fio:</td>
          <td><input type="text" name="fio" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Text:</td>
          <td><textarea name="text" cols="32" rows="5" maxlength="150"></textarea></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Foto:</td>
          <td><label for="foto"></label>
          <input type="file" name="foto" id="foto" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Tel:</td>
          <td><input type="text" name="tel" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Mail:</td>
          <td><input type="text" name="mail" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">пароль</td>
          <td><input name="predm" type="text" id="predm" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">&nbsp;</td>
          <td><input type="submit" value="Вставить запись" /></td>
        </tr>
      </table>
      <input type="hidden" name="MM_insert" value="form1" />
    </form>
	  
	 <form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data" name="form2" id="form2" style="display: none">
      <table align="center">
        <tr valign="baseline">
          <td nowrap="nowrap" align="right"><p>ФИО Массово:</p>
         </td>
          <td><textarea name="text" cols="32" rows="5" ></textarea></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">&nbsp;</td>
          <td><input type="submit" value="Вставить запись" /></td>
        </tr>
      </table>
      <input type="hidden" name="MM_insert" value="form2" />
    </form> 
	  
    <p>&nbsp;</p>
    <div>
      <input name="radio2" type="radio" id="radio" value="radio" checked="checked" />
      по одному
      <label for="radio2">
        <input type="radio" name="radio2" id="radio2" value="radio2" />
      массово </label>
    </div>
    <p>&nbsp;</p>
<hr />
       <p><button id="pch">печать</button></p>
    <table border="1" id="pepe">
      <tr>
        <td  class="npc">num</td>
        <td>fio</td>
        <td  class="npc">text</td>
        <td class="npc">foto</td>
        <td class="npc">tel</td>
        <td class="npc">mail</td>
		  <td>predmet</td>
      </tr>
      <?php do { ?>
        <tr>
          <td class="npc"><a href="?del=<?php echo $row_prepod['num']; ?>&ph=<?php echo $row_prepod['foto']; ?>">удалить</a></td>
          <td data-num="<?php echo $row_prepod['num']; ?>" data-name="fio"><?php echo $row_prepod['fio']; ?></td>
          <td class="npc"><?php echo mb_strcut($row_prepod['text'],1,100); ?>...</td>
          <td class="npc"><a href="../pimg/<?php echo $row_prepod['foto']; ?>" target="_blank"><?php echo $row_prepod['foto']; ?></a></td>
          <td class="npc"><?php echo $row_prepod['tel']; ?></td>
          <td class="npc"><?php echo $row_prepod['mail']; ?></td>
			    <td  data-num="<?php echo $row_prepod['num']; ?>" data-name="predmet"><?php echo $row_prepod['predmet']; ?></td>
        </tr>
        <?php } while ($row_prepod = mysqli_fetch_assoc($prepod)); ?>
    </table>
<!-- end .content --></div>
  <div class="sidebar2">
    <h4>&nbsp;</h4>
    <!-- end .sidebar2 --></div>
  <!-- end .container --></div>
  <script src="../js/jquery-1.11.3.min.js"></script>

<!-- Include all compiled plugins (below), or include individual files as needed --> 
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
			$('td').on('click',function(){
		if ($(this).data('num')==undefined) return;
			if (fl==0){
			console.log($(this));fl=1;
	//	console.log($('#p'+$(this).prop('name')));
				if ($(this).data('tip')==undefined)tip="text"; else tip= $(this).data('tip');
$(this)[0].innerHTML='<input type="'+tip+'" name="textfield" id="textfield" value="'+$(this)[0].innerText+'"><input type="button" name="button" id="btg" value="V">';}
		});
		
		$('#radio').on('click',function(){
		$('#form1').show();$('#form2').hide();
			});
		$('#radio2').on('click',function(){
		$('#form2').show();$('#form1').hide();
			});
		
		
		
			$('body').on('click', '#btg',function(){
			
			num=$(this).parent().data('num')
				name=$(this).parent().data('name');
				val=$(this).parent().children().first().val();
			par=	$(this).parent();fl=0;
			$(this).parent().children().remove();$(par).append(val)
			$.post('add_prepod.php', {'upd':'1', 'num' :num,'name':name,'val':val},
		function(data) {
			
	
		});});
	});	
			
	
	</script>
</body>
</html>
<?php
mysqli_free_result($prepod);

?>
