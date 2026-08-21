<?php require_once('Connections/testmed.php'); ?>
<?php if (!isset($_SESSION)) {
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
 /* fixed MMiC */ mysqli_select_db(DB::$link, $database_testmed);
$query_test = sprintf(" SELECT `tm_prepod`.`fio`,`tm_prepod`.`foto`,`tm_prepod`.`text`,`tm_prepod`.`tel`,
  `tm_prepod`.`mail`, `tm_prepod`.`predmet`
FROM
  `tm_prepod`
 ");
//WHERE
 // (`tm_prepod_spec`.`spec` = %s) or (`tm_prepod_spec`.`spec` = -1) ", GetSQLValueString($colname_test, "int"));
$test =  /* fixed MMiC */ DB::Query($query_test, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
//echo $query_test;
$row_test =  /* fixed MMiC */ mysqli_fetch_assoc($test);
$totalRows_test =  /* fixed MMiC */ mysqli_num_rows($test);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Тесты по специальности</title>

<!-- Bootstrap -->
<link rel="stylesheet" href="css/bootstrap.css">
<link href="css/bootstrap-3.3.7.css" rel="stylesheet" type="text/css">

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body class="caption">

<?php include("header.php");?>

<!-- HEADER --><!-- / HEADER --> 

<!--  SECTION-1 -->

<section>
  <div class="container">
  <div class="row text-center">
       <?php do { ?>
    <div class="col-sm-4 col-md-4 col-lg-4 col-xs-12">
      <div class="thumbnail"> <img class="media-object img-circle img-fluid" src="pimg/<?php echo $row_test['foto']  ?>">
        <div class="caption">
          <h3><?php echo $row_test['fio']  ?></h3>
          <div class="row">
            <div class="col-lg-4"> <span class="glyphicon glyphicon-tasks" aria-hidden="true"></span> </div>
            <div class="col-lg-6  text-left"><?php echo $row_test['predmet1']  ?></div>
          </div>
          <div class="row">
            <div class="col-lg-4"><span class="glyphicon glyphicon-earphone" aria-hidden="true"></span> </div>
            <div class="col-lg-6  text-left"><?php echo $row_test['tel']  ?></div>
          </div>
          <div class="row">
            <div class="col-lg-4"><span class="glyphicon glyphicon-envelope" aria-hidden="true"></span></div>
            <div class="col-lg-6  text-left"><a href="mailto:<?php echo $row_test['mail']  ?>"><?php echo $row_test['mail']  ?></a></div>
          </div>
      
       </div>
    </div> </div>
              <?php } while ($row_test =  /* fixed MMiC */ mysqli_fetch_assoc($test)); ?>
     
     
    </div>
  </div>
</section>

<!-- FOOTER -->
<div class="container">
  <div class="row"></div>
</div>

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