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

$query_spec = "SELECT * FROM tm_spec where zap=1 order by kategor";
$spec1 =  /* fixed MMiC */ DB::Query($query_spec, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_spec1 =  /* fixed MMiC */ mysqli_fetch_assoc($spec1);
$totalRows_spec1 =  /* fixed MMiC */ mysqli_num_rows($spec1);

$query_spec = "SELECT   `tm_news`.`num`,`tm_news`.`nazv`,`tm_news`.`content`,`tm_news`.`img`,`tm_news`.`dat` FROM   `tm_news` order by `tm_news`.`dat` DESC  LIMIT 5";
$news =  /* fixed MMiC */ DB::Query($query_spec, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_news =  /* fixed MMiC */ mysqli_fetch_assoc($news);
$totalRows_news =  /* fixed MMiC */ mysqli_num_rows($news);
$speca="SELECT 
  `tm_spec`.`num`,
  `tm_spec`.`nazv`,
  `tm_spec`.`dat`,
  `tm_spec`.`img`,
  `tm_spec`.`actiiv`,
  `tm_spec`.`zap`,
  `tm_spec`.`kr`,
  `tm_spec`.`razdel`,
  `tm_spec`.`kategor`,
  `tm_spec`.`chas`,
  `tm_spec`.`cena`,
  `tm_spec`.`gl`
FROM
  `tm_spec`
WHERE
  `tm_spec`.`gl` = 1 AND 
  `tm_spec`.`actiiv` = 1
ORDER BY
  `tm_spec`.`dat` DESC limit 0,3";
$speca =  /* fixed MMiC */ DB::Query($speca, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_speca =  /* fixed MMiC */ mysqli_fetch_assoc($speca);
$totalRows_speca =  /* fixed MMiC */ mysqli_num_rows($speca);

$otz="SELECT 
  `tm_otziv`.`num`,
  `tm_otziv`.`dat`,
  `tm_otziv`.`nazv`,
  `tm_otziv`.`img`,
  `tm_otziv`.`comment`
FROM
  `tm_otziv`";
$otz =  /* fixed MMiC */ DB::Query($otz, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_otz =  /* fixed MMiC */ mysqli_fetch_assoc($otz);
$totalRows_otz =  /* fixed MMiC */ mysqli_num_rows($otz);


?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta content="IE=edge" http-equiv="X-UA-Compatible">
  <meta content="width=device-width,initial-scale=1" name="viewport">
  <meta content="description" name="description">
  <meta name="google" content="notranslate" />
  <meta content="ASBCORP" name="author">

  <!-- Disable tap highlight on IE -->
  <meta name="msapplication-tap-highlight" content="no">
  
  
  <link rel="apple-touch-icon" sizes="180x180" href="./assets/apple-icon-180x180.png">
  <link href="./favicon.ico" rel="icon">
<link href="https://fonts.googleapis.com/css?family=Forum" rel="stylesheet">

<style type="text/css">
    .input-group-addon.primary {
    color: rgb(255, 255, 255);
    background-color: rgb(50, 118, 177);
    border-color: rgb(40, 94, 142);
}
.input-group-addon.success {
    color: rgb(255, 255, 255);
    background-color: rgb(92, 184, 92);
    border-color: rgb(76, 174, 76);
}
.input-group-addon.info {
    color: rgb(255, 255, 255);
    background-color: rgb(57, 179, 215);
    border-color: rgb(38, 154, 188);
}
.input-group-addon.warning {
    color: rgb(255, 255, 255);
    background-color: rgb(240, 173, 78);
    border-color: rgb(238, 162, 54);
}
.input-group-addon.danger {
    color: rgb(255, 255, 255);
    background-color: rgb(217, 83, 79);
    border-color: rgb(212, 63, 58);
}    
	.checkbox {
	position: absolute;
	z-index: -1;
	opacity: 0;
	margin: 10px 0 0 20px;
}
.checkbox + label {
	position: relative;
	padding: 0 0 0 60px;
	cursor: pointer;
}
.checkbox + label:before {
	content: '';
	position: absolute;
	top: -4px;
	left: 0;
	width: 50px;
	height: 26px;
	border-radius: 13px;
	background: #CDD1DA;
	box-shadow: inset 0 2px 3px rgba(0,0,0,.2);
	transition: .2s;
}
.checkbox + label:after {
	content: '';
	position: absolute;
	top: -2px;
	left: 2px;
	width: 22px;
	height: 22px;
	border-radius: 10px;
	background: #FFF;
	box-shadow: 0 2px 5px rgba(0,0,0,.3);
	transition: .2s;
}
.checkbox:checked + label:before {
	background: #9FD468;
}
.checkbox:checked + label:after {
	left: 26px;
}
.checkbox:focus + label:before {
	box-shadow: inset 0 2px 3px rgba(0,0,0,.2), 0 0 0 3px rgba(255,255,0,.7);
}
	
	
	</style>
  <title>ОТДЕЛЕНИЕ ДОПОЛНИТЕЛЬНОГО ОБРАЗОВАНИЯ</title>
<style>
	header {
 font-family: 'Forum', -apple-system, system-ui, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
}
</style>
	
	   <link rel="stylesheet" href="css/core.css">
    <!-- Theme shortcodes/elements style -->
    <link rel="stylesheet" href="css/shortcode/shortcodes.css">
    <!-- Theme main style -->
    <link rel="stylesheet" href="style.css">
    <!-- Responsive css -->
    <link rel="stylesheet" href="css/responsive.css">
    <!-- Style customizer (Remove these two lines please) -->
    <link rel="stylesheet" href="css/color/color-1.css">
	 <link href="css/bootstrap-select.min.css" rel="stylesheet">
 <style>
		.sidebar-title {
    background-color: #4457c0;
}
	button.submit {
    background-color: #4457c0;
}
		</style>

</head>


<body> <!-- Add your content of header -->
<header>
 <?php include('header.php');?>
</header>

<!-- Add your site or app content here -->
  <div class="hero-full-container background-image-container white-text-container">
    <div class="container">
      <div class="row">
        <div class="col-xs-12">
          <h1>ГАПОУ ЗМК</h1>
          <p>ОТДЕЛЕНИЕ ДОПОЛНИТЕЛЬНОГО ОБРАЗОВАНИЯ </p>
             <p>
организовано в составе колледжа и функционирует с 1992 года.
Отделение осуществляет последипломное обучение по специальностям
 

«Лечебное дело»; «Сестринское дело»; «Сестринское дело в педиатрии»; «Акушерское дело»; «Анестезиология и реаниматология»; «Общая практика»; «Физиотерапия»; «Медицинский массаж» в виде специализации или усовершенствования на циклах повышения квалификации (по 32 тематикам) или тематических циклах на базе среднего медицинского образования на бюджетной и внебюджетной основе. К преподаванию на отделении привлекаются кандидаты медицинских наук, штатные преподаватели училища, квалифицированные врачи – клиницисты.<br/>



</p>
         
          
        </div>
      </div>
    </div>
  </div>
<div class="" style="padding-bottom: 150px">
            <div class="container">
              <div class="row">
                  <div class="col-md-12">
                        <div class="section-title text-center pt-110 mb-60">
                            <h1 class="uppercase">Новые курсы и конференции</h1>
                            <p>Текущие курсы вы сможете посмотреть в разделе</p>
                          
                        </div>
                </div>
              </div>
                <div class="row">
					<?php  $i=0;do { $i++; ?>
                    <div class="col-md-4 col-sm-6 ">
                        <div class="news-are card-shadow">
                          <div class="news-img">
                                <img src="/timg/<?php echo $row_speca['img'];?>" width="370" height="250" alt="">
                                <div class="news-date navy-bg">
                                    <div class="blog-meta-2">
                                        <span class="published3">
                                            <i class="icofont icofont-ui-calendar"></i>
                                            <?php echo $row_speca['dat'];?>
                                        </span>
                                    </div>
                                    <div class="blog-meta for-news">
                                        <span class="published3">
                                            <a href="#">
                                                <i class="icofont icofont-basket"></i> <?php echo $row_speca['cena'];?>
                                            </a>
                                        </span>
                                        <span class="published4">
                                            <a href="#">
                                                <i class="icofont icofont-comment"></i> 20
                                            </a>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="img-text gray-bg" >
							
								<h3 style="min-height:30px"><a href="#"><?php echo $row_speca['kategor'];?></a></h3>
                                <p style="min-height:100px"><?php echo $row_speca['nazv'];?> </p>
                              <a class="button extra-small" href="course-details.php?curs=<?php echo $row_speca['num'];?>">
                                    <span>подробнее</span>
                                </a>
                            </div>
                        </div>
                    </div>
                     <?php } while ($row_speca =  /* fixed MMiC */ mysqli_fetch_assoc($speca)); ?>
                  
                </div>
                <div class="row">
                    <div class="col-md-12 text-center">
                      <a class="button extra-small news-btn mt-60" href="course.php" style="  background-color: #4457c0;">
                            <span>Все курсы</span>
                        </a>
                    </div>
                </div>
  </div>
</div>
	
	<br><br>
    <div class="section-container">
    <div class="container">
      <div class="row">      
          <div class="col-xs-12">


            <div id="carousel-example-generic" class="carousel carousel-fade slide" data-interval="30000" data-ride="carousel">
                
                <div class="carousel-inner" role="listbox">
<?php  $i=0;do { $i++; ?>
              
                    <div class="item <?php if ($i==1) echo "active"; ?>">
                        <img class="img-responsive" src="./newsimage/<?php echo $row_news['img'];?>" alt="First slide">
                        <div class="carousel-caption card-shadow reveal">
                          
                          <h3>  <?php echo $row_news['nazv'];?></h3>
                          <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
                            <i class="fa fa-chevron-left" aria-hidden="true"></i>
                            <span class="sr-only">Previous</span>
                          </a>
                          <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
                            <i class="fa fa-chevron-right" aria-hidden="true"></i>
                            <span class="sr-only">Next</span>
                          </a>
                          <p>
                           <?php echo $row_news['content'];?>
                          </p>
                         <h3>
                           <?php echo $row_news['dat'];?></h3>
                       
                        </div>
                    </div>
               
            
            <?php } while ($row_news =  /* fixed MMiC */ mysqli_fetch_assoc($news)); ?>
                </div>
               
            </div>

           
          </div>
          
        </div>  
      
    </div>
  </div>
<a name="reg">
 <br>
  <div class="section-container contact-container">
    <div class="container">
      <div class="row">
        <div class="col-xs-12 col-md-12">
          <div class="section-container-spacer">
            <h2 class="text-center">Регистрация</h2>
            <p class="text-center">Вы можете зарегистрироватся на курсы и добавить документы позже</p>
          </div>
          <div class="card-container">
            <dv class="card card-shadow col-xs-10 col-xs-offset-1 col-md-8 col-md-offset-2 reveal">
               <?php if ($totalRows_spec1>0){ ?>
              <form action="new_user.php" class="reveal-content"  method="post" enctype="multipart/form-data" >
              	
              		<input type="checkbox" class="checkbox" name="urlico" id="chec" value="1">
								<label for="chec">Юридическое лицо</label>
          <div class="form-group">
                       
             
                       
                        <label for="type1" class="control-label">Специальность</label>
            <select class="selectpicker  form-control show-tick" name="spec" id="type1">
            	
            	
 
             <?php $oldk="-1"; do  { 
              
             if ($oldk!=$row_spec1['kategor']) {
             	
             	if ($oldk!='-1') echo ' </optgroup>';
             	echo' <optgroup label="'.$row_spec1['kategor'].'">';$oldk=$row_spec1['kategor'];}
              ?>
               <option value="<?php echo $row_spec1['num'];?>"<?php if (!(strcmp($row_spec1['num'], $_GET['sp']))) {echo "selected=\"selected\"";} ?>>[<?php echo $row_spec1['dat'];?>] <?php echo $row_spec1['nazv'];?></option>
            
            <?php } while ($row_spec1 =  /* fixed MMiC */ mysqli_fetch_assoc($spec1)); ?>
                      </select>
                       
                        <label for="pricefrom" class="control-label">ФИО</label>
            <div class="input-group">
              <div class="input-group-addon" id="basic-addon1">ФИО</div>
              <input type="text" class="form-control" id="pricefrom" placeholder="Фамилия Имя Отчество" aria-describedby="basic-addon1" name="fio">
            </div>
                                     <label for="pricefrom" class="control-label">EMAIL</label>
           <div class="input-group">
             
              <input type="email" placeholder="user@gmail.com" class="form-control" id="pricefrom" aria-describedby="basic-addon1" name="mail"> <div class="input-group-addon" id="basic-addon1"><input type="checkbox" checked name="rss" id="rss" value="1"></div>
            </div>
              
              
             <?php if (140>200) do { ?>
 
                                    
                                     <?php if ($row_spec['typ']==0){?>
                                     <label for="pricefrom" class="control-label"><?php echo $row_spec['nazv']; ?></label>
            <div class="input-group">
              <div class="input-group-addon" id="basic-addon1"></div>
              <input type="text" list="data<?php echo $row_spec['num']; ?>"  class="form-control" id="pricefrom" aria-describedby="basic-addon1" name="fom[<?php echo $row_spec['num']; ?>]">
              
           <?php 
				
			$sql="SELECT DISTINCT
  `tm_user_sv`.`value`
  
FROM
  `tm_user_sv`
  INNER JOIN `tm_typsv` ON (`tm_user_sv`.`tm_typsv` = `tm_typsv`.`num`)
WHERE
  `tm_typsv`.`poi` = 1  and `tm_user_sv`.`tm_typsv` = ".$row_spec['num'];
				$spec11 =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));

				?>
      <datalist id="data<?php echo $row_spec['num']; ?>">
       <?php do {?>
                                                             
			<option value="<?php echo $ro1['value'] ?>"/>
			<?php } while ($ro1 = mysqli_fetch_assoc($spec11))?>
			
		</datalist>            
                                  
                                  </div><?php } ?>
                                   
                                    <?php if ($row_spec['typ']==2){?>
                                     <label for="pricefrom" class="control-label"><?php echo $row_spec['nazv']; ?></label>
            <div class="input-group">
              <div class="input-group-addon" id="basic-addon1"></div>
              <input type="date" class="form-control" id="pricefrom" aria-describedby="basic-addon1" name="fom[<?php echo $row_spec['num']; ?>]">
            </div><?php } ?>
            
              <?php if ($row_spec['typ']==3){?>
                                     <label for="pricefrom" class="control-label"><?php echo $row_spec['nazv']; ?></label>
            <div class="input-group">
              <div class="input-group-addon" id="basic-addon1"></div>
              <input type="file" class="form-control" id="pricefrom" aria-describedby="basic-addon1" name="fom[<?php echo $row_spec['num']; ?>]" accept="image/jpeg">
            </div><?php } ?>
            
            
             <?php if ($row_spec['typ']==1){?>
                                     <label for="pricefrom" class="control-label"><?php echo $row_spec['nazv']; ?></label>
            <div class="input-group">
              <div class="input-group-addon" id="basic-addon1"></div>
              <input type="number" class="form-control" id="pricefrom" aria-describedby="basic-addon1" min="15" max="100" name="fom[<?php echo $row_spec['num']; ?>]">
            </div><?php } ?>
            
            <?php } while ($row_spec =  /* fixed MMiC */ mysqli_fetch_assoc($spec)); ?>
        </div>
				  
			<label for="pricefrom2" class="control-label">капча</label>
           <div class="input-group">
             
         
              <div class="input-group-addon" id="basic-addon1" style="padding:  0px;"> <img src="getcapcha.php" class="input-block-level"></div>
          
       	 
        	  <input type="text" class="form-control" placeholder="Введите текст с изображения" aria-describedby="basic-addon1"  name="capch">
        	
        </div>
				  <br>
          <p class="text-center"><input type="submit" class="btn btn-info" value="Отправить данные">

</p>
        
<?php } ?>
              </form>
            </div>
            <div class="card-image col-xs-12" style="background-image: url('/assets/images/img-01.jpg')">
            </div>
          </div>
        </div>  
      </div>
    </div>
  </div>

<script>
  document.addEventListener("DOMContentLoaded", function (event) {
    navbarFixedTopAnimation();
  });
	</script>  <section class="testimonial-area ptb-110">
<div class="container">
                <div class="row">
                    <div class="col-md-3 col-sm-3">
                        <div class="testimonial-text">
                            <h2>Отзывы слушателей</h2>
                        </div>
                    </div>
                    <div class="col-md-9 col-sm-9">
                        <div class="slider-active2">
							<?php do{ ?>
                            <div class="testimonial-all">
                                <div class="testimonial-peragraph">
                                    <p><?php echo $row_otz['comment'] ?></p>
                                </div>
                                <div class="testimonial-img">
                                    <img alt="" src="./timg/<?php echo $row_otz['img'] ?>" >
                                    <div class="img-title navy-bg">
                                        <h3><?php echo $row_otz['nazv'] ?></h3>
                                        <p><?php echo $row_otz['dat'] ?></p>
                                    </div>
                                </div>
                            </div>
                            <?php } while ($row_otz =  /* fixed MMiC */ mysqli_fetch_assoc($otz)); ?>
                              
                        </div>
                    </div>
                </div>
            </div></section>
<footer class="footer-container white-text-container">
  <div class="container">
    <div class="row">

     
      <div class="col-xs-12">
        <h3>ASBcorp</h3>

        <div class="row">
          <div class="col-xs-12 col-sm-7">
            <p><small>сайт сделан <a href="mailto:asbcorp24@gmail.com" title="Балабанов Анатолий Сергеевич">asbcorp24@gmail.com</a></small>
            </p>
          </div>
          <div class="col-xs-12 col-sm-5">
            <p class="text-right">
              <a href="https://facebook.com/" class="social-round-icon white-round-icon fa-icon" title="">
                <i class="fa fa-facebook" aria-hidden="true"></i>
              </a>
              <a href="https://twitter.com/" class="social-round-icon white-round-icon fa-icon" title="">
                <i class="fa fa-twitter" aria-hidden="true"></i>
              </a>
              <a href="https://www.linkedin.com/" class="social-round-icon white-round-icon fa-icon" title="">
                <i class="fa fa-linkedin" aria-hidden="true"></i>
              </a>
            </p>
          </div>
        </div>
        
        
      </div>
    </div>
  </div>
</footer>

<script>
  document.addEventListener("DOMContentLoaded", function (event) {
    navActivePage();
    scrollRevelation('.reveal');
  });
</script>

<!-- Google Analytics: change UA-XXXXX-X to be your site's ID 

<script>
  (function (i, s, o, g, r, a, m) {
    i['GoogleAnalyticsObject'] = r; i[r] = i[r] || function () {
      (i[r].q = i[r].q || []).push(arguments)
    }, i[r].l = 1 * new Date(); a = s.createElement(o),
      m = s.getElementsByTagName(o)[0]; a.async = 1; a.src = g; m.parentNode.insertBefore(a, m)
  })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');
  ga('create', 'UA-XXXXX-X', 'auto');
  ga('send', 'pageview');
</script>

--> 
  <script type="text/javascript" src="ScriptLibrary/jquery-latest.pack.js"></script>
    <script type="text/javascript" src="./main.0cf8b554.js"></script>
    <script type="text/javascript" src="js/bootstrap-select.js"></script>
  <script src="js/plugins.js"></script>
 
  <script>

  	$('#chec').click( function(e) {
if ($(this).prop('checked')==true){
		$('#pricefrom').prop('placeholder','Название организации');} else {
			$('#pricefrom').prop('placeholder','ваше ФИО');
			
		}
});			
 $('.slider-active2').slick({
      centerMode: true,
      dots: true,
      centerPadding: '0',
      slidesToShow: 1,
      arrows: false,
      responsive: [
        {
          breakpoint: 1100,
          settings: {
            slidesToShow: 1,
          }
        },
        {
          breakpoint: 970,
          settings: {
            slidesToShow: 1,
          }
        },
        {
          breakpoint: 767,
          settings: {
            slidesToShow: 1,
          }
        }
      ]
    }); 
    
    /*--
    slick slider
    ------------------------*/
    $('.slider-active3').slick({
      centerMode: true,
      dots: true,
      centerPadding: '0',
      slidesToShow: 1,
      arrows: false,
      responsive: [
        {
          breakpoint: 1100,
          settings: {
            slidesToShow: 1,
          }
        },
        {
          breakpoint: 970,
          settings: {
            slidesToShow: 1,
          }
        },
        {
          breakpoint: 767,
          settings: {
            slidesToShow: 1,
          }
        }
      ]
    }); 
	
  </script>
  <style>
	.btn.focus, .btn:focus, .btn:hover {
    color: #010101;
    text-decoration: none;
}

.btn-default {
    color:#010101;
    background-color: transparent;
    border-color: #010101;
}	
	</style>
</body>

</html>