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

 /* fixed MMiC */ mysqli_select_db(DB::$link, $database_testmed);



if ((isset($_GET["dat"])) && ($_GET["dat"] == 1)) {
	$num=(int)$_GET["num"];
$sql="SELECT 
  `tm_login_dat`.`num`,
  `tm_login_dat`.`user`,
  `tm_login_dat`.`dat`,
  `tm_login_dat`.`dop`
FROM
  `tm_login_dat`
WHERE
  `tm_login_dat`.`user` = $num";	
	//echo $sql;
$spec =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_spec =  /* fixed MMiC */ mysqli_fetch_assoc($spec);

do{	echo '<span class="badge pull-right"> ['.$row_spec['dat'].']</span>';
    
		  } while ($row_spec =  /* fixed MMiC */ mysqli_fetch_assoc($spec));
	
	exit();
}


$specc=0;
if (isset($_GET['spec']))$specc=intval($_GET['spec']);

if (isset($_POST['media']))
{
	$media=intval($_POST['media']);
$sql="SELECT DISTINCT 
  `tm_nmo_razd_media`.`id` AS `media_id`,
  `tm_nmo_razd_media`.`nazv`
FROM
  `tm_nmo_razd_media`
WHERE
  `tm_nmo_razd_media`.`tm_nmo_razd` = $media AND 
  ((`tm_nmo_razd_media`.`tip` = 3) or (`tm_nmo_razd_media`.`tip` = 15))";

$media =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_media =  /* fixed MMiC */ mysqli_fetch_assoc($media);
$totalRows_media =  /* fixed MMiC */ mysqli_num_rows($media);
//data-id="grp echo $row_media['value']; "	
echo '<table width="200" border="1"  class="table table-bordered table-striped">';
 echo ' <tbody>';
echo '    <tr>';
echo '      <th scope="col">&nbsp;</th>';
echo '    </tr>';
	   do { 
		   echo '    <tr>';
		    echo '<td>';
		   echo "<h3>".$row_media['nazv']."</h3> ";echo '<button type="button" class="btn btn-primary btn-xs pull-right rpc2" id=""><i class="glyphicon glyphicon-print"></i></button>';
	$sql="SELECT DISTINCT 
  `tm_user`.`fio`,
  `tm_user`.`num`,
  `tm_nmo_razd_user`.`id` AS `utnum`,
  `tm_nmo_razd_user`.`psp`,
  `tm_nmo_razd_user`.`sp`,
  `tm_nmo_razd_user`.`dop`,
  `tm_nmo_razd_user`.`dat`,
  `tm_nmo_razd_user`.`dop_file`,
  `tm_nmo_razd_user`.`proydeno`,
  `tm_nmo_razd_user`.`razdel`,
  `tm_grupp`.`nazv` as value
FROM
  `tm_user`
  INNER JOIN `tm_nmo_razd_user` ON (`tm_user`.`num` = `tm_nmo_razd_user`.`user`)
  LEFT OUTER JOIN `tm_grupp` ON (`tm_user`.`grupp` = `tm_grupp`.`id`)
WHERE
  `tm_nmo_razd_user`.`razdel` = ".$row_media['media_id'];;	   
  // echo $sql;
		$rmedia =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$rrow_media =  /* fixed MMiC */ mysqli_fetch_assoc($rmedia);
$rtotalRows_media =  /* fixed MMiC */ mysqli_num_rows($rmedia);   

	echo '	   <table width="200" border="1" class="table table-bordered table-striped">';
 echo ' <tbody>';
 echo '   <tr >';
  echo ' 		     <th class="">грп</th>';
 echo ' 		     <th class="npc"></th>';
 echo '     <th scope="col">&nbsp;</th>';
 echo '     <th scope="col">&nbsp;</th>';
  echo '     <th scope="col">&nbsp;</th>';
echo '    </tr>';
		    do { 
		    	$d=strtotime($rrow_media['dat']);
echo '    <tr id="ttr'.$rrow_media['utnum'].'"  data-id="grp'.$rrow_media['value'].'" class="fuser dtr"  data-ide="'.date("Y-m-d", $d).'">';
echo '      <td>'.$rrow_media['value'].'</td>';
	echo ' 			<td align="center" class="npc"><button data-razdel="'.$rrow_media['razdel'].'" class="resa" data-user="'.$rrow_media['num'].'" data-id="'.$rrow_media['utnum'].'"><i class="glyphicon glyphicon-th"></i></button></td>';
echo '      <td>'.$rrow_media['fio'].'</td>';
echo '      <td>'.$rrow_media['proydeno']*$rrow_media['psp']/$rrow_media['sp'].'</td>';
echo '      <span style="display:none">'.$rrow_media['proydeno'].":".$rrow_media['psp'].":".$rrow_media['sp'].'</span>';
echo '      <td>'.$rrow_media['dat'].'</td>';
echo '    </tr>';
if (!isset($rrow_media['num']))$rrow_media['num']=0;
	$sql="SELECT DISTINCT 
  `tm_user`.`fio`,
  `tm_user`.`num`,
  `tm_nmo_razd_user_arh`.`id` AS `utnum`,
  `tm_nmo_razd_user_arh`.`psp`,
  `tm_nmo_razd_user_arh`.`sp`,
  `tm_nmo_razd_user_arh`.`dop`,
  `tm_nmo_razd_user_arh`.`dat`,
  `tm_nmo_razd_user_arh`.`dop_file`,
  `tm_nmo_razd_user_arh`.`proydeno`,
  `tm_nmo_razd_user_arh`.`razdel`
FROM
  `tm_user`
  INNER JOIN `tm_nmo_razd_user_arh` ON (`tm_user`.`num` = `tm_nmo_razd_user_arh`.`user`)
WHERE
 `tm_user`.`num`=".$rrow_media['num']." and `tm_nmo_razd_user_arh`.`dat`<>'".$rrow_media['dat']."' and 
  `tm_nmo_razd_user_arh`.`razdel` = ".$row_media['media_id']." AND 
  `tm_user`.`spec` = ".(int)$_POST['spec'];	
  
//	echo $sql;	   
		$rmedia1 =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$rrow_media1 =  /* fixed MMiC */ mysqli_fetch_assoc($rmedia1);
$rtotalRows_media1 =  /* fixed MMiC */ mysqli_num_rows($rmedia1);   
if ($rtotalRows_media1 >0 ){
 echo '<tr  data-id="grp'.$rrow_media['value'].'" class="fuser dtr"  data-ide="'.date("Y-m-d", $d).'""><td colspan=4>';
do {
	

//	echo $rrow_media1['fio'];
	//	echo $sql;
//echo '      <td>'.$rrow_media['proydeno']*$rrow_media['psp']/$rrow_media['sp'].'</td>';
echo '<span class="badge">'; 
echo $rrow_media1['dat'];echo "-";
echo (round($rrow_media1['proydeno']*$rrow_media1['psp']/$rrow_media1['sp'],2));echo '</span>';

  } while ($rrow_media1 =  /* fixed MMiC */ mysqli_fetch_assoc($rmedia1));}
echo "</td><tr>";
				  } while ($rrow_media =  /* fixed MMiC */ mysqli_fetch_assoc($rmedia));

echo '  </tbody>';
echo '</table>';
		   
		   
		   
		   
		   
		   
		   
		   
		   
		   
		   
		   
		   
		   
		   echo '</td>';
          
		   echo '   </tr>';
        } while ($row_media =  /* fixed MMiC */ mysqli_fetch_assoc($media));


 
 echo '   <tr>';
 echo '     <td>&nbsp;</td>';
 echo '   </tr>';
 echo ' </tbody>';
echo '</table>	';
	exit();
	
}
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
  `tm_spec`.`num`,
  `tm_spec`.`nazv`,
  `tm_spec`.`dat`
