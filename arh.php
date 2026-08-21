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


$query_spec = "SELECT DISTINCT
  `tm_spec`.`num`,
  `tm_spec`.`nazv`
FROM
  `tm_nmo_razd_media`
  INNER JOIN `tm_nmo_razd` ON (`tm_nmo_razd_media`.`tm_nmo_razd` = `tm_nmo_razd`.`id`)
  INNER JOIN `tm_spec` ON (`tm_nmo_razd`.`spec` = `tm_spec`.`num`)
WHERE
  `tm_nmo_razd_media`.`obyaz` = 1 and  `tm_nmo_razd_media`.`tip`=7";

$spec01 =  /* fixed MMiC */ DB::Query($query_spec, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_spec01 =  /* fixed MMiC */ mysqli_fetch_assoc($spec01);
$totalRows_spec01 =  /* fixed MMiC */ mysqli_num_rows($spec01);

$gd=0;
if (isset($_GET['napr']))$gd=(int)$_GET['napr'];
$query_spec = "SELECT DISTINCT 
  `tm_nmo_razd_media`.`nazv`,
  `tm_nmo_razd_media`.`id`
FROM
  `tm_nmo_razd_media`
  INNER JOIN `tm_nmo_razd` ON (`tm_nmo_razd_media`.`tm_nmo_razd` = `tm_nmo_razd`.`id`)
WHERE
  `tm_nmo_razd_media`.`obyaz` = 1 AND 
  `tm_nmo_razd`.`spec` = $gd";
//
$spec =  /* fixed MMiC */ DB::Query($query_spec, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_spec =  /* fixed MMiC */ mysqli_fetch_assoc($spec);
$totalRows_spec =  /* fixed MMiC */ mysqli_num_rows($spec);
	  //echo $totalRows_spec;
if (isset($_GET['spec'])){
	$sp=(int)$_GET['spec'];
	$query_spec = "SELECT DISTINCT 
  `tm_nmo_razd_media`.`nazv`,
  `tm_nmo_razd_media`.`id`,
  `tm_konf_user_files`.`path`,
  `tm_user`.`fio`
FROM
  `tm_nmo_razd_media`
  INNER JOIN `tm_konf_user_files` ON (`tm_nmo_razd_media`.`id` = `tm_konf_user_files`.`media`)
  INNER JOIN `tm_user` ON (`tm_konf_user_files`.`user` = `tm_user`.`num`)
WHERE
  `tm_nmo_razd_media`.`id` =".$sp;
$stud2 =  /* fixed MMiC */ DB::Query($query_spec, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_stud2 =  /* fixed MMiC */ mysqli_fetch_assoc($stud2);

}


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
    
<script>
// * Dependencies * 
// this function requires the following snippets:
// JavaScript/Randomizers/randomNumber
// JavaScript/conversions/base_conversion/decToHex
	
function randomBgColor()
{
  var r,g,b;
  r = decToHex(randomNumber(256)-1);
  g = decToHex(randomNumber(256)-1);
  b = decToHex(randomNumber(256)-1);
  document.bgColor = "#" + r + g + b;
}

</script>    


</head>
<body>
<?php include("header.php");?>
<div class="container"></div>
<hr>
 <div class="container">
  <div class="row text-center">
	
<ul class="nav nav-pills">
	<li class="active"><a href="arh.php"><i class="glyphicon glyphicon-th"></i> Архивы работ</a></li>
  <li><a href="arh_dipl.php"><i class="glyphicon glyphicon-list-alt"></i> Архивы документов</a></li>
 
</ul>	  
	  
	  
<h2 class="text-center">Архив работ</h2>
     <p><?php echo $row_spec0['naz']?></p>
     
      <?php if($totalRows_spec01>0){?>
          <div >
<div class="btn-group">
    <button id="btt" type="button" class="btn btn-success btn-lg dropdown-toggle btn-block"  data-toggle="dropdown" style="min-width: 200px">
        Направления   <span class="caret"></span>
    </button>
      
        
         <ul class="dropdown-menu" role="menu">
    
      <?php do { ?>
          <li><a href="?napr=<?php echo $row_spec01['num']; ?>"><?php echo $row_spec01['nazv']; ?></a></li>
        <?php } while ($row_spec01 =  /* fixed MMiC */ mysqli_fetch_assoc($spec01)); ?>
        
    </ul>
</div>

	  	  </div>
	  	 <?php }

	  ?> <br>
	  	 
      <?php if($totalRows_spec>0) {?>
          <div >
			  
<div class="btn-group">
    <button id="btt" type="button" class="btn btn-success btn-lg dropdown-toggle btn-block"  data-toggle="dropdown" style="min-width: 200px">
       Разделы  <span class="caret"></span>
    </button>
      
        
         <ul class="dropdown-menu" role="menu">
    
      <?php do { ?>
          <li><a href="?napr=<?php echo $_GET['napr']; ?>&spec=<?php echo $row_spec['id']; ?>"><?php echo $row_spec['nazv']; ?> </a></li>
        <?php } while ($row_spec =  /* fixed MMiC */ mysqli_fetch_assoc($spec)); ?>
        
    </ul>
</div>

	  	  </div>
	  	 <?php } ?> 
	  	   <?php if(isset($_GET['spec'])and (!isset($_GET['stud']))){?>
<div >
<div class="btn-group">
  <ul class="dropdown-menu text-center form-control" role="menu">
    
    <?php do { ?>
    <li><a href="?god=<?php echo $_GET['god']; ?>&spec=<?php echo $_GET['spec']; ?>&stud=<?php echo $row_stud['num']; ?>"><?php echo $row_stud['fam']; ?> <?php echo mb_substr($row_stud['name'],0,1,'UTF-8'); ?>.<?php echo mb_substr($row_stud['otch'],0,1,'UTF-8'); ?>.</a></li>
        <?php } while ($row_stud =  /* fixed MMiC */ mysqli_fetch_assoc($stud)); ?>
        
  </ul>
</div>

	  	  </div>
	  	 <?php } ?> 
	  	 <br>
<div class="table-responsive"> 
<table class="table table-bordered table-striped">
	  	     <tbody>
	  	       <tr>
	  	         <th>ФИО</th>
	  	         <th>Ссылка</th>
  	           </tr>
				   <?php do { ?>
  
	  	       <tr>
	  	         <td><?php echo $row_stud2['fio']; ?></td>
	  	         <td><a href="<?php echo $row_stud2['path']; ?>" target="_blank"><?php echo $row_stud2['path']; ?></a></td>
  	           </tr>
				 
        <?php } while ($row_stud2 =  /* fixed MMiC */ mysqli_fetch_assoc($stud2)); ?>
  </tbody>
</table>
	  </div>
<hr>
<div class="container">
<hr>
<h2 class="text-center">&nbsp;</h2>
<footer class="text-center">
  <div class="container">
    <div class="row">
      <div class="col-xs-12">
        <p></p>
      </div>
    </div>
  </div>
</footer>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) --> 
<script src="js/jquery-1.11.3.min.js"></script> 
<!-- Include all compiled plugins (below), or include individual files as needed --> 
<script src="js/bootstrap.js"></script>
</body>
</html>
<?php
 /* fixed MMiC */ mysqli_free_result($spec);
?>