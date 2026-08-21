<?php require_once('Connections/testmed.php'); ?>
<?php

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
echo "<h2>Зеленодольский медицинский колледж</h2><br>";
echo "<h3>Экзаменуемый - ".$row_user['fio']."</h3><br><hr>";

$file="./userxml/".$_GET['razdel'];

if (file_exists($file)) {
	
	$string=file_get_contents($file);
$xml = simplexml_load_string($string);
echo "<h3>Процент правильных ответов :".(floatval($xml->summary['percent'])*100)."</h3><br>";
echo "Ответы на тест экзаменуемого<br><br>";
  foreach ($xml->questions->children() as $second_gen){
   echo "Вопрос -".(String)$second_gen->direction."<br>";
 echo "Ответ ";
    $vn= $second_gen['status']=='correct'?"Верный":"Не верный";
   echo $vn."</br>";
 $i=(String)$second_gen->answers['userAnswerIndex'];
    
   echo "Ответ экзаменуемого -".(String)$second_gen->answers->answer[intval($i)]."<br>";
    
       $i=(String)$second_gen->answers['correctAnswerIndex'];
    //    echo "Правильный ответ -".(String)$second_gen->answers->answer[intval($i)]."<br><hr>";

   }
 
}
 
?>
 
	