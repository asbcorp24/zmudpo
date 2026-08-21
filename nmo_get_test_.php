<?php

function get_ip()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP']))
    {
        $ip=$_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
    {
        $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
        $ip=$_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

if (!isset($_SESSION)) {
  session_start();
}
 require_once('Connections/testmed.php');
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}
$_SESSION['MM_spec']=$_GET['num'];
$MM_restrictGoTo = "login.php";
if (!((isset($_SESSION['MM_Username1'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username1'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;	
}
$sql="SELECT 
  `tm_user`.`fio`
FROM
  `tm_user`
WHERE
  `tm_user`.`num` = ".$_SESSION['MM_Username1'];
$nmo1 =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_nmo1 =  /* fixed MMiC */ mysqli_fetch_assoc($nmo1);
$tmp= "<h1 style='position: relative;z-index: 10000'>".$row_nmo1['fio']."-".date("F j, Y, g:i a")."</h1>";
$sql="SELECT 
  `tm_nmo_razd_media`.`nazv`
FROM
  `tm_nmo_razd_media`
WHERE
  `tm_nmo_razd_media`.`id` =".$_SESSION['MM_spec'];
$nmo1 =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_nmo1 =  /* fixed MMiC */ mysqli_fetch_assoc($nmo1);  
$tmp2= "<h2 style='position: relative;z-index: 10000'>".$row_nmo1['nazv']."</h2>";
$sql=" INSERT INTO `tmo_nmo_test_dat` (`num`, `user`, `test`, `dat`,ip) VALUES (NULL, ".$_SESSION['MM_Username1'].", ".$_SESSION['MM_spec'].", '".date("Y-m-d H:i:s")."','".get_ip()."')";
DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
 // header("Location: ); 
 // echo getcwd()."<br>";<base href="/example/images/">
 
  chdir("./nmo/test/".str_replace('index.html','',$_GET['test'])."/");
  
// echo getcwd();
  ;
$sts="<script type='text/javascript'>
function parseURL(url) {
  var parts = url.split('?');
      link = parts.length > 1 ? parts.shift() : '';
      gets = parts.join('?').split('&'),
      data = {};

  for(var index = 0; index < gets.length; index++) {
    parts = gets[index].split('=');
    assignValue(data, decodeURIComponent(parts.shift()), decodeURIComponent(parts.join('=')));
  }

  function assignValue(data, key, value) {
    var parts = key.replace(/\[(.*?)\]/g, '.$1').split(/\./);
    key = parts.shift();
    if (parts.length === 0) {
      data[key] = value;
    } else {
      assignValue(key in data ? data[key] : (data[key] = {}), parts.join('.'), value);
    }
  }

  return {
    link: link,
    data: data
  }
}





flg=0;
	$(function() {


    $('div').on('click','.bottom_panel', function(e) {
     $('div.bottom_panel').find('.component_container.review').remove();
  e.preventDefault();
    console.log('hren');
    });	


    $('div').on('mousedown','.component_container.review.active', function(e) {
         $('div.bottom_panel').find('.component_container.review').remove();
    e.stopPropagation();

e.preventDefault();
    console.log('hren2');
    });	
    
	
 (function(send) {
        XMLHttpRequest.prototype.send = function() {
        console.log($('div.bottom_panel').find('.component_container.review'));
        $('div.bottom_panel').find('.component_container.review').remove();
              if(flg==3)return;
       flg++;  
            console.log('ajax');
        // console.log();
            send.apply(this, arguments);
            var arguments2=decodeURI(arguments[0]);
      arguments2=arguments2.substring(1);
 //  arguments2=arguments2.substring(0, arguments2.length - 1);
            
          //    console.log(arguments2);
                 if(flg==2)return;
              dat= parseURL(arguments[0]);
              console.log(JSON.stringify(dat.data.dr));
                 
   $.ajax({
  type: 'POST',
 url: 'http://zmudpo.ru/num_res_test.php',
  data: { tp: dat.data.tp, ps: dat.data.ps,psp: dat.data.psp,sp: dat.data.sp,dop:1,user:".$_SESSION['MM_Username1'].",razdel:".$_SESSION['MM_spec']."}
,
 
});          
              
      
               
         
        };
    })(XMLHttpRequest.prototype.send);	
	
	
console.log('lala;');

});
</script>
";
 $text = file_get_contents( 'index.html');

 $ss="./nmo/test/".str_replace('index.html','',$_GET['test']);
$ss="./nmo/test/".str_replace('index.html','',$_GET['test']);
 //echo $ss;
 //$text='<script src=data><script>';
 $text=str_replace('<div id="preloader"></div>', '<div id="preloader"></div><script src="../../../js/jquery-1.11.3.min.js"></script> ',$text);
$text=str_replace('<head>', '<head><base href="'.$ss.'">',$text);
$text=str_replace('<body>', '<body>'.$tmp.$tmp2,$text);
$text=str_replace('</body>', $sts.'</body>',$text);
  // Переводим содержимое в видимую форму 
  //$text = htmlspecialchars($text); 
  // Выводим содержимое файла 

 echo $text; 
?>
