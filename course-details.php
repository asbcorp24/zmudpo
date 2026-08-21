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

$curs=-1;
if (isset($_GET['curs']))$curs=(int)$_GET['curs'];
$query_spec = "SELECT 
  `tm_spec_zn`.`num`,
  `tm_spec_dop`.`nazv`,
  `tm_spec_zn`.`znach`,
  `tm_spec_dop`.`type`
FROM
  `tm_spec_zn`
  INNER JOIN `tm_spec_dop` ON (`tm_spec_zn`.`spec_dop` = `tm_spec_dop`.`num`)
WHERE
  `tm_spec_zn`.`spec` = $curs AND 
  `tm_spec_dop`.`type` = 0
  ";
$spec1 =  /* fixed MMiC */ DB::Query($query_spec, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_spec1 = mysqli_fetch_assoc($spec1);
$totalRows_spec1 =  /* fixed MMiC */ mysqli_num_rows($spec1);

$query_spec2 = "SELECT 
  `tm_spec_zn`.`num`,
  `tm_spec_dop`.`nazv`,
  `tm_spec_zn`.`znach`,
  `tm_spec_dop`.`type`
FROM
  `tm_spec_zn`
  INNER JOIN `tm_spec_dop` ON (`tm_spec_zn`.`spec_dop` = `tm_spec_dop`.`num`)
WHERE
  `tm_spec_zn`.`spec` = $curs AND 
  `tm_spec_dop`.`type` = 1
  ";
$spec2 =  /* fixed MMiC */ DB::Query($query_spec2, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_spec2 = mysqli_fetch_assoc($spec2);
$totalRows_spec2 =  /* fixed MMiC */ mysqli_num_rows($spec2);

$sql="SELECT 
  `tm_spec`.`num`,
  `tm_spec`.`nazv`,
  `tm_spec`.`dat`,
  `tm_spec`.`img`,
  `tm_spec`.`actiiv`,
  `tm_spec`.`zap`,
  `tm_spec`.`kr`,
  `tm_spec`.`razdel`,
  `tm_spec`.`kategor`,
  `tm_spec`.`chas`, `tm_spec`.`cena`
FROM
  `tm_spec`
WHERE
  `tm_spec`.`num` = $curs";
$spec3 =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_spec3 = mysqli_fetch_assoc($spec3);
$totalRows_spec3 =  /* fixed MMiC */ mysqli_num_rows($spec3);

$tmp=0;
$sql="SELECT DISTINCT 
  COUNT(`tm_spec`.`kategor`) AS skt,
  `tm_spec`.`kategor`
FROM
  `tm_spec`
WHERE
  `tm_spec`.`kategor` IS NOT NULL
GROUP BY
  `tm_spec`.`kategor`";
$spec4 =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_spec4 = mysqli_fetch_assoc($spec4);
$totalRows_spec4 =  /* fixed MMiC */ mysqli_num_rows($spec4);

$sql="SELECT 
  `tm_prepod`.`fio`,
  `tm_prepod`.`text`,
  `tm_prepod`.`foto`,
  `tm_prepod`.`tel`,
  `tm_prepod`.`mail`
FROM
  `tm_prepod_spec`
  INNER JOIN `tm_prepod` ON (`tm_prepod_spec`.`prepod` = `tm_prepod`.`num`)
WHERE
  `tm_prepod_spec`.`spec` = $curs";
$spec5 =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_spec5 = mysqli_fetch_assoc($spec5);
$totalRows_spec5 =  /* fixed MMiC */ mysqli_num_rows($spec5);
?>

<!doctype html>
<html class="no-js" lang="zxx">


<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Курсы</title>
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
		.sidebar-title {
    background-color: #4457c0;
}
	button.submit {
    background-color: #4457c0;
}
		</style>
    <!-- Modernizr JS -->
    <script src="js/vendor/modernizr-2.8.3.min.js"></script>
</head>

<body style="background-image:url('2017_01.jpg');background-repeat: space">
    <!--[if lt IE 8]>
    <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->  
    <!-- Body main wrapper start -->
    <div>
        <!-- Start of header area -->
       <header>
 <?php include('header.php');?>
</header>
    
	
	
	<!--[if lt IE 8]>
    <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->  
    <!-- Body main wrapper start -->
    <div class="wrapper">
        <!-- Start of header area -->
        
        <!-- mobile-menu-area start -->
        
        <!-- mobile-menu-area end -->
        <!-- End of header area -->
  
        <!-- Start page content -->
        <section class="courses-details pt-110 pb-80">
            <div class="container">
                <div class="row">
                    <div class="col-md-8 col-sm-8">
                        <div class="course-left-sidebar">
                            <div class="course-details-img2">
                                <img alt="" src="imgb.php?img=timg/<?php echo $row_spec3['img']; ?>" style="max-width: 770px" >
                                <div class="blog-meta">
                                    <span class="published3">
                                        <i class="icofont icofont-ui-calendar"></i>
                                       <?php echo $row_spec3['dat']; ?>
                                    </span>
                                    <span class="published4">
                                        <a href="#">
                                            <i class="icofont icofont-comment"></i>
                                            20
                                        </a>
                                           <a>стоимость:<?php echo $row_spec3['cena']; ?></a>
                                    </span>
                                </div>
                                <div class="free-area">
                                    <div class="free-text">
                                        <p><?php echo $row_spec3['nazv']; ?></p>
                                    </div>
                                    <div class="free-button">
                                     
                                       <form action="index.php#reg" method="get" enctype="application/x-www-form-urlencoded">
                                    <?php if($row_spec3['actiiv']==1){  ?>
                                     <button  type="submit" class="submit" method="GET">Записаться на курс </button> <?php } ?>
                                     <input type="hidden" name="sp" value="<?php echo $row_spec3['num'];?>"></input>
                                     </form>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-7 col-sm-12">
                                    <div class="courses-information gray-bg">
                                        <h3 class="sidebar-title">Информация о курсе </h3>
                                        <ul class="sidebar-menu">
											  <?php do { ?>
       
        
     
                                            <li><?php echo $row_spec1['nazv']; ?><span><?php echo $row_spec1['znach']; ?></span></li>
                                            
											   <?php } while ($row_spec1 = mysqli_fetch_assoc($spec1)); ?>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-md-5 col-sm-12">
                                    <div class="courses-lectures gray-bg course-sm mrg-xs">
                                        <h3 class="sidebar-title">Лектор курса </h3>
                                        <div class="lectures-details">
                                            <img alt="" src="pimg/<?php echo $row_spec5['foto']; ?>" height="80" width="80">
                                            <h3><?php echo $row_spec5['fio']; ?></h3>
                                            <p><?php echo $row_spec5['text']; ?></p>
                                            <ul>
                                                <li>
                                                    <a href="#">
                                                        <i class="icofont icofont-social-pinterest"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#">
                                                        <i class="icofont icofont-social-facebook"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#">
                                                        <i class="icofont icofont-social-dribbble"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#">
                                                        <i class="icofont icofont-social-tumblr"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div> 
								  <?php do { ?>
       
        
     
                                           
                                            
											
                            <div class="about-lectures mt-40 mb-40">
                                <h3><?php echo $row_spec2['nazv']; ?></h3>
                                <p><?php echo $row_spec2['znach']; ?></p>
                            </div>
                              <?php } while ($row_spec2 = mysqli_fetch_assoc($spec2)); ?>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <div class="course-sidebar mrg-xs">
                            <div class="course-categoris">
                                <h3 class="cate-title">Категории</h3>
                                <ul>
                                     <?php do { ?>
       
        
     
                                            <li><?php echo $row_spec4['kategor']; ?><span><?php echo $row_spec4['skt']; ?></span></li>
                                            
											   <?php } while ($row_spec4 = mysqli_fetch_assoc($spec4)); ?>
                                </ul>
                            </div>
                           
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End page content -->
        <!-- Start footer area -->
       
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
    <script src="js/vendor/jquery-1.12.0.min.js"></script>
    <!-- Bootstrap framework js -->
    <script src="js/bootstrap.min.js"></script>
    <!-- ajax-mail JS
    ============================================ -->		
    <script src="js/ajax-mail.js"></script>
    <!-- All js plugins included in this file. -->
    <script src="js/plugins.js"></script>
    <script src="js/main.js"></script>

</body>

</html>