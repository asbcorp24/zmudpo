<?php require_once('Connections/testmed.php'); ?>
<?php require_once('enc.php'); ?>
<?php




if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

 // $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
//echo $_GET['user']."|".my_encrypt(2166);
$pod='';

if (isset($_GET['user']))$pod=my_decrypt($_GET['user']);
//echo my_decrypt(my_encrypt('2166'));
//echo my_decrypt($_GET['user']);
if (is_numeric($pod) ) {
	
 $insertSQL = sprintf("UPDATE `tm_user` SET `mail_pod`= 1 WHERE `num` = $pod");
  //                     GetSQLValueString($_POST['fio'], "text"),
  //                     GetSQLValueString($_POST['spec'], "int"),
  //                     GetSQLValueString(generate_password(8), "text"),
  //                     GetSQLValueString($_POST['mail'], "text"));

 //echo $insertSQL;
 $Result1 = DB::Query($insertSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
	  
//	  mysql_query($insertSQL, $loc) or die(mysql_error());
//$spec =  /* fixed MMiC */ DB::Query($query_spec, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
//$row_spec =  /* fixed MMiC */ mysqli_fetch_assoc($spec);
//$totalRows_spec =  /* fixed MMiC */ mysqli_num_rows($spec);	
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Отделение допольнительного образования ЗМУ</title>

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

<!-- HEADER -->
<header>
	
	  <div class="container">
      <div class="row">
      
			<div class="panel panel-default drop-shadow">
  <div class="panel-body">
	   <?php   ?>
	  <p class="text-center">
	  

	<p class="text-center"><?php echo $_POST['fio']; ?> </p>
			
			<p class="text-center">Спасибо за подтверждение. в ближайшее время с вами свяжутся наши менеджеры по дальнейшему обучению</p>
				</div>
</div>
			
         
     
      </div>
    </div>
	
  
</header>
<!-- / HEADER --> 

<!--  SECTION-1 -->


<!-- FOOTER -->
<footer class="text-center">
  <div class="container">
    <div class="row"></div>
  </div>
</footer>
<!-- / FOOTER --> 
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) --> 
<script src="js/jquery-1.11.3.min.js"></script> 
<!-- Include all compiled plugins (below), or include individual files as needed --> 
<script src="js/bootstrap.js"></script>
</body>
</html>