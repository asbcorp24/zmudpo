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








/* fixed MMiC */ mysqli_select_db(DB::$link, $database_testmed);
$query_spec = "SELECT * FROM tm_typsv";
$spec =  /* fixed MMiC */ DB::Query($query_spec, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_spec =  /* fixed MMiC */ mysqli_fetch_assoc($spec);
$totalRows_spec =  /* fixed MMiC */ mysqli_num_rows($spec);


$sql="SELECT DISTINCT 
  `tm_spec`.`razdel`
FROM
  `tm_spec` where `tm_spec`.`razdel` IS NOT NULL order by   `tm_spec`.`razdel`";
$razdel =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_razdel =  /* fixed MMiC */ mysqli_fetch_assoc($razdel);
$totalRows_razdel =  /* fixed MMiC */ mysqli_num_rows($razdel);

$sqla="SELECT DISTINCT 
  `tm_spec`.`kategor`
FROM
  `tm_spec`
WHERE
  `tm_spec`.`kategor` IS NOT NULL order by  `tm_spec`.`kategor`";

$kateg =  /* fixed MMiC */ DB::Query($sqla, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_kateg =  /* fixed MMiC */ mysqli_fetch_assoc($kateg);
$totalRows_kateg =  /* fixed MMiC */ mysqli_num_rows($kateg);
//echo $totalRows_kateg;
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
        <!-- mobile-menu-area start -->
       
        <!-- mobile-menu-area end -->
        <!-- End of header area -->
      
        <!-- Start page content -->
		
        <section class="top-courses pt-110 pb-80">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="courses-area bg-opacity bg-relative ptb-40 mb-60"  style="background-color:#4457c0">
                            <div class="courses pr-100 pl-100">
                                <form class="ordering">
                                    <div class="orderby-wrapper">
                                        <select name="razdel" class="orderby" id="razdel">
                                            <option value="-1" selected='selected'>Все разделы*</option>
                                          <?php do { ?>
										 <option value="<?php echo $row_razdel['razdel'];?>"><?php echo $row_razdel['razdel'];?></option>
											
											 <?php } while ($row_razdel =  /* fixed MMiC */ mysqli_fetch_assoc($razdel)); ?>
                                        </select>
                                    </div>
                                    <div class="orderby-wrapper mrg-chosen">
                                        <select name="kateg" class="orderby" id="kateg">
                                          <option value="-1" selected='selected'>Все категории*</option>
                                          <?php do { ?>
										 <option value="<?php echo $row_kateg['kategor'];?>"><?php echo $row_kateg['kategor'];?></option>
											
											 <?php } while ($row_kateg =  /* fixed MMiC */ mysqli_fetch_assoc($kateg)); ?>
                                        </select>
                                    </div>
                                
                                    <div class="chosen-submit">
                                        <a class="button extra-small" href="#" id="bttb">
                                            <span>Найти курсы</span>
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" id="indo">
				<?php include('cs.php'); ?>
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
    <!-- Bootstrap framework js -->
    	 <script src="js/jquery-1.11.3.min.js"></script>
	<script src="js/bootstrap-3.3.7.js"></script>
    <!-- ajax-mail JS
    ============================================ -->		
  
		
  <script type="text/javascript">
	$(function() {
		fl=0;
		
	
		$('#pch').on('click',function(e){
			
		$('#naa').html($("#spec :selected").val());
		$('#pe').printThis();
		
		
	});		
		
		
		$('#bttb').on('click',function(e){
	
			e.preventDefault();	
		razdel=$("#razdel :selected").val();
				kateg=$("#kateg :selected").val();
			num=$(this).data('num')
				
			$.post('cs.php', {'gt':'1', 'num' :num,'razdel':razdel,'kateg':kateg},
		function(data) {
		$('#indo').html(data);	
	
		});});
		

		///////////
		

	$('body').on('click', '.tbt',function(e){
			e.preventDefault();
			num=$(this).data('num')
				
			$.post('cs.php', {'gt':'1', 'num' :num},
		function(data) {
		$('#indo').html(data);	
	
		});});});
	
	
	</script>

</body>

</html>