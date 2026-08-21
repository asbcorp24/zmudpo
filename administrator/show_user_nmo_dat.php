<?php require_once('Connections/testmed.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";
if (!isset($_GET['spec']))$_GET['spec']=-1;

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




$sqlp="SELECT DISTINCT 
  `tm_nmo_prepod_dat`.`dat`,
  `tm_nmo_prepod_dat`.`time`,
  `tm_nmo_prepod_dat`.`nomer_zan`,
  `tm_nmo_prepod_dat`.`vm_chel`,
  `tm_nmo_prepod_dat`.`num`
FROM
  `tm_nmo_prepod_dat`
WHERE
  `tm_nmo_prepod_dat`.`nmo_prepod_spec` =".GetSQLValueString($_GET['spec'], "int")."
ORDER BY
  `tm_nmo_prepod_dat`.`dat` ";
//echo $sqlp;
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

$sqlfio="SELECT 
  `tm_user`.`fio`, `tm_user`.`num`
FROM
  `tm_user`
  INNER JOIN `tm_nmo_prepod_spec` ON (`tm_user`.`spec` = `tm_nmo_prepod_spec`.`spec`)
WHERE
  `tm_nmo_prepod_spec`.`num` = ".GetSQLValueString($_GET['spec'], "int")."
ORDER BY
  `tm_user`.`fio`";

$sqlf =  /* fixed MMiC */ DB::Query($sqlfio, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_fio = mysqli_fetch_assoc($sqlf);

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
    <h1>Отслеживание практик</h1>
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
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <table width="99%" border="1" id="pe">
      <tr>
		  <td>Дата</td>
        <?php do { ?>
      <td class="mini"><?php echo $row_sqlp['dat']?></td>
        <?php } while ($row_sqlp = mysqli_fetch_assoc($sqlp));
		     /* fixed MMiC */ mysqli_data_seek($sqlp, 0);$row_sqlp = mysqli_fetch_assoc($sqlp)
		  ?>
      </tr>
		 <tr>
			  <td>урок</td>
        <?php do { ?>
      <td class="mini"  align="center"><?php echo $row_sqlp['nomer_zan']?></td>
        <?php } while ($row_sqlp = mysqli_fetch_assoc($sqlp)); ?>
      </tr>
		
<?php do { ?>		
	<tr>
		
      <?php 
      if ($row_fio['num']=='')$row_fio['num']=-1;
		$slqd="SELECT DISTINCT 
  `tm_nmo_prepod_dat`.`dat`,
  `tm_nmo_prepod_dat`.`time`,
  `tm_nmo_user_dat`.`user`,
  `tm_nmo_prepod_dat`.`nomer_zan`,
  `tm_nmo_prepod_dat`.`vm_chel`
FROM
  `tm_nmo_prepod_dat`
  LEFT OUTER JOIN `tm_nmo_user_dat` ON (`tm_nmo_prepod_dat`.`num` = `tm_nmo_user_dat`.`dat`)
   and (`tm_nmo_user_dat`.`user`=".$row_fio['num'].")
WHERE
  `tm_nmo_prepod_dat`.`nmo_prepod_spec` =  ".GetSQLValueString($_GET['spec'], "int")."
 
ORDER BY
  `tm_nmo_prepod_dat`.`dat`";
	//echo $slqd;
	$sqlfa =  /* fixed MMiC */ DB::Query($slqd, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_fioa = mysqli_fetch_assoc($sqlfa);
		?>
		<td><?php echo $row_fio['fio'];?></td>
		
		<?php
		do { ?>
      <td align="center"><?php if (isset($row_fioa['user'])) echo "x";?></td>
      <?php } while ($row_fioa = mysqli_fetch_assoc($sqlfa)); ?>
		</tr>
	  <?php } while ($row_fio = mysqli_fetch_assoc($sqlf)); ?>	
		
    </table>
   <p><button id="pch">Печать</button>	</p>
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
