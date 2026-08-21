<?php require_once('Connections/testmed.php'); 

if (!isset($_SESSION)) {
  session_start();
}
?>
<?php require_once('enc.php'); ?>
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
function generate_password($number)
  {
    $arr = array('1','2','3','4','5','6',
                 '7','8','9','0');
    // Генерируем пароль
    $pass = "";
    for($i = 0; $i < $number; $i++)
    {
      // Вычисляем случайный индекс массива
      $index = rand(0, count($arr) - 1);
      $pass .= $arr[$index];
    }
    return $pass;
  }
 if(isset($_POST['userid'])){
	 
	 
	 $id=(int)$_POST['userid'];
$sql="SELECT 
  `tm_user`.`num`,
  `tm_user`.`fio`,
  `tm_user`.`mail`,`tm_user`.`passw`
FROM
  `tm_user`
WHERE
  `tm_user`.`num` = $id";
 
	 $spec =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_spec =  /* fixed MMiC */ mysqli_fetch_assoc($spec);
 $to=$row_spec['mail'];
$totalRows_spec =  /* fixed MMiC */ mysqli_num_rows($spec);//my_encrypt($md['mn'])
if ($totalRows_spec==0){echo "Такого пользователя не существует;";exit();}	 
$subject = "Восстановление пароля на сайте дополнительного образования ГАПОУ ЗМУ "; 
$ech=my_encrypt($row_spec['passw']);
$message = ' 
<html>
<head>
<meta charset="utf-8">
<title>Восстановление пароля на сайте дополнительного образования ГАПОУ ЗМУ</title>
</head>

<body bgcolor="#6178E4" text="#F8F8F8">
<p>Здравствуйте '.$row_spec['fio'].'</p>
<p><strong>Для восстановления пароля  <h2>ДПО ГАПОУ Зеленодольского медицинского училища</h2></strong></p>
<p>перейдите по ссылке -----&gt;&gt;&gt;&gt; <a href="'.$_SERVER['SERVER_NAME'].'/vost_p.php?id='.$id.'&user='.$ech.'">'.$_SERVER['SERVER_NAME'].'/vost_p.php?id='.$id.'&user='.$ech.'</a></p>
</body>
</html>'; 

$headers  = "Content-type: text/html; charset=utf-81 \r\n"; 
$headers .= "From: Зеленодольское медицинское училище <dpo@zmudpo.ru>\r\n"; 


mail($to, $subject, $message, $headers,"-f dpo@zmudpo.ru"); 	
	 echo $row_spec['fio']." на ваш почтовый ящик было отправлено письмо с инструкцией к восстановлению пароля";
exit(0);	 
 }

if (isset($_GET['user'])){
$pass=my_decrypt($_GET['user']);
	$id=-1;
	if(isset($_GET['id']))$id=(int)$_GET['id'];
$sql="SELECT 
  `tm_user`.`num`,
  `tm_user`.`fio`,
  `tm_user`.`mail`,
  `tm_user`.`passw`
FROM
  `tm_user`
WHERE
  `tm_user`.`num` = $id AND 
  `tm_user`.`passw` = '$pass'"	;
//echo $_GET['user']."<br>";
//echo my_encrypt($row_spec['passw']);

	$spec =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_spec =  /* fixed MMiC */ mysqli_fetch_assoc($spec);
 $to=$row_spec['mail'];
$totalRows_spec =  /* fixed MMiC */ mysqli_num_rows($spec);
	if($totalRows_spec>0){
		$pass=generate_password(10);
		$sql="update `tm_user` set passw=$pass where `tm_user`.`num`=$id";
 DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));	
$subject = "Пароль изменен на сайте дополнительного образования ГАПОУ ЗМУ "; 

$message = ' 
<html>
<head>
<meta charset="utf-8">
<title>Пароль изменен на сайте дополнительного образования ГАПОУ ЗМУ</title>
</head>

<body bgcolor="#6178E4" text="#F8F8F8">
<p>Здравствуйте '.$row_spec['fio'].'</p>
<p><strong>Ваш новый пароль '.$pass.'</strong></p>

</body>
</html>'; 

$headers  = "Content-type: text/html; charset=utf-81 \r\n"; 
$headers .= "From: Зеленодольское медицинское училище <dpo@zmudpo.ru>\r\n"; 


mail($to, $subject, $message, $headers,"-f dpo@zmudpo.ru"); 	 
$sooob="Ваш пароль успешно изменен, пожалуйста проверьте свою почту";		
		
	} else {$sooob="Не удалось изменить пароль, попробуйте еще раз";}
	
}

/* fixed MMiC */ mysqli_select_db(DB::$link, $database_testmed);
//$query_spec = "SELECT * FROM tm_spec WHERE actiiv = 1";
//$spec =  /* fixed MMiC */ DB::Query($query_spec, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
//$row_spec =  /* fixed MMiC */ mysqli_fetch_assoc($spec);
//$totalRows_spec =  /* fixed MMiC */ mysqli_num_rows($spec);


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
       <div class="container">
    <div class="row">
    
			<div class="panel panel-info">
				<div class="panel-heading">Оплата обучения</div>
  <div class="panel-body"><?php echo $sooob;?>
</div>
</div>
		
		   </div></div>	
        <!-- Start page content -->
        <section class="top-courses pt-110 pb-80"></section>
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
 
</body>
</html>
<?php
 /* fixed MMiC */ mysqli_free_result($spec);
?>