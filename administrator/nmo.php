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

$colname_test = "-1";
if (isset($_SESSION['MM_UserGroup'])) {
  $colname_test = $_SESSION['MM_UserGroup'];
}
$username_test = "0";
if (isset($_SESSION['MM_Username1'])) {
  $username_test = $_SESSION['MM_Username1'];
}

if ($_POST["kr"]==1){
//	$.post('nmo.php', {'kr':'1', 'id' :deff1,'name':deff2},		function(data) {		});			
	$insertSQL = sprintf("INSERT INTO `tm_nmo_razd_user` (`id`, `user`, `razdel`, `proydeno`,dat,dop) VALUES (NULL, %s, %s,%s, %s,%s)",
                       GetSQLValueString($username_test, "int"),
					  GetSQLValueString($_POST["razd"], "int"),
 					  GetSQLValueString($_POST["id"], "int"),
					 GetSQLValueString(date('Y-m-d'), "date"),
						  GetSQLValueString($_POST["name"], "text"));

	echo $insertSQL;
	DB::Query($insertSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link)); 
exit(0);

}



$query_test="SELECT 
  `tm_nmo_razd`.`id`,
  `tm_nmo_razd`.`spec`,
  `tm_nmo_razd`.`nazv`,
  `tm_nmo_razd`.`activ`,
  `tm_nmo_razd`.`comment`,
  `tm_nmo_razd`.`num`,
   `tm_nmo_razd`.`img`
FROM
  `tm_nmo_razd`
WHERE
  `tm_nmo_razd`.`spec` = $colname_test and activ=1 order by num";
