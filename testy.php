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

if ($_POST["zav"]==1){
//	$.post('nmo.php', {'kr':'1', 'id' :deff1,'name':deff2},		function(data) {		});			
	
$insertSQL="update tm_user set zav=1 where num=$username_test";
//	echo $insertSQL;
	DB::Query($insertSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
	
	include('add_arh.php');
//exit();

}
 /* fixed MMiC */ mysqli_select_db(DB::$link, $database_testmed);
//$query_test = sprintf(" SELECT    `tm_spec_test`.`activ`,   `tm_spec_test`.`otv_col` AS `votv_col`,   `tm_spec_test`.`inn`,   `tm_spec_test`.`inn`,   `tm_spec_test`.`nazvanie`,   `tm_test`.`img`,   `tm_test`.`nazv`,   `tm_test`.`tex`,   `tm_test`.`path`,   `tm_user_test`.`dat`,   `tm_user_test`.`otv_col`,   `tm_test`.`col_v`,   `tm_spec_test`.`num`,   `tm_user_test`.`res` FROM   `tm_spec_test`   INNER JOIN `tm_test` ON (`tm_spec_test`.`tm_test` = `tm_test`.`num`)   LEFT OUTER JOIN `tm_user_test` ON (`tm_spec_test`.`num` = `tm_user_test`.`test`) WHERE   `tm_spec_test`.`inn` = %s AND    `tm_user_test`.`inn` = %s union SELECT    `tm_spec_test`.`activ`,   `tm_spec_test`.`otv_col` AS `votv_col`,   `tm_spec_test`.`inn`,   `tm_spec_test`.`inn`,   `tm_spec_test`.`nazvanie`,   `tm_test`.`img`,   `tm_test`.`nazv`,   `tm_test`.`tex`,   `tm_test`.`path`,   `tm_user_test`.`dat`,   `tm_user_test`.`otv_col`,   `tm_test`.`col_v`,   `tm_spec_test`.`num`,   `tm_user_test`.`res` FROM   `tm_spec_test`   INNER JOIN `tm_test` ON (`tm_spec_test`.`tm_test` = `tm_test`.`num`)   LEFT OUTER JOIN `tm_user_test` ON (`tm_spec_test`.`num` = `tm_user_test`.`test`) WHERE   `tm_spec_test`.`inn` = %s AND  `tm_spec_test`.`num` not in(SELECT    `tm_user_test`.`test` FROM   `tm_user_test` WHERE   `tm_user_test`.`inn` = %s)  ", GetSQLValueString($colname_test, "int"),GetSQLValueString($username_test, "int"),GetSQLValueString($colname_test, "int"),GetSQLValueString($username_test, "int"));
$query_test = sprintf("SELECT 
  `tm_spec_test`.`activ`,
  `tm_spec_test`.`otv_col` AS `votv_col`,
  `tm_spec_test`.`inn`,
  `tm_spec_test`.`inn`,
  `tm_spec_test`.`nazvanie`,
  `tm_test`.`img`,
  `tm_test`.`nazv`,
  `tm_test`.`tex`,
  `tm_test`.`path`,
  `tm_user_test`.`dat`,
  `tm_user_test`.`otv_col`,
  `tm_test`.`col_v`,
  `tm_spec_test`.`num`,
  `tm_user_test`.`res`
FROM
  `tm_spec_test`
  INNER JOIN `tm_test` ON (`tm_spec_test`.`tm_test` = `tm_test`.`num`)
  LEFT OUTER JOIN `tm_user_test` ON (`tm_spec_test`.`num` = `tm_user_test`.`test`)
WHERE
  `tm_spec_test`.`inn` = %s AND 
  `tm_user_test`.`inn` = %s

UNION

SELECT 
  `tm_spec_test`.`activ`,
  `tm_spec_test`.`otv_col` AS `votv_col`,
  `tm_spec_test`.`inn`,
  `tm_spec_test`.`inn`,
  `tm_spec_test`.`nazvanie`,
  `tm_test`.`img`,
  `tm_test`.`nazv`,
  `tm_test`.`tex`,
  `tm_test`.`path`,
  0 AS `dat`,
  0 AS `otv_col`,
  `tm_test`.`col_v`,
  `tm_spec_test`.`num`,
  0 AS `res`
FROM
  `tm_spec_test`
  INNER JOIN `tm_test` ON (`tm_spec_test`.`tm_test` = `tm_test`.`num`)
WHERE
  `tm_spec_test`.`inn` = %s
  and `tm_spec_test`.`num` not in( SELECT 
  `tm_user_test`.`test`
FROM
  `tm_user_test`
WHERE
  `tm_user_test`.`inn` = %s)

 ", GetSQLValueString($colname_test, "int"),GetSQLValueString($username_test, "int"),GetSQLValueString($colname_test, "int"),GetSQLValueString($username_test, "int"));
$test =  /* fixed MMiC */ DB::Query($query_test, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_test =  /* fixed MMiC */ mysqli_fetch_assoc($test);
$totalRows_test =  /* fixed MMiC */ mysqli_num_rows($test);
//echo $query_test;

$sql="SELECT 
  `tm_user`.`num`,
  `tm_user`.`fio`,
  `tm_user`.`spec`,
  `tm_user`.`passw`,
  `tm_user`.`act`,
  `tm_user`.`mail`,
  `tm_user`.`mail_pod`,
  `tm_user`.`rss`,
  `tm_user`.`data_nach`,
  `tm_user`.`zav`,
  `tm_user`.`urlico`,
  `tm_user`.`ur_parent`,
  `tm_user`.`post`,
  `tm_user`.`post_addr`,`tm_user`.`personal`
FROM
  `tm_user`
WHERE
  `tm_user`.`num` = ".$_SESSION['MM_Username1'];

$tzaz =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_tzaz =  /* fixed MMiC */ mysqli_fetch_assoc($tzaz);

$sql="SELECT 
  COUNT(`tm_spec_test`.`num`) AS kolv
FROM
  `tm_spec_test`
  INNER JOIN `tm_test` ON (`tm_spec_test`.`tm_test` = `tm_test`.`num`)
  LEFT OUTER JOIN `tm_user_test` ON (`tm_spec_test`.`num` = `tm_user_test`.`test`)
WHERE
  `tm_spec_test`.`inn` = $colname_test AND 
  `tm_user_test`.`inn` = ".$_SESSION['MM_Username1'];;

$test1 =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_test1 =  /* fixed MMiC */ mysqli_fetch_assoc($test1);

$sql="SELECT 
  COUNT(`tm_spec_test`.`num`) AS `kolv`
FROM
  `tm_spec_test`
  INNER JOIN `tm_test` ON (`tm_spec_test`.`tm_test` = `tm_test`.`num`)
WHERE
  `tm_spec_test`.`inn` = $colname_test";
$test2 =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_test2 =  /* fixed MMiC */ mysqli_fetch_assoc($test2);


$sql="SELECT 
  `tm_docs`.`path`,
  `tm_docs`.`nazv`,
  `tm_docs`.`dat`
FROM
  `tm_doc_spec`
  INNER JOIN `tm_docs` ON (`tm_doc_spec`.`doc` = `tm_docs`.`num`)
WHERE
  `tm_doc_spec`.`spec` =  $colname_test AND 
  `tm_docs`.`typ_doc` = 0";
$doca2 =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_doca2 =  /* fixed MMiC */ mysqli_fetch_assoc($doca2);
$totalrow_doca2 =  /* fixed MMiC */ mysqli_num_rows($doca2);


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
<section>
  <div class="row text-center">

	    <div class="col-lg-12  ">
		<div class="panel panel-info">
  <div class="panel-heading">Документы к курсу</div>
  <div class="panel-body">
    <?php if ($totalrow_doca2>0){ ?>
   
     	  <?php do { ?>
    <div class="input-group">
		  <span class="input-group-addon">
             <i class="icofont icofont-book"></i>
      </span>
      <input type="text" class="form-control" value="<?php echo $row_doca2['nazv'];?>">
        <span class="input-group-addon info">
      <a href="docs/<?php echo $row_doca2['path'];?>" target="_blank"> <i class="icofont icofont-learn"></i>скачать
            </span></a>
    </div> <br>
	 <?php } while ($row_doca2 =  /* fixed MMiC */ mysqli_fetch_assoc($doca2));
			 mysqli_data_seek($doca2,1);				  
		  ?>
	  <?php }?>
  </div>
</div>
 </div>
</div>
<section>
<!--  SECTION-1 -->
<section>
  <div class="row">
	<?php  if($row_tzaz['zav']>0){?> 
	    <div class="col-lg-12 page-header text-center">
	<div class="panel panel-default">
  <div class="panel-body"><h3>Поздравляем, вы прошли обучение, за получением оригинала сертификата обращайтесь к нашим менеджерам</h3>
	
	
     
    </div>
    <p><div class="caption"  style="padding-left: 20px">
         
            <p><a href="./sert/get_dipl2.php?stud=<?php echo $row_tzaz['num'];?>&pr " class="btn btn-primary" role="button"><span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span> Сохранить</a></p>
        </div></p>
      <div class="thumbnail"> <img src="./sert/get_dipl2.php?stud=<?php echo $row_tzaz['num'];?>" alt="" class="img-responsive">
        
  </div>
</div>  
	   </div>
	<?php }  else {?>  
	
	
	
	
	  
    <div class="col-lg-12 page-header text-center">
      <h2>Выберите тестирование</h2>
      
    </div>
  </div>
  <div class="container ">
    <div class="row">
      <?php do { ?>
        <div class="col-lg-4 col-sm-12 text-center"  style="min-height: 800px"><img class="img-circle" alt="140x140" style="width: 140px; height: 140px;" src="timg/<?php echo $row_test['img']; ?>" data-holder-rendered="true">
          <h5  style="min-height: 50px"><?php echo $row_test['nazv']; ?></h5>
          <p>.</p><div class="table-responsive">
    <table width="100%" class="table table-bordered">
        <tbody>
          <tr style="min-height: 100px">
            <td style="height:100px;width:140px;">Раздел теста</td>
            <td style="width:140px;"><?php echo $row_test['nazvanie']; ?></td>
            </tr>
          <tr>
            <td>Попыток сдачи</td>
            <td> <?php if (isset($row_test['otv_col']))  echo $row_test['otv_col']; else echo("0");?> из <?php echo $row_test['votv_col']; ?></td>
          </tr>
          <tr>
            <td>Последний результат</td>
            <td><?php echo $row_test['res']; ?>&nbsp; из <?php echo $row_test['col_v']; ?></td>
          </tr>
          <tr>
            <td>Дата сдачи</td>
            <td><?php echo $row_test['dat']; ?></td>
          </tr>
          <tr>
            <td>Ответы</td>
            <td>
            <?php if ( $row_test['activ']!=1) {?>
            <?php if (file_exists("otv/".$row_test['tex'])){ ?>
            <a href="/otv/<?php echo $row_test['tex']; ?>" target="_blank"><?php echo $row_test['tex']; ?></a>
            <?php }}	?>
            </td>
          </tr>
        </tbody>
    </table>
</div>
          <?php if (($row_test['otv_col'] < $row_test['votv_col'])and( $row_test['activ']==1)) {  ?>
  <p class="text-center"><a class="btn btn-primary btn-lg" href="get_test.php?test=<?php echo $row_test['path']; ?>&num=<?php echo $row_test['num']; ?>" role="button">Пройти тест</a></p>
  <?php } // Show if recordset empty ?>
        </div>
		
      <?php } while ($row_test =  /* fixed MMiC */ mysqli_fetch_assoc($test)); ?>
	<?php if($row_test1['kolv']==$row_test2['kolv']){ ?>
		
		<div class="col-lg-4 col-sm-12 text-center shadow-sm"  style="min-height: 800px"><img class="img-circle" alt="140x140" style="width: 140px; height: 140px;" src="zav.jpg" data-holder-rendered="true">
         <h5  style="min-height: 50px"><?php echo $row_test['nazv']; ?></h5>
          <p>.</p>
          <?php //if (($row_test['otv_col'] < $row_test['votv_col'])and( $row_test['activ']==1))

{  ?>
  <p class="text-center">
	  <form method="post">
		  <input type="hidden" value="1" name="zav">
		  
		  
		   <input type="submit" class="btn btn-info form-control" value="завершить обучение">
         
		  </form>
	 </p>
  <?php } // Show if recordset empty ?>
      </div>
		  <?php }  ?>
		
    </div>
    <div class="row"></div>
    <div class="row"></div>
    
  </div>
<?php } ?>  
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
 <?php //echo $query_test;?>
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
