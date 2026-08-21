<?php require_once('Connections/testmed.php'); ?>
<?php

if (!isset($_SESSION)) {
  session_start();
}
function getExtension1($filename) {
    return end(explode(".", $filename));
  }
$sql="SELECT 
  `tm_teg`.`id`,
  `tm_teg`.`tag`,
  `tm_teg`.`act`
FROM
  `tm_teg`
WHERE
  `tm_teg`.`act` = 1";

$img2 =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_img2 =  /* fixed MMiC */ mysqli_fetch_assoc($img2);
$totalRows_img2=  /* fixed MMiC */ mysqli_num_rows($img2);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Галерея работ</title>
<!-- Bootstrap -->

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
<link rel="stylesheet" href="css/bootstrap.css">
<script type="text/javascript" src="ScriptLibrary/jquery-latest.pack.js"></script>
<link href="css/bootstrap-3.3.7.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap-responsive.css" />
	<script type="text/javascript" src="bootstrap/js/bootstrap.js"></script>
	    <link href="css/star-rating.css" media="all" rel="stylesheet" type="text/css"/>
<style>
	 .thumb img { 
      filter: none; /* IE6-9 */ 
      -webkit-filter: grayscale(0); 
      border-radius:5px; 
      background-color: #fff; 
      border: 1px solid #ddd; 
      padding:5px; 
    } 
    .thumb img:hover { 
      filter: gray; /* IE6-9 */ 
      -webkit-filter: grayscale(1); 
    } 
    .thumb { 
      padding:5px; 
     height:250px;
    } 
  </style> 

	</style>
</head>
<body>
<?php include("header.php");?>

<!-- HEADER --><!-- / HEADER --> 

<!--  SECTION-1 -->
<section>
  <div class="row">
    <div class="col-lg-12 page-header">
      <h2 class="text-center">Внеурочная и воспитательная деятельность</h2>
      
 
    <div class="container">
   <div class="panel panel-default">
  
  <div class="panel-body" id="zagr">
 <div class="btn-group">
 <a href="galereya.php" class="btn btn-success"  style="margin:3px">Все</a>
    
      <?php      do { ?>
      <a href="?razd=<?php echo $row_img2['id']; ?>" class="btn btn-success" style="margin:3px"><?php echo $row_img2['tag']; ?></a>
    
        <?php } while ($row_img2 = mysqli_fetch_assoc($img2)); ?>
 
</div>
  </div>
</div>
</div>

    
    </div>
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

<!--  <script src="js/bootstrap-3.3.7.js"></script> --> 
<link rel="stylesheet" href="fancybox/jquery.fancybox.css" type="text/css" media="screen" />
<!-- Подключение JS файла Fancybox -->
<script type="text/javascript" src="fancybox/jquery.fancybox.js"></script>
<script src="js/star-rating.js" type="text/javascript"></script>
<script type="text/javascript"> 

$(function() {
	li=0;razd='';
	<?php if (isset($_GET['razd'])) echo "razd='&razd=".$_GET['razd']."';"; ?>
	$.get('galler.php?lim='+li+razd, function(data) {
				$('#zagr').append(data);
		 
		
			li+=6;
	});
	

	
	$('[data-fancybox="images"]').fancybox({
  buttons : [ 
    'slideShow',
    'share',
    'zoom',
    'fullScreen',
    'close'
  ],
  thumbs : {
    autoStart : true
  }
});
	   
	   var loading = false;
	$(window).scroll(function() 
{
    
      if((($(window).scrollTop()+$(window).height())+50)>=$(document).height())
    // if  ($(window).scrollTop() == $(document).height() - $(window).height()) 
     { 
     	if(loading == false){
         loading = true;
      //   alert('dssd');
		$.get('galler.php?lim='+li+razd, function(data) {
				$('#zagr').append(data);
			li+=6;
		//	console.log(li); 
		 loading = false;	
		});
	  

     }
     	
     
     }
});
  
});	
</script>

</body>
</html>
<?php

?>
