<?php require_once('Connections/testmed.php'); ?>
<?php require_once('ball.php'); ?>
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

if ($_POST["num"]==0){
	
$sql="INSERT INTO `tm_nmo_razd_media_user_act_test` (user,razd_media_test,datact) VALUES (".GetSQLValueString($_POST["user"],"int").",".GetSQLValueString($_POST["razd"],"int").",".GetSQLValueString($_POST["val"],"text").")";
	echo $sql;
	 $Result1 =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
	exit();
}	
if ($_POST["num"]!=0)	{
$sql="update ".$_POST["bas"]." set ".$_POST["name"]."='".$_POST["val"]."' where id=".GetSQLValueString($_POST["num"],"int");	

	echo $sql;
  $Result1 =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
	exit();
}
}
	if ((isset($_GET["del"])) ) {
	$sql="delete from `tm_nmo_prepod_dat` where num=".GetSQLValueString($_GET["del"],"int");	
$Result1 =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));

	
	
}
if (isset($_POST["mid"])){ 
  print_r($_POST);
if($_POST["mid"]==0){

//  mid: 85382
$msp=intval($_POST['msp']);
$mpsp=intval($_POST['mpsp']);
$mproydeno=intval($_POST['mproydeno']);
$muser=intval($_POST['muser']);
  $mrazdel=intval($_POST['mrazdel']);
  $mdat=($_POST['mdat']);
$sql="INSERT INTO `tm_nmo_razd_user` (`id`, `user`, `razdel`, `proydeno`, `dop_file`, `dat`, `dop`, `sp`, `psp`, `pop`) VALUES (NULL, $muser,$mrazdel, $mproydeno, NULL, '$mdat', NULL, $msp,$mpsp, '01')";
  echo $sql;
$Result1 =  DB::Query($sql, $testmed) or die(  mysqli_error(DB::$link));
  exit;
}

if($_POST["mid"]!=0){

//  mid: 85382
$mid=intval($_POST['mid']);  
$msp=intval($_POST['msp']);
$mpsp=intval($_POST['mpsp']);
$mproydeno=intval($_POST['mproydeno']);
$muser=intval($_POST['muser']);
  $mrazdel=intval($_POST['mrazdel']);
  $mdat=($_POST['mdat']);
$sql="update `tm_nmo_razd_user` set proydeno=$mproydeno, sp=$msp,psp=$mpsp,dat='$mdat' where id=$mid";
  echo $sql;
$Result1 =  DB::Query($sql, $testmed) or die(  mysqli_error(DB::$link));
  exit;
}
  
};
if (isset($_POST["spec"])){ 

	$insertSQL = sprintf("INSERT INTO `tm_nmo_prepod_dat` (`num`, `nmo_prepod_spec`, `dat`, `time`) VALUES (NULL, %s, %s, %s)",
                       GetSQLValueString($_POST['spec'], "int"),
                       GetSQLValueString($_POST['dat'], "date"),
                       GetSQLValueString($_POST['tim'], "date"));

echo $insertSQL;
$Result1 =  DB::Query($insertSQL, $testmed) or die(  mysqli_error(DB::$link));
	
//  $Result1 = mysql_query($insertSQL, $loc) or die(mysql_error());

					}

$query_spec = "SELECT 
  `tm_spec`.`num`,
  `tm_spec`.`nazv`,
  `tm_spec`.`dat`,
  `tm_spec`.`img`,
  `tm_spec`.`actiiv`,
  `tm_spec`.`zap`,
  `tm_spec`.`kr`
FROM
  `tm_spec`
WHERE
  `tm_spec`.`kr` >0 AND 
  `tm_spec`.`actiiv` = 1";

