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

$un=$_SESSION['MM_Username1'];


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
  `tm_nmo_razd_media`.`povt`,
  `tm_nmo_razd_media`.`data_act`,
  `tm_nmo_razd_media`.`data_okon`,
  `tm_nmo_razd_media`.`avtor`,
  `tm_nmo_razd_media`.`tippr`,
  `tm_nmo_razd_media`.`kvn`,
  `tm_nmo_razd_media`.`pop`,
  `tm_nmo_razd_media`.`gal`
FROM
  `tm_nmo_razd_media`
WHERE
  `tm_nmo_razd_media`.`id` = ".intval($_GET['media']);
	 $sqkvr =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_sqkvr =  /* fixed MMiC */ mysqli_fetch_assoc($sqkvr);

//echo $sql;
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


  
<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<?php  if (!isset($_GET['h']))include("header.php");?>
	


	
	
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
                            <h2 class="page-title"><?php echo  $row_sqkvr['nazv']; ?></h2>
                          	<div class="panel panel-default">
  <div class="panel-heading">Информация</div>
  <div class="panel-body">
	    
	        <button type="button" class="btn btn-info btn-sm pull-left" id="pech" data-id="<?php echo $row_sqkvr['id']; ?>" >Печать</button><br><br>
	  <hr>
	  <div id="pepe" class="table-responsive">
<?php 
	$row = 1;
		  
 {
   $homepage = file_get_contents("./nmo/csv/".$row_sqkvr['path']);
	 $pizza = mb_convert_encoding($homepage, 'utf-8','CP1251');
    echo '<table class="table table-condensed table-striped">';
   //str_getcsv 
	 $convertedText = explode(PHP_EOL, $pizza);

	
	 foreach ($convertedText as &$value) 

 {$data = str_getcsv($value, ";");
        $num = count($data);
        if ($row == 1) {
            echo '<thead><tr>';
        }else{
            echo '<tr>';
        }
       
        for ($c=0; $c < $num; $c++) {
            //echo $data[$c] . "<br />\n";
            if(empty($data[$c])) {
               $value = "&nbsp;";
            }else{
               $value = $data[$c];
            }
            if ($row == 1) {
                echo '<th>'.$value.'</th>';
            }else{
                echo '<td>'.$value.'</td>';
            }
        }
       
        if ($row == 1) {
            echo '</tr></thead><tbody>';
        }else{
            echo '</tr>';
        }
        $row++;
    }
   
    echo '</tbody></table>';
    fclose($handle);
}
?>	  
		  
		  

		  </div>
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
		
	$('#pech').on('click',function(){	
			$('#pepe').printThis({ importCSS: true,            // import parent page css
    importStyle: true,removeInline: false,  afterPrint:function(){
			
				
			}});
		//	$('.npc').show();
	});	
			
		
	
	
	</script>
</body>
</html>

