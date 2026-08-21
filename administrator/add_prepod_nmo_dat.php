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
$sql="update `tm_nmo_prepod_dat` set ".$_POST["name"]."='".$_POST["val"]."' where num=".$_POST["num"];	
DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link)); exit(0);	
	
}
	if ((isset($_GET["del"])) ) {
	$sql="delete from `tm_nmo_prepod_dat` where num=".GetSQLValueString($_GET["del"],"int");	
$Result1 =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));

	
	
}
print_r($_POST);
if (isset($_POST["spec"])){ 

	$insertSQL = sprintf("INSERT INTO `tm_nmo_prepod_dat` (`num`, `nmo_prepod_spec`, `dat`, `time`,vm_chel,nomer_zan,comment) VALUES (NULL, %s, %s, %s, %s, %s,%s)",
                       GetSQLValueString($_POST['spec'], "int"),
                       GetSQLValueString($_POST['dat'], "date"),
                       GetSQLValueString($_POST['tim'], "date"), GetSQLValueString($_POST['vm_chel'], "int"), GetSQLValueString($_POST['nomer_zan'], "text"),GetSQLValueString($_POST['comment'], "text"));

echo $insertSQL;
$Result1 =  DB::Query($insertSQL, $testmed) or die(  mysqli_error(DB::$link));
	
//  $Result1 = mysql_query($insertSQL, $loc) or die(mysql_error());

					}

$query_prepod = "SELECT * FROM tm_prepod ORDER BY fio ASC";