$spec =  /* fixed MMiC */ DB::Query($query_spec, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
//$prepod = mysql_query($query_prepod, $loc) or die(mysql_error());
$row_spec = mysqli_fetch_assoc($spec);
$totalRows_spec = mysqli_num_rows($spec);

$coluser=-1;
if (isset($_GET['user'])){
	
	$coluser=$_GET['user'];
}

$colspec=-1;
if (isset($_GET['mid'])){
  $mid=intval($_GET['mid']);
$query_user = "SELECT * FROM `tm_nmo_razd_user` WHERE id=".$mid;

  
  
$user =  /* fixed MMiC */ DB::Query($query_user, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
//$prepod = mysql_query($query_prepod, $loc) or die(mysql_error());
$row_user = mysqli_fetch_assoc($user);
echo json_encode($row_user);
exit;

};
if (isset($_GET['spec'])){
	
	$colspec=GetSQLValueString($_GET['spec'], "int");
	
}

// Фильтр по подгруппе. Пустое значение означает "все подгруппы".
$colpodgruppa = "";
$podgruppa_filter_sql = "";
if (isset($_GET['podgruppa']) && $_GET['podgruppa'] !== "") {
  $colpodgruppa = $_GET['podgruppa'];
  $podgruppa_filter_sql = " AND `tm_user`.`podgruppa` = ".GetSQLValueString($colpodgruppa, "text");
}

// Список подгрупп для выбранной специальности.
$query_podgruppa = "SELECT DISTINCT
  `tm_user`.`podgruppa`
FROM
  `tm_user`
WHERE
  `tm_user`.`spec` = $colspec AND
  `tm_user`.`podgruppa` IS NOT NULL AND
  `tm_user`.`podgruppa` <> ''
ORDER BY
  `tm_user`.`podgruppa` ASC";
$podgruppa =  /* fixed MMiC */ DB::Query($query_podgruppa, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_podgruppa = mysqli_fetch_assoc($podgruppa);
$totalRows_podgruppa = mysqli_num_rows($podgruppa);

$query_user = "SELECT 
  `tm_user`.`num`,
  `tm_user`.`fio`,
  `tm_user`.`podgruppa`
FROM
  `tm_user`
WHERE
  `tm_user`.`spec` = $colspec $podgruppa_filter_sql
ORDER BY
  `tm_user`.`fio` ASC";

$user =  /* fixed MMiC */ DB::Query($query_user, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
//$prepod = mysql_query($query_prepod, $loc) or die(mysql_error());
$row_user = mysqli_fetch_assoc($user);
$totalRows_user = mysqli_num_rows($user);



?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Добавление праткик нмо</title>
<link href="thrColLiq.css" rel="stylesheet" type="text/css" />
		<link href="../css/icofont.css" rel="stylesheet" type="text/css"/>

<style>
/* Красивое отображение результата без вложенной таблицы */
#pe td.mini {
  vertical-align: top;
  padding: 4px;
}
.nmo-result-card {
  min-width: 135px;
  padding: 7px 8px;
  border: 1px solid #d9e2ef;
  border-radius: 10px;
  background: #f8fbff;
  box-shadow: 0 1px 3px rgba(0,0,0,0.08);
  font-size: 12px;
  line-height: 1.25;
}
.nmo-result-main {
  display: flex;
  align-items: center;
  gap: 8px;
  margin-bottom: 6px;
}
.nmo-date-badge {
  display: inline-block;
  padding: 3px 7px;
  border-radius: 14px;
  background: #eaf2ff;
  color: #174a8b;
  font-weight: bold;
  text-decoration: none;
  white-space: nowrap;
}
.nmo-score-row {
  display: flex;
  flex-wrap: wrap;
  gap: 5px;
  margin-bottom: 7px;
}
.nmo-pill {
  display: inline-flex;
  align-items: center;
  gap: 4px;
  padding: 3px 6px;
  border-radius: 12px;
  background: #ffffff;
  border: 1px solid #e4e9f2;
  white-space: nowrap;
}
.nmo-actions {
  display: flex;
  flex-wrap: wrap;
  gap: 5px;
}
.nmo-action {
  display: inline-flex;
  align-items: center;
  gap: 4px;
  padding: 3px 6px;
  border-radius: 7px;
  border: 1px solid #d4deec;
  background: #ffffff;
  color: #1f3f69;
  text-decoration: none;
  cursor: pointer;
  font-weight: normal;
}
.nmo-action:hover, .nmo-date-badge:hover {
  text-decoration: none;
  filter: brightness(0.96);
}
@media print {
  .nmo-result-card {
    box-shadow: none;
    background: #fff;
    border: 1px solid #777;
  }
  /* В печатном отчете служебные кнопки не нужны */
  .nmo-actions,
  .nmo-action {
    display: none !important;
  }
}
</style>
</head>

<body>

<div class="container">
  <div class="sidebar1">
   <?php include("menu.php"); ?>
    <p>&nbsp;</p>
    <!-- end .sidebar1 --></div>
  <div class="content" style="max-width: 400px;">
    <h1>Управление прохождением тестов по нмо</h1>
    <form id="form2" name="form2" method="get" action="">
      <p><span style="text-align: center">Специальность</span></p>
      <table width="100%" border="0" cellspacing="2">
        <tbody>
          <tr>
            <td><select name="spec" id="spec" style="width: 99%">
              <?php
do {  
?>
              <option value="<?php echo $row_spec['num']?>" <?php if (isset($_GET['spec']) && !(strcmp($row_spec['num'], $_GET['spec']))) {echo 'selected="selected"';} ?>><?php echo $row_spec['nazv'];?></option>
              <?php
} while ($row_spec=  /* fixed MMiC */ mysqli_fetch_assoc($spec));?>
            </select></td>
            <td><input type="submit" name="button" id="button" value="Выбрать" style="width: 99%" /></td>
          </tr>
          <tr>
            <td>
              <select name="podgruppa" id="podgruppa" style="width: 99%">
                <option value="">Все подгруппы</option>
                <?php if ($totalRows_podgruppa > 0) { do { ?>
                  <option value="<?php echo $row_podgruppa['podgruppa']; ?>" <?php if (isset($_GET['podgruppa']) && !(strcmp($row_podgruppa['podgruppa'], $_GET['podgruppa']))) {echo 'selected="selected"';} ?>><?php echo $row_podgruppa['podgruppa']; ?></option>
                <?php } while ($row_podgruppa = mysqli_fetch_assoc($podgruppa)); } ?>
              </select>
            </td>
            <td><input type="submit" value="Фильтр" style="width: 99%" /></td>
          </tr>
        </tbody>
      </table>
      <p>&nbsp;</p>
    </form>
	  
	 <hr>
    <form id="form3" name="form3" method="get" action="">
      <p><span style="text-align: center">Студенты</span></p>
      <table width="100%" border="1" cellspacing="2" id="pe" style="border-spacing: 0 10px;font-weight: bold;">
        <tbody>
        
        
        
          <tr>
			
			
			<td>ФИО</td>
		<td>Подгр.</td>
			
			<?php
			$pathslq="SELECT 
  `tm_nmo_razd_user`.`dat`,
  `tm_nmo_razd_user`.`proydeno`,
  `tm_nmo_razd_user`.`user`,
  `tm_nmo_razd_user`.`razdel`,
  `tm_nmo_razd_media`.`nazv`,
  `tm_nmo_razd_media`.`num`,
   `tm_nmo_razd_user`.`dop_file`,
   `tm_nmo_razd_user`.`sp`,
   `tm_nmo_razd_user`.`psp`
FROM
  `tm_nmo_razd_user`
  INNER JOIN `tm_nmo_razd_media` ON (`tm_nmo_razd_user`.`razdel` = `tm_nmo_razd_media`.`id`)
WHERE
  `tm_nmo_razd_user`.`user` = $coluser
union

SELECT 
CAST('2001-01-01' as DATE) AS `dat`,
0 as `proydeno`,
0 as user,
0 as razdel,
  `tm_nmo_razd_media`.`nazv`,
   `tm_nmo_razd_media`.`num`
   ,0 as dop_file, 0 as `sp`,
  0 as`psp`
FROM
  `tm_nmo_razd_media`
  INNER JOIN `tm_nmo_razd` ON (`tm_nmo_razd_media`.`tm_nmo_razd` = `tm_nmo_razd`.`id`)
WHERE
  `tm_nmo_razd`.`spec` = $colspec and `tm_nmo_razd_media`.`tip` in (3,4) and `tm_nmo_razd_media`.`id` not in (SELECT 
   `tm_nmo_razd_user`.`razdel`
  FROM
  `tm_nmo_razd_user`
WHERE
  `tm_nmo_razd_user`.`user` = $coluser) order by num ";;
$paths =  /* fixed MMiC */ DB::Query($pathslq, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
//$prepod = mysql_query($query_prepod, $loc) or die(mysql_error());
$row_paths = mysqli_fetch_assoc($paths);
$totalRows_paths = mysqli_num_rows($paths);
			
			
			?>
			              <?php
do { 
?>  
		<td class="mini"  style="min-width:50px;word-break: break-all;">
<?php echo $row_paths['nazv']?>
			</td>	
	<?php
} while ($row_paths=  /* fixed MMiC */ mysqli_fetch_assoc($paths));?>	
		  </tr>
			
			
              <?php
do { 
?>  




<tr>
            <td>
             <?php echo $row_user['fio'];?>     
          </td>
			
            <td><?php echo $row_user['podgruppa']; ?></td>
			
			<?php
			
		 $coluser=	$row_user['num'];
		 if($coluser==null)$coluser=-1;
			$pathslq="SELECT 
  `tm_nmo_razd_user`.`dat`, `tm_nmo_razd_user`.`id` as rnum,
  `tm_nmo_razd_user`.`proydeno`,
  `tm_nmo_razd_user`.`user`,
  `tm_nmo_razd_user`.`razdel`,`tm_nmo_razd_media`.tm_nmo_razd,
  `tm_nmo_razd_media`.`nazv`,
   `tm_nmo_razd_media`.`num`,
   `tm_nmo_razd_user`.`sp`,
   `tm_nmo_razd_user`.`psp` 
FROM
  `tm_nmo_razd_user`
  INNER JOIN `tm_nmo_razd_media` ON (`tm_nmo_razd_user`.`razdel` = `tm_nmo_razd_media`.`id`)
WHERE
  `tm_nmo_razd_user`.`user` = $coluser
union

SELECT 
CAST('2001-01-01' as DATE) AS `dat`, 0 as rnum,
0 as `proydeno`,
0 as user,
0 as razdel,`tm_nmo_razd_media`.tm_nmo_razd,
  `tm_nmo_razd_media`.`nazv`,
   `tm_nmo_razd_media`.`num`,0 as `sp`,
  0 as`psp` 
FROM
  `tm_nmo_razd_media`
  INNER JOIN `tm_nmo_razd` ON (`tm_nmo_razd_media`.`tm_nmo_razd` = `tm_nmo_razd`.`id`)
WHERE
  `tm_nmo_razd`.`spec` = $colspec and `tm_nmo_razd_media`.`tip` in (3,4) and `tm_nmo_razd_media`.`id` not in (SELECT 
   `tm_nmo_razd_user`.`razdel`
  FROM
  `tm_nmo_razd_user`
WHERE
  `tm_nmo_razd_user`.`user` = $coluser) order by num ";
  
 // echo $pathslq;
$paths =  /* fixed MMiC */ DB::Query($pathslq, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
//$prepod = mysql_query($query_prepod, $loc) or die(mysql_error());
$row_paths = mysqli_fetch_assoc($paths);
$totalRows_paths = mysqli_num_rows($paths);
		//echo $pathslq;	
			
			?>
			
			              <?php $oldr='0';
do { if( $row_paths['razdel']=='0')  $row_paths['razdel']=$oldr; else $oldr=$row_paths['razdel']; 
?>  
		<td class="mini">
			<?php if ($row_paths['dat']!='2001-01-01'){ ?>
			  <?php
			    $ball = '';
			    if ($row_paths['proydeno'] != 0 && $row_paths['psp'] != 0) {
			      @$ball_raw = $row_paths['proydeno'] / ($row_paths['sp'] / $row_paths['psp']);
			      $ball = vball($ball_raw);
			    }
			    $percent = 0;
			    if ($row_paths['psp'] != 0) {
			      @$percent = $row_paths['sp'] / $row_paths['psp'] * 100;
			    }
			  ?>
			  <div class="nmo-result-card">
			    <div class="nmo-result-main">
			      <i class="icofont icofont-calendar"></i>
			      <a href="#" target="new" class="resa nmo-date-badge" data-razdel="<?php echo $row_paths['razdel']."_".$row_paths['user'].".xml"; ?>" data-user="<?php echo $row_paths['user']; ?>"><?php echo date("m.d", strtotime($row_paths['dat'])); ?></a>
			    </div>
			    <div class="nmo-score-row">
			      <span class="nmo-pill" title="Оценка"><i class="icofont icofont-star"></i> <?php echo $ball; ?></span>
			      <span class="nmo-pill" title="Результат"><i class="icofont icofont-tasks"></i> <?php echo $row_paths['proydeno']; ?>/<?php echo $percent; ?></span>
			    </div>
			    <div class="nmo-actions">
			      <a href="#" class="blank nmo-action" data-razdel="<?php echo $row_paths['razdel']."_".$row_paths['user'].".xml"; ?>" data-user="<?php echo $row_paths['user']; ?>" data-fio=" <?php echo $row_user['fio'];?>" data-dat="<?php echo $row_paths['dat']; ?>" data-pr="<?php echo $row_paths['proydeno'];?>" data-sp="<?php echo $row_paths['sp']?>" data-psp="<?php echo $row_paths['psp'];?>" data-ball="<?php echo $ball; ?>"><i class="icofont icofont-print"></i> Печать</a>
			      <a href="#" class="ed_b nmo-action" data-id="<?php echo $row_paths['rnum'];?>" data-user="<?php echo $row_user['num'];?>" data-razd="<?php echo $row_paths['tm_nmo_razd'];?>"><i class="icofont icofont-edit"></i> Редакт</a>
			      <a class="nmo-action" target="blank" href="../nmo_get_res.php?num=<?php echo $row_paths['rnum']; ?>"><i class="icofont icofont-file-document"></i> Экзамен</a>
			    </div>
			  </div>
			<?php } ?>

			</td>	
	<?php
} while ($row_paths=  /* fixed MMiC */ mysqli_fetch_assoc($paths));?>		
			
          </tr>    <?php
} while ($row_user=  /* fixed MMiC */ mysqli_fetch_assoc($user));?>
           
        </tbody>
      </table>
    </form>
		<button id="pch">Печать</button>	<button id="pch2">Печать отчета</button>	
		<div id="ress"></div>
      <p>&nbsp;</p>

  
    <p>&nbsp;</p>
	
	  <h2>&nbsp;</h2>
    <p>&nbsp;</p>
<hr />
    <p>&nbsp;</p>
  <!-- end .content --></div>
  <div class="sidebar2">
    <h4>&nbsp;</h4>
    <?php include('menu_nmo.php');?>
  </div>
  <!-- end .container --></div>
  <div id="pz">
  

  </div> 
<div class="modal fade" id="modal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Редактор данных</h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <label>Дата заполнения</label>        
                <input type="datetime-local" id="mdat" class="form-control">
<input type="hidden" id="muser" value="-1">

        <input type="hidden" id="mrazdel" value="-1">
        <input type="hidden" id="mid" value="-1">
        <label>Всего баллов</label>
        
                <input type="number" id="allmsp" class="form-control">
        
        <input type="number" id="msp" class="form-control" style="display:none">
        <label>Проходной балл в процентах</label>
        <input type="number" id="mpsp" class="form-control">
        <label>Пройдено</label>
        <input type="number" id="mproydeno" class="form-control">
      </div>
      <div class="modal-footer">
        <button id="add_m">Добавить</button>
      </div>
    </div>
  </div>
</div>
</body>
	
	  <script src="../js/jquery-1.11.3.min.js"></script>
  	 <link href="../css/bootstrap.css" rel="stylesheet">
  <link href="../css/bootstrap-select.min.css" rel="stylesheet">
	 
	 <script src="../js/bootstrap.js"></script>
    <script src="qrcode.min.js"></script>
  
	  <script src="printThis.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed --> 

  <script type="text/javascript">
	$(function() {
		fl=0;
	deff='';	

		
	$('.resa').on('click',function(e){
		e.preventDefault();
		
		razdel=$(this).data('razdel');
		user=$(this).data('user');
		$.get( "parsttestnmo.php",{razdel:razdel,user:user}, function(data) {
$('#ress').append(data);
})
		
	});
        // Печать без printThis.js.
        // Старый printThis в новых браузерах может давать предупреждения:
        // Permissions policy violation: unload is not allowed in this document.
        // Поэтому печатаем через отдельное окно и свои стили.
        function nativePrintHtml(title, html, css) {
            var w = window.open('', '_blank', 'width=1000,height=800');
            if (!w) {
                alert('Браузер заблокировал окно печати. Разрешите всплывающие окна для этого сайта.');
                return;
            }

            w.document.open();
            w.document.write('<!doctype html><html><head><meta charset="utf-8"><title>'+title+'</title>');
            w.document.write('<style>');
            w.document.write('@page{size:A4;margin:12mm;}');
            w.document.write('body{font-family:Arial,Helvetica,sans-serif;color:#111;background:#fff;font-size:12px;}');
            w.document.write('table{width:100%;border-collapse:collapse;}');
            w.document.write('th,td{border:1px solid #999;padding:5px;vertical-align:top;}');
            w.document.write('th{background:#eef4fb;font-weight:bold;}');
            w.document.write('.mini{font-size:10px;word-break:break-word;}');
            w.document.write(css || '');
            w.document.write('</style></head><body>');
            w.document.write(html);
            w.document.write('</body></html>');
            w.document.close();

            setTimeout(function(){
                w.focus();
                w.print();
                setTimeout(function(){ try { w.close(); } catch(e) {} }, 500);
            }, 400);
        }

        function nativePrintElement(selector, title, css) {
            var el = document.querySelector(selector);
            if (!el) return;
            nativePrintHtml(title, el.innerHTML, css);
        }

        // CSS именно для печати сводной таблицы.
        // Главное: убираем экранные действия «Печать / Редакт / Экзамен»,
        // чтобы в отчете остались только дата, оценка и результат.
        var tablePrintCss = ''+
            '#pe .nmo-actions,#pe .nmo-action{display:none!important;}'+
            '.nmo-result-card{min-width:105px;padding:6px 7px;border:1px solid #888;border-radius:8px;background:#fff;font-size:10px;line-height:1.25;}'+
            '.nmo-result-main{display:flex;align-items:center;gap:5px;margin-bottom:4px;}'+
            '.nmo-date-badge{display:inline-block;padding:2px 6px;border-radius:10px;background:#eef4fb;color:#111;font-weight:bold;text-decoration:none;white-space:nowrap;}'+
            '.nmo-score-row{display:flex;flex-wrap:wrap;gap:4px;margin-bottom:0;}'+
            '.nmo-pill{display:inline-flex;align-items:center;gap:3px;padding:2px 5px;border-radius:10px;background:#fff;border:1px solid #ccc;white-space:nowrap;}'+
            '#pe th,#pe td{border:1px solid #777;padding:4px;vertical-align:top;}'+
            '#pe th{background:#eef4fb;font-weight:bold;}';

	$('#pch').on('click',function(){
            var title = '<h2 style="text-align:center;margin:0 0 12px 0;">Сводная таблица прохождения тестов</h2>';
            nativePrintHtml('Сводная таблица', title + $('#pe').prop('outerHTML'), tablePrintCss);
	});		
		$('#pch2').on('click',function(){
            // Кнопка "Печать отчета" печатает блок #ress.
            // Раньше #ress заполнялся только после ручного клика по дате результата (.resa),
            // поэтому если ничего не открыть заранее — на печати был белый лист.
            // Теперь перед печатью автоматически собираем отчеты по всем видимым результатам.
            var links = $('.resa');

            if (links.length === 0) {
                alert('Нет результатов для печати отчета.');
                return;
            }

            $('#ress').empty().append('<div style="padding:20px;font-family:Arial">Формируется отчет...</div>');

            var requests = [];
            links.each(function(){
                var razdel = $(this).data('razdel');
                var user = $(this).data('user');

                requests.push($.get('parsttestnmo.php', {razdel: razdel, user: user}));
            });

            $.when.apply($, requests).done(function(){
                $('#ress').empty();

                // Если отчет один, jQuery возвращает data напрямую.
                // Если отчетов несколько, каждый аргумент — массив [data, status, xhr].
                if (requests.length === 1) {
                    $('#ress').append(arguments[0]);
                } else {
                    for (var i = 0; i < arguments.length; i++) {
                        $('#ress').append(arguments[i][0]);
                        $('#ress').append('<div style="page-break-after:always"></div>');
                    }
                }

                nativePrintElement('#ress', 'Печатный отчет', '.nmo-actions,.nmo-action{display:none!important;}');
            }).fail(function(){
                $('#ress').empty();
                alert('Не удалось сформировать отчет. Проверьте parsttestnmo.php или соединение с сервером.');
            });
	});		
	
     $(".blank").click(function(e){
     e.preventDefault();
     //console.log();
     sins="";  
       razdel=$(this).data('razdel');
		user=$(this).data('user');
       fio=$(this).data('fio');
       pr=($(this).data('pr')/10);
       sp=($(this).data('sp')/10);
         console.log([pr,sp]);
              re=($(this).data('sp')/$(this).data('psp')*10);  
     //  pr=$(this).data('sp')/re*10;
       itog=Math.round(pr/re*100);
         console.log([re,pr]);
      datt=$(this).data('dat');
     
		$.get( "parsttestnmores.php",{razdel:razdel,user:user}, function(data) {
          data=JSON.parse(data);
 
     data.forEach(function(item, i, arr) {
       st = item.status == 1 ? "Да" : "Нет";
       ip = String(i + 1);
       sins = sins + '<span class="answer-card '+(item.status == 1 ? 'answer-ok' : 'answer-no')+'"><b>'+ip.padStart(2, '0')+'</b><em>'+st+'</em></span>';
 
});

          function escHtml(value) {
            return $('<div/>').text(value == null ? '' : value).html();
          }

          var specName = escHtml($('#spec option:selected').text());
          var fioPrint = escHtml(fio);
          var datPrint = escHtml(datt);
          var resultText = "Пройдено";
          var reportHtml = '';

          reportHtml += '<style>';
          reportHtml += '@page{size:A4;margin:14mm;}';
          reportHtml += '.nmo-print-report{font-family:Arial,Helvetica,sans-serif;color:#111;font-size:13px;line-height:1.35;max-width:190mm;margin:0 auto;}';
          reportHtml += '.nmo-print-report *{box-sizing:border-box;}';
          reportHtml += '.report-top{border-bottom:3px solid #1f4e79;padding-bottom:10px;margin-bottom:18px;text-align:center;}';
          reportHtml += '.report-org{font-size:14px;font-weight:bold;text-transform:uppercase;letter-spacing:.2px;}';
          reportHtml += '.report-college{font-size:16px;font-weight:bold;margin-top:6px;}';
          reportHtml += '.report-title{text-align:center;font-size:22px;text-transform:uppercase;margin:18px 0 6px 0;color:#1f4e79;}';
          reportHtml += '.report-subtitle{text-align:center;font-size:15px;margin:0 0 18px 0;}';
          reportHtml += '.report-meta{width:100%;border-collapse:collapse;margin:10px 0 16px 0;}';
          reportHtml += '.report-meta td{border:1px solid #b8c7d6;padding:8px 10px;}';
          reportHtml += '.report-meta td:first-child{width:38%;background:#eef4fb;font-weight:bold;}';
          reportHtml += '.result-table{width:100%;border-collapse:collapse;margin:12px 0 18px 0;}';
          reportHtml += '.result-table th{background:#1f4e79;color:#fff;border:1px solid #1f4e79;padding:9px 7px;text-align:center;font-size:12px;}';
          reportHtml += '.result-table td{border:1px solid #8fa9c2;padding:10px 7px;text-align:center;font-size:14px;}';
          reportHtml += '.result-table td:first-child{text-align:left;font-weight:bold;}';
          reportHtml += '.status-good{display:inline-block;padding:5px 12px;border:1px solid #2f7d32;border-radius:12px;font-weight:bold;}';
          reportHtml += '.answers-title{font-size:16px;font-weight:bold;margin:18px 0 10px 0;color:#1f4e79;}';
          reportHtml += '.answers-grid{display:grid;grid-template-columns:repeat(10,1fr);gap:6px;margin-bottom:18px;}';
          reportHtml += '.answer-card{border:1px solid #b8c7d6;border-radius:6px;min-height:34px;padding:4px 3px;text-align:center;background:#fff;}';
          reportHtml += '.answer-card b{display:block;font-size:11px;color:#555;line-height:12px;}';
          reportHtml += '.answer-card em{display:block;font-style:normal;font-size:13px;font-weight:bold;}';
          reportHtml += '.answer-ok{border-color:#8abf8d;background:#f4fbf4;}';
          reportHtml += '.answer-no{border-color:#d9a0a0;background:#fff5f5;}';
          reportHtml += '.signatures{margin-top:26px;display:grid;grid-template-columns:1fr 1fr;gap:16px;}';
          reportHtml += '.sign-box{border-top:1px solid #111;padding-top:8px;text-align:center;font-size:13px;min-height:45px;}';
          reportHtml += '.footer-row{margin-top:18px;display:flex;align-items:flex-end;justify-content:space-between;gap:20px;}';
          reportHtml += '.note{font-size:11px;color:#555;max-width:120mm;}';
          reportHtml += '#qrcode{width:92px;min-height:92px;text-align:right;}';
          reportHtml += '#qrcode img{width:92px!important;height:92px!important;display:block!important;}';
          reportHtml += '#qrcode canvas{width:92px!important;height:92px!important;display:block!important;}';
          reportHtml += '#qrcode table{width:92px!important;height:92px!important;border-collapse:collapse!important;border:0!important;margin:0!important;}';
          reportHtml += '#qrcode table td{width:auto!important;height:auto!important;border:0!important;padding:0!important;margin:0!important;}';
          reportHtml += '@media print{body{background:#fff!important}.nmo-print-report{max-width:none}.answers-grid{grid-template-columns:repeat(10,1fr);page-break-inside:auto}.result-table,.report-meta,.signatures{page-break-inside:avoid}}';
          reportHtml += '</style>';

          reportHtml += '<div class="nmo-print-report">';
          reportHtml += '<div class="report-top">';
          reportHtml += '<div class="report-org">Государственное автономное профессиональное образовательное учреждение</div>';
          reportHtml += '<div class="report-college">ГАПОУ «Зеленодольский медицинский колледж»</div>';
          reportHtml += '</div>';
          reportHtml += '<div class="report-title">Протокол результатов тестирования</div>';
          reportHtml += '<div class="report-subtitle">'+specName+'</div>';
          reportHtml += '<table class="report-meta"><tbody>';
          reportHtml += '<tr><td>Дата тестирования</td><td>'+datPrint+'</td></tr>';
          reportHtml += '<tr><td>ФИО аттестуемого</td><td>'+fioPrint+'</td></tr>';
          reportHtml += '</tbody></table>';
          reportHtml += '<table class="result-table"><thead><tr>';
          reportHtml += '<th>ФИО аттестуемого</th><th>Всего тестовых заданий</th><th>Количество правильных заданий</th><th>Процент выполнения</th><th>Результат</th>';
          reportHtml += '</tr></thead><tbody>';
          reportHtml += '<tr><td>'+fioPrint+'</td><td>'+re+'</td><td>'+pr+'</td><td>'+itog+'%</td><td><span class="status-good">'+resultText+'</span></td></tr>';
          reportHtml += '</tbody></table>';
          reportHtml += '<div class="answers-title">Таблица правильных ответов</div>';
          reportHtml += '<div class="answers-grid">'+sins+'</div>';
          reportHtml += '<div class="signatures">';
          reportHtml += '<div class="sign-box">Аттестуемый</div>';
          reportHtml += '<div class="sign-box">Член экспертной группы</div>';
          reportHtml += '</div>';
          reportHtml += '<div class="footer-row">';
          reportHtml += '<div class="note">Документ сформирован автоматически. QR-код содержит ссылку для проверки результата тестирования.</div>';
          reportHtml += '<div id="qrcode"></div>';
          reportHtml += '</div>';
          reportHtml += '</div>';

          $("#pz").empty().append(reportHtml);

          // QRCode.js обычно рисует QR как canvas.
          // Если просто скопировать innerHTML в окно печати, содержимое canvas пропадает,
          // поэтому перед печатью превращаем QR в обычную картинку <img src="data:image/png...">.
          var qrBox = document.getElementById("qrcode");
          var qrText = location.protocol + "//" + location.hostname + "/rt.php" + "?razdel=" + encodeURIComponent(razdel) + "&user=" + encodeURIComponent(user);

          if (qrBox) {
              qrBox.innerHTML = '';

              // ВАЖНО: в старой qrcode.min.js часто работает именно простой вызов
              // new QRCode(element, text), а объект {text,width,height} может не отработать.
              try {
                  new QRCode(qrBox, qrText);
              } catch (e) {
                  console.log('QRCode error', e);
                  qrBox.innerHTML = '<div style="font-size:10px;word-break:break-all;border:1px solid #999;padding:5px;width:92px;height:92px;">' + qrText + '</div>';
              }
          }

          setTimeout(function(){
              var qrBox2 = document.getElementById("qrcode");
              if (qrBox2) {
                  var canvas = qrBox2.querySelector("canvas");
                  var img = qrBox2.querySelector("img");
                  var table = qrBox2.querySelector("table");

                  // Если QR нарисован canvas — превращаем в img, иначе canvas в новом окне будет пустым.
                  if (canvas) {
                      try {
                          qrBox2.innerHTML = '<img src="' + canvas.toDataURL("image/png") + '" style="width:92px;height:92px;display:block;">';
                      } catch (e) {
                          console.log('QR canvas convert error', e);
                      }
                  } else if (img) {
                      qrBox2.innerHTML = '<img src="' + img.src + '" style="width:92px;height:92px;display:block;">';
                  } else if (table) {
                      // Некоторые старые qrcode.min.js рисуют QR таблицей.
                      // Таблицу оставляем, но выше добавлены CSS-правила, чтобы общие стили печати ее не ломали.
                      table.setAttribute('style', 'width:92px!important;height:92px!important;border-collapse:collapse!important;border:0!important;margin:0!important;');
                      $(table).find('td').attr('style', 'border:0!important;padding:0!important;margin:0!important;');
                  } else if (qrBox2.innerHTML.trim() === '') {
                      qrBox2.innerHTML = '<div style="font-size:10px;word-break:break-all;border:1px solid #999;padding:5px;width:92px;height:92px;">' + qrText + '</div>';
                  }
              }

              nativePrintElement('#pz', 'Протокол результатов тестирования', '');
          }, 1200);
})
       
       
      
       





//   
       
       
     
     });
      
     
fl=0;
      $('#allmsp').keyup(function(){
      d=$('#allmsp').val();
        d=d/100*$('#mpsp').val();
        $('#msp').val(d);
      });
      $('body').on('click','.ed_b',function(){
   user=$(this).data('user');
        console.log(user);
      if($(this).data('id')==0){
        $('#muser').val(user);
          $('#mrazdel').val($(this).data('razd'));
           $('#mid').val(0);
          $('#msp').val('');
          $('#mpsp').val('');
          $('#mproydeno').val('');
          $('#modal').modal('show');
        return;
      }
        $.get( "add_student_nmo_test_path.php?mid="+$(this).data('id'),{}, function(data) {
          data=JSON.parse(data); 
console.log(data);
          if(data.dat!='') dd=data.dat.replace(' ', 'T');
          $('#mdat').val(dd);
          $('#muser').val(user);
          $('#mid').val(data.id);
          $('#msp').val(data.sp);
          $('#mpsp').val(data.psp);
        
          
          $('#allmsp').val(data.sp/data.psp*100);
          
          $('#mproydeno').val(data.proydeno);
          $('#modal').modal('show');
});
       });
      
      
$('#add_m').click(function(){
          mid= $('#mid').val();
          msp=$('#msp').val();
          mpsp=$('#mpsp').val();
    mrazdel=$('#mrazdel').val();
           mdat=$('#mdat').val();
  mdat=mdat.replace('T',' ');
  user=$('#muser').val();
          mproydeno=$('#mproydeno').val();
  console.log(mrazdel);
	$.post('add_student_nmo_test_path.php', {'mid':mid, 'msp' :msp,'mpsp':mpsp,'mproydeno':mproydeno,'muser':user,'mrazdel':mrazdel,'mdat':mdat},		function(data) {
    
      $('#modal').modal('hide');
    
    
    });

});      
      
		$('td').on('click',function(){
		if ($(this).data('num')==undefined) return;
			if (fl==0){
			console.log($(this));fl=1;
	//	console.log($('#p'+$(this).prop('name')));
if ($(this).data('t')==0)$(this)[0].innerHTML='<input type="text" name="textfield" id="textfield" value="'+$(this)[0].innerText+'"><input type="button" name="button" id="btg" value="V">';
				
	//	console.log($('#p'+$(this).prop('name')));
if ($(this).data('t')==4)$(this)[0].innerHTML='<input type="date" name="textfield" id="textfield" value="'+$(this)[0].innerText+'"><input type="button" name="button" id="btg" value="V">';				
				
if ($(this).data('t')==2)$(this)[0].innerHTML='<textarea type="text" name="textfield" id="textfield" value="'+$(this)[0].innerText+'">'+$(this).data('com')+'</textarea><input type="button" name="button" id="btg" value="V">'; 

if ($(this).data('t')==3)
	{
		console.log($(this)[0].innerText);
		if ($(this)[0].innerText=="1")ch='checked="checked"' ; else ch='';
	$(this)[0].innerHTML='<input type="checkbox" name="textfield" id="textfield" '+ch+'><input type="button" name="button" id="btg" value="V">'; }
}
		});

	$('body').on('click', '#btg',function(){
			
			num=$(this).parent().data('num')
				name=$(this).parent().data('name');
				bas=$(this).parent().data('base');
			razd=$(this).parent().data('razd');
		user=$(this).parent().data('user');
				val=$(this).parent().children().first().val();
		
		if ($(this).parent().data('t')==3){

			if($(this).parent().children().first().prop("checked")==true)
		val=1; else val=0;
		
		}
			par=	$(this).parent();fl=0;
		$(this).parent().children().remove();$(par).append(val)
		$.post('add_student_nmo_test_path.php', {'upd':'1', 'num' :num,'name':name,'val':val,'bas':bas,'razd':razd,'user':user},		function(data) {		});
		
	});		
		
	});
	 
	
	</script>
</html>
<?php
if (isset($podgruppa) && $podgruppa) mysqli_free_result($podgruppa);
// mysqli_free_result($prepod); // переменная $prepod в этом файле не используется

?>
