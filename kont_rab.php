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

$colname_test = "-1";
if (isset($_SESSION['MM_UserGroup'])) {
  $colname_test = $_SESSION['MM_UserGroup'];
}
$username_test = "0";
if (isset($_SESSION['MM_Username1'])) {
  $username_test = $_SESSION['MM_Username1'];
}

if (isset($_GET['d'])) {
$sql="SELECT `tm_irab_stud`.`path` from `tm_irab_stud` where `tm_irab_stud`.`num`=".$_GET['d'];
$Result1 =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));	
$row_test =  /* fixed MMiC */ mysqli_fetch_assoc($Result1);
if (file_exists('resdoc/'.$row_test['path']))	unlink('resdoc/'.$row_test['path']);	
$sql="delete from `tm_irab_stud`  where `tm_irab_stud`.`num`=".$_GET['d'];
$Result1 =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));	
}





if (isset($_FILES['fimg'])) {
 $filename =uniqid().'.docx';

		
	move_uploaded_file($_FILES['fimg']['tmp_name'],'resdoc/'.$filename);
	
	
$sql="SELECT `tm_irab_stud`.`path` from `tm_irab_stud` where `tm_irab_stud`.`num`=".$_POST['num'];
$Result1 =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));	
$row_test =  /* fixed MMiC */ mysqli_fetch_assoc($Result1);
if (file_exists('resdoc/'.$row_test['path']))	unlink('resdoc/'.$row_test['path']);	
	
	
	$insertSQL = sprintf("UPDATE `tm_irab_stud` SET `path`= %s WHERE `num` = %s",
						 GetSQLValueString($filename, "text"),
                       GetSQLValueString($_POST['num'], "int")
                       );					
   /* fixed MMiC */ mysqli_select_db(DB::$link, $database_testmed);
  $Result1 =  /* fixed MMiC */ DB::Query($insertSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
 // echo $insertSQL;
  $_GET['tspec']=$_POST['tspec'];
}


