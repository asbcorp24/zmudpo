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

if ((isset($_POST["upd"])) && ($_POST["upd"] == "1")) {
$sql="update `tm_typsv_konf_user` set ".$_POST["name"]."='".$_POST["val"]."' where num=".$_POST["num"];	
DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
	echo $sql;
	exit(0);	
	
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
$num=-1;
if (isset($_GET['num']))$num=(int)$_GET['num'];
$sql="
SELECT 
   `tm_nmo_razd_media`.`nazv`,`tm_nmo_razd_media`.`id`
FROM
  `tm_nmo_razd_media`
  INNER JOIN `tm_nmo_razd` ON (`tm_nmo_razd_media`.`tm_nmo_razd` = `tm_nmo_razd`.`id`)
WHERE
  `tm_nmo_razd`.`spec` = $num AND 
  `tm_nmo_razd_media`.`tip` = 7";

$ank =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_ank =  /* fixed MMiC */ mysqli_fetch_assoc($ank);
$totalRows_ank =  /* fixed MMiC */ mysqli_num_rows($ank);
$sql="SELECT 
  `tm_user`.`fio`,
  `tm_user`.`num`
FROM
  `tm_user`
WHERE
  `tm_user`.`spec` =$num";
$user =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_user =  /* fixed MMiC */ mysqli_fetch_assoc($user);
$totalRows_user =  /* fixed MMiC */ mysqli_num_rows($user);
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
    <h1>отчет по файлам НМО</h1>
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
	   <button id="pch">печать</button>
    <hr>
    <table width="100%" border="1" id="pepe">
      <tbody>
        <tr>
			<td></td>
			   <?php
do {  
?>
          <td><?php echo $row_ank['nazv']; ?></td>
   <?php       } while ($row_ank =  /* fixed MMiC */ mysqli_fetch_assoc($ank));?>
        </tr>
		   <?php
do {  
?>
        <tr>
          <td><?php echo $row_user['fio']; ?></td>
      
			<?php   mysqli_data_seek($ank, 0);
	  $row_ank =  /* fixed MMiC */ mysqli_fetch_assoc($ank);
			do { 
				if (is_null($row_ank['num']))$row_ank['num']=-1;
				if (is_null($row_user['num']))$row_user['num']=-1;
				$sql="SELECT 
  `tm_konf_user_files`.`num`,
  `tm_konf_user_files`.`user`,
  `tm_konf_user_files`.`media`,
  `tm_konf_user_files`.`path`,
  `tm_konf_user_files`.`name`,
  `tm_konf_user_files`.`yname`,
  `tm_nmo_razd_media`.`nazv`,
  `tm_nmo_razd_media`.`id`
FROM
  `tm_konf_user_files`
  INNER JOIN `tm_nmo_razd_media` ON (`tm_konf_user_files`.`media` = `tm_nmo_razd_media`.`id`)
WHERE
  `tm_konf_user_files`.`user` = ".$row_user['num']." AND 
  `tm_nmo_razd_media`.`id` = ".$row_ank['id']."";
			$rrs =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_rrs =  /* fixed MMiC */ mysqli_fetch_assoc($rrs);
			?>   <td><?php if ($row_rrs['path']!='') {?><a target="_blank" href="<?php echo $row_rrs['path']; ?>"><?php echo $row_rrs['name']; ?></a><?php } ?></td><?php     } while ($row_ank =  /* fixed MMiC */ mysqli_fetch_assoc($ank));?> 
        </tr>
		   <?php       } while ($row_user =  /* fixed MMiC */ mysqli_fetch_assoc($user));?>
      </tbody>
    </table>
	  
	  
	  <div id="addra"></div>
<!-- end .content --></div>
  
  <!-- end .container --></div>
   <script src="../js/jquery-1.11.3.min.js"></script>
	    <script src="printThis.js"></script>
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
			$.post('add_ank_sved.php', {'upd':'1', 'num' :num,'name':name,'val':val},
		function(data) {
			
	
		});});
		
		
				$('#pch').on('click',function(){
		
		
			$('#pepe').printThis({afterPrint:function(){	}});
		//	$('.npc').show();
	});	});
	
	
	</script>
</body>
</html>
<?php mysqli_free_result($spec); mysqli_free_result($Recordset1);?>
