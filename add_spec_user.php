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


if ((isset($_POST["pod"])) && ($_POST["pod"] == "1")) {
	
$query_Recordset1 = sprintf("SELECT * FROM tm_user WHERE num = %s", GetSQLValueString($_POST["num"], "int"));
$Recordset1 =  /* fixed MMiC */ DB::Query($query_Recordset1, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_Recordset1 =  /* fixed MMiC */ mysqli_fetch_assoc($Recordset1);

	
$sql="update `tm_user` set rss=1 where num=".$_POST["num"];	

DB::Query($sql, $testmed) or die(  mysqli_error(DB::$link));


$to=$row_Recordset1['mail'];

$subject ='Регистрация  ООО Центр обучения "АК-БАРС" '; 

$message = ' 
<html>
<head>
<meta charset="utf-8">
<title>Регистрация на сайте дополнительного образования ООО Центр обучения "АК-БАРС"</title>
</head>

<body bgcolor="#6178E4" text="#F8F8F8">
<p>Здравствуйте '.$row_Recordset1['fio'].'</p>
<p>
Ваша учетная запись подтвержена, теперь вы можете продолжить обучение на сайте
</p>
<p>
Ваша пароль - '.$row_Recordset1['passw'].'
</p>
</body>
</html>'; 

$headers  = "Content-type: text/html; charset=utf-8 \r\n"; 
$headers .= 'From: ООО Центр обучения "АК-БАРС" <dpo@ak-barsdpo.ru>\r\n'; 

//echo  $to.$subject. $message. $headers;
mail($to, $subject, $message, $headers); 


exit;

	
}

if ((isset($_POST["pod"])) && ($_POST["pod"] == "2")) {
	
$query_Recordset1 = sprintf("SELECT * FROM tm_user WHERE num = %s", GetSQLValueString($_POST["num"], "int"));
$Recordset1 =  /* fixed MMiC */ DB::Query($query_Recordset1, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_Recordset1 =  /* fixed MMiC */ mysqli_fetch_assoc($Recordset1);

	
$sql="update `tm_user` set rss=1 where num=".$_POST["num"];	

DB::Query($sql, $testmed) or die(  mysqli_error(DB::$link));


$to=$row_Recordset1['mail'];

$subject ='отправка письма  ООО Центр обучения "АК-БАРС" '; 

$message = ' 
<html>
<head>
<meta charset="utf-8">
<title>отправка документ о обучении ООО Центр обучения "АК-БАРС"</title>
</head>

<body bgcolor="#6178E4" text="#F8F8F8">
<p>Здравствуйте '.$row_Recordset1['fio'].'</p>
<p>
Ваши документы о обучении отправлены. 
</p>
<p>
для отслеживания посылки воспользуйтесь треком  - '.$row_Recordset1['post_addr'].'
</p>
</body>
</html>'; 

$headers  = "Content-type: text/html; charset=utf-8 \r\n"; 
$headers .= 'From: ООО Центр обучения "АК-БАРС" <dpo@ak-barsdpo.ru>\r\n'; 

//echo  $to.$subject. $message. $headers;
mail($to, $subject, $message, $headers); 


exit;

	
}

if ((isset($_POST["upd"])) && ($_POST["upd"] == "1")) {
$sql="update `tm_user` set ".$_POST["name"]."='".$_POST["val"]."' where num=".$_POST["num"];	
DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link)); exit(0);	
	
}
if ((isset($_GET['del'])) && ($_GET['del'] != "")) {
  $deleteSQL = sprintf("DELETE FROM tm_user WHERE num=%s",
                       GetSQLValueString($_GET['del'], "int"));

   /* fixed MMiC */ mysqli_select_db(DB::$link, $database_testmed);
  $Result1 =  /* fixed MMiC */ DB::Query($deleteSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
}

if (isset($_GET["upd"])) {
if (isset($_GET["zn"])){//$tm=1;
if ($_GET["zn"]==1) $tm=0;else $tm=1;
  $updateSQL = sprintf("UPDATE tm_user SET act=%s WHERE num=%s",
                       GetSQLValueString($tm, "int"),
                       GetSQLValueString($_GET['upd'], "int"));
   /* fixed MMiC */ mysqli_select_db(DB::$link, $database_testmed);
  $Result1 =  /* fixed MMiC */ DB::Query($updateSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
						 
}}



$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form2")) {
  if ($_POST["tp"]=="m"){
	  $ss=$_POST['fio'];
	  $res=explode(PHP_EOL,$ss);
	  echo count($res);
	 for ($i=0;$i<=count($res);$i++){
  $insertSQL = sprintf("INSERT INTO tm_user (fio, spec,passw,data_nach,ur_parent) VALUES (%s, %s,%s,%s,%s)",
                       GetSQLValueString($res[$i], "text"),
                       GetSQLValueString($_POST['spec'], "int"),
					   GetSQLValueString(generate_password(8), "int"),
										  GetSQLValueString($_POST['dat'], "text"), GetSQLValueString($_POST['urlico'], "int")
					  );
		
   /* fixed MMiC */ mysqli_select_db(DB::$link, $database_testmed);
  $Result1 =  /* fixed MMiC */ DB::Query($insertSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));

	} 
	
	  
	  }else{
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
$query_spec = "SELECT num, concat(dat,' ',nazv) as nazv FROM tm_spec ORDER BY nazv ASC";
$spec =  /* fixed MMiC */ DB::Query($query_spec, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_spec =  /* fixed MMiC */ mysqli_fetch_assoc($spec);
$totalRows_spec =  /* fixed MMiC */ mysqli_num_rows($spec);

$colname_Recordset1 = "-1";
if (isset($_GET['num'])) {
  $colname_Recordset1 = $_GET['num'];
}
 /* fixed MMiC */ mysqli_select_db(DB::$link, $database_testmed);
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
    <h1>Добавление пользователя в группу по специальности</h1>
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

      <input name="radio2" type="radio" id="radio1" />
      <label for="radio">по одному</label>
      <input name="radio2" type="radio" id="radio2"/>
      <label for="radio2">массово</label>
<div id="odn">
      <form action="<?php echo $editFormAction; ?>" method="post" name="form2" id="form2">
        <table align="center" border="0">
			  <tr valign="baseline">
          <td nowrap="nowrap" align="right">Дата начала занятий:</td>
          <td><input type="date" name="dat" value="<?php echo date("Y-m-d"); ?>" size="32" style="width: 99%" /></td>
        </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">ФИО:</td>
            <td><input type="text" name="fio" value="" size="32" style="width: 99%"/></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">является юр.лицом</td>
            <td><input type="checkbox" name="urll" id="urll" /></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">от юрлица</td>
            <td><select name="urlico" id="urlico" style="size:landscape">
              <option value="-1">Без юрлица</option>
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
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">&nbsp;</td>
            <td><input type="submit" value="Вставить запись" /></td>
          </tr>
        </table>
        <input type="hidden" name="spec" value="<?php echo $_GET['num']; ?>" />
        <input type="hidden" name="MM_insert" value="form2" />
        <input name="tp2" type="hidden" id="tp2" value="o" />
      </form>
    </div>
    <div id="mass" style="display: none">
    <form action="<?php echo $editFormAction; ?>" method="post" name="form2" id="form4">
      <table align="center">
		    <tr valign="baseline">
          <td nowrap="nowrap" align="right">Дата начала занятий:</td>
          <td><input type="date" name="dat" value="<?php echo date("Y-m-d"); ?>" size="32" style="width: 99%"/></td>
        </tr>
		  <tr valign="baseline">
            <td nowrap="nowrap" align="right">Юрлицо</td>
            <td><select name="urlico" id="urlico" style="size:landscape">
				  <option value="-1">Без юрлица</option>
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
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Фамилии:</td>
          <td><textarea name="fio" cols="32" style="width: 99%"></textarea></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">&nbsp;</td>
          <td><input type="submit" value="Вставить запись" /></td>
        </tr>
      </table>
      <input type="hidden" name="spec" value="<?php echo $_GET['num']; ?>" />
      <input type="hidden" name="MM_insert" value="form2" />
      <input name="tp" type="hidden" id="tp" value="m" />
    </form>
    </div>
    
    <p>&nbsp;</p>
    <hr />
    <p>     <button id="pch">печать</button><button id="pch2">печать адресов доставки</button></p>
    <?php if ($totalRows_Recordset1 > 0) { // Show if recordset not empty ?>
  <table width="95%" border="1" cellpadding="2" cellspacing="2" id="pepe">
    <tr bgcolor="#FFFFFF">
	
      <td  class="npc">Действия</td>
      <td>Фамилия:</td>
      <td>Актив</td>
       <td>Под</td>
        <td>Gf</td>
		 <td>почта</td>
		<td>Дат нач зан</td>
		 <td>Зак</td>
		<td>url</td>
		 <td>род</td>
		<td>Тд</td>
		<td>трек</td>
		<td class="npc">Сооб об акт</td>
		<td class="npc">письмо отпр</td>
    </tr>
    <?php do { ?>
      <tr bgcolor="#FFFFFF" align="center">
        <td  class="npc"><a href="?del=<?php echo $row_Recordset1['num']; ?>&num=<?php echo $_GET['num']; ?>">Удалить</a></td>
        <td  data-num="<?php echo $row_Recordset1['num']; ?>" data-name="fio"><?php echo $row_Recordset1['fio']; ?></td>
        <td><input <?php if (!(strcmp($row_Recordset1['act'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="ch" class="cha" data-num="<?php echo $_GET['num'] ?>" data-upd="<?php echo $row_Recordset1['num']; ?>" data-zn="<?php echo $row_Recordset1['act']; ?>" />
         </td>
         <td><?php if ($row_Recordset1['mail_pod']==1){ echo '<i class="icofont icofont-check-circled"></i>';}?></td>
        <td  data-num="<?php echo $row_Recordset1['num']; ?>" data-name="passw"><?php echo $row_Recordset1['passw']; ?></td>
            <td data-num="<?php echo $row_Recordset1['num']; ?>" data-name="mail"><?php echo $row_Recordset1['mail']; ?></td>
		     <td  data-num="<?php echo $row_Recordset1['num']; ?>" data-name="data_nach"><?php echo $row_Recordset1['data_nach']; ?></td>
		      <td  data-num="<?php echo $row_Recordset1['num']; ?>" data-name="zav"><?php echo $row_Recordset1['zav']; ?></td>
		  	    <td  data-num="<?php echo $row_Recordset1['num']; ?>" data-name="urlico"><?php echo $row_Recordset1['urlico']; ?></td>
		
		   <td ><?php echo $row_Recordset1['parent']; ?></td>
		     <td ><a href="#" class="daddr" data-num="<?php echo $row_Recordset1['num']; ?>"><?php if($row_Recordset1['post']==1)echo '<i class="icofont icofont-male"></i>'; ?><?php if($row_Recordset1['post']==2)echo '<i class="icofont icofont-envelope"></i>'; ?></a></td>
		       <td  data-num="<?php echo $row_Recordset1['num']; ?>" data-name="post_addr"><?php echo $row_Recordset1['post_addr']; ?></td>
		        <td class="npc"><button class="pdt" data-num="<?php echo $row_Recordset1['num']; ?>" <?php if ($row_Recordset1['rss']==1){ echo 'disabled="disabled"'; } ?>>Подтв</button></td>
		   <td class="npc"><button class="pdt2" data-num="<?php echo $row_Recordset1['num']; ?>" >Подтв</button></td>
      </tr>
      <?php } while ($row_Recordset1 =  /* fixed MMiC */ mysqli_fetch_assoc($Recordset1)); ?>
  </table>
  <?php } // Show if recordset not empty ?>
	  
	  
	  <hr>
	  
	  
	  <div id="addra"></div>
<!-- end .content --></div>
  
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
		
			$('.pdt').on('click',function(){
		if ($(this).data('num')==undefined) return;
		num=$(this).data('num');
		tm=$(this);
			$.post('add_spec_user.php', {'pod':'1', 'num' :num},	function(data) {
		tm.prop("disabled",true);	

		});
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

$('#radio1').on('click',function(){
	$('#odn').show();
	$('#mass').hide();
			});
$('.daddr').on('click',function(e){
	e.preventDefault();
	num=$(this).data('num');
	$.post('printaddr.php', {'user':num},
		function(data) {
			
	$('#addra').append(data);
		});
			});
$('#radio2').on('click',function(){
	$('#odn').hide();
	$('#mass').show();
			});
$('.cha').on('click',function(){
	
	num=$(this).data('num')	;
	upd=$(this).data('upd')	;
	zn=$(this).data('zn')	;
		
	 $.get( "add_spec_user.php",{'num':num,'upd':upd,'zn':zn}, function() {
 // alert( "success" );
});
	
			});

	$('body').on('click', '#btg',function(){
			
			num=$(this).parent().data('num')
				name=$(this).parent().data('name');
				val=$(this).parent().children().first().val();
			par=	$(this).parent();fl=0;
			$(this).parent().children().remove();$(par).append(val)
			$.post('add_spec_user.php', {'upd':'1', 'num' :num,'name':name,'val':val},
		function(data) {
			
	
		});});});
	
	
	</script>
</body>
</html>
<?php mysqli_free_result($spec); mysqli_free_result($Recordset1);?>
