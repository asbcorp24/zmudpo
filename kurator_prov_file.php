<?php require_once('Connections/testmed.php'); 

if (!isset($_SESSION)) {
  session_start();
}
?>
<?php
if (!isset($_SESSION)) {
  session_start();
}

/*DB::Query("set profiling_history_size=100", $testmed);
DB::Query("set profiling=1", $testmed);*/
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
$user=$_SESSION['MM_Username2020'];
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

 /* fixed MMiC */ mysqli_select_db(DB::$link, $database_testmed);

if ((isset($_POST["get"])) && ($_POST["get"] == "1")) {

	$id=intval($_POST['id']);
$media=intval($_POST['media']);
		
if (!isset($row_media['num']))$row_media['num']=-1;	
			  
$sql="	SELECT 
  `tm_konf_user_files`.`num`,
  `tm_konf_user_files`.`user`,
  `tm_konf_user_files`.`media`,
  `tm_konf_user_files`.`path`,
  `tm_konf_user_files`.`name`,
  `tm_konf_user_files`.`yname`,
  `tm_nmo_razd_media`.`nazv`
FROM
  `tm_konf_user_files`
  INNER JOIN `tm_nmo_razd_media` ON (`tm_konf_user_files`.`media` = `tm_nmo_razd_media`.`id`)
WHERE
  `tm_nmo_razd_media`.`tm_nmo_razd` =  $media AND 
  `tm_konf_user_files`.`user` = $id"; 
//echo $sql;	
$uf =  DB::Query($sql, $testmed) or die( mysqli_error(DB::$link));
$row_uf =  mysqli_fetch_assoc($uf);
$totalRows_uf =  mysqli_num_rows($uf);	  
		  if ($totalRows_uf>0){
		 ?>
	 <div class="table-responsive"> 
<table class="table table-bordered table-striped" id="pepe">
	  	     <tbody id="tb<?php echo $row_uf['num']; ?>">
	  	       <tr>
				 <th></th>
			    <th width="40px">слк</th>
				    <th>ссылка</th>
	  	         <th>коммент</th>
				   <th>файл</th>
	  	      
				    
  	           </tr>
				   <?php do {
				 
				 ?>
<tr>

			    <td align="center" class="npc"></td>
				    <td align="center" class="npc"><a  href="<?php echo $row_uf['path']; ?>"    class="btn btn-info btn-xs " target="blank_" ><i class="glyphicon glyphicon-eye-open"></i></a>
				  
	
	</td>
	  <td ><?php echo $row_uf['path']; ?></td>
				    <td ><?php echo $row_uf['nazv']; ?></td>
		   <td><?php echo $row_uf['yname']; ?></td>
	  	 
			
	
		
	
	
	
  	          </tr> 
	  	   
				 
        <?php } while ($row_uf =   mysqli_fetch_assoc($uf)); ?>
  </tbody>
</table>
	  </div>  	  
	<?php }



exit();



}


function getExtension1($filename) {
    return end(explode(".", $filename));
  }
function rotate_img($src, $dest, $degrees){
if (!file_exists($src)) return false;
$size_img = getimagesize($src);
$format = strtolower(substr($size_img['mime'], strpos($size_img['mime'], '/')+1));
$icfunc = "imagecreatefrom" . $format;
if (!function_exists($icfunc)) return false;
$image = $icfunc($src);
$rotate = imagerotate($image, $degrees, 0);
	
imagejpeg($rotate, $dest, 95);
}







$query_spec = "SELECT 
  `tm_nmo_razd`.`id`,
  `tm_nmo_razd`.`nazv`,
  `tm_spec`.`nazv` AS `spnazv`
FROM
  `tm_nmo_razd`
  INNER JOIN `tm_spec` ON (`tm_nmo_razd`.`spec` = `tm_spec`.`num`)
   INNER JOIN `tm_nmo_razd_media` ON (`tm_nmo_razd`.`id` = `tm_nmo_razd_media`.`tm_nmo_razd`)
WHERE
  `tm_nmo_razd_media`.`tip` = 7 and
  `tm_nmo_razd`.`prepod` = $user
   union
  SELECT 
  `tm_nmo_razd`.`id`,
  `tm_nmo_razd`.`nazv`,
  `tm_spec`.`nazv`  AS `spnazv`
FROM
  `tm_nmo_razd_dop_prepod`
  INNER JOIN `tm_nmo_razd` ON (`tm_nmo_razd_dop_prepod`.`razdel` = `tm_nmo_razd`.`id`)
  INNER JOIN `tm_spec` ON (`tm_nmo_razd`.`spec` = `tm_spec`.`num`)
   INNER JOIN `tm_nmo_razd_media` ON (`tm_nmo_razd`.`id` = `tm_nmo_razd_media`.`tm_nmo_razd`)
WHERE
  `tm_nmo_razd_dop_prepod`.`prepod` = $user AND 
  `tm_nmo_razd_media`.`tip` = 7
  ";
