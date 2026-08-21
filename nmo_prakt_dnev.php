<?php require_once('Connections/testmed.php'); ?>
<?php
$js1=0;
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

function yn($oplat1,$s){
foreach ($oplat1 as &$value) {
   if ($value[0]==$s) return($value[1]);
}	
return(-1);	
}
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

$MM_restrictGoTo = "login.php";
if (!((isset($_SESSION['MM_Username1'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username1'], $_SESSION['MM_UserGroup'])))) {   
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
$sql="update `tm_nmo_pract` set ".$_POST["name"]."='".addslashes($_POST["val"])."' where id=".$_POST["num"];	
DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
//	echo $sql;
	
	exit(0);	
	
}


if (isset($_POST['chto_del'])){
	
$sql=" INSERT INTO `tm_nmo_pract` (`id`, `user`, `razdel`, `dat`, `chto_del`, `otvets`) VALUES (NULL, ".$_SESSION['MM_Username1'].", ".$_SESSION['media'].", '".$_POST['dat']."',  '".addslashes($_POST['chto_del'])."', '".addslashes($_POST['kur'])."')"	;
	
	 DB::Query($sql, $testmed) or die( mysqli_error(DB::$link));
	/*dat:2020-04-25
chto_del:qeqdqdeq
kur:222
tip:12
tm_nmo_razd:*/
$sql="select max(id) as id from tm_nmo_pract";
	 $sqkvr =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_sqkvr =  /* fixed MMiC */ mysqli_fetch_assoc($sqkvr);
	$id=$row_sqkvr['id'];
echo '<tr>
   
      <td data-num="'.$id.'" data-name="dat" data-t="4">'.$_POST['dat'].'</td>
      <td data-num="'.$id.'" data-name="chto_del" data-t="3" style="font-size:small">'.stripslashes($_POST['chto_del']).'</td>
      <td data-num="'.$id.'" data-name="otvets">'.stripslashes($_POST['kur']).'</td>
	  </tr>';
	
exit;	
}


$colname_test = "-1";
if (isset($_SESSION['MM_UserGroup'])) {
  $colname_test = $_SESSION['MM_UserGroup'];
}
$username_test = "0";
if (isset($_SESSION['MM_Username1'])) {
  $username_test = $_SESSION['MM_Username1'];
}
$media = "0";
if (isset($_GET['media'])) {
  $media=$_GET['media'];
}
$_SESSION['media']=$media;

$sql="SELECT 
  `tm_nmo_pract`.`dat`,
  `tm_nmo_pract`.`chto_del`,
  `tm_nmo_pract`.`otvets`,
  `tm_nmo_pract`.`id`
FROM
  `tm_nmo_pract`
WHERE
  `tm_nmo_pract`.`user` = $username_test AND 
  `tm_nmo_pract`.`razdel` = $media
ORDER BY
 `tm_nmo_pract`.`dat`";
	 $sqkvr =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_sqkvr =  /* fixed MMiC */ mysqli_fetch_assoc($sqkvr);
$totalRows_sqkvr =  /* fixed MMiC */ mysqli_num_rows($sqkvr); 
$sql="SELECT 
  `tm_nmo_razd_media`.`nazv`
FROM
  `tm_nmo_razd_media`
WHERE
  `tm_nmo_razd_media`.`id` = $media ";
	 $sqkvr2 =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_sqkvr2 =  /* fixed MMiC */ mysqli_fetch_assoc($sqkvr2);
//echo $sql;
$sql="SELECT DISTINCT 
  `tm_nmo_pract`.`otvets`
FROM
  `tm_nmo_pract`";
	 $sqkvr3 =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_sqkvr3 =  /* fixed MMiC */ mysqli_fetch_assoc($sqkvr3);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo $row_name['nazv']; ?></title>

<!-- Bootstrap -->
<link rel="stylesheet" href="css/bootstrap.css">
<link rel="stylesheet" href="css/icofont.css">
	<script src="js/jquery-1.11.3.min.js"></script> 
	<script src="js/bootstrap.js"></script>
<style>


	.container {
 font-family: 'Forum', -apple-system, system-ui, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
}

    .drop-shadow {
        -webkit-box-shadow: 0 0 2px 1px rgba(0, 0, 0, .5);
        box-shadow: 0 0 2px 1px rgba(0, 0, 0, .5);
    }
    .container.drop-shadow {
        padding-left:0;
        padding-right:0;
    }
</style>
<link rel="stylesheet" href="nmo.css">
	   <style type="text/css">
   
    .input-group-addon.primary {
    color: rgb(255, 255, 255);
    background-color: rgb(50, 118, 177);
    border-color: rgb(40, 94, 142);
}
.input-group-addon.success {
    color: rgb(255, 255, 255);
    background-color: rgb(92, 184, 92);
    border-color: rgb(76, 174, 76);
}
.input-group-addon.info {
    color: rgb(255, 255, 255);
    background-color: rgb(57, 179, 215);
    border-color: rgb(38, 154, 188);
}
.input-group-addon.warning {
    color: rgb(255, 255, 255);
    background-color: rgb(240, 173, 78);
    border-color: rgb(238, 162, 54);
}
.input-group-addon.danger {
    color: rgb(255, 255, 255);
    background-color: rgb(217, 83, 79);
    border-color: rgb(212, 63, 58);
}    
	.checkbox {
	position: absolute;
	z-index: -1;
	opacity: 0;
	margin: 10px 0 0 20px;
}
.checkbox + label {
	position: relative;
	padding: 0 0 0 60px;
	cursor: pointer;
}
.checkbox + label:before {
	content: '';
	position: absolute;
	top: -4px;
	left: 0;
	width: 50px;
	height: 26px;
	border-radius: 13px;
	background: #CDD1DA;
	box-shadow: inset 0 2px 3px rgba(0,0,0,.2);
	transition: .2s;
}
.checkbox + label:after {
	content: '';
	position: absolute;
	top: -2px;
	left: 2px;
	width: 22px;
	height: 22px;
	border-radius: 10px;
	background: #FFF;
	box-shadow: 0 2px 5px rgba(0,0,0,.3);
	transition: .2s;
}
.checkbox:checked + label:before {
	background: #9FD468;
}
.checkbox:checked + label:after {
	left: 26px;
}
.checkbox:focus + label:before {
	box-shadow: inset 0 2px 3px rgba(0,0,0,.2), 0 0 0 3px rgba(255,255,0,.7);
}
.radio {
	position: absolute;
	z-index: -1;
	opacity: 0;
	margin: 10px 0 0 7px;
}	
.radio + label {
	position: relative;
	padding: 0 0 0 35px;
	cursor: pointer;
}
.radio + label:before {
	content: '';
	position: absolute;
	top: -3px;
	left: 0;
	width: 22px;
	height: 22px;
	border: 1px solid #CDD1DA;
	border-radius: 50%;
	background: #FFF;
}
.radio + label:after {
	content: '';
	position: absolute;
	top: 1px;
	left: 4px;
	width: 16px;
	height: 16px;
	border-radius: 50%;
	background: #9FD468;
	box-shadow: inset 0 1px 1px rgba(0,0,0,.5);
	opacity: 0;
	transition: .2s;
}
.radio:checked + label:after {
	opacity: 1;
}
.radio:focus + label:before {
	box-shadow: 0 0 0 3px rgba(255,255,0,.7);
}		
.disabledbutton {
    pointer-events: none;
    opacity: 0.8;
}	


</style>
<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<?php include("header.php");?>
	
	<div id="myModalBox" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- Заголовок модального окна -->
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title">Добавление практики</h4>
      </div>
      <!-- Основное содержимое модального окна -->
      <div class="modal-body">
       <form id="gfa" action="nmo_prakt_dnev.php">
		  <div class="form-group"> <label for="nazv">Дата</label> <input name="dat" type="date" class="form-control" id="nazv" placeholder="Введите дату"> </div> <div class="form-group"> <label for="comment">Содержимое практики</label> <textarea name="chto_del"  class="form-control" id="comment" placeholder="Комментарий"></textarea> </div>
		   <div class="form-group"> <label for="kur">Куратор</label> <input name="kur" type="text" class="form-control" id="kur" placeholder="Введите куратора" list="phonesList19">
		  <datalist id="phonesList19">
			  	  <?php do { ?>
   <option><?php echo $row_sqkvr3['otvets'];?></option>
	  <?php } while ($row_sqkvr3 =  /* fixed MMiC */ mysqli_fetch_assoc($sqkvr3));  ?>
					
			   </datalist>
		   </div>
		  <input type="hidden" name="tip" value="12"><input type="hidden" name="tm_nmo_razd" value="">
		</form>
		  
		
		  
      </div>
      <!-- Футер модального окна -->
      <div class="modal-footer">
	
        <button type="button" class="btn btn-info" data-dismiss="modal" id="sv">сохранить</button>
       
      </div>
    </div>
  </div>
</div>	

	
	
    <!--[if lt IE 8]>
    <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->  
    <!-- Body main wrapper start -->
    <div class="wrapper">
        <!-- Start of header area -->
        
        <!-- mobile-menu-area start -->
         <header class="header-area">
   
        </header>
        <!-- mobile-menu-area end -->
        <!-- End of header area -->
        <section class="breadcrumbs-area bg-3 ptb-110 bg-opacity bg-relative">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <div class="breadcrumbs">
                            <h2 class="page-title"><?php echo  $row_sqkvr2['nazv']; ?></h2>
                          	<div class="panel panel-default">
  <div class="panel-heading">Дневник практики</div>
  <div class="panel-body">
  <div align="left"><button class="btn btn-info add_r " data-razd="<?php echo $row_spec['id']; ?>">Добавить выполненную работу</button>
	  <button class="btn btn-info " id="pch">Печать</button>
	  <a class="btn btn-info" href="nmo.php">Вернутся</a>
	  
		 </div>
	  <hr>
	  <?php if($totalRows_sqkvr>0) { ?>
	  <table class="table table-striped table-bordered" id="pepe">
  <thead>
    <tr>
     
      <th>Дата</th>
      <th>Выполненная работа</th>
      <th>Куратор</th>
    </tr>
  </thead>
  <tbody id="tml">
	  <?php do { ?>
    <tr>
   
      <td  data-num="<?php echo $row_sqkvr['id']; ?>" data-name="dat" data-t="4"><?php echo $row_sqkvr['dat'];?></td>
      <td  data-num="<?php echo $row_sqkvr['id']; ?>" data-name="chto_del" data-t="3" style="font-size:small"><?php echo $row_sqkvr['chto_del'];?></td>
      <td data-num="<?php echo $row_sqkvr['id']; ?>" data-name="otvets" ><?php echo $row_sqkvr['otvets'];?></td>
	  </tr>
	  <?php } while ($row_sqkvr =  /* fixed MMiC */ mysqli_fetch_assoc($sqkvr));  ?>
  </tbody>
</table>
  <?php } ?>
  </div>
</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
		
<div class="container">
  <div class="row">

	
	
	
	</div>
</div>	

<!-- FOOTER -->
<div class="container">
  <div class="row"></div>
</div>
<footer class="text-center">
  <div class="container">
    <div class="row">
      <div class="col-xs-12">

        <p>Cделал ASBcorp24</p>
      </div>
    </div>
  </div>
</footer>
<!-- / FOOTER --> 
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) --> 

<!-- Include all compiled plugins (below), or include individual files as needed --> 

 <script src="printThis.js"></script>
<script type="text/javascript">
	
	$(function() {
		fl=0;
		
	$('#sv').on('click', function() {
			  
			$.post('nmo_prakt_dnev.php', $("#gfa").serialize(),
					function(data) {
				console.log(data);
				$('#tml').append(data);
			
					});	
		
		  });
		
	
		
		  $('.add_r').on('click', function() {
			  
			  	$("#myModalBox").modal('show');
		  });
		
  $('body').on('click', 'td', function() {
	   console.log($(this)[0].innerHTML);
        if ($(this).data('num') == undefined) return;
        if (fl == 0) {
           
            fl = 1;
            //	console.log($('#p'+$(this).prop('name')));
            if ($(this).data('tip') == undefined) tip = "text";
            else tip = $(this).data('tip');
			
           ssa= '<input type="' + tip + '" name="textfield"  value="' + $(this)[0].innerText + '" class="form-control"><input type="button" name="button"  value="Сохр" id="btg" class="btn btn-danger btn-md form-control">';
			if ($(this).data('t')==3)ssa='<textarea  data-d="ar" style="width:100%" class=" form-control" >'+$(this)[0].innerText+'</textarea><input type="button" name="button"  value="Сохр" id="btg" class="btn btn-danger btn-md form-control">';
			if ($(this).data('t')==4)ssa='<input type="date" name="textfield"  value="'+$(this)[0].innerText+'" class="form-control"><input type="button" name="button"  value="Сохр" id="btg" class="btn btn-danger btn-md form-control">';
			 $(this)[0].innerHTML =ssa;
        }
    });
		
 $('#pch').on('click', function() {

      //  $('.npc').hide();
        $('#pepe').printThis({
            afterPrint: function() {
             //   $('.npc').show();
            }
        });	  });	
		
$('body').on('click', '#btg',function(){
			console.log('dd');
			num=$(this).parent().data('num')
				name=$(this).parent().data('name');
				bas=$(this).parent().data('base');
				val=$(this).parent().children().first().val();
		if ($(this).data('d')=='ar') val=$(this).val();
			par=	$(this).parent();
	console.log($(par));
		$(par).children().remove();$(par).append(val)
		$.post('nmo_prakt_dnev.php', {'upd':'1', 'num' :num,'name':name,'val':val,'bas':bas},		function(data) {	
fl=0;
});
		
		
	});		
				
		
		
		
	
	});
	
	</script>
</body>
</html>