if (isset($_POST["kontr"])) {
  $insertSQL = sprintf("INSERT INTO `tm_irab_stud` (`num`, `student`, `itog_rab`, `path`, `antiplagiat`, `result`, `comment`) VALUES (NULL, %s, %s, NULL, NULL, NULL, NULL)",					                          $username_test,$_POST['kontr']);
$test =  /* fixed MMiC */ DB::Query($insertSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
 // mysql_select_db($database_port, $port);
 // $Result1 = mysql_query($insertSQL, $port) or die(mysql_error());
}

 /* fixed MMiC */ mysqli_select_db(DB::$link, $database_testmed);
$query_test = sprintf(" SELECT
`tm_irab_spec`.`num`,
  `tm_irab_spec`.`spec`,
  `tm_irab_spec`.`urov`
FROM
  `tm_irab_def_sp`
  INNER JOIN `tm_irab_spec` ON (`tm_irab_def_sp`.`irab_spec` = `tm_irab_spec`.`num`)
WHERE
  `tm_irab_def_sp`.`spec` = %s  ", GetSQLValueString($colname_test, "int"));
$test =  /* fixed MMiC */ DB::Query($query_test, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_test =  /* fixed MMiC */ mysqli_fetch_assoc($test);
$totalRows_test =  /* fixed MMiC */ mysqli_num_rows($test);

if (($totalRows_test<2)and($totalRows_test>0)) $_GET['tspec']= $row_test['num'];



$colname_test2 = "0";
if (isset($_GET['tspec'])) {
  $colname_test2 =$_GET['tspec'];
}


$query_kont = sprintf(" SELECT 
  `tm_irab_tem`.`num`,
  `tm_irab_tem`.`inn`,
  `tm_irab_tem`.`nazv`
FROM
  `tm_irab_tem`
WHERE
  `tm_irab_tem`.`inn` =%s and `tm_irab_tem`.`num` not in (
SELECT 
  `tm_irab_stud`.`itog_rab`
FROM
  `tm_irab_stud`
  INNER JOIN `tm_irab_tem` ON (`tm_irab_stud`.`itog_rab` = `tm_irab_tem`.`num`)
  INNER JOIN `tm_irab_def_sp` ON (`tm_irab_tem`.`inn` = `tm_irab_def_sp`.`irab_spec`)
WHERE
  `tm_irab_tem`.`inn` = %s AND 
  `tm_irab_def_sp`.`spec` = %s) ", GetSQLValueString($colname_test2, "int"), GetSQLValueString($colname_test2, "int"), GetSQLValueString($colname_test, "int"));
//echo $query_kont;
$kont =  /* fixed MMiC */ DB::Query($query_kont, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
//echo $query_kont;
$row_kont =  /* fixed MMiC */ mysqli_fetch_assoc($kont);
$totalRows_kont =  /* fixed MMiC */ mysqli_num_rows($kont);


$query_kont = sprintf(" SELECT 
  `tm_irab_stud`.`itog_rab`,
  `tm_irab_stud`.`num`,
  `tm_irab_stud`.`student`,
  `tm_irab_stud`.`path`,
  `tm_irab_stud`.`antiplagiat`,
  `tm_irab_stud`.`result`,
  `tm_irab_stud`.`comment`,
  `tm_irab_tem`.`nazv`
FROM
  `tm_irab_stud`
  INNER JOIN `tm_irab_tem` ON (`tm_irab_stud`.`itog_rab` = `tm_irab_tem`.`num`)
  INNER JOIN `tm_irab_def_sp` ON (`tm_irab_tem`.`inn` = `tm_irab_def_sp`.`irab_spec`)
WHERE
  `tm_irab_tem`.`inn` = %s AND 
  `tm_irab_def_sp`.`spec` = %s ", GetSQLValueString($colname_test2, "int"), GetSQLValueString($colname_test, "int"));
//echo $query_kont;
$rab =  /* fixed MMiC */ DB::Query($query_kont, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
//echo $query_kont;
$row_rab =  /* fixed MMiC */ mysqli_fetch_assoc($rab);
$totalRows_rab =  /* fixed MMiC */ mysqli_num_rows($rab);


?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Тесты по специальности</title>
<!-- Bootstrap -->
<link rel="stylesheet" href="bootstrap/css/bootstrap.css">
<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
<link rel="stylesheet" href="css/bootstrap.css">

<script type="text/javascript">
function MM_goToURL() { //v3.0
  var i, args=MM_goToURL.arguments; document.MM_returnValue = false;
  for (i=0; i<(args.length-1); i+=2) eval(args[i]+".location='"+args[i+1]+"'");
}
</script>
</head>
<body>
<?php include("header.php");?>

<!-- HEADER --><!-- / HEADER --> 

<!--  SECTION-1 -->
<section>
  <div class="row">
    <div class="col-lg-12 page-header text-center">
      <h2>Итоговые и контрольные работы</h2>
      
    </div>
  </div>
 <?php if ($totalRows_test>1)
{ ?>
    <div class="container">
    <div class="row">
      <div class="col-lg-12 page-header text-center">
        <p>Выберите раздел контрольной работы, красным цветом выделяется итоговая работа, синим промежуточная </p>
        <div class="btn-toolbar" role="toolbar">
        
        <div class="btn-group btn-group-justified" role="group">
           <?php do { ?>    
          
  <a href="?tspec=<?php echo $row_test['num']; ?>" class="btn <?php if ($row_test['urov']==1) echo "btn-danger"; else echo "btn-primary";  ?> "><?php echo $row_test['spec']; ?></a>
             <?php } while ($row_test =  /* fixed MMiC */ mysqli_fetch_assoc($test)); ?>
		  </div>
		  </div>
      
      </div>
    </div>
  </div>
     <?php  } ?>    
   <!-- /container -->
   <?php if (($totalRows_rab<1) and ($totalRows_kont>0)){?>
  <form action="" name="fmn" method="post">
  <div class="container">
    <div class="row">
      <div class="col-lg-12 page-header text-center">	
   	     <p>Выберите тему своей работы</p>
        <div class="input-group">
          <span class="input-group-addon"></span>
         <select class="form-control" id="spr" name="kontr">
    
          <?php
do {  
?>
          <option value="<?php echo $row_kont['num']?>" ><?php echo mb_substr($row_kont['nazv'],0,130,"UTF-8"); ?>...</option>
          <?php
} while ($row_kont = mysqli_fetch_assoc($kont));
  
?>
        </select><span class="input-group-btn">
          <button class="btn btn-primary" type="submit">Вперед!</button>
        </span>
        <input type="hidden" name="tspec" id="hiddenField" value="<?php echo $_GET['tspec'];?>">

        </div>
       
    </div>
    <div class="row"></div>
  </div>
</div></form>
 <?php } ?>
  <!-- /container -->
    <?php if ($totalRows_rab>0){ ?>
  <div>
    <div class="container">
      <div class="row">
        <div class="col-xs-12 col-md-12">
         
         
            <div class="card card-shadow col-xs-10 col-xs-offset-1 col-md-8 col-md-offset-2 reveal">

              <form action="kont_rab.php" class="reveal-content"  method="post" enctype="multipart/form-data" >
          <div class="form-group">
                       
           
                       
                <label for="pricefrom" class="control-label">Тема</label>
        <input type="hidden" value="<?php echo $row_rab['num']; ?>" name="num">
            <input type="hidden" value="<?php echo $row_rab['itog_rab']; ?>" name="itog_rab">
             <input type="hidden" value="<?php echo $_GET['tspec']; ?>" name="tspec">
             
              <p class="form-control-static"><span style="overflow: hidden"><?php echo $row_rab['nazv']; ?></span></p>
              
        <label for="pricefrom" class="control-label">Файл с документом</label>
            <div class="input-group">
              <div class="input-group-addon" id="basic-addon1">Файл</div>
               <p class="form-control"><input type="file"  class="filestyle" accept="application/vnd.openxmlformats-officedocument.wordprocessingml.document" name="fimg" ></p>
                <span class="input-group-btn">
         <a class="btn btn-primary" href="resdoc/<?php echo $row_rab['path']; ?>">Скачать</a>
        </span>
            </div>
            <label for="pricefrom" class="control-label">Проверка антиплагиат</label>
            <div class="input-group">
              <div class="input-group-addon" id="basic-addon1"><?php echo $row_rab['antiplagiat']; ?></div>
				<p class="form-control">Результат </p>    <span class="input-group-btn">
         <a class="btn btn-primary" href="resdoc/<?php echo $row_rab['path']; ?>">Вперед!</a>
        </span>
            </div>
               <label for="pricefrom" class="control-label">Итоговая оценка</label>
            <div class="input-group">
              <div class="input-group-addon" id="basic-addon1"><?php echo $row_rab['result']; ?></div>
				<p class="form-control">Результат </p>
            </div>
        
                                  
           
        </div>
         
          <p class="text-center"><input type="submit" class="btn btn-info" value="Отправить данные">
          <input type="submit" class="btn btn-danger" onClick="MM_goToURL('parent','kont_rab.php?d=<?php echo $row_rab['num']; ?>');return document.MM_returnValue" value="Удалить">

</p>

              </form>
          </div>
           
       
      </div>  
    </div>
    </div>
	</div> <?php } ?>
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

	</script>
</body>
</html>
<?php
 /* fixed MMiC */ mysqli_free_result($test);
?>
