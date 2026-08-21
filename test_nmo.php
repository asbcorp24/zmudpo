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
$grp=-1;
if (isset($_GET['grp']))$grp=$_GET['grp'];
 /* fixed MMiC */ mysqli_select_db(DB::$link, $database_testmed);
$query_spec = "SELECT * FROM tm_spec WHERE actiiv = 1  and kr=$grp";
$spec =  /* fixed MMiC */ DB::Query($query_spec, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_spec =  /* fixed MMiC */ mysqli_fetch_assoc($spec);
$totalRows_spec =  /* fixed MMiC */ mysqli_num_rows($spec);

$sql="SELECT 
  `tm_spec_type`.`num`,
  `tm_spec_type`.`nazv`
FROM
  `tm_spec_type`";
$spect =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_spect =  /* fixed MMiC */ mysqli_fetch_assoc($spect);
$totalRows_spect =  /* fixed MMiC */ mysqli_num_rows($spect);
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

<!-- HEADER --><!-- / HEADER --> 

<!--  SECTION-1 -->
<section>
  <div class="row"></div>
  <hr>
 <div class="container">
  <div class="row text-center">
<h2 class="text-center">Выберите специальность</h2>

	  </div>
	  </div>

<hr>
  <div class="container ">
    <div class="row">
		
		<ul class="nav nav-tabs">

			<?php do { ?>	
			<?php if($row_spect['num']==0){ ?><li ><a href="test.php"><?php echo $row_spect['nazv']; ?></a></li><?php }?>
 <?php if($row_spect['num']>0){ ?> <li <?php if($grp==$row_spect['num']){ echo 'class="active"'; } ?>><a href="test_nmo.php?grp=<?php echo $row_spect['num']; ?>"><?php echo $row_spect['nazv']; ?></a></li><?php }?>
  <?php } while ($row_spect =  /* fixed MMiC */ mysqli_fetch_assoc($spect)); ?>

</ul>
		<br>
		 <?php  if($totalRows_spec>0){ ?>
      <?php do { ?>
        <div class="col-lg-4 col-sm-12 text-center" style="min-height: 400px"> <img class="img-circle" alt="140x140" style="width: 140px; height: 140px;" src="timg/<?php echo $row_spec['img']; ?>" data-holder-rendered="true">
         <div style="min-height:50px"> <h4><?php echo $row_spec['nazv']; ?></h4></div>
        
                    <p class="text-center"><a class="btn btn-primary btn-lg" href="login.php?spec=<?php echo $row_spec['num']; ?>" role="button">Войти в группу</a> </p>
        </div>
        <?php } while ($row_spec =  /* fixed MMiC */ mysqli_fetch_assoc($spec)); ?>
		<?php } ?>
    </div>
</div>
<!-- /container -->
  
  <div class="container"> </div>
  <!-- / CONTAINER--> 
</section>
<div class="well"> </div>

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
<?php
 /* fixed MMiC */ mysqli_free_result($spec);
?>
