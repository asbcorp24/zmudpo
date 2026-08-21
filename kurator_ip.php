<?php require_once('Connections/testmed.php'); 

if (!isset($_SESSION)) {
  session_start();
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
  `tm_nmo_razd_media`.`tip` = 3";
	//echo $sql;
	//echo $sql."------->>>";	 
$media =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_media =  /* fixed MMiC */ mysqli_fetch_assoc($media);
$totalRows_media =  /* fixed MMiC */ mysqli_num_rows($media);
	
echo '<table width="200" border="1"  class="table table-bordered table-striped">';
 echo ' <tbody>';
echo '    <tr>';
echo '      <th scope="col">&nbsp;</th>';
echo '    </tr>';
	   do { 
		   echo '    <tr>';
		    echo '<td>';
		   echo "<h3>".$row_media['nazv']."</h3> ";echo '<button type="button" class="btn btn-primary btn-xs pull-right rpc2" id=""><i class="glyphicon glyphicon-print"></i></button>';
$ord=" fio,`tmo_nmo_test_dat`.`dat`";
	if (isset($_POST['ip'])){ $ord=" ip,fio,`tmo_nmo_test_dat`.`dat`";}
	$sql="SELECT DISTINCT  `tm_nmo_razd_user`.`id`,
  `tm_nmo_razd_user`.`dat`, `tm_nmo_razd_user`.`user`,
  `tm_user`.`fio`,
  `tm_nmo_razd_user`.`proydeno`,
  `tm_nmo_razd_user`.`dop`,
  `tm_nmo_razd_user`.`psp`,
  `tm_nmo_razd_user`.`sp`,
  `tmo_nmo_test_dat`.`dat`,
  `tmo_nmo_test_dat`.`ip`
FROM
  `tm_user`
  INNER JOIN `tm_nmo_razd_user` ON (`tm_user`.`num` = `tm_nmo_razd_user`.`user`)
  INNER JOIN `tmo_nmo_test_dat` ON (`tm_user`.`num` = `tmo_nmo_test_dat`.`user`)
  AND (`tm_nmo_razd_user`.`razdel` = `tmo_nmo_test_dat`.`test`)
WHERE    `tmo_nmo_test_dat`.`ip`<>'' and
  `tm_nmo_razd_user`.`razdel` =".$row_media['media_id']." order by $ord";	   
//echo $sql."------->>>";	   
		$rmedia =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$rrow_media =  /* fixed MMiC */ mysqli_fetch_assoc($rmedia);
$rtotalRows_media =  /* fixed MMiC */ mysqli_num_rows($rmedia);   
	echo '	   <table width="200" border="1" class="table table-bordered table-striped">';
 echo ' <tbody>';
 echo '   <tr >';
 echo ' 		     <th class="npc"></th>';
 echo '     <th scope="col">&nbsp;</th>';
 echo '     <th scope="col">&nbsp;</th>';
echo '    </tr>';
		    do { 
echo '    <tr id="ttr'.$rrow_media['utnum'].'">';
	echo ' 			<td align="center" class="npc">'.$rrow_media['dat'].'</td>';
echo '      <td style="background-color:#'.dechex( $rrow_media['user']).';color:black">'.$rrow_media['fio'].'</td>';
echo '      <td>'.$rrow_media['proydeno']*$rrow_media['psp']/$rrow_media['sp'].'</td>';
echo '      <td style="background-color:rgba('.trim((stristr ( str_ireplace(".", ",", $rrow_media['ip']),',',false)),',').');color:black">'.$rrow_media['ip'].'</td>';
echo '    </tr>';
				  } while ($rrow_media =  /* fixed MMiC */ mysqli_fetch_assoc($rmedia));
echo '    <tr>';
echo '      <td>&nbsp;</td>';
 echo '     <td>&nbsp;</td>';
echo '    </tr>';
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
  `tm_nmo_razd_media`.`tip` = 3 AND 
  `tm_nmo_razd`.`spec` = $specc";
$stud2a =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_stud2a =  /* fixed MMiC */ mysqli_fetch_assoc($stud2a);
$totalrow_stud2a =  /* fixed MMiC */ mysqli_num_rows($stud2a);
//echo $query_spec;
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

  <!-- 2 панель -->
  <div class="panel panel-default" >
    <!-- Заголовок 2 панели -->
    <div class="panel-heading">
      <h4 class="panel-title">
     
		  <form class="form-inline" id="ct">
			   <label for="cbip">по ip</label>
			 	  <input type="checkbox" name="ip" value="1" style="height: 28px; width: 28px" id="cbip" >
		  <select name="media">
		   <?php do { ?>
  <option value="<?php echo $row_stud2a['id']; ?>"> <?php echo $row_stud2a['nazv']; ?></option>
	  	     
	  	        
				 
        <?php } while ($row_stud2a =  /* fixed MMiC */ mysqli_fetch_assoc($stud2a)); ?>
		  </select>
		  
		   <button type="submit" class="btn btn-danger btn-xs">Выбрать</button>
		  </form>
      </h4>
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
			url: 'kurator_ip.php',
			data: $("#ct").serialize(),
			type: 'POST',
			success: function(data){
				console.log(data);
				$("#stp").html(data);
				
				
			}
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
console.log(data);
$('#ttr'+id).after("<tr><td colspan=3><button class='rpc'>печать</button><pre style=' font-size: 10pt;'>"+data+"</pre></td></tr>");
});	});	
		
		
});
	
	
	</script>
</body>
</html>
<?php
 /* fixed MMiC */ mysqli_free_result($spec);
?>