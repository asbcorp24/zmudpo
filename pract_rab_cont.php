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
 function getExtension1($filename) {
    return end(explode(".", $filename));
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
$pnum = "0";
if (isset($_GET['pnum'])) {
  $pnum = $_GET['pnum'];
}

$query_spec = "SELECT 
  `tm_pract_temy`.`num`,
  `tm_pract_temy`.`inn`,
  `tm_pract_temy`.`zadanie`,
  `tm_pract_temy`.`file`,
  `tm_pract_temy`.`nazv_zad`,
  `tm_pract_temy`.`ball`
  
FROM
  `tm_pract_temy`

WHERE
  `tm_pract_temy`.`inn` = ".$pnum;
$pr =  /* fixed MMiC */ DB::Query($query_spec, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_pr =  /* fixed MMiC */ mysqli_fetch_assoc($pr);
$totalRows_spec =  /* fixed MMiC */ mysqli_num_rows($pr);



?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Тесты по специальности</title>
<!-- Bootstrap -->

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
<link rel="stylesheet" href="css/bootstrap.css">
<script type="text/javascript" src="ScriptLibrary/jquery-latest.pack.js"></script>
<link href="css/bootstrap-3.3.7.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap-responsive.css" />
<script type="text/javascript" src="bootstrap/js/bootstrap.js"></script>
<style>
	 .thumb img { 
      filter: none; /* IE6-9 */ 
      -webkit-filter: grayscale(0); 
      border-radius:5px; 
      background-color: #fff; 
      border: 1px solid #ddd; 
      padding:5px; 
    } 
    .thumb img:hover { 
      filter: gray; /* IE6-9 */ 
      -webkit-filter: grayscale(1); 
    } 
    .thumb { 
      padding:5px; 
    } 
  </style> 

	</style>
</head>
<body>
<?php include("header.php");?>

<!-- HEADER --><!-- / HEADER --> 

<!--  SECTION-1 -->
<section>
  <div class="row">
    <div class="col-lg-12 page-header">
      <h2 class="text-center">Практические работы</h2>
      
 
    <div class="container">
     <div class="panel-group" id="accordion">
      <?php  
		 
		 $ii=0;do { 
		 $ii++;
		 ?>
  <!-- 1 панель -->
  <div class="panel panel-default">
    <!-- Заголовок 1 панели -->
    <div class="panel-heading">
    <div class="list-group">
    <a  class="list-group-item active" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $ii; ?>" data-toggle="false">
        <span class="glyphicon glyphicon-star"></span> 
        <span style="text-transform: uppercase;"><?php echo $ii; ?>: <?php echo $row_pr['nazv_zad']; ?></span>
       <span class="glyphicon glyphicon-chevron-down"></span>  <span class="badge">оценка - <?php echo $row_pr['res']; ?></span>
    </a>
		</div>
       
     

    </div>
    <div id="collapse<?php echo $ii; ?>" class="panel-collapse collapse in">
      <!-- Содержимое 1 панели -->
      <div class="panel-body">
      
      
       <div>
   
     
       <div class="panel panel-default">
  <div class="panel-heading"> <h4>Задание</h4></div>
  <div class="panel-body">
  <p ><?php echo $row_pr['zadanie']; ?> </p>
  </div>
</div>
            
		 
              <div class="panel panel-default">
  <div class="panel-heading"> <h4 >Приложение</h4></div>
       <div class="panel-body">     
    
        <?php $sql="SELECT  `tm_pract_temy_file`.`path`, `tm_pract_temy_file`.`num`,  `tm_pract_temy_file`.`inn` FROM  `tm_pract_temy_file` WHERE   `tm_pract_temy_file`.`inn` = ".$row_pr['num'];
		$pr_temy_f   =/* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
				$row_pr_temy_f = mysqli_fetch_assoc($pr_temy_f);	  
			  do { ?>
  <div class="col-sm-6 col-md-4">
    <div class="thumbnail">
    <?php if ((@getExtension1($row_pr_temy_f['path'])=='jpg')or (@getExtension1($row_pr_temy_f['path'])=='png')){ ?>
       <a class="fancyimage" data-fancybox-group="group" href="./pract_temy/<?php echo $row_pr_temy_f['path']; ?>"> 
  <img class="img-responsive" src="get_img.php?img=pract_temy/<?php echo $row_pr_temy_f['path']; ?>&w=300&h=200" /> 
</a>   <?php }  else {?> 
      <img class="img-responsive" src="get_img.php?img=pdf.jpg&w=300&h=200" />  <?php }  ?> 
      <div class="caption">
        
        <p><a  href="./pract_temy/<?php echo $row_pr_temy_f['path']; ?>" target="_blank" class="btn btn-primary form-control" role="button">Скачать</a> </p>
      </div>
    </div>
  </div>
          
         
                <?php } while ($row_pr_temy_f = mysqli_fetch_assoc($pr_temy_f)); ?>
                
                
        </div>
          </div>
  
   
       
       
 
       <div class="panel panel-default">
  <div class="panel-heading form-inline"> <span class="form-control">Решение</span>     <div class="btn-group btn-group-sm">
 
  <?php if (in_array($row_pr['ball'],array(2,4,5,6))) { ?> <button type="button" class="btn btn-primary d1" name="<?php echo $row_pr['num']; ?>">Добавить скриншот</button><?php } ?>
  <?php if (in_array($row_pr['ball'],array(1,3,5,6))) { ?>  <button type="button" class="btn btn-primary d2" name="<?php echo $row_pr['num']; ?>">Добавить файл</button><?php } ?>
    <?php if (in_array($row_pr['ball'],array(0,3,4,6))) { ?><button type="button" class="btn btn-primary d3" name="<?php echo $row_pr['num']; ?>">Добавить текст</button><?php } ?>
</div></div>
       <div class="panel-body">     
      <div class="row" id="otvet<?php echo $row_pr['num']; ?>" >

	   <?php $sql="SELECT 
  `tm_user_pract`.`num`,
  `tm_user_pract`.`user`,
  `tm_user_pract`.`tema_pr`,
  `tm_user_pract`.`tema`,
  `tm_user_pract`.`file`,
  `tm_user_pract`.`img`,
  `tm_user_pract`.`res`,
  `tm_user_pract`.`otv`
FROM
  `tm_user_pract`
WHERE
  `tm_user_pract`.`tema_pr` = ".$row_pr['num']." AND 
  `tm_user_pract`.`user` = ".$username_test;
		//	 echo $sql;
		$pr_temy_f   =/* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
				$row_pr_temy_f = mysqli_fetch_assoc($pr_temy_f);	  
			  do { ?>
       
               <!-- 1 Изображение -->
  <div class="col-sm-6 col-md-4" id="thm<?php echo $row_pr_temy_f['num']; ?>">
    <div class="thumbnail">
     <?php
		if ($row_pr_temy_f['tema']==1)
		{ ?> 
		  <a class="fancyimage" data-fancybox-group="group" href="./pract_user/img/<?php echo $row_pr_temy_f['img']; ?>"> 
  <img class="img-responsive" src="get_img.php?img=pract_user/img/<?php echo $row_pr_temy_f['img']; ?>&w=300&h=200" /> 
</a>  
      
      
      <?php } ?>
          
          <?php
		if ($row_pr_temy_f['tema']==2)
		{ ?> 
		
  <img class="img-responsive" src="15.jpg" /> 
</a>  
      
      
      <?php } ?>
          
           <?php
		if ($row_pr_temy_f['tema']==3)
		{ ?> 
		  <a class="fancyimage" data-fancybox-group="group" href="#testube<?php echo $row_pr_temy_f['num']; ?>">
  <img class="img-responsive" src="get_img.php?img=14.jpg&w=300&h=200" /> 
</a>  
      <div style="display:none" id="testube<?php echo $row_pr_temy_f['num']; ?>">
 <!-- HTML - код ролика -->
<p><<?php echo nl2br($row_pr_temy_f['otv']); ?></p>
	  </div>
<!-- конец  HTML - кода ролика -->
      
      <?php } ?>
      
      <div class="caption">
      
        <div class="btn-group btn-group-justified">

       <?php  if ($row_pr_temy_f['tema']==1) {?>
        <a href="./pract_user/img/<?php echo $row_pr_temy_f['img']; ?>" class="btn btn-primary" role="button" target="_blank">Сохранить</a> 
        <?php } ?>
         <?php  if ($row_pr_temy_f['tema']==2) {?>
        <a href="./pract_user/file/<?php echo $row_pr_temy_f['file']; ?>" class="btn btn-primary" role="button" target="_blank">Сохранить</a> 
        <?php } ?>
        <a href="#" class="btn btn-danger dell" role="button" name="<?php echo $row_pr_temy_f['num']; ?>">Удалить</a>
         </div>
      </div>
    </div>
  </div>
               
            
                <?php } while ($row_pr_temy_f = mysqli_fetch_assoc($pr_temy_f)); ?>
		  
		  
		  
 

   </div>  
        <form enctype="multipart/form-data" name="form<?php echo $row_pr['num']; ?>" id="form<?php echo $row_pr['num']; ?>">
 
  
  
 <button class="btn btn-primary form-control sendd" type="button" id="<?php echo $row_pr['num']; ?>" style="display: none">Сохранить!</button>
 <input type="hidden" name="tnum" value="<?php echo $row_pr['num']; ?>" id="hnm">
  
</form>
   
    <div align="center" id="res<?php echo $row_pr['num']; ?>"></div>
</div>
 </div>
          </div>
  

       
      </div>
    </div>
    
    
  </div>
   <?php } while ($row_pr =  /* fixed MMiC */ mysqli_fetch_assoc($pr)); ?>
 </div>
</div>

    
    </div>
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
<script src="js/bootstrap-3.3.7.js"></script>
<link rel="stylesheet" href="fancybox/jquery.fancybox.css" type="text/css" media="screen" />
<!-- Подключение JS файла Fancybox -->
<script type="text/javascript" src="fancybox/jquery.fancybox.pack.js"></script>

<script type="text/javascript"> 
nm=0;
	mm=0;
$(function() {
	   $("a.fancyimage").fancybox(); 
  $('.d1').click(function(){mm++;
	fm=$(this).prop('name');
	 $('#form'+fm).first().prepend('<div class="form-group se"><label for="inputEmail">Скриншот результата '+mm+' </label><div class="input-group"><input type="file" name="fscreen[]"  class="form-control" accept="image/jpeg,image/png"></div></div>');
	 	 $('.sendd#'+fm).show();	 
	  
  });
	 $('.d2').click(function(){mm++;
	fm=$(this).prop('name');
	 $('#form'+fm).first().prepend('<div class="form-group se "><label for="inputEmail">файл результата '+mm+'</label><div class="input-group"><input type="file" name="ffile[]"  class="form-control"></div></div>');
	 $('.sendd#'+fm).show();	 
	  
  });
	
		 $('.d3').click(function(){mm++;
	fm=$(this).prop('name');
	 $('#form'+fm).first().prepend('<div class="form-group se"><label for="inputEmail">Текст результата '+mm+'</label><textarea name="ftextr[]"  class="form-control" rows="3" id="tar"></textarea></div>');
	 	 $('.sendd#'+fm).show();	 
	  
  });

	$('.prs').click(function(){
	 window.open(this.value, '_blank').focus();
   
});	

	$('body').on('click', '.dell', function(e){
	// $('.dell').click(function(){
	fm=$(this).prop('name');
		console.log(fm);
	 $('#thm'+fm).remove();
	 $.get('del_user_pr_f.php', { del: fm}, function(data) {
		//	console.log(data);
			
		});

	  
  });	
	
$('.sendd').click(function(){

	nm=this.id;
	sendAjaxForm('otvet'+nm,'form'+nm,'pract_obr.php');
$('.se').remove();
//	$("#prsm"+nm).first().prop('href',dat1.ffile);
$('.sendd').hide();	
});
	
function sendAjaxForm(result_form, ajax_form, url1) {
   var form =($('#'+ajax_form)[0]);
	var formData = new FormData(form);
	
	var request = new XMLHttpRequest();
function reqReadyStateChange() {
	if (request.readyState == 4 && request.status == 200){
	
    if (request.responseText=="-|-") alert("файл слишком большой, предельный размер 1 мб") ; else
	{
		$('#'+result_form).append(request.responseText);
	//	console.log(request.responseText);
	//	data1=JSON.parse(request.responseText);
	
	//    document.getElementById(result_form).innerHTML=request.responseText;//sdata1.ffile;
	//	$("#prsm"+nm).first().prop('href',data1.ffile);
	//	console.log($("#prsm"+nm));
	}
		
	}
}

request.open("POST", url1);
request.onreadystatechange = reqReadyStateChange;
request.send(formData);


}
});	
</script>

</body>
</html>
<?php
 /* fixed MMiC */ mysqli_free_result($test);
?>
