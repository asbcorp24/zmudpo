<?php require_once('Connections/testmed.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";



$query_user = "SELECT 
  `tm_user`.`num`,
  `tm_user`.`fio`
FROM
  `tm_user`
WHERE
  `tm_user`.`num` = ".(int)$_GET['user'];

$user =  /* fixed MMiC */ DB::Query($query_user, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
//$prepod = mysql_query($query_prepod, $loc) or die(mysql_error());
$row_user = mysqli_fetch_assoc($user);
?>



   
        

<?php 
//print_r($_REQUEST);
$file="../userxml/".$_GET['razdel'];
if (!file_exists($file))$file="../userxml/def.xml";
//echo $file;
if (file_exists($file)) {
	
	$string=file_get_contents($file);
$xml = simplexml_load_string($string);
  $row=[];
  foreach ($xml->questions->children() as $second_gen){
   
  $otv=[];
    $otv['status']= $second_gen['status']=='correct'?1:0;
    $otv['vopros']=(String)$second_gen->direction;
   $otv['uotv']=(String)$second_gen->answers['userAnswerIndex'];
    
     $otv['uanswer']=(String)$second_gen->answers->answer[intval($otv['uotv'])];
    
       $otv['cotv']=(String)$second_gen->answers['correctAnswerIndex'];
      $otv['canswer']=(String)$second_gen->answers->answer[intval($otv['cotv'])];

 $row[]=$otv;
   }
  echo json_encode($row);
}
 
?>
 
	