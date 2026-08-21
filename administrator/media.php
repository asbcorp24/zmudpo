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

$MM_restrictGoTo = "test.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
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
if (isset($_SESSION['MM_Username'])) {
  $username_test = $_SESSION['MM_Username'];
}
 /* fixed MMiC */ mysqli_select_db(DB::$link, $database_testmed);
$query_test = sprintf(" SELECT 
  `tm_media`.`nazv`,
  `tm_media_spec`.`num`,
  `tm_media_spec`.`comment`,
  `tm_media`.`path`,
  `tm_media`.`spec`
FROM
  `tm_media_spec`
  INNER JOIN `tm_media` ON (`tm_media_spec`.`media` = `tm_media`.`num`)
WHERE
  `tm_media_spec`.`spec` = %s
ORDER BY
  `tm_media_spec`.`num` ", GetSQLValueString($colname_test, "int"));
$test =  /* fixed MMiC */ DB::Query($query_test, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_test =  /* fixed MMiC */ mysqli_fetch_assoc($test);
$totalRows_test =  /* fixed MMiC */ mysqli_num_rows($test);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Медиа по специальности</title>

<!-- Bootstrap -->
<link rel="stylesheet" href="css/bootstrap.css">

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
<section>
  <div class="row">
    <div class="col-lg-12 page-header text-center">
      <h2>Медиа материалы</h2>
<?php if ($totalRows_test<1){?>  <h3>Чтобы увидет материалы войдите в группу</h3>    <?php} ?>
    </div>
  </div>
  <div class="container ">
    <div class="row">
      <?php 
		
		if ($totalRows_test>0) do { ?>
        <div class="col-lg-4 col-sm-12 text-center">
           <div class="thumbnail">
 <?php $s=$row_test['path'];
			 $s=str_replace('https://www.youtube.com/watch?v=','https://www.youtube.com/embed/',$s);
		  	$s=str_replace('https://youtu.be/','https://www.youtube.com/embed/',$s);
			   ?>
       <iframe width="95%"  src="<?php echo $s; ?>" frameborder="0" allowfullscreen></iframe>      
         
         </div>
          <h3><?php echo $row_test['nazv']; ?></h3>
        <p><?php echo $row_test['comment']; ?></p> 
        
        </div>
        <?php } while ($row_test =  /* fixed MMiC */ mysqli_fetch_assoc($test)); ?>
    </div>
    <div class="row"></div>
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
</body>
</html>
<?php
 /* fixed MMiC */ mysqli_free_result($test);
?>
