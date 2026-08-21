<?php header('Access-Control-Allow-Origin: *');
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

?>
<?php require_once('Connections/testmed.php'); ?>
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

 $passing_percent = $_POST['sp'];
 $sp= $_POST['ps'];
 $psp= $_POST['psp'];
$user=$_SESSION['MM_Username1'];
$testn=$_SESSION['MM_spec'];
if (isset($_POST['dr'])){
$current=$_POST['dr'];
if (isset($_POST['user']))	$user=$_POST['user'];
if (isset($_POST['razdel']))	$testn=$_POST['razdel'];

 require_once 'includes/common.inc.php';

   $requestParameters = RequestParametersParser::getRequestParameters($_POST, !empty($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : null);
   // _log($requestParameters);


 //$quizResults = new QuizResults();
   //   $quizResults->InitFromRequest($requestParameters);
  //      $generator = QuizReportFactory::CreateGenerator($quizResults, $requestParameters);
 //       $report = $generator->createReport();

       
       

$file='./userxml/'.$testn."_".$user.".xml";
// @file_put_contents($file, $report);
	file_put_contents($file, $current);
}


/* `num` int(11) NOT NULL AUTO_INCREMENT,
  `inn` int(11) DEFAULT NULL,
  `test` int(11) DEFAULT NULL,
  `res` int(11) DEFAULT NULL,
  `dat` date DEFAULT NULL,
  `otv_col` int(11) DEFAULT NULL,*/
$tmp="";
 /* fixed MMiC */ mysqli_select_db(DB::$link, $database_testmed);

if (isset($_POST['dop'])){
$sql="select `tm_nmo_razd_user`.`pop` from  `tm_nmo_razd_user` where user=".GetSQLValueString($user, "int")." and razdel=".GetSQLValueString($testn, "int")." and sp=$sp and psp=$psp";	
  $Result1 =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$totalRows_pop =  /* fixed MMiC */ mysqli_num_rows($Result1);	
echo $sql."Дополнения "; 
if ($totalRows_pop==0) {
	$user=$_POST['user'];$testn=$_POST['razdel'];
$delsql="delete from `tm_nmo_razd_user` where user=".GetSQLValueString($user, "int")." and razdel=".GetSQLValueString($testn, "int");
$Result1 =  /* fixed MMiC */ DB::Query($delsql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
  $insertSQL = sprintf("INSERT INTO `tm_nmo_razd_user` (`id`, `user`, `razdel`, `proydeno`,dat,psp,sp,pop) VALUES (NULL, %s, %s,%s, %s,%s,%s,%s)",
                       GetSQLValueString($user, "int"),
					  GetSQLValueString($testn, "int"),
 					  GetSQLValueString($passing_percent, "double"),
					 GetSQLValueString(date('Y-m-d H:i:s'), "date"), GetSQLValueString($psp, "double"), GetSQLValueString($sp, "double"),1);	
					 
					   $Result1 =  /* fixed MMiC */ DB::Query($insertSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
					 
}
exit;
}
$sql="select `tm_nmo_razd_user`.`pop` from  `tm_nmo_razd_user` where user=".GetSQLValueString($user, "int")." and razdel=".GetSQLValueString($testn, "int");
  $Result1 =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_pop =  /* fixed MMiC */ mysqli_fetch_assoc($Result1);
$totalRows_pop =  /* fixed MMiC */ mysqli_num_rows($Result1);
$ress=$row_pop['pop'];$ress++;

$delsql="delete from `tm_nmo_razd_user` where user=".GetSQLValueString($user, "int")." and razdel=".GetSQLValueString($testn, "int");
$Result1 =  /* fixed MMiC */ DB::Query($delsql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
  $insertSQL = sprintf("INSERT INTO `tm_nmo_razd_user` (`id`, `user`, `razdel`, `proydeno`,dat,psp,sp,pop) VALUES (NULL, %s, %s,%s, %s,%s,%s,%s)",
                       GetSQLValueString($user, "int"),
					  GetSQLValueString($testn, "int"),
 					  GetSQLValueString($passing_percent, "double"),
					 GetSQLValueString(date('Y-m-d H:i:s'), "date"), GetSQLValueString($psp, "double"), GetSQLValueString($sp, "double"),$ress);

//file_put_contents('t.txt',$tmp);
  
echo $insertSQL;
  $Result1 =  /* fixed MMiC */ DB::Query($insertSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
 $insertSQL = sprintf("INSERT INTO `tm_nmo_razd_user_arh` (`id`, `user`, `razdel`, `proydeno`,dat,psp,sp,pop) VALUES (NULL, %s, %s,%s, %s,%s,%s,%s)",
                       GetSQLValueString($user, "int"),
					  GetSQLValueString($testn, "int"),
 					  GetSQLValueString($passing_percent, "double"),
					 GetSQLValueString(date('Y-m-d H:i:s'), "date"), GetSQLValueString($psp, "double"), GetSQLValueString($sp, "double"),$ress);

 $Result1 =  /* fixed MMiC */ DB::Query($insertSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));


?>
