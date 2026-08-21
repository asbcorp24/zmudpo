<?php 
header('Access-Control-Allow-Origin: http://priem.medzel.ru');
//print_r($_POST);
require_once('Connections/testmed.php');
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
  	$err='';
if (isset($_POST['email']) and $_POST['email']=='' ){
		
		$err=$_POST['email']." вы не ввели MAIL";
	$totalRows_md=0;
		goto endd;
		
	} 
	
$sql="SELECT num  FROM tm_user where mail='".$_POST['email']."' and spec=73 ";
  $Result1 = DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));	
$totalRows_spec01 =  /* fixed MMiC */ mysqli_num_rows($Result1);
if ($totalRows_spec01>0){	$err=$_POST['email']." вы уже регистрировались";	goto endd;}
/*if ($_SESSION["code"]!=$_POST['capch']){
		
		$err=$_POST['mail']." Вы неправильно ввели капчу";
	$totalRows_md=0;
		goto endd;
		
	}  */
if (isset($_POST["fam"])) {
$rss=0;
$pss=generate_password(8);

	$urlico=0;

  $insertSQL = sprintf("INSERT INTO tm_user (fio, spec, passw, mail,rss,data_nach,urlico) VALUES (%s, %s, %s, %s,%s,%s,%s)",
                       GetSQLValueString($_POST['fam']." ".$_POST['name']." ".$_POST['otch'], "text"),
                       GetSQLValueString(73, "int"),
                       GetSQLValueString($pss, "text"),
                       GetSQLValueString($_POST['email'], "text"),
                        0,GetSQLValueString(date("Y-m-d"), "text"),
					  GetSQLValueString(0, "int")
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
$md=$md['mn'];
switch (intval($_POST['sgr'])) {
    case 1:
       $sp="31.02.01 Лечебное дело";
        break;
        
         case 2:
       $sp="34.02.01 Сестринское дело";
        break;
            case 3:
       $sp="33.02.01 Фармация";
        break;
            case 4:
       $sp="Очно-заочная форма /34.02.01 Сестринское дело";
        break;
      
            case 5:
       $sp="Вечерняя форма/ 39.01.01 Социальный работник ";
        break;
            case 6:
       $sp="Вечерняя форма/ 34.01.01 Младшая медицинская сестра по уходу за больными";
        break;
  
}
$sql="INSERT INTO `tm_typsv_konf_user` (`num`, `user`, `ank`, `value`,`razdel`) VALUES (NULL, $md,21, '$sp',193)";
 DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
/*sgr] => 6 [fam] => Балабанов [name] => Анатолий [otch] => 222 [dr] => 2020-06-04 [gr] => 1 [addr] => Малая красная 9 кв 15 [att] => 111 [polnn] => 11 [srb] => 11 [obsh] => еу [email] => asbcorp24@gmail.com [message]*/
$sql="INSERT INTO `tm_typsv_konf_user` (`num`, `user`, `ank`, `value`,`razdel`) VALUES (NULL, $md,22,". GetSQLValueString($_POST['fam'], "text").",193)";
 DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
 $sql="INSERT INTO `tm_typsv_konf_user` (`num`, `user`, `ank`, `value`,`razdel`) VALUES (NULL, $md,23,". GetSQLValueString($_POST['name'], "text").",193)";
 DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
 $sql="INSERT INTO `tm_typsv_konf_user` (`num`, `user`, `ank`, `value`,`razdel`) VALUES (NULL, $md,24,". GetSQLValueString($_POST['otch'], "text").",193)";
 DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
 $sql="INSERT INTO `tm_typsv_konf_user` (`num`, `user`, `ank`, `value`,`razdel`) VALUES (NULL, $md,25,". GetSQLValueString($_POST['dr'], "text").",193)";
 DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
  $sql="INSERT INTO `tm_typsv_konf_user` (`num`, `user`, `ank`, `value`,`razdel`) VALUES (NULL, $md,26,". GetSQLValueString($_POST['gr'], "text").",193)";
 DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
  $sql="INSERT INTO `tm_typsv_konf_user` (`num`, `user`, `ank`, `value`,`razdel`) VALUES (NULL, $md,27,". GetSQLValueString($_POST['addr'], "text").",193)";
 DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link)); 
   $sql="INSERT INTO `tm_typsv_konf_user` (`num`, `user`, `ank`, `value`,`razdel`) VALUES (NULL, $md,28,". GetSQLValueString($_POST['att'], "text").",193)";
 DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link)); 
    $sql="INSERT INTO `tm_typsv_konf_user` (`num`, `user`, `ank`, `value`,`razdel`) VALUES (NULL, $md,29,". GetSQLValueString($_POST['polnn'], "text").",193)";
 DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link)); 
     $sql="INSERT INTO `tm_typsv_konf_user` (`num`, `user`, `ank`, `value`,`razdel`) VALUES (NULL, $md,30,". GetSQLValueString($_POST['srb'], "text").",193)";
 DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link)); 
 
     $sql="INSERT INTO `tm_typsv_konf_user` (`num`, `user`, `ank`, `value`,`razdel`) VALUES (NULL, $md,31,". GetSQLValueString($_POST['obsh'], "text").",193)";
 DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link)); 
     $sql="INSERT INTO `tm_typsv_konf_user` (`num`, `user`, `ank`, `value`,`razdel`) VALUES (NULL, $md,32,". GetSQLValueString($_POST['email'], "text").",193)";
 DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link)); 
      $sql="INSERT INTO `tm_typsv_konf_user` (`num`, `user`, `ank`, `value`,`razdel`) VALUES (NULL, $md,33,". GetSQLValueString($_POST['message'], "text").",193)";
 DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link)); 
 
 


if ((isset($_POST["email"])) and ($_POST["email"]!=""))
	{$to  = $_POST["email"] ; 


$subject = "Регистрация на сайте  ГАПОУ ЗМУ "; 

$message = ' 
<html>
<head>
<meta charset="utf-8">
<title>Регистрация на сайте образования ГАПОУ ЗМУ</title>
</head>

<body bgcolor="#6178E4" text="#F8F8F8">
<p>Здравствуйте '.$_POST['fam'].' '.$_POST['name'].' '.$_POST['otch'].'</p>
<p><strong>Добро пожаловать на сайт <h2>ГАПОУ Зеленодольского медицинского училища</h2></strong></p>
<p>Пароль для входа в личный кабинет: '.$pss.'</p>
<p>Вход будет доступен после активации модераторами в течении одного дня</p>
<p>вход в личный кабинет <a href="http://zmudpo.ru/login.php?spec=73">http://zmudpo.ru/login.php?spec=73</a><p>
<p></p>
</body>
</html>'; 

$headers  = "Content-type: text/html; charset=utf-8 \r\n"; 
$headers .= "From: Зеленодольское медицинское училище <dpo@zmudpo.ru>\r\n"; 


mail($to, $subject, $message, $headers,"-f dpo@zmudpo.ru"); 

		
	}

echo $message;
exit();
endd:
echo $err;



?>