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
$query_spec = "SELECT * FROM tm_spec WHERE actiiv = 1";
$spec =  /* fixed MMiC */ DB::Query($query_spec, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_spec =  /* fixed MMiC */ mysqli_fetch_assoc($spec);
$totalRows_spec =  /* fixed MMiC */ mysqli_num_rows($spec);

$query_spec = "SELECT distinct comment FROM tm_docs where comment<>''";
$specda =  /* fixed MMiC */ DB::Query($query_spec, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_specda =  /* fixed MMiC */ mysqli_fetch_assoc($specda);
$totalRows_specda =  /* fixed MMiC */ mysqli_num_rows($specda);


$query_spec = "SELECT 
  `tm_docs`.`num`,
  `tm_docs`.`spec`,
  `tm_docs`.`path`,
  `tm_docs`.`dat`,
  `tm_docs`.`nazv`,
  `tm_docs`.`comment`,
  `tm_docs`.`comm`,
  `tm_docs`.`img`
FROM
  `tm_doc_spec`
    RIGHT OUTER JOIN  `tm_docs` ON (`tm_doc_spec`.`doc` = `tm_docs`.`num`)
 where  ";
if (isset($_GET['spec']) and ($_GET['spec']!='-1') )$query_spec=$query_spec." (`tm_docs`.`comment`=".GetSQLValueString($_GET['spec'],'text').")"; else $query_spec=$query_spec." (`tm_docs`.`spec`<>-100)"; 
//echo $query_spec;
$startRow_dosc=0;
$maxRows_dosc=6;
if(isset($_GET['s']))
$startRow_dosc=$_GET['s'];
if(isset($_GET['p']))	
$maxRows_dosc=$_GET['p'];
$specd =  /* fixed MMiC */ DB::Query($query_spec, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$totalRows_spec =  /* fixed MMiC */ mysqli_num_rows($specd);


$query_limit_dosc = sprintf(" %s order by spec asc,dat desc LIMIT %d, %d", $query_spec, $startRow_dosc, $maxRows_dosc);
//WHERE `tm_docs`.`spec` = 1 ORDER BY   `tm_docs`.`dat` DESC

$specd =  /* fixed MMiC */ DB::Query($query_limit_dosc, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_doc =  /* fixed MMiC */ mysqli_fetch_assoc($specd);


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Документы</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="images/favicon.jpg">
    
    <!-- All css files are included here. -->
    <!-- Bootstrap fremwork main css -->
    <link rel="stylesheet" href="css/bootstrap-3.3.7.css">
    <!-- This core.css file contents all plugings css file. -->
    <link rel="stylesheet" href="css/core.css">
    <!-- Theme shortcodes/elements style -->
    <link rel="stylesheet" href="css/shortcode/shortcodes.css">
    <!-- Theme main style -->
    <link rel="stylesheet" href="style.css">
    <!-- Responsive css -->
    <link rel="stylesheet" href="css/responsive.css">
    <!-- Style customizer (Remove these two lines please) -->
    <link rel="stylesheet" href="css/color/color-1.css">
<style>
	element.style {
    background-color: #4457c0;
}
</style>
</head>

<body>
    <!--[if lt IE 8]>
    <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->  
    <!-- Body main wrapper start -->
    <div class="wrapper">
        <!-- Start of header area -->
      	 <header class="header-area">
          
			 <?php include('header.php');?>
        </header>
        <!-- End of header area -->
       
        <!-- Start page content -->
        <section class="top-courses pt-110 pb-80">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="courses-area bg-2 bg-opacity bg-relative ptb-40 mb-60"  style="background-color:#4457c0">
                            <div class="courses pr-100 pl-100">
                              <form class="ordering" method="get" id="myForm" >
                                    <div class="orderby-wrapper">
										 <select name="specr" class="orderby">
                                           <option value="-1" selected='selected'>Любое направление</option>
                                     
										 <?php do { ?>
										 
                                            <option value="<?php echo $row_spec['num']; ?>"><?php echo $row_spec['nazv']; ?></option>
                                          
        
        <?php } while ($row_spec =  /* fixed MMiC */ mysqli_fetch_assoc($spec)); ?>
                                          </select>
                                    </div>
                                    <div class="orderby-wrapper">
										 <select name="spec" class="orderby">
                                           <option value="-1" selected='selected'>Любой раздел</option>
                                     
										 <?php do { ?>
										 
                                            <option value="<?php echo $row_specda['comment']; ?>"><?php echo $row_specda['comment']; ?></option>
                                          
        
        <?php } while ($row_specda =  /* fixed MMiC */ mysqli_fetch_assoc($specda)); ?>
                                      </select>
                                    </div>
                                   <a class="button extra-small" href="#" id="ser">
                                    <span>Найти</span>
                                </a>
                                  
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                   
                      <?php if ($totalRows_spec>0)do { ?>
         
         <div class="col-md-4 col-lg-3 col-sm-6 ">
                        <div class="blog-all mrg-xs" >
                            <div class="blog-img">
                            	<?php if(($row_doc['img']==null)or ($row_doc['img']=='null') ){?> <a href="#"><img src="422580.jpg" alt="" height="160"></a><?php } else {?>
                                <a href="#"><img src="timg/<?php echo $row_doc['img']; ?>" alt="" height="160"></a><?php } ?>
                            </div>
                            <div class="blog-details white-bg card-shadow"  >
                                <h3 style="min-height: 70px"><a href="#"><?php echo $row_doc['nazv']; ?></a></h3>
                             
                                <p  style="min-height:110px"><?php echo $row_doc['comm']; ?> </p>
								
								<?php 
          	
          	if (strripos($row_doc['path'],"http")===false ){
          	?>
          	<a href="docs/<?php echo $row_doc['path']; ?>" target="_blank"  class="button extra-small" role="button">  <span>Скачать</span></a></p>          	
          	<?php } else { ?>
<a href="<?php echo $row_doc['path']; ?>" target="_blank"  class="button extra-small" role="button">  <span>Скачать</span></a></p>

        <?php } ?>
								
                                
                            </div>
                        </div>
                    </div>
					
    
    <?php } while ($row_doc =  /* fixed MMiC */ mysqli_fetch_assoc($specd)); ?>
                    <div class="col-md-12 text-center pt-30">
                        <div class="pages2">
                            <ul>
                                <li><a href="#">01</a></li>
                                <li><a href="#">02</a></li>
                                <li class="active"><a href="#">03</a></li>
                                <li><a href="#">04</a></li>
                                <li><a href="#">05</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End page content -->
        <!-- Start footer area -->
            <?php include('footer.php');  ?>
        <!-- End footer area -->
        <!-- start scrollUp
        ============================================ -->
        <div id="toTop">
            <i class="fa fa-chevron-up"></i>
        </div>
    </div>
    <!-- Body main wrapper end -->
    
    
    
    
    
    <!-- Placed js at the end of the document so the pages load faster -->
    <!-- jquery latest version -->
 	 <script src="js/jquery-1.11.3.min.js"></script>
	<script src="js/bootstrap-3.3.7.js"></script>
 <script type="text/javascript">
	$(function() {
		fl=0;
		$('#ser').on('click',function(){
	$('#myForm').submit();
		});

});
	
	
	</script>
</body>
</html>
<?php
 /* fixed MMiC */ mysqli_free_result($spec);
?>