FROM
  `tm_spec`
WHERE
  `tm_spec`.`kr` > 0";

$spec01 =  /* fixed MMiC */ DB::Query($query_spec, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_spec01 =  /* fixed MMiC */ mysqli_fetch_assoc($spec01);
$totalRows_spec01 =  /* fixed MMiC */ mysqli_num_rows($spec01);

$query_Recordset1 = sprintf("SELECT 
  `tm_user`.`fio`,
  MAX(`tm_login_dat`.`dat`) AS dat,
  `tm_login_dat`.`dop`,
  `tm_user`.`num`
FROM
  `tm_login_dat`
  RIGHT OUTER JOIN `tm_user` ON (`tm_login_dat`.`user` = `tm_user`.`num`)
WHERE
  `tm_user`.`spec` = $specc
GROUP BY
  `tm_user`.`fio`,
  `tm_login_dat`.`dop`,
  `tm_user`.`num`  ORDER BY fio ASC");
$stud2 =  /* fixed MMiC */ DB::Query($query_Recordset1, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_stud2 =  /* fixed MMiC */ mysqli_fetch_assoc($stud2);
$totalrow_stud2 =  /* fixed MMiC */ mysqli_num_rows($stud2);

//echo $query_Recordset1;

$sql="SELECT DISTINCT 
  `tm_nmo_razd`.`nazv`,
  `tm_nmo_razd`.`id`
FROM
  `tm_nmo_razd`
  INNER JOIN `tm_nmo_razd_media` ON (`tm_nmo_razd`.`id` = `tm_nmo_razd_media`.`tm_nmo_razd`)
WHERE
  ((`tm_nmo_razd_media`.`tip` = 3) or (`tm_nmo_razd_media`.`tip` = 15)) AND 
  `tm_nmo_razd`.`spec` = $specc";
$stud2a =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_stud2a =  /* fixed MMiC */ mysqli_fetch_assoc($stud2a);
$totalrow_stud2a =  /* fixed MMiC */ mysqli_num_rows($stud2a);
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
	  	<div class="panel-group" id="accordion">
  <!-- 1 панель -->
  <div class="panel panel-default">
    <!-- Заголовок 1 панели -->
    <div class="panel-heading ">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">Статистика входов</a><span class=""><button type="button" class="btn btn-primary btn-xs pull-right" id="pch"><i class="glyphicon glyphicon-print"></i></button></span>
      </h4>
		
    </div>
    <div id="collapseOne" class="panel-collapse collapse">
      <!-- Содержимое 1 панели -->
      <div class="panel-body">
        <div class="table-responsive"> 
<table class="table table-bordered table-striped" id="pepe">
	  	     <tbody>
	  	       <tr>
				   <th class="npc"></th>
	  	         <th>ФИО</th>
	  	         <th>последний вход</th>
  	           </tr>
				   <?php do { ?>
  
	  	       <tr>
				    <td align="center" class="npc"><button data-id="<?php echo $row_stud2['num']; ?>" class="sho"><i class="glyphicon glyphicon-th"></i></button></td>
	  	         <td><?php echo $row_stud2['fio']; ?></td>
	  	         <td><?php echo $row_stud2['dat']; ?></a></td>
  	           </tr>
	  	       <tr  style="display: none" id="tr1_<?php echo $row_stud2['num']; ?>">
	  	         <td align="center" class="npc">&nbsp;</td>
	  	         <td colspan="2" id="td1_<?php echo $row_stud2['num']; ?>">&nbsp;</td>
	  	         </tr>
				 
        <?php } while ($row_stud2 =  /* fixed MMiC */ mysqli_fetch_assoc($stud2)); ?>
  </tbody>
</table>
	  </div>
      </div>
    </div>
  </div>
  <!-- 2 панель -->
  <div class="panel panel-default">
    <!-- Заголовок 2 панели -->
    <div class="panel-heading">
      <h4 class="panel-title">
     
		  <form class="form-inline" id="ct">
			     <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">Прох тест </a>
			 
		  <select name="media">
		   <?php do { ?>
  <option value="<?php echo $row_stud2a['id']; ?>"> <?php echo $row_stud2a['nazv']; ?></option>
	  	     
	  	        
				 
        <?php } while ($row_stud2a =  /* fixed MMiC */ mysqli_fetch_assoc($stud2a)); ?>
        
		  </select>
		  <input type="hidden" value="<?php echo $_GET['spec'];?>" name="spec">
		   <button type="submit" class="btn btn-danger btn-xs">Выбрать</button>
		  </form>
      </h4>
      	 <div class="pull-right btn-xs" >
			 <input type="date" id="fild<?php echo $row_spec['id']; ?>">
			 <button class="fd" data-id="<?php echo $row_spec['id']; ?>" id="fd<?php echo $row_spec['id']; ?>" >фильтр дат</button></div>
			 
      	 <div class="pull-right btn-xs" >
		 	 	<select id="fild2122">
		  <option value="-1">все</option>
			  	   <?php do { ?>
			  	   	  <option ><?php echo $row_grp['nazv']; ?></option>
			           <?php } while ($row_grp =  /* fixed MMiC */ mysqli_fetch_assoc($grp)); mysqli_data_seek($grp,0);?>
		 	
		 	</select>
		 	
		 
		<button class="fdgrp" data-id="<?php echo $row_spec['id']; ?>" >фильтр</button></div>
    </div>
    <div id="collapseTwo" class="panel-collapse collapse in">
      <!-- Содержимое 2 панели -->
      <div class="panel-body">
		  <div id="stp"></div>
      </div>
    </div>
  </div>
  <!-- 3 панель -->

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

			$('.fd').on('click',function(){
			
			id=$(this).data('id');
			deff=$('#fild'+id).val();;
		//	console.log(deff);
	if (deff=="")$('.dtr').show(); else
	$('.dtr').each(function(idx){//=idx;
		
						if ($(this).data('ide')==deff) {$(this).show();
						//			    console.log($(this).data('id'),deff);
									   } else $(this).hide();;		   
								   
						//		  console.log(deff);
								  
								  });	
		});	
	
	
	
			$('.fdgrp').on('click',function(){
		
			id=$(this).data('id');
			deff=$('#fild2122').val();;
					  console.log(deff);
	if (deff==-1)$('.fuser').show(); else
	$('.fuser').each(function(idx){//=idx;
		
						if ($(this).data('id')=='grp'+deff) {$(this).show();
									    console.log($(this).data('id'),deff);
									   } else $(this).hide();;		   
								   
								  console.log(deff);
								  
								  });	
		});

	
	
		
		$('#pch').on('click',function(){
		
		$('.npc').hide();
			$('#pepe').printThis({afterPrint:function(){	$('.npc').show();}});
		//	$('.npc').show();
	});	
				$('#pch2').on('click',function(){
		
		
			$('#addra').printThis({afterPrint:function(){}});
		//	$('.npc').show();
	});	
					
		
		
	$('body').on('click', '.rpc',function(){
	ts=	$(this);	
		$(this).hide();
			$(this).parent().printThis({afterPrint:function(){$(ts).show();}});
		//	$('.npc').show();
	});	
		$('body').on('click', '.rpc2',function(){
	ts=	$(this);	
		$(this).hide();
			$(this).parent().parent().printThis({afterPrint:function(){$(ts).show();}});
		//	$('.npc').show();
	});	
					
			
		fl=0;
		
		

$('.sho').on('click',function(){
num=$(this).data('id')	;
$.get( "kurator.php",{'num':num,'dat':1}, function(data) {

$('#tr1_'+num).html("<td colspan=3>"+data+"</td>");
console.log($('#tr1_'+num));
$('#tr1_'+num).show();
 // alert( "success" );
});
	
			});
		
	$('body').on('click', '.resa',function(e){
		e.preventDefault();
		
		razdel=$(this).data('razdel')+'_'+$(this).data('user')+'.xml';
		user=$(this).data('user');
			id=$(this).data('id');
		$.get( "administrator/parsttestnmo.php",{razdel:razdel,user:user}, function(data) {
//console.log(data);
$('#ttr'+id).after("<tr><td colspan=3><button class='rpc'>печать</button><pre style=' font-size: 10pt;'>"+data+"</pre></td></tr>");
});	});	
		
		
});
	
	
	</script>
</body>
</html>
<?php
 /* fixed MMiC */ mysqli_free_result($spec);
?>