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
  `tm_arh_diplom`.`god`
FROM
  `tm_arh_diplom`";

$spec01 =  /* fixed MMiC */ DB::Query($query_spec, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_spec01 =  /* fixed MMiC */ mysqli_fetch_assoc($spec01);
$totalRows_spec01 =  /* fixed MMiC */ mysqli_num_rows($spec01);


if (isset($_GET['god'])){
$god=(int)$_GET['god'];	
	
$query_spec = "SELECT DISTINCT 
  `tm_arh_diplom`.`spec`
FROM
  `tm_arh_diplom` where `tm_arh_diplom`.`god`=$god";

$spec02 =  /* fixed MMiC */ DB::Query($query_spec, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_spec02 =  /* fixed MMiC */ mysqli_fetch_assoc($spec02);
$totalRows_spec02 =  /* fixed MMiC */ mysqli_num_rows($spec02);	
	
	
	
}

if (isset($_GET['spec'])){
$spec=$_GET['spec'];	
$god=(int)$_GET['god'];		
$query_spec = "SELECT DISTINCT 
 *
FROM
  `tm_arh_diplom` where `tm_arh_diplom`.`god`=$god and `tm_arh_diplom`.`spec`='$spec'";

$spec03 =  /* fixed MMiC */ DB::Query($query_spec, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_spec03 =  /* fixed MMiC */ mysqli_fetch_assoc($spec03);
$totalRows_spec03 =  /* fixed MMiC */ mysqli_num_rows($spec03);	
	
	
	
}

if (isset($_GET['num'])){
$spec=(int)$_GET['num'];	
	
$query_spec = "SELECT DISTINCT 
 *
FROM
  `tm_arh_diplom` where `tm_arh_diplom`.`num`=$spec";
//echo $query_spec;
$spec04 =  /* fixed MMiC */ DB::Query($query_spec, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_spec04 =  /* fixed MMiC */ mysqli_fetch_assoc($spec04);
$totalRows_spec04 =  /* fixed MMiC */ mysqli_num_rows($spec04);	
	
	
	
}



//echo $query_spec;
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Архив документов</title>

<!-- Bootstrap -->
<link rel="stylesheet" href="css/bootstrap.css">




</head>
<body>
<?php include("header.php");?>
<div class="container"></div>
<hr>
 <div class="container">
  <div class="row text-center form-inline">
	  <ul class="nav nav-pills">
	<li><a href="arh.php"><i class="glyphicon glyphicon-th"></i> Архивы работ</a></li>
  <li class="active"><a href="arh_dipl.php"><i class="glyphicon glyphicon-list-alt"></i> Архивы документов</a></li>
 
</ul>	  
	  
<h2 class="text-center">Архив документов</h2>
     <p><?php echo $row_spec01['naz']?></p>
     
      <?php if($totalRows_spec01>0){?>
          <div >
<div class="btn-group">
    <button id="btt" type="button" class="btn btn-success btn-lg dropdown-toggle btn-block btn-xs"  data-toggle="dropdown" style="min-width: 200px">
        Год   <span class="caret"></span>
    </button>
      
        
         <ul class="dropdown-menu" role="menu">
    
      <?php do { ?>
          <li><a href="?god=<?php echo $row_spec01['god']; ?>"><?php echo $row_spec01['god']; ?></a></li>
        <?php } while ($row_spec01 =  /* fixed MMiC */ mysqli_fetch_assoc($spec01)); ?>
        
    </ul>
</div>

	  	  </div>
	  	 <?php }

	  ?> <br>
	  	 
   <?php if($totalRows_spec02>0){?>
          <div >
<div class="btn-group">
    <button id="btt" type="button" class="btn btn-success btn-lg dropdown-toggle btn-block btn-xs"  data-toggle="dropdown" style="min-width: 200px">
        Специальность   <span class="caret"></span>
    </button>
      
        
         <ul class="dropdown-menu" role="menu">
    
      <?php do { ?>
          <li ><a href="?spec=<?php echo $row_spec02['spec']; ?>&god=<?php echo $_GET['god'];?>"><?php echo $row_spec02['spec']; ?></a></li>
        <?php } while ($row_spec02 =  /* fixed MMiC */ mysqli_fetch_assoc($spec02)); ?>
        
    </ul>
</div>

	  	  </div>
	  	 <?php }?>
	  <br>
   <?php if($totalRows_spec03>0){?>
          <div >
<div class="btn-group">
    <button id="btt" type="button" class="btn btn-success btn-lg dropdown-toggle btn-block btn-xs"  data-toggle="dropdown" style="min-width: 200px">
        Обучающийся   <span class="caret"></span>
    </button>
      
        
         <ul class="dropdown-menu" role="menu">
    
      <?php do { ?>
          <li><a href="?num=<?php echo $row_spec03['num']; ?>&god=<?php echo $_GET['god'];?>&spec=<?php echo $_GET['spec'];?>"><?php echo $row_spec03['fio']; ?></a></li>
        <?php } while ($row_spec03 =  /* fixed MMiC */ mysqli_fetch_assoc($spec03)); ?>
        
    </ul>
</div>

	  	  </div>
	  	 <?php }	  
	  

	  ?> <br>
	</div>
	 </div>
	  	 

<hr>
<div class="container">
<hr>
<h2 class="text-center"><?php echo $row_spec04['fio'];?></h2>
<footer class="text-center">
  <div class="container">
    <div class="row">
      <div class="col-xs-12">
      <?php if($totalRows_spec04>0){ ?>
      <img src="arh/<?php echo $row_spec04['path'];?>" width="80%" alt=""/>
      
      <?php }?>
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