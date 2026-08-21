<?php require_once('Connections/testmed.php'); ?>
<?php

if (!isset($_SESSION)) {
  session_start();
}

function er($st){
	$er1="php|php3|php4|php5|php6|phtml|pl|asp|aspx|cgi|dll|exe|shtm|shtml|fcg|fcgi|fpl|asmx|pht|py|psp|rb|var";
if (strripos($er1,$st)!=false)	$st=$st.'_';
	return($st);
}
 function getExtension1($filename) {
    return end(explode(".", $filename));
  }
$username_test = "0";
if (isset($_SESSION['MM_Username1'])) {
  $username_test = $_SESSION['MM_Username1'];
}

$sql="SELECT 
  `tm_user_pract`.`file`,
  `tm_user_pract`.`img`,
  `tm_user_pract`.`num`,
  `tm_user_pract`.`tema`
FROM
  `tm_user_pract`
WHERE
  `tm_user_pract`.`user` =$username_test AND 
  `tm_user_pract`.`num` = ".$_GET['del'];
	
$Result1 =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
	$lastid=mysqli_fetch_assoc($Result1);
	if (file_exists('./pract_user/file/'.$lastid['file'])) unlink('./pract_user/file/'.$lastid['file']);	
	if (file_exists('./pract_user/img/'.$lastid['img'])) unlink('./pract_user/img/'.$lastid['img']);	
$sql="delete from  `tm_user_pract` where num=".$lastid['num'];
$Result1 =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
?>