<?php  if (!isset($_SESSION)) {
  session_start();
}
if (isset($_POST["pismo"])) {
	
	if (isset($_POST['capch']) and $_POST['capch']=='' ){
		
		$err=$_POST['mail']." Вы неправильно ввели капчу";
		echo $err;
	$totalRows_md=0;
		echo $err;
		exit;
		
	}  
if (isset($_POST['mail']) and $_POST['mail']=='' ){
		
		$err=$_POST['mail']." вы не ввели MAIL";
	$totalRows_md=0;
	echo $err;
		exit;
		
	} 
if ($_SESSION["code"]!=$_POST['capch']){
		
		$err=$_POST['mail']." Вы неправильно ввели капчу";
	$totalRows_md=0;
		echo $err;
		exit;
		
	}  
	
	
	
//print_r($_POST);
//Array ( [name] => Галерея [email] => 112@mail.ru [subject] => lkjdflkjlsdfsdf [pismo] => 1 [message] => )
$subject = $_POST['subject']; 

$message = 'Имя:'.$_POST['name']."\r\n mail:".$_POST['email']."\r\n ".$_POST['message']."\r\n "; 

$headers  = "Content-type: text/html; charset=utf-81 \r\n"; 
$headers ='<Eca@mail.ru>\r\n'; 


mail('centerobakbars@mail.ru', $subject, $message, $headers); 
	
echo "письмо отправлено";	
exit;	
}
?>
<!doctype html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Контакты</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="images/favicon.jpg">
    
    <!-- All css files are included here. -->
    <!-- Bootstrap fremwork main css -->
   <link href="css/bootstrap.css" rel="stylesheet">
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
 
</head>

<body>
    <!--[if lt IE 8]>
    <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->  
	
	
	     <div id="myModalBox" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- Заголовок модального окна -->
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title">Спасибо за регистрацию</h4>
      </div>
      <!-- Основное содержимое модального окна -->
      <div class="modal-body" id="mbd">
        Содержимое модального окна...
      </div>
      <!-- Футер модального окна -->
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
       
      </div>
    </div>
  </div>
</div>
    <!-- Body main wrapper start -->
    <div class="wrapper">
        <!-- Start of header area -->
         <?php include('header.php');?>
     
        <!-- Start page content -->
        <section class="contact-area ptb-110">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="contact-area-all">
                            <div id="hastech2"><iframe src="https://yandex.ru/map-widget/v1/-/CGhJEByO" width="100%" height="100%" frameborder="1" allowfullscreen="true"></iframe></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8 col-sm-8">
                        <div class="conract-area-bottom pt-110">
                            <h3 class="main-contact">Написать</h3>
                            <form id="contact-form" action="" method="post">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="main-input">
                                            <input name="name" placeholder="ФИО*" type="text">
                                            <i class="icofont icofont-hotel-boy"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="main-input mrg-eml">
                                            <input name="email" placeholder="Email*" type="email">
                                            <i class="icofont icofont-envelope"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                       <div class="main-input mt-20 mb-20">
                                            <input name="subject" placeholder="Тема*" type="text">
                                            <i class="icofont icofont-pencil"></i>
                                        </div>
                                    </div>
									<input type="hidden" name="pismo" value="1">
                                    <div class="col-md-12">
                                        <div class="text-leave2">
                                            <textarea name="message" placeholder="Введите текст письмы......."></textarea>
											 <br>
         
              <div class="input-group-addon" id="basic-addon1" style="padding:  0px; background-image: url(images/bbr.png);"> <img src="getcapcha.php" class="input-block-level" height="100%" id="cph" ></div>
          
       	 <br>
        	  <input type="text" class="form-control" placeholder="Введите текст с изображения" aria-describedby="basic-addon1"  name="capch">
        	 <br>
        
											
											
                                            <button class="submit" type="submit" id="bpp">Отправить письмо</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <p class="form-messege"></p>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <div class="communication-all pt-110">
                            <h3 class="main-contact">Адрес</h3>
                            <div class="single-communication mb-20">
                                <div class="communication-icon">
                                    <i class="icofont icofont-social-google-map"></i>
                                </div>
                                <div class="communication-text">
                                    <p><a href="https://yandex.ru/maps/-/CGhJEByO" title="Россия, Республика Татарстан, Зеленодольск,улица Гоголя, 5">улица Гоголя, 5,</a></p>
                                    <div>
                                      <div>
                                        <div itemprop="telephone">Зеленодольск</div>
                                        <div></div>
                                      </div>
                                  </div>
                                   
                                    <p>&nbsp;</p>
                                </div>
                            </div>
                            <div class="single-communication mb-20">
                                <div class="communication-icon">
                                    <i class="icofont icofont-ui-call"></i>
                                </div>
                                <div class="communication-text">
                                    <p>+7(84371) 2-14-81<br> 89297211172<br> 89276770621                              </p>
                                </div>
                            </div>
                            <div class="single-communication">
                                <div class="communication-icon">
                                    <i class="icofont icofont-envelope"></i>
                                </div>
                                <div class="communication-text">
                                    <p>
                                        <a href="#">zelmu@yandex.ru</a><a href="#"></a>
                                    </p>
                                </div>
                            </div>
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
    
    
    
    
    <!-- jquery latest version -->
  <script type="text/javascript" src="ScriptLibrary/jquery-latest.pack.js"></script>
    <script type="text/javascript" src="./main.0cf8b554.js"></script>
    <!-- ajax-mail JS
    ============================================ -->		

    <!-- All js plugins included in this file. -->
    
    <!-- Main js file that contents all jQuery plugins activation. -->
    <!-- google map api
    ============================================ -->
    
 
	<script>
			$(function() {
		$('#bpp').click( function(e) {
		 e.preventDefault();
    $.ajax({
        url: 'contact.php',
        type: 'post',
                data: $('form#contact-form').serialize(),
        success: function(data) {
		
            $("#mbd").html(data);
			$("#myModalBox").modal('show');
			 $('#cph').attr("src","getcapcha.php?n="+Math.random());
                 }
    });
});	
			});
	</script>
</body>

</html>