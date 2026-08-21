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

function getsokr($slovo){
$res="";	
$pieces = explode(" ", $slovo);
foreach($pieces as $val){
$res=$res.mb_substr($val,0,3)." ";	
}	
return $res;	
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


$query_spec = "SELECT 
  `tm_stat`.`id`,
  `tm_stat`.`nazv`,
  `tm_stat`.`sql`
FROM
  `tm_stat`";

$spec01 =  /* fixed MMiC */ DB::Query($query_spec, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_spec01 =  /* fixed MMiC */ mysqli_fetch_assoc($spec01);
$totalRows_spec01 =  /* fixed MMiC */ mysqli_num_rows($spec01);
$sql="SELECT 
  `tm_stat`.`id`,
  `tm_stat`.`nazv`,
  `tm_stat`.`sql`
FROM
  `tm_stat`
WHERE
  `tm_stat`.`id` = ".intval($_GET['spec']);
$spec02 =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_spec02 =  /* fixed MMiC */ mysqli_fetch_assoc($spec02);
$totalRows_spec02 =  /* fixed MMiC */ mysqli_num_rows($spec02);
if ($totalRows_spec02>0){
	$sql=$row_spec02['sql'];
$spec03 =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_spec03 =  /* fixed MMiC */ mysqli_fetch_assoc($spec03);	
	
}

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

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->


<script src="js/Chart.min.js"></script> 
<script src="js/utils.js"></script>	

</head>
<body>
	
<?php include("header.php");?>

<hr>

	
	
	
	
 <div class="container">
  <div class="row text-center">
	
     <?php include("kb.php");?>  

	</div>  
	</div> 
	 <div class="container">
  <div class="row text-center">
	
        
<div class="btn-group pull-left">
    <button id="btt" type="button" class="btn btn-success btn-lg dropdown-toggle btn-block"  data-toggle="dropdown" style="min-width: 200px">
       <?php if (isset($_GET['spec'])) { echo $row_spec01['nazv']; } else { ?> Статистика <?php }?>  <span class="caret"></span>
    </button>
      
        
         <ul class="dropdown-menu" role="menu">
    
      <?php do { ?>
          <li><a href="?spec=<?php echo $row_spec01['id']; ?>"><?php echo $row_spec01['nazv']; ?></a></li>
        <?php } while ($row_spec01 =  /* fixed MMiC */ mysqli_fetch_assoc($spec01)); ?>
        
    </ul>
</div>
	</div>  
	</div>  
<div class="container">
 <div class="row ">
	  <br>
<div class="panel panel-default ass">
  <div class="panel-heading"> <?php echo $row_spec02['nazv'];?><button type="button" class="btn btn-primary btn-xs pull-right" id="pch"><i class="glyphicon glyphicon-print"></i></button></div>
  <div class="panel-body">
 <div id="container" style="width: 95%;">
		<canvas id="canvas"></canvas>
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

	</script>
	<script>
		var color = Chart.helpers.color;
		var barChartData = {
			labels: [ <?php do {if (strlen($row_spec03['x'])>80)$sss=(getsokr(DB::escape($row_spec03['x'])));else $sss=$row_spec03['x']; echo '"'.trim($sss) .'",';} while ($row_spec03 =  /* fixed MMiC */ mysqli_fetch_assoc($spec03));mysqli_data_seek($spec03, 0);$row_spec03=mysqli_fetch_assoc($spec03); ?>],
			datasets: [{
				label: '<?php echo $row_spec02['nazv'];?>',
				backgroundColor: color(window.chartColors.red).alpha(0.5).rgbString(),
				borderColor: window.chartColors.red,
				borderWidth: 1,
				data:  [ <?php do { echo '"'.$row_spec03['y'].'",';} while ($row_spec03 =  /* fixed MMiC */ mysqli_fetch_assoc($spec03));mysqli_data_seek($spec03, 0); ?>]
			}]

		};

window.onload = function() {
			var ctx = document.getElementById('canvas').getContext('2d');
			window.myBar = new Chart(ctx, {
				type: 'bar',
				data: barChartData,
				options: {
					responsive: true,
					legend: {
						position: 'top',
					},
					title: {
						display: true,
						text: 'Статистика по сайту'
					}
				}
			});

		};
	$('#pch').on('click',function(){
		
	
			$('#canvas').printThis({afterPrint:function(){	}});
		//	$('.npc').show();
	});	
				
</script>
</body>
</html>
<?php
 /* fixed MMiC */ mysqli_free_result($spec);
?>