$test =  /* fixed MMiC */ DB::Query($query_test, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_test =  /* fixed MMiC */ mysqli_fetch_assoc($test);
$totalRows_test =  /* fixed MMiC */ mysqli_num_rows($test);


$sql_name="SELECT 
  `tm_spec`.`nazv`
FROM
  `tm_spec`
WHERE
  `tm_spec`.`num` = $colname_test";

$test_name =  /* fixed MMiC */ DB::Query($sql_name, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_name =  /* fixed MMiC */ mysqli_fetch_assoc($test_name);
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
<style>
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
<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<?php include("header.php");?>

<!-- HEADER --><!-- / HEADER --> 

<!--  SECTION-1 -->
	<div id="myModalBox" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- Заголовок модального окна -->
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title">Показ видео</h4>
      </div>
      <!-- Основное содержимое модального окна -->
      <div class="modal-body">
       
		 <div id="dfm"></div>
		  
		
		  
      </div>
      <!-- Футер модального окна -->
      <div class="modal-footer">
        <button type="button" class="btn btn-info" data-dismiss="modal">Закрыть</button>
       
      </div>
    </div>
  </div>
</div>	
	
	
<section>
  <div class="row">
    <div class="col-lg-12 page-header text-center">
      <h2>Система непрерывного обучения</h2>
     <h3> <?php echo $row_name['nazv']; ?></h3>
    </div>
  </div>
  <div class="container">
    <div class="row">
    
    </div>
    <div class="row">
	  
	  <div class="panel-group" id="accordion">
		    <?php
		  
		  
		  
		  $x=$row_test['id']; 
		  if (isset($_GET['row']))$x=$_GET['row']; else  $_GET['row']=$x;
		  
		  $stop=0; do {
		  
		  ?>
  <!-- 1 панель -->
  <div class="panel panel-default drop-shadow">
    <!-- Заголовок 1 панели -->
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne<?php echo $row_test['id']; ?>"><?php echo $row_test['nazv']; ?></a>
      </h4>
    </div>
    <div id="collapseOne<?php echo $row_test['id']; ?>" class="panel-collapse collapse <?php if($row_test['id']==$x) echo "in"; ?>">
      <!-- Содержимое 1 панели -->
      <div class="panel-body">
		   <?php //if ( $stop==0)
		  
		  
		  {?> 
		  
		  
		  <!-- Содержимое Блока коммент -->  
	  <div class="wp-block property list">
        <div class="wp-block-body">
			
			<?php if($row_test['img']!='0') {?>
          <div class="wp-block-img">
          
              <img src="../nmo/img/<?php echo $row_test['img']; ?>" alt="">
          
          </div>
			   <?php } ?>
          <div class="wp-block-content">
            <small>
<span class="glyphicon glyphicon-calendar" aria-hidden="true"></span> <?php echo date("d.m.y");?></small>
            <h4 class="content-title"><?php echo $row_test['nazv']; ?></h4>
            <p class="description"><?php echo $row_test['comment']; ?></p>
           
            <span class="pull-right">
              <span class="capacity">
                <i class="fa fa-user"></i><?php echo $row_us['fio'];  ?>
              </span>
            </span>
          </div>
        </div>
     
      </div>	  
		  
		  
			  <p>
			  
	<?php 
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
  `tm_nmo_razd_media`.`povt`
FROM
  `tm_nmo_razd_media`
WHERE
  `tm_nmo_razd_media`.`tm_nmo_razd` =".$row_test['id']." order by  `tm_nmo_razd_media`.`num`"; 
					  $razd =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_razd =  /* fixed MMiC */ mysqli_fetch_assoc($razd);
$totalRows_razd =  /* fixed MMiC */ mysqli_num_rows($razd);
			  ?>
			  
		  <?php   $stop=0; do {  ?>	  
			  
		 <?php if ($row_razd['tip']==1) {?> <div class="panel  panel-info  "><?php }?>
		   <?php if ($row_razd['tip']==2) {?> <div class="panel panel-success"><?php }?>
		    <?php if ($row_razd['tip']==3) {?> <div class="panel panel-danger"><?php }?>
		    <?php if ($row_razd['tip']==4) {?> <div class="panel panel-warning"><?php }?>
  <div class="panel-heading">
	 
	  
	<?php if ($row_razd['tip']==1){?> <span class="glyphicon glyphicon-book" aria-hidden="true"></span><?php } ?>
	  	<?php if ($row_razd['tip']==2){?> <span class="glyphicon glyphicon-film" aria-hidden="true"></span><?php } ?> 
	  	<?php if ($row_razd['tip']==3){?> <span class="glyphicon glyphicon-qrcode" aria-hidden="true"></span><?php } ?> 
	  	<?php if ($row_razd['tip']==4){?> <span class="glyphicon glyphicon-file" aria-hidden="true"></span><?php } ?> 
	  	
	  
	  <?php echo $row_razd['nazv']; ?><span class="badge pull-right">  <?php echo $row_razd['num']; ?></span>
		  </div>
  <div class="panel-body">
	<?php if ( $stop==0){ ?> 
	  
	 <?php if ($row_razd['tip']==1) {?>
          <a href="nmo/doc/<?php echo $row_razd['path']; ?>" class="btn btn-info form-control" target="new">скачать</a>
       <?php }?>
	  
	   <?php if ($row_razd['tip']==2) {?>
      <input type="button" class="btn btn-info form-control vdo" value="Посмотреть" data-vdo="<?php echo $row_razd['path']; ?>">
       <?php }?>
	  
	  
	  
	   <?php if ($row_razd['tip']==3) {
	  $sqlu="SELECT 
  `tm_nmo_razd_user`.`dat`,
  `tm_nmo_razd_user`.`proydeno`,
  `tm_nmo_razd_user`.`dop_file`
FROM
  `tm_nmo_razd_user`
WHERE
  `tm_nmo_razd_user`.`proydeno` > 0 AND 
  `tm_nmo_razd_user`.`user` = $username_test AND 
  `tm_nmo_razd_user`.`razdel` = ".$row_razd['id'];
				//  echo $sqlu;
				   $sqlu =  /* fixed MMiC */ DB::Query($sqlu, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
	  $row_sqlu =  /* fixed MMiC */ mysqli_fetch_assoc($sqlu);
$totalRows_sqlu =  /* fixed MMiC */ mysqli_num_rows($sqlu);
	  
	  
	  
	  
	  ?>
	 <?php if ($totalRows_sqlu>0) {
	   $stop=0;
	  ?>
	  
	  
	     <div class="wp-block-content">
         
<span class="glyphicon glyphicon-calendar" aria-hidden="true"></span> <?php echo date("d.m.y");?></small>
            Тест пройден<span class="glyphicon glyphicon-bookmark pull-right" aria-hidden="true">  правильных ответов <?php echo $row_sqlu['proydeno']; ?></span>  
             </div>
	  
	  <?php } else {
			  $stop=1;
		  $ussql="SELECT 
  `tm_nmo_razd_media_user_act_test`.`datact`
FROM
  `tm_nmo_razd_media_user_act_test`
WHERE
  `tm_nmo_razd_media_user_act_test`.`razd_media_test` = ".$row_razd['id']." AND 
  `tm_nmo_razd_media_user_act_test`.`user` = $username_test";
					   $sqluus =  /* fixed MMiC */ DB::Query($ussql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
	  $row_sqluus =  /* fixed MMiC */ mysqli_fetch_assoc($sqluus);  
		
		if ($row_sqluus['datact']<=date(("Y-m-d"))){  
		  
		  ?>
          <a href="nmo_get_test.php?num=<?php echo $row_razd['id']; ?>&test=<?php echo $row_razd['path']; ?>" class="btn btn-info form-control">Пройти тест</a>
		  <?php } else {?> <span class="glyphicon glyphicon-calendar" aria-hidden="true"></span>  тест будет доступен  <?php echo $row_sqluus['datact'];?></small><?php }?>
       <?php }?>
	  <?php }?>	  
	  <?php if ($row_razd['tip']==4) {
	$sqkvr="SELECT 
  `tm_nmo_razd_user`.`id`,
  `tm_nmo_razd_user`.`user`,
  `tm_nmo_razd_user`.`razdel`,
  `tm_nmo_razd_user`.`proydeno`,
  `tm_nmo_razd_user`.`dop_file`,
  `tm_nmo_razd_user`.`dat`,
  `tm_nmo_razd_user`.`dop`
FROM
  `tm_nmo_razd_user`
WHERE
  `tm_nmo_razd_user`.`razdel` = ".$row_razd['id']." AND 
  `tm_nmo_razd_user`.`user` = $username_test"; 
				  
				
	 $sqkvr =  /* fixed MMiC */ DB::Query($sqkvr, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_sqkvr =  /* fixed MMiC */ mysqli_fetch_assoc($sqkvr);
$totalRows_sqkvr =  /* fixed MMiC */ mysqli_num_rows($sqkvr);  			  
				  
if (($totalRows_sqkvr>0)and ($row_sqkvr['dop_file']=='')){?>
	<form action="nmo.php" method="post" enctype="multipart/form-data" id="fm<?php echo $row_sqkvr['id'];?>">	  
	<p class="form-control"><?php echo $row_sqkvr['dop'];?><small>
<span class="glyphicon glyphicon-calendar pull-right" aria-hidden="true"><?php echo $row_sqkvr['dat'];?></span></small></p>	  
	<input type="file" name="fnm" class="form-control">	  
		<input type="hidden" name="id" value="<?php echo $row_sqkvr['id'];?>">
		<div class="progress">

</div>
		
	<input type="button" class="btn-danger form-control gf" id="gf<?php echo $row_sqkvr['id'];?>" value="Отправить" data-id="<?php echo $row_sqkvr['id'];?>">
		</form>  
		  <?php }
		if (($totalRows_sqkvr>0)and ($row_sqkvr['dop_file']!='')){?>
		  <p class="form-control"><?php echo $row_sqkvr['dop'];?><small>
<span class="glyphicon glyphicon-calendar pull-right" aria-hidden="true"><?php echo $row_sqkvr['dat'];?></span></small></p>	 
	<a class="btn-danger form-control" href="<?php echo $row_sqkvr['dop_file'];?>">Посмотреть</a>

		
		  <?php }		  
				  
				  if ($totalRows_sqkvr<1){
				  
				  
				  
	  $sqllist="SELECT 
  `tm_nmo_razd_media_list`.`id`,
  `tm_nmo_razd_media_list`.`tm_nmo_razd_media`,
  `tm_nmo_razd_media_list`.`tex`
FROM
  `tm_nmo_razd_media_list`
WHERE
  `tm_nmo_razd_media_list`.`tm_nmo_razd_media` =".$row_razd['id'];
		  $list =  /* fixed MMiC */ DB::Query($sqllist, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_list =  /* fixed MMiC */ mysqli_fetch_assoc($list);
$totalRows_list =  /* fixed MMiC */ mysqli_num_rows($list);  
	  
	  
	  ?>
          <select name="list" id="list<?php echo $row_razd['id']; ?>" class="form-control">
			   <?php do { ?>	 
            <option value="<?php echo $row_list['id']; ?>"><?php echo $row_list['tex']; ?></option>
			   <?php } while ($row_list =  /* fixed MMiC */ mysqli_fetch_assoc($list)); ?>	
          </select><br>
	     <input type="button" class="btn btn-info form-control vdos" value="Выбрать" data-id="<?php echo $row_razd['id']; ?>" data-url="<?php echo $row_test['id'];?> ">
       <?php }}?>
      <p class="form-control-static"><?php echo $row_razd['comment']; ?></p>
		  
		  <?php } else { ?>  Будет доступно после сдачи тестов предыдущего раздела <?php } ?>
      </div>
    
  </div>

	   <?php } while ($row_razd =  /* fixed MMiC */ mysqli_fetch_assoc($razd));
							  
		  ?>	  
		  </p>
	   <?php } /* конец проверки на стоп*/
	 
	  ?>
	  

	 
      </div>
    </div>
  </div>
    <?php } while ($row_test =  /* fixed MMiC */ mysqli_fetch_assoc($test)); ?>
  <!-- 3 панель -->

</div>
	  
	  
	  </div>
    <div class="row"></div>
    
  </div>

  <!-- /container -->
  
  <div class="container">
    <div class="row"></div>
    <div class="row"></div>
  </div>
  <!-- / CONTAINER--> 
</section>

<!-- FOOTER -->
<div class="container">
  <div class="row"></div>
</div>
<footer class="text-center">
  <div class="container">
    <div class="row">
      <div class="col-xs-12">
 <?php //echo $query_test;?>
        <p>Cделал ASBcorp24</p>
      </div>
    </div>
  </div>
</footer>
<!-- / FOOTER --> 
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) --> 
<script src="js/jquery-1.11.3.min.js"></script> 
<!-- Include all compiled plugins (below), or include individual files as needed --> 
<script src="js/bootstrap.js"></script>
<script src="js/jquery.countdown.min.js"></script>

	
<script type="text/javascript">
	
	$(function() {
		
		$('[data-countdown]').each(function() {
  var $this = $(this), finalDate = $(this).data('countdown');
  $this.countdown(finalDate, function(event) {
    $this.html(event.strftime('%D days %H:%M:%S'));
  });
});
		
		function sendAjaxForm(result_form, ajax_form, url1,fpr='') {

   var form =($('#'+ajax_form)[0]);
	var formData = new FormData(form);
	
	var request = new XMLHttpRequest();
	request.upload.onprogress = function(event) {
  console.log(event.loaded + ' / ' + event.total);
  }
function reqReadyStateChange() {
	if (request.readyState == 4 && request.status == 200){
  		
		console.log($('#'+result_form).parent());
			location.href = "nmo.php?row="+<?php echo $_GET['row']; ?>;
	}
}

request.open("POST", url1);
request.onreadystatechange = reqReadyStateChange;
request.send(formData);
}	
		
		$('.gf').on('click',function(){
			deff=$(this).data('id');
			
			$(this).val('Отправляем');
		sendAjaxForm('gf'+deff,'fm'+deff,'nmo_lf.php','pb'+deff);
			
			
		});	
		
		
		$('.vdos').on('click',function(){
			deff=$(this).data('id');	
		url=$(this).data('url');	
deff1=$("#list"+deff+" :selected").val();
deff2=$("#list"+deff+" :selected").text();				
	$.post('nmo.php', {'kr':'1', 'id' :deff1,'name':deff2,'razd':deff},		function(data) {
	
	location.href = "nmo.php?row="+url;
	});			
		});
	$('.vdo').on('click',function(){
			deff=$(this).data('vdo');
		//$.post('add_nmo.php', {'list':deff},		function(data) {
	
		x=deff.lastIndexOf(".be");	console.log(x);
		deff=deff.substring(x+3);
			deff="https://www.youtube.com/embed/"+deff;	
		console.log(deff);
	$('#dfm').html( '<iframe width="100%" height="545" src="'+deff+'" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>');		
//	https://www.youtube.com/embed/qssHvCCePaY" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>				 
		$("#myModalBox").modal('show');

	});	
	 $("#myModalBox").on('hidden.bs.modal', function(){

 	 $('#dfm').html('');
  });		
		
	});
	
	</script>
</body>
</html>
<?php
 /* fixed MMiC */ mysqli_free_result($test);
?>