$prepod =  /* fixed MMiC */ DB::Query($query_prepod, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
//$prepod = mysql_query($query_prepod, $loc) or die(mysql_error());
$row_prepod = mysqli_fetch_assoc($prepod);
$totalRows_prepod = mysqli_num_rows($prepod);




$sqlp="SELECT 
  `tm_nmo_prepod_dat`.`num`,
  `tm_nmo_prepod_dat`.`nmo_prepod_spec`,
  `tm_nmo_prepod_dat`.`dat`,
  `tm_nmo_prepod_dat`.`time`,
  `tm_nmo_prepod_dat`.`vm_chel`,
  `tm_nmo_prepod_dat`.`nomer_zan`, `tm_nmo_prepod_dat`.`comment`
FROM
  `tm_nmo_prepod_dat`
WHERE
  `tm_nmo_prepod_dat`.`nmo_prepod_spec` = ". GetSQLValueString($_GET['spec'], "int");
$sqlp =  /* fixed MMiC */ DB::Query($sqlp, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
//$prepod = mysql_query($query_prepod, $loc) or die(mysql_error());
$row_sqlp = mysqli_fetch_assoc($sqlp);
$totalRows_sqlp = mysqli_num_rows($sqlp);

$sql="SELECT 
  `tm_prepod`.`fio`,
  `tm_spec`.`nazv`,
  `tm_nmo_prepod_spec`.`num`,
  `tm_nmo_prepod_spec`.`predmet`
FROM
  `tm_nmo_prepod_spec`
  INNER JOIN `tm_prepod` ON (`tm_nmo_prepod_spec`.`prepod` = `tm_prepod`.`num`)
  INNER JOIN `tm_spec` ON (`tm_nmo_prepod_spec`.`spec` = `tm_spec`.`num`)
WHERE
  `tm_spec`.`actiiv` = 1";
$sqlp2 =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
//$prepod = mysql_query($query_prepod, $loc) or die(mysql_error());
$row_sqlp2 = mysqli_fetch_assoc($sqlp2);
$totalRows_sqlp2 = mysqli_num_rows($sqlp2);

?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Добавление праткик нмо</title>
<link href="thrColLiq.css" rel="stylesheet" type="text/css" />
</head>

<body>

<div class="container">
  <div class="sidebar1">
   <?php include("menu.php"); ?>
    <p>&nbsp;</p>
    <!-- end .sidebar1 --></div>
  <div class="content">
    <h1>установка дат практических по нмо</h1>
    <form id="form2" name="form2" method="get" action="">
      <p><span style="text-align: center">Специальность-преподавателсь </span></p>
      <table width="100%" border="0">
        <tbody>
          <tr>
            <td><select name="spec" id="spec">
              <?php
do {  
?>
              <option value="<?php echo $row_sqlp2['num']?>" <?php if (!(strcmp($row_sqlp2['num'], $_GET['spec']))) {echo "selected=\"selected\"";} ?>><?php echo $row_sqlp2['fio']?>-<?php echo $row_sqlp2['nazv'];?>-<?php echo $row_sqlp2['predmet'];?></option>
              <?php
} while ($row_sqlp2=  /* fixed MMiC */ mysqli_fetch_assoc($sqlp2));?>
            </select></td>
            <td><input type="submit" name="button" id="button" value="Выбрать" /></td>
          </tr>
        </tbody>
      </table>
      <p>&nbsp;</p>
    </form>
    <p>&nbsp;</p>
    <form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data" name="form1" id="form1">
      <table align="center">
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Дата возможная :</td>
          <td align="left"><input name="dat" type="date" id="dat" value="" size="32" /></td>
        </tr>
		     <tr valign="baseline">
          <td nowrap="nowrap" align="right">время возможное :</td>
          <td align="left"><input name="tim" type="time" id="tim" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Номер занятия:</td>
          <td align="left"><input name="nomer_zan" type="number" id="nomer_zan" placeholder="1 занятие" max="50" min="0"> 
            0-если без номеров</td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Количество человек:</td>
          <td align="left"><input name="vm_chel" type="number" id="vm_chel" placeholder="Количество" max="150" min="1"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Комментарий</td>
          <td align="left"><textarea name="comment" cols="40" rows="3" id="comment"></textarea></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right"></td>
          <td align="left"><input type="submit" value="Вставить запись" /></td>
        </tr>
      </table>
		<input name="spec" type="hidden" id="spec" value="<?php echo $_GET['spec']; ?>" />
      <input type="hidden" name="MM_insert" value="form1" />
    </form>
    <p>&nbsp;</p>
<hr />
    <p><button id="pch">Печать</button>	</p>
    <table width="99%" border="1" id="pe">
		 <tr>
        <td colspan="6" id="naa">num</td>
        
      </tr>
      <tr>
        <td>num</td>
        <td>дата</td>
        <td>впемя</td>
        <td>емкость</td>
        <td>название зан</td>
		   <td>Комментарий</td>
      </tr>
      <?php do { ?>
        <tr>
          <td><a href="?del=<?php echo $row_sqlp['num']; ?>&ph=<?php echo $row_sqlp['num']; ?>&spec=<?php echo $_GET['spec']; ?>">удалить</a></td>
            
          <td data-num="<?php echo $row_sqlp['num']; ?>" data-name="dat"><?php echo $row_sqlp['dat']; ?></td>
          <td data-num="<?php echo $row_sqlp['num']; ?>" data-name="time"><?php echo $row_sqlp['time']; ?></td>
			 <td data-num="<?php echo $row_sqlp['num']; ?>" data-name="vm_chel"><?php echo $row_sqlp['vm_chel']; ?></td>
          <td data-num="<?php echo $row_sqlp['num']; ?>" data-name="nomer_zan"><?php echo $row_sqlp['nomer_zan']; ?></td>
			   <td data-num="<?php echo $row_sqlp['num']; ?>" data-name="comment"><?php echo $row_sqlp['comment']; ?></td>     
        </tr>
        <?php } while ($row_sqlp = mysqli_fetch_assoc($sqlp)); ?>
    </table>
<!-- end .content --></div>
 
  <!-- end .container --></div>
</body>
	 <script src="../js/jquery-1.11.3.min.js"></script>
		  <script src="printThis.js"></script>
  <script type="text/javascript">
	$(function() {
		fl=0;
		
	
		$('#pch').on('click',function(){
		$('#naa').html($("#spec :selected").text());
		$('#pe').printThis();
		
		
	});		
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
			$.post('add_prepod_nmo_dat.php', {'upd':'1', 'num' :num,'name':name,'val':val},
		function(data) {
			
	
		});});});
	
	
	</script>
</html>
<?php
mysqli_free_result($prepod);

?>