//echo $query_spec;
$spec =  /* fixed MMiC */ DB::Query($query_spec, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_spec =  /* fixed MMiC */ mysqli_fetch_assoc($spec);
$totalRows_spec =  /* fixed MMiC */ mysqli_num_rows($spec);


//echo $query_spec;

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
<title>Работа куратором</title>

<!-- Bootstrap -->
<link rel="stylesheet" href="css/bootstrap.css">
	<link rel="stylesheet" href="css/ekko-lightbox.css">

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
  <style> 
        input.largerCheckbox { 
            width: 20px; 
            height: 20px; 
        } 
    </style>  


</head>
<body>
<?php include("header.php");?>

<hr>
	
	<form id="form_00" enctype="multipart/form-data" style="display: none">
			<h3> выберите файл с расширением doc,docx,pdf,jpg,pptx,ppt</h3>
		<div class="progress" id="prog_0" style="display: none">
    <div class="progress-bar progress-bar-striped progress-bar-warning" role="progressbar" style="width: 0% " aria-valuenow="14" aria-valuemin="0" aria-valuemax="100" id="pb"></div>
</div>
<div id="drop-area">
 </div>
		<div id='bgd_0'>
	
			
  <input type="file" title="Click to add Files" class="form-control" name="fileuser" accept="application/pdf,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.openxmlformats-officedocument.presentationml.presentation,image/jpeg;application/vnd.ms-powerpoint,application/msword;image/png" >
	<br>
		<input type="hidden" value="0_0" name="mediarazd" id="mediarazd">
	<button class="btn btn-info form-control send"  type="button" >Отправить</button>
		</div>
</form>
	
	
	
	
	
 <div class="container">
  <div class="row text-center">
	
     <?php include("kb.php");?>  

	</div>  
	</div>  
<div class="container">
 <div class="row ">
	  <br>
	  	<div class="panel-group" id="accordion">
    <?php do { ?>
  
    
  <!-- 2 панель -->
  <div class="panel panel-default">
    <!-- Заголовок 2 панели -->
    <div class="panel-heading">
      <h4 class="panel-title">
     
		 
			     <a data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $row_spec['id']; ?>">[<?php echo $row_spec['spnazv']; ?>] <?php echo  mb_substr($row_spec['nazv'],0,30); ?>...</a>
		  <?php 
		
		 
			  
			  

		{  ?>
		 <div class="pull-right btn-xs" >
		 	 	<select id="fild2<?php echo $row_spec['id']; ?>">
		  <option value="-1">все</option>
			  	   <?php do { ?>
			  	   	  <option ><?php echo $row_grp['nazv']; ?></option>
			           <?php } while ($row_grp =  /* fixed MMiC */ mysqli_fetch_assoc($grp)); mysqli_data_seek($grp,0);?>
		 	
		 	</select>
		 	
		 
		<button class="fdgrp" data-id="<?php echo $row_spec['id']; ?>" >фильтр гр</button></div>
		  <?php } 
		{  ?>
		 <div class="pull-right btn-xs" >
			 <input type="date" id="fild<?php echo $row_spec['id']; ?>">
			 <button class="fd" data-id="<?php echo $row_spec['id']; ?>" id="fd<?php echo $row_spec['id']; ?>" >фильтр</button></div>
		  <?php } ?>
      </h4>
    </div>
    <div id="collapse<?php echo $row_spec['id']; ?>" class="panel-collapse collapse fio">
      <!-- Содержимое 2 панели -->
      <div class="panel-body">
	
		  <?php 
			 if (!isset($row_spec['id']))$row_spec['id']=-1; 
			  
			  $sql="SELECT DISTINCT 
  `tm_user`.`num`,
  `tm_user`.`fio`,
  `tm_grupp`.`nazv` AS `value`
FROM
  `tm_user`
  LEFT OUTER JOIN `tm_grupp` ON (`tm_user`.`grupp` = `tm_grupp`.`id`)
  INNER JOIN `tm_konf_user_files` ON (`tm_user`.`num` = `tm_konf_user_files`.`user`)
  INNER JOIN `tm_nmo_razd_media` ON (`tm_konf_user_files`.`media` = `tm_nmo_razd_media`.`id`)
WHERE
  `tm_nmo_razd_media`.`tm_nmo_razd` = ".$row_spec['id']."
 group by num ORDER BY
  `fio` "; 
			 
		  $media =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_media =  /* fixed MMiC */ mysqli_fetch_assoc($media);
$totalRows_media =  /* fixed MMiC */ mysqli_num_rows($media);
//	echo $totalRows_media;	  
		  ?>
		<div class="panel-group" id="accordion<?php echo $row_media['value']; ?>">
  <!-- 1 панель -->
				   <?php do {
				
				 ?>
  <div class="panel panel-default fuser" data-id="grp<?php echo $row_media['value']; ?>">
    <!-- Заголовок 1 панели -->
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion2<?php echo $row_media['value']; ?>" href="#collapsem2<?php echo $row_media['num']; ?><?php echo $row_spec['id']; ?>"><?php echo $row_media['fio']; ?></a><span class="badge pull-right">  <?php echo $row_media['value']; ?></span>
      </h4>
    </div>
    <div id="collapsem2<?php echo $row_media['num']; ?><?php echo $row_spec['id']; ?>" class="panel-collapse collapse bfd" data-id="<?php echo $row_media['num']; ?>" data-media="<?php echo $row_spec['id']; ?>">
      <!-- Содержимое 1 панели -->
      <div class="panel-body" id="fss<?php echo $row_media['num']; ?>_<?php echo $row_spec['id']; ?>">
		 
<?php
	?>	  
		  
		  
		  
      </div>
    </div>
  </div>
			
			<?php } while ($row_media =  /* fixed MMiC */ mysqli_fetch_assoc($media)); ?>
  <!-- 2 панель -->
  
</div>  
		  
		  
		  
		  
		  
		  
      </div>
    </div>
  </div>
			    <?php } while ($row_spec =  /* fixed MMiC */ mysqli_fetch_assoc($spec)); ?>
  <!-- 3 панель -->

</div> 
	 </div>
</div>
	  	 <br>

<hr>
<?php 
/*echo "-------------->";
$rs = DB::Query("show profiles",$testmed);
while($rd = mysqli_fetch_object($rs))
{
    echo $rd->Query_ID.' - '.round($rd->Duration,4) * 1000 .' ms - '.$rd->Query.'<br />';
}
*/
?>
<hr>
<h2 class="text-center">&nbsp;</h2>
<footer class="text-center">

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) --> 
<script src="js/jquery-1.11.3.min.js"></script> 
<!-- Include all compiled plugins (below), or include individual files as needed --> 
<script src="js/bootstrap.js"></script>
	   <script src="printThis.js"></script>
	<script src="js/ekko-lightbox.min.js"></script>
  <script type="text/javascript">
	$(function() {

$(document).on('click', '[data-toggle="lightbox"]', function(event) {
    event.preventDefault();
    $(this).ekkoLightbox();
});		
		
		
function sendAjaxForm2(result_form, ajax_form, url1,fpr) {

   var form =($('#'+ajax_form)[0]);
	var formData = new FormData(form);
	//console.log(form);
	var request = new XMLHttpRequest();
	request.upload.onprogress = function(event) {
		pr=event.loaded/ event.total*100;
	$('#pb'+fpr).css('width',pr+"%");
		console.log($('#pb'+fpr));
		
  console.log(event.loaded + ' / ' + event.total);
  }
function reqReadyStateChange() {
	if (request.readyState == 4 && request.status == 200){
  		//console.log('dssdsd');
		$(form).parent().parent().html(request.responseText);
		//console.log(request.responseText);
			//location.href = "nmo.php?row="
	}
}

request.open("POST", url1);
request.onreadystatechange = reqReadyStateChange;
request.send(formData);
}	

		

function getRandomInt(max) {
  return Math.floor(Math.random() * Math.floor(max));
}		
		
		
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
		mr=0;
		
$('.ccb').on('click',function(){
	num=$(this).data('num')
				name=$(this).data('name');
	if ($(this).prop('checked'))val=1; else val=0;
				//$(this).parent().children().first().val();
			
		
			
	
			$.post('kurator_add.php', {'upd':'1', 'num' :num,'name':name,'val':val},
		function(data) {
			
	
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


			
		$('.fd').on('click',function(){
			
			id=$(this).data('id');
			deff=$('#fild'+id).val();;
			console.log(deff);
	if (deff=="")$('.dtr').show(); else
	$('.dtr').each(function(idx){//=idx;
		
						if ($(this).data('id')==deff) {$(this).show();
									    console.log($(this).data('id'),deff);
									   } else $(this).hide();;		   
								   
								  console.log(deff);
								  
								  });	
		});	
	
$('body').on('click','.sho',function(){
	th=$(this);
num=$(this).data('id')	;


	$.post( "kurator_prov.php",{'del':1,'num':num}, function(data) {
th.parent().parent().hide();
 // alert( "success" );
});
	});	
$('body').on('click','.shop',function(){
	th=$(this);
num=$(this).data('id')	;


	$.post( "kurator_prov.php",{'pov':1,'num':num}, function(data) {

	$('.az').each(function(i,elem) {
	tmp=$(elem).prop('href');
		console.log(tmp);
		tmp=tmp+'&ron='+getRandomInt(100);
		$(elem).prop('href',tmp);
		console.log(tmp);
		
});
 // alert( "success" );
});	

	
			});


		
 $('body').on('show.bs.collapse', '.bfd', function(){
   id=$(this).data('id');
	 media=$(this).data('media');
	console.log(id,media);
	$.post( "kurator_prov_file.php",{'get':1,'id':id,'media':media}, function(data) {
$('#fss'+id+"_"+media).html(data);
$( "#fd"+media).trigger( "click" );
 // alert( "success" );
}); 
	 
  });
		
		
		
});
//	/(".hello").clone().appendTo(".container");
	
	</script>
</body>
</html>
<?php
 /* fixed MMiC */ mysqli_free_result($spec);
?>