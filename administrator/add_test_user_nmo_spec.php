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

$colname_Recordset1 = "-1";
if (isset($_GET['num'])) {
  $colname_Recordset1 = $_GET['num'];
}
$query_spec = "SELECT num, concat(dat,' ',nazv) as nazv FROM tm_spec ORDER BY nazv ASC";
$spec =  /* fixed MMiC */ DB::Query($query_spec, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_spec =  /* fixed MMiC */ mysqli_fetch_assoc($spec);
$totalRows_spec =  /* fixed MMiC */ mysqli_num_rows($spec);


if ((isset($_POST["upd"])) && ($_POST["upd"] == "1")) {
	
	//{'upd':'1', 'num' :num,'mid':mid,'val':val,'uid':uid},

if($_POST['num']<0){
	
	$sql="INSERT INTO `tm_nmo_razd_media_user_act_test` (`id`, `user`, `razd_media_test`, `act`, `datact`) VALUES (NULL, ".$_POST["uid"].", ".$_POST["mid"].", NULL, '".$_POST["val"]."')";
	
DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
	echo $sql;
$sql="select max(id) as mm from `tm_nmo_razd_media_user_act_test`";
	$tets =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_tets =  /* fixed MMiC */ mysqli_fetch_assoc($tets);
	echo($row_tets['mm']);
}	
	else {
$sql="update `tm_nmo_razd_media_user_act_test` set datact='".$_POST["val"]."' where id=".$_POST["num"];	
DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
		echo($_POST["num"]);
	}
	exit(0);	
	
}
if ((isset($_GET['del'])) && ($_GET['del'] != "")) {
  $deleteSQL = sprintf("DELETE FROM tm_user WHERE num=%s",
                       GetSQLValueString($_GET['del'], "int"));

   /* fixed MMiC */ mysqli_select_db(DB::$link, $database_testmed);
  $Result1 =  /* fixed MMiC */ DB::Query($deleteSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
}




$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form2")) {
 {
	  $uli="NULL";
	  if (isset($_POST['urll']))$uli=1; 
  $insertSQL = sprintf("INSERT INTO tm_user (fio, spec,passw,data_nach,ur_parent,urlico) VALUES (%s, %s,%s,%s,%s,%s)",
                       GetSQLValueString($_POST['fio'], "text"),
                       GetSQLValueString($_POST['spec'], "int"),
	                   GetSQLValueString(generate_password(8), "int"),
										  GetSQLValueString($_POST['dat'], "text"), GetSQLValueString($_POST['urlico'], "int"), 
					   $uli);
 echo $insertSQL;
   /* fixed MMiC */ mysqli_select_db(DB::$link, $database_testmed);//urll
  $Result1 =  /* fixed MMiC */ DB::Query($insertSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));}
}

 /* fixed MMiC */ mysqli_select_db(DB::$link, $database_testmed);

 /* fixed MMiC */ mysqli_select_db(DB::$link, $database_testmed);


$sql="SELECT 
  `tm_nmo_razd_media`.`id`,
  `tm_nmo_razd_media`.`tm_nmo_razd`,
  `tm_nmo_razd_media`.`path`,
  `tm_nmo_razd_media`.`tip`,
  `tm_nmo_razd_media`.`act`,
  `tm_nmo_razd_media`.`obyaz`,
  `tm_nmo_razd_media`.`num`,
  `tm_nmo_razd_media`.`comment`,
  `tm_nmo_razd_media`.`dop_file`,
  `tm_nmo_razd_media`.`nazv`,
  `tm_nmo_razd_media`.`povt`,
  `tm_nmo_razd_media`.`data_act`
FROM
  `tm_nmo_razd_media`
  INNER JOIN `tm_nmo_razd` ON (`tm_nmo_razd_media`.`tm_nmo_razd` = `tm_nmo_razd`.`id`)
