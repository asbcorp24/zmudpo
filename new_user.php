<?php require_once('Connections/testmed.php'); ?>
<?php require_once('enc.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$totalRows_md=1;
function imageresize($outfile,$infile,$neww,$newh,$quality) {

    $im=imagecreatefromjpeg($infile);
    $im1=imagecreatetruecolor($neww,$newh);
    imagecopyresampled($im1,$im,0,0,0,0,$neww,$newh,imagesx($im),imagesy($im));

    imagejpeg($im1,$outfile,$quality);
    imagedestroy($im);
    imagedestroy($im1);
    }


if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

 // $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
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

if (isset($_POST['capch']) and $_POST['capch']=='' ){
		
		$err=$_POST['mail']." Вы неправильно ввели капчу";
	$totalRows_md=0;
		goto endd;
		
	}  
if (isset($_POST['mail']) and $_POST['mail']=='' ){
		
		$err=$_POST['mail']." вы не ввели MAIL";
	$totalRows_md=0;
		goto endd;
		
	} 
if ($_SESSION["code"]!=$_POST['capch']){
		
		$err=$_POST['mail']." Вы неправильно ввели капчу";
	$totalRows_md=0;
		goto endd;
		
	}  
if (isset($_POST["fio"])) {
$rss=0;
$pss=generate_password(8);

	$urlico=0;
	if (isset($_POST['urlico']))$urlico=1;
  $insertSQL = sprintf("INSERT INTO tm_user (fio, spec, passw, mail,rss,data_nach,urlico) VALUES (%s, %s, %s, %s,%s,%s,%s)",
                       GetSQLValueString($_POST['fio'], "text"),
                       GetSQLValueString($_POST['spec'], "int"),
                       GetSQLValueString($pss, "text"),
                       GetSQLValueString($_POST['mail'], "text"),
                        0,GetSQLValueString(date("Y-m-d"), "text"),
					  GetSQLValueString($urlico, "int")
					  );



  
  $Result1 = DB::Query($insertSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
	  
//	  mysql_query($insertSQL, $loc) or die(mysql_error());
//$spec =  /* fixed MMiC */ DB::Query($query_spec, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
//$row_spec =  /* fixed MMiC */ mysqli_fetch_assoc($spec);
//$totalRows_spec =  /* fixed MMiC */ mysqli_num_rows($spec);	
}
$sql="SELECT MAX(num)as mn FROM tm_user";
  $Result1 = DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
  $md = mysqli_fetch_assoc($Result1);

if ((isset($_POST["mail"])) and ($_POST["mail"]!=""))
	{$to  = $_POST["mail"] ; 

$md=my_encrypt($md['mn']);
$subject = "Регистрация на сайте дополнительного образования ГАПОУ ЗМУ "; 

$message = ' 
<html>
<head>
<meta charset="utf-8">
<title>Регистрация на сайте дополнительного образования ГАПОУ ЗМУ</title>
</head>

<body bgcolor="#6178E4" text="#F8F8F8">
<p>Здравствуйте '.$_POST['fio'].'</p>
<p><strong>Добро пожаловать на сайт <h2>ДПО ГАПОУ Зеленодольского медицинского училища</h2></strong></p>
<p>Пароль для входа в раздел тестирования: '.$pss.'</p>
<p>Для подтверждения регистрации перейдите по ссылке -----&gt;&gt;&gt;&gt; <a href="'.$_SERVER['SERVER_NAME'].'/user_pod.php?user='.$md.'">'.$_SERVER['SERVER_NAME'].'/user_pod.php?user='.$md.'</a></p>
</body>
</html>'; 

$headers  = "Content-type: text/html; charset=utf-8 \r\n"; 
$headers .= "From: Зеленодольское медицинское училище <dpo@zmudpo.ru>\r\n"; 


mail($to, $subject, $message, $headers,"-f dpo@zmudpo.ru");  }






$fom= $_POST['fom'];
$fil=$_FILES['fom'];
$fi="";

if (isset($fil)){
foreach($fil['name'] as $key=>$value)
{

$fi =uniqid().'.jpg';
		move_uploaded_file($fil['tmp_name'][$key],'usrimg/'.$fi);	
		imageresize('usrimg/'.$fi,'usrimg/'.$fi,800,600,75);
	$sql=sprintf("INSERT INTO tm_user_sv (inn,tm_typsv, value) VALUES (%s, %s, %s)",GetSQLValueString($md['mn'], "int"),
GetSQLValueString($key, "int"),GetSQLValueString($fi, "text"));
  $Result1 = DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
	
	
} }


foreach($fom as $key=>$value){
$sql=sprintf("INSERT INTO tm_user_sv (inn,tm_typsv, value) VALUES (%s, %s, %s)",GetSQLValueString($md['mn'], "int"),
GetSQLValueString($key, "int"),GetSQLValueString($value, "text"));
  $Result1 = DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
	}  
endd:
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
<style>
    .drop-shadow {
        -webkit-box-shadow: 0 0 5px 2px rgba(0, 0, 0, .5);
        box-shadow: 0 0 5px 2px rgba(0, 0, 0, .5);
    }
    .container.drop-shadow {
        padding-left:0;
        padding-right:0;
    }
</style>
<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<?php include("header.php");?>

<!-- HEADER -->
<header>
  
    <div class="container">
      <div class="row">
      
			<div class="panel panel-default drop-shadow">
  <div class="panel-body">	<?php if ($totalRows_md==0){ ?>  <p class="text-center">
				
				<?php echo $err." "; ?> </p>
	 
			<a class="btn btn-danger" href="index.php#reg">Вернутся</a>	
	   <?php  } else {?>
	  <p class="text-center">
	  

	  <?php echo $_POST['fio']; ?> </p>
			<p class="text-center">Ваши данные переданы для обработки </p>
			<p class="text-center">на ваш почтовый ящик выслано письмо подтверждение       </p>
         <p class="text-center">пожалуйста пройдите по ссылке в письме чтобы подтвердить регистрацию. </p>
          <p class="text-center">Если письмо не пришло проверьте папку СПАМ, иногда письма отправляются туда </p>
		  <p class="text-center">через некоторое время наши менеджеры </p>
		  <p class="text-center">свяжутся с вами по телефону или электронной почте        </p>
				<?php  }?>
				
				</div>
</div>
			
         
     
      </div>
    </div>
  
</header>
<!-- / HEADER --> 

<!--  SECTION-1 -->


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