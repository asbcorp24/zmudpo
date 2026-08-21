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

<h2> <?php echo $row_user['fio'];?></h2>

   
        

<?php 
//print_r($_REQUEST);
/*$file="../userxml/".$_GET['razdel'];
//echo $file;
if (file_exists($file)) {
	
	$_POST['dr']= file_get_contents($file);
		$_POST['qt']='-';
	$_POST['sp']=0;
	$_POST['ps']=0;
	 require_once '../includes/common.inc.php';
    $requestParameters = RequestParametersParser::getRequestParameters($_POST, !empty($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : null);
 //   _log($requestParameters);
  
    {
        $quizResults = new QuizResults();
        $quizResults->InitFromRequest($requestParameters);
        $generator = QuizReportFactory::CreateGenerator($quizResults, $requestParameters);
        $report = $generator->createReport();
       
      echo  ($report);
        echo "OK";
    }
}
 */
 
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
    $count= ($second_gen->answers->count());
    echo $count;
if($count<2){

$uo= $otv['uotv']==1?'Нет':'Да';	
  echo $otv['vopros'].": ". $uo."<br>";	
  continue;
}
     $otv['uanswer']=(String)$second_gen->answers->answer[intval($otv['uotv'])];
    
    //   $otv['cotv']=(String)$second_gen->answers['correctAnswerIndex'];
    //  $otv['canswer']=(String)$second_gen->answers->answer[intval($otv['cotv'])];
    echo $otv['vopros'].": ". $otv['uanswer']."<br>";

 //$row[]=$otv;
   }
 //print_r($row);
}
 
 
?>
 
	