WHERE
  `tm_nmo_razd_media`.`tip` = 3 AND 
  `tm_nmo_razd`.`spec` =$colname_Recordset1 order by num";
$tets =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_tets =  /* fixed MMiC */ mysqli_fetch_assoc($tets);
$totalRows_tets =  /* fixed MMiC */ mysqli_num_rows($tets);


$stm='';
if ($_GET['urlico']>0) $stm=' and  `tm_user`.`ur_parent`='.$_GET['urlico']; 
$query_Recordset1 = sprintf("SELECT 
  `tm_user1`.`fio` AS `parent`,
  `tm_user`.`num`,
  `tm_user`.`fio`,
  `tm_user`.`spec`,
  `tm_user`.`passw`,
  `tm_user`.`act`,
  `tm_user`.`mail`,
  `tm_user`.`mail_pod`,
  `tm_user`.`rss`,
  `tm_user`.`data_nach`,
  `tm_user`.`zav`,
  `tm_user`.`urlico`,
  `tm_user`.`ur_parent`,`tm_user`.`post`,
  `tm_user`.`post_addr`
FROM
  `tm_user`
   LEFT OUTER JOIN `tm_user` `tm_user1` ON (`tm_user`.`ur_parent` = `tm_user1`.`num`) WHERE `tm_user`.`spec` = %s  $stm ORDER BY act ASC", GetSQLValueString($colname_Recordset1, "int"));
$Recordset1 =  /* fixed MMiC */ DB::Query($query_Recordset1, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_Recordset1 =  /* fixed MMiC */ mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 =  /* fixed MMiC */ mysqli_num_rows($Recordset1);


$sql="SELECT 
  `tm_user`.`fio`,
  `tm_user`.`num`
FROM
  `tm_user`
WHERE
  `tm_user`.`urlico` = 1";

$urlico =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_urlico =  /* fixed MMiC */ mysqli_fetch_assoc($urlico);
$totalRows_urlico =  /* fixed MMiC */ mysqli_num_rows($urlico);


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
    <h1>Распределение тестирования по датам</h1>
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
            <td>Юрлицо</td>
            <td><select name="urlico" id="urlico" style="size:landscape">
				  <option value="-1">все пользователи</option>
              <?php
do {  
?>
              <option value="<?php echo $row_urlico['num']?>"<?php if (!(strcmp($row_urlico['num'], $_GET['urlico']))) {echo "selected=\"selected\"";} ?>><?php echo $row_urlico['fio']?></option>
              <?php
} while ($row_urlico =  /* fixed MMiC */ mysqli_fetch_assoc($urlico));
  $rows =  /* fixed MMiC */ mysqli_num_rows($urlico);

       /* fixed MMiC */ mysqli_data_seek($urlico, 0);
	
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
<hr />
  
    <hr>
	  
	  
	  <div id="addras">
	    <table width="100%" border="1" id="pepe">
	      <tbody>
	        <tr>
				<td>ФИО</td>
	        <?php
do {  
?><td> <?php echo $row_tets['nazv']?> :<?php echo $row_tets['num']?></td>
             
              <?php
} while ($row_tets =  /* fixed MMiC */ mysqli_fetch_assoc($tets));?>
				
	         
            </tr>
			  
		 <?php
do {   
?> <tr>
			  <td> <?php echo $row_Recordset1['fio']?> 
			  
	<?php
	if (is_null($row_Recordset1['num']))$row_Recordset1['num']=-1;

	
	$sql="(SELECT 
`tm_nmo_razd_media`.`id` as `razd_media_test`,
'-' as datact,
0 as user,
-1 as id,
  `tm_nmo_razd_media`.`nazv`,
  `tm_nmo_razd_media`.`id` AS `mediaid`,`tm_nmo_razd_media`.`num` AS `num`
FROM
  `tm_nmo_razd_media`
  INNER JOIN `tm_nmo_razd` ON (`tm_nmo_razd_media`.`tm_nmo_razd` = `tm_nmo_razd`.`id`)
WHERE
  `tm_nmo_razd_media`.`tip` = 3 AND 
  `tm_nmo_razd`.`spec` =  $colname_Recordset1 and `tm_nmo_razd_media`.`id` not in (select  `tm_nmo_razd_media_user_act_test`.`razd_media_test` 
  from `tm_nmo_razd_media_user_act_test` where  `tm_nmo_razd_media_user_act_test`.`user`=".$row_Recordset1['num']."
   )
  )
  union
 ( SELECT 
  `tm_nmo_razd_media_user_act_test`.`razd_media_test`,
  `tm_nmo_razd_media_user_act_test`.`datact`,
  `tm_nmo_razd_media_user_act_test`.`user`,
  `tm_nmo_razd_media_user_act_test`.`id`,
  `tm_nmo_razd_media`.`nazv`,
  `tm_nmo_razd_media`.`id` AS `mediaid`,`tm_nmo_razd_media`.`num` AS `num`
FROM
  `tm_nmo_razd_media_user_act_test`
  RIGHT OUTER JOIN `tm_nmo_razd_media` ON (`tm_nmo_razd_media_user_act_test`.`razd_media_test` = `tm_nmo_razd_media`.`id`)
  INNER JOIN `tm_nmo_razd` ON (`tm_nmo_razd_media`.`tm_nmo_razd` = `tm_nmo_razd`.`id`)
WHERE
  `tm_nmo_razd_media`.`tip` = 3 AND 
  `tm_nmo_razd`.`spec` = $colname_Recordset1 AND 
  (
  `tm_nmo_razd_media_user_act_test`.`user` = ".$row_Recordset1['num']."))
  order by num ";
//	echo $sql;
	$tu =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_tu =  /* fixed MMiC */ mysqli_fetch_assoc($tu);
			  
			  
			  ?>		 </td> 
			  
		 <?php
do {  
?><td data-mediaid="<?php echo $row_tu['mediaid']?>" data-userid="<?php if  (($row_tu['id'])<0) echo $row_Recordset1['num']; else echo $row_tu['id']?>" data-num="<?php echo $row_tu['id']?>"><?php echo $row_tu['datact']?>
			
			  </td>
             
              <?php
} while ($row_tu =  /* fixed MMiC */ mysqli_fetch_assoc($tu));?>
	        </tr>     
              <?php
} while ($row_Recordset1 =  /* fixed MMiC */ mysqli_fetch_assoc($Recordset1));?>
		</tbody>
        </table>
	  </div>
<!-- end .content --></div>
  
  <!-- end .container --></div>
   <script src="../js/jquery-1.11.3.min.js"></script>
	    <script src="printThis.js"></script>
  <script type="text/javascript">
	$(function() {
		fl=0;
			$('#pch').on('click',function(){
			$('#addras').printThis({afterPrint:function(){	}});
		
	});	
		
		///////////
		$('td').on('click',function(){
		if ($(this).data('num')==undefined) return;
			if (fl==0){
			console.log($(this));fl=1;
	//	console.log($('#p'+$(this).prop('name')));
				
$(this)[0].innerHTML='<input type="date" name="textfield" id="textfield" value="'+$(this)[0].innerText+'"><input type="button" name="button" id="btg" value="V">';}
		});


			});

	$('body').on('click', '#btg',function(){
			
			num=$(this).parent().data('num')
				mid=$(this).parent().data('mediaid');
		uid=$(this).parent().data('userid');
				val=$(this).parent().children().first().val();
			par=	$(this).parent();fl=0;
			$(this).parent().children().remove();$(par).append(val)
			$.post('add_test_user_nmo_spec.php', {'upd':'1', 'num' :num,'mid':mid,'val':val,'uid':uid},
		function(data) {
			
	
		});});
	
	
	</script>
</body>
</html>
<?php mysqli_free_result($spec); mysqli_free_result($Recordset1);?>
