<?php require_once('Connections/testmed.php'); 


if (!isset($_SESSION)) {
  session_start();
}
?>
<?php
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

$MM_restrictGoTo = "loginpr.php";
if (!((isset($_SESSION['MM_Username2020'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username2020'], $_SESSION['MM_UserGroup2020'])))) {   
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

$specc=0;
function getsokr($slovo){
$res="";	
$pieces = explode(" ", $slovo);
foreach($pieces as $val){
$res=$res.mb_substr($val,0,4)." ";	
}	
return $res;	
}



if ((isset($_POST["del"])) ) {
	$del=intval($_POST["num"]);
$sql="update `tm_nmo_pract` set old=user  where  id=".$del;	
DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
		echo $sql;
$sql="update `tm_nmo_pract` set user=-10  where  id=".$del;	
DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
	echo $sql;
	
	exit(0);	
	
}

if ((isset($_POST["get"])) && ($_POST["get"] == "1")) {

	$id=intval($_POST['id']);
$media=intval($_POST['media']);
$sql="SELECT 
  `tm_typsv_konf_user`.`num`,
  `tm_typsv_konf_user`.`user`,
  `tm_typsv_konf_user`.`ank`,
  `tm_typsv_konf_user`.`value`,
  `tm_typsv_konf_user`.`razdel`,
  `tm_typsv_konf`.`nazv`,
  `tm_typsv_konf`.`typ`
FROM
  `tm_typsv_konf_user`
  INNER JOIN `tm_typsv_konf` ON (`tm_typsv_konf_user`.`ank` = `tm_typsv_konf`.`num`)
WHERE
  `tm_typsv_konf_user`.`user` = $id AND 
  `tm_typsv_konf_user`.`razdel` = $media";


	//echo $sql;
	$spec01 =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_spec01 =  /* fixed MMiC */ mysqli_fetch_assoc($spec01);
$totalRows_spec01 =  /* fixed MMiC */ mysqli_num_rows($spec01);
	$prepod=-1;
	
?>
<table class="table table-striped table-bordered" id="pepe">
  <thead>
    <tr>
     <th>тип</th>
      <th>Значение</th>
      <th></th>
      <th></th>
    </tr>
  </thead>
  <tbody id="tml">
	  
	  

<div class="panel-group" id="accordion">
  <!-- 1 панель -->
	  <?php do { ?>
	    <tr>
<td><?php echo $row_spec01['nazv']; ?></td>
      <td><?php echo $row_spec01['value']; ?></td>
      <td style="font-size:small"><?php echo $row_spec01['chto_del']; ?></td>
      <td><?php echo $row_spec01['otvets']; ?></td>
	  </tr>
 

         
        <?php } while ($row_spec01 =  /* fixed MMiC */ mysqli_fetch_assoc($spec01)); ?>

	    </tbody>
</table>
<?	
	
	exit;
}

if ((isset($_POST["fio"])) && ($_POST["fio"] == "1")) {
$sql="SELECT DISTINCT 
  `tm_user`.`num`,
  `tm_user`.`fio`, `tm_grupp`.`nazv` as grupp,
  `tm_grupp`.`nazv`,`tm_nmo_razd_media`.`id`,`tm_nmo_razd`.`id` as rid
FROM
  `tm_nmo_razd`
  INNER JOIN `tm_user` ON (`tm_nmo_razd`.`spec` = `tm_user`.`spec`)
  LEFT OUTER JOIN `tm_grupp` ON (`tm_user`.`grupp` = `tm_grupp`.`id`)
  INNER JOIN `tm_nmo_razd_media` ON (`tm_nmo_razd`.`id` = `tm_nmo_razd_media`.`tm_nmo_razd`)
WHERE `tm_user`.`act`=1 and 
  `tm_nmo_razd_media`.`id` =  ".intval($_POST['id'])." order by grupp,fio";
	///echo $sql;
	$spec01 =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_spec01 =  /* fixed MMiC */ mysqli_fetch_assoc($spec01);
$totalRows_spec01 =  /* fixed MMiC */ mysqli_num_rows($spec01);
	
?>
<div class="panel-group" id="accordion">
  <!-- 1 панель -->
	  <?php do { ?>
  <div class="panel panel-default fuser" id="pan<?php echo $row_spec01['num']; ?>" data-id="grp<?php echo $row_spec01['grupp']; ?>">
    <!-- Заголовок 1 панели -->
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $row_spec01['num']; ?>"><?php echo $row_spec01['fio']; ?></a><span class="badge pull-right">  <?php echo $row_spec01['grupp']; ?></span>
      </h4>
    </div>
    <div id="collapse<?php echo $row_spec01['num']; ?>" class="panel-collapse collapse bfd" data-id="<?php echo $row_spec01['num']; ?>" data-media="<?php echo $row_spec01['rid']; ?>">
      <!-- Содержимое 1 панели -->
		<button class="btn btn-info pull-right pch btn-md" data-id="<?php echo $row_spec01['num']; ?>"><i class="glyphicon glyphicon-print"></i></button>
      <div class="panel-body" id="fss<?php echo $row_spec01['num']; ?>">
       
      </div>
    </div>
  </div>

         
        <?php } while ($row_spec01 =  /* fixed MMiC */ mysqli_fetch_assoc($spec01)); ?>

</div>

<?	
	
	exit;
}



if (isset($_GET['spec']))$specc=intval($_GET['spec']);
 /* fixed MMiC */ mysqli_select_db(DB::$link, $database_testmed);
$query_spec = "SELECT 
  `tm_spec`.`num`,
  `tm_spec`.`nazv`,
  `tm_spec`.`dat`
FROM
  `tm_spec`
WHERE
  `tm_spec`.`num`=$specc";

$spec =  /* fixed MMiC */ DB::Query($query_spec, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_spec =  /* fixed MMiC */ mysqli_fetch_assoc($spec);
$totalRows_spec =  /* fixed MMiC */ mysqli_num_rows($spec);
$query_spec = "SELECT 
  `tm_spec`.`nazv`,`tm_spec`.`num`
FROM
  `tm_nmo_razd_media`
  INNER JOIN `tm_nmo_razd` ON (`tm_nmo_razd_media`.`tm_nmo_razd` = `tm_nmo_razd`.`id`)
  INNER JOIN `tm_spec` ON (`tm_nmo_razd`.`id` = `tm_spec`.`num`)
WHERE
  `tm_nmo_razd_media`.`tip` = 6 and 
  `tm_spec`.`kr` > 0";

$spec01 =  /* fixed MMiC */ DB::Query($query_spec, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_spec01 =  /* fixed MMiC */ mysqli_fetch_assoc($spec01);
$totalRows_spec01 =  /* fixed MMiC */ mysqli_num_rows($spec01);

$sql="
SELECT 
  `tm_nmo_razd_media`.`nazv` as mnazv,`tm_nmo_razd_media`.`id` as pid,
  `tm_nmo_razd`.`nazv` ,`tm_nmo_razd`.`id`
FROM
  `tm_nmo_razd_media`
  INNER JOIN `tm_nmo_razd` ON (`tm_nmo_razd_media`.`tm_nmo_razd` = `tm_nmo_razd`.`id`)
WHERE
  `tm_nmo_razd_media`.`tip` = 6 AND 
  `tm_nmo_razd`.`spec` = $specc AND (
  `tm_nmo_razd`.`prepod` = ".$_SESSION['MM_Username2020']."
  or  ".$_SESSION['MM_Username2020']." in (SELECT 
  `tm_nmo_razd_dop_prepod`.`prepod`
FROM
  `tm_nmo_razd_dop_prepod`
WHERE
  `tm_nmo_razd_dop_prepod`.`razdel` = `tm_nmo_razd`.`id`))
  ";
//echo $sql;
$spec02 =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_spec02 =  /* fixed MMiC */ mysqli_fetch_assoc($spec02);
$totalRows_spec02 =  /* fixed MMiC */ mysqli_num_rows($spec02);
$sql="SELECT DISTINCT 
  `tm_nmo_razd_media`.`id`,
  `tm_nmo_razd_media`.`nazv`
FROM
  `tm_nmo_razd_media`
WHERE
  `tm_nmo_razd_media`.`tip` = 16 AND 
  `tm_nmo_razd_media`.`tm_nmo_razd` =$specc";
$spec03 =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_spec03 =  /* fixed MMiC */ mysqli_fetch_assoc($spec03);
$totalRows_spec03 =  /* fixed MMiC */ mysqli_num_rows($spec03);


$sql="SELECT 
  `tm_grupp`.`id`,
  `tm_grupp`.`nazv`
FROM
  `tm_grupp`";
  $grp =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_grp =  /* fixed MMiC */ mysqli_fetch_assoc($grp);
$totalRows_grp =  /* fixed MMiC */ mysqli_num_rows($grp);
  
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Архив работ</title>

<!-- Bootstrap -->
<link rel="stylesheet" href="css/bootstrap.css">

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
<style>
/* CSS */
.btn-circle {
    width: 38px;
    height: 38px;
    border-radius: 19px;
    text-align: center;
    padding-left: 0;
    padding-right: 0;
    font-size: 16px;
}
</style>    
  


</head>
<body>
<?php include("header.php");?>

<hr>
<div class="container">
  <div class="row text-center">
     <?php include("kb.php");?>

	</div>  
	</div>  
<hr>
 <div class="container">
  <div class="row text-center">
	
        
<div class="btn-group pull-left">
    <button id="btt" type="button" class="btn btn-success btn-lg dropdown-toggle btn-block"  data-toggle="dropdown" style="min-width: 200px">
       <?php if (isset($_GET['spec'])) { echo $row_spec['nazv']; } else { ?> Специальности <?php }?>  <span class="caret"></span>
    </button>
      
        
         <ul class="dropdown-menu" role="menu">
    
      <?php do { ?>
          <li><a href="?spec=<?php echo $row_spec01['num']; ?>"><?php echo $row_spec01['nazv']; ?></a></li>
        <?php } while ($row_spec01 =  /* fixed MMiC */ mysqli_fetch_assoc($spec01)); ?>
        
    </ul>
</div>
	</div>  
	</div>  
<div class="container">
 <div class="row ">
	  <br>
	  	<div class="panel panel-default">
  <div class="panel-body">
<div class="btn-group pull-left">
	<div><select style="max-width: 200px" id="srazd">
		 
      <?php do { ?>
         <option value="<?php echo $row_spec02['pid']; ?>"><?php echo getsokr($row_spec02['nazv']); ?>[<?php echo $row_spec02['mnazv']; ?>]</option>
        <?php } while ($row_spec02 =  /* fixed MMiC */ mysqli_fetch_assoc($spec02)); ?>
        
		
		
		</select>
<button  id="razd">выбрать</button>
		  
		 <div class="pull-right" >
		 	 	<select id="fild2<?php echo $row_spec['id']; ?>">
		  <option value="-1">все</option>
			  	   <?php do { ?>
			  	   	  <option ><?php echo $row_grp['nazv']; ?></option>
			           <?php } while ($row_grp =  /* fixed MMiC */ mysqli_fetch_assoc($grp)); mysqli_data_seek($grp,0);?>
		 	
		 	</select>
		 	
		 
		<button class="fdgrp" data-id="<?php echo $row_spec['id']; ?>" >фильтр</button></div>
		  
	</div>
  <div id="todo"></div>
</div>
			
			
			</div>
</div>
	 </div>
</div>
	  	 <br>

<hr>

<hr>
<h2 class="text-center">&nbsp;</h2>
<footer class="text-center">

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) --> 
<script src="js/jquery-1.11.3.min.js"></script> 
<!-- Include all compiled plugins (below), or include individual files as needed --> 
<script src="js/bootstrap.js"></script>
	   <script src="printThis.js"></script>
  <script type="text/javascript">
	$(function() {
		
		$("#ct").submit(function(event) {
		event.preventDefault();
		$.ajax({	
			url: 'kurator.php',
			data: $("#ct").serialize(),
			type: 'POST',
			success: function(data){
			//	console.log(data);
				$("#stp").html(data);
				
				
			}
		});
	});

	
 $('body').on('show.bs.collapse', '.bfd', function(){
   id=$(this).data('id');
	 media=$(this).data('media');
	console.log(id,media);
	$.post( "kurator_ank_dan.php",{'get':1,'id':id,'media':media}, function(data) {
$('#fss'+id).html(data);

 // alert( "success" );
}); 
	 
  });

		
$('body').on('click', '.pch',function(){
			$(this).hide();
	n=$(this);
			id=$(this).data('id');
			$('#pan'+id).printThis({afterPrint:function(){}});
			$(n).show();
	});	
	
					
		
		
		
			
		fl=0;
		
		


$('#razd').on('click',function(){
id=$('#srazd').val();
$.post( "kurator_ank_dan.php",{'fio':1,'id':id}, function(data) {
$('#todo').html(data);

 // alert( "success" );
});
	
			});


		
$('.fdgrp').on('click',function(){
			
			id=$(this).data('id');
			deff=$('#fild2'+id).val();;
	if (deff==-1)$('.fuser').show(); else
	$('.fuser').each(function(idx){//=idx;
		
						if ($(this).data('id')=='grp'+deff) {$(this).show();
									    console.log($(this).data('id'),deff);
									   } else $(this).hide();;		   
								   
								  console.log(deff);
								  
								  });	
		});
	
 $('body').on('click','.sho', function() {
        th = $(this);
        num = $(this).data('id');
        d = $(this).data('d');
      
            $.post("kurator_ank_dan.php", {
                'del': 1,
                'num': num
            }, function(data) {
                th.parent().parent().hide();
                // alert( "success" );
            });
		});
		
		
		
});
	
	
	</script>
</body>
</html>
<?php
 /* fixed MMiC */ mysqli_free_result($spec);
?>