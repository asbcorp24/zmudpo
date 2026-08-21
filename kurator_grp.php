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




if ((isset($_POST["uo"])) && ($_POST["uo"] == "1")) {
$sql="update `tm_user` set grupp='".$_POST["val"]."' where num=".$_POST["num"];	
DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
	echo $sql;
	
	exit(0);	
	
}

$sql="select distinct num,fio,grupp from (
SELECT 
  `tm_user`.`fio`,
  `tm_user`.`grupp`,
  `tm_user`.`num`
FROM
  `tm_user`
  INNER JOIN `tm_spec` ON (`tm_user`.`spec` = `tm_spec`.`num`)
  INNER JOIN `tm_nmo_razd` ON (`tm_spec`.`num` = `tm_nmo_razd`.`spec`)
WHERE
  `tm_nmo_razd`.`prepod` = $user

union
SELECT 
  `tm_user`.`fio`,
  `tm_user`.`grupp`,
  `tm_user`.`num`
FROM
  `tm_user`
  INNER JOIN `tm_spec` ON (`tm_user`.`spec` = `tm_spec`.`num`)
  INNER JOIN `tm_nmo_razd` ON (`tm_spec`.`num` = `tm_nmo_razd`.`spec`)
  INNER JOIN `tm_nmo_razd_dop_prepod` ON (`tm_nmo_razd`.`id` = `tm_nmo_razd_dop_prepod`.`razdel`)
WHERE
  `tm_nmo_razd_dop_prepod`.`prepod` = $user
ORDER BY
  `grupp`,
  `fio`) res order by grupp,fio";//echo $sql;
$spec =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_spec =  /* fixed MMiC */ mysqli_fetch_assoc($spec);
$totalRows_spec =  /* fixed MMiC */ mysqli_num_rows($spec);

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
	<!--панель скопирования элементов-->

	
	
	
	
	
 <div class="container">
  <div class="row text-center">
	
     <?php include("kb.php");?>

	</div>  
	</div>  
<div class="container">
 <div class="row ">
	  <br>
	 
  
    
  <!-- 2 панель -->
  <div class="panel panel-default">
    <!-- Заголовок 2 панели -->
    <div class="panel-heading">
      <h4 class="panel-title">
     
		 
			  Привязка студента к группе
		 
      </h4>
    </div>

      <!-- Содержимое 2 панели -->
      <div class="panel-body">

		 
		  <hr>
		 

		  
		   <div class="table-responsive"> 
<table class="table table-bordered table-striped" id="pepe">
	  	     <tbody id="tb<?php echo $row_spec['id']; ?>">
	  	       <tr> <th class="ФИО"></th>
				  
				    <th>Группа</th>
	  	       
  	           </tr>
				   <?php do {
			
	  	  ?><tr class="info">
				 
				   <td data-num="<?php echo $row_spec['num']; ?>" data-name="comment"  class="info"><?php echo $row_spec['fio']; ?></td>
			
				   <td data-num="<?php echo $row_spec['num']; ?>" data-name="comment"  class="info">
				   	<select data-num="<?php echo $row_spec['num']; ?>" class="sg">
		
			  	   <?php do { ?>
			  	   	  <option value="<?php echo $row_grp['id']; ?>"  <?php if (!(strcmp($row_spec['grupp'],$row_grp['id']))) {echo "SELECTED";} ?>><?php echo $row_grp['nazv']; ?></option>
			           <?php } while ($row_grp =  /* fixed MMiC */ mysqli_fetch_assoc($grp)); mysqli_data_seek($grp,0);?>
        </select></td>
  	           </tr>
	  	 
				 
        <?php } while ($row_spec =  /* fixed MMiC */ mysqli_fetch_assoc($spec)); ?>
  </tbody>
</table>
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

  




    fl = 0;
  $('.sg').on('blur',function(e){
val=$(this).val();
		num=$(this).data('num');
	//	razdel=$(this).data('razdel');
	$.post('kurator_grp.php', {'uo':'1', 'num' :num,'val':val},		function(data) {});
	//e.preventDefault;
		 //$("#post").submit();	
		});	

 

  

});
//	/(".hello").clone().appendTo(".container");
	
	</script>
</body>
</html>
<?php
 /* fixed MMiC */ mysqli_free_result($spec);
?>