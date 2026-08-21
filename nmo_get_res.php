<?php header('Access-Control-Allow-Origin: *');
//header('Content-Type: application/octet-stream');
//header('Content-Disposition: attachment; filename="Протокол_тестирования.html"');
if (!isset($_SESSION)) {
  session_start();
}

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

?>
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
 
 require_once 'includes/common.inc.php';
 
   // _log($requestParameters);


 //$quizResults = new QuizResults();
   //   $quizResults->InitFromRequest($requestParameters);
  //      $generator = QuizReportFactory::CreateGenerator($quizResults, $requestParameters);
 //       $report = $generator->createReport();

       
       


// @file_put_contents($file, $report);
 



/* `num` int(11) NOT NULL AUTO_INCREMENT,
  `inn` int(11) DEFAULT NULL,
  `test` int(11) DEFAULT NULL,
  `res` int(11) DEFAULT NULL,
  `dat` date DEFAULT NULL,
  `otv_col` int(11) DEFAULT NULL,*/
$tmp="";
 /* fixed MMiC */ mysqli_select_db(DB::$link, $database_testmed);
 $user=$_GET['num'];
$sql="SELECT `tm_nmo_razd_user`.*,`tm_user`.`fio`,tm_spec.nazv FROM `tm_nmo_razd_user` 
INNER JOIN tm_user ON tm_user.num=`tm_nmo_razd_user`.`user`
INNER JOIN tm_spec ON tm_spec.num=tm_user.spec

WHERE `tm_nmo_razd_user`.`proydeno` > 0 AND  `tm_nmo_razd_user`.`id` = ".GetSQLValueString($user, "int");

 

  $Result1 =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_pop =  /* fixed MMiC */ mysqli_fetch_assoc($Result1);
$totalRows_pop =  /* fixed MMiC */ mysqli_num_rows($Result1);
 //echo  $row_pop['fio']; 
 
//$uress=$row_pop['fio'];$ress++;
//$rspec=$row_pop['nazv'];
// Загружаем XML
$file='./userxml/'.$row_pop['razdel']."_".$row_pop['user'].".xml";
$xml = simplexml_load_file($file);

// Получаем все вопросы
$questions = $xml->questions->multipleChoiceQuestion;

// Начинаем вывод HTML-таблицы
$table='<table border="1" cellpadding="4" cellspacing="0"><tr>';
 
$count = 0;
foreach ($questions as $q) {
    // Читаем атрибуты
    $ans = $q->answers;
    $correct = (int)$ans['correctAnswerIndex'];
    $user    = (int)$ans['userAnswerIndex'];

    // Формируем содержимое ячейки, например "1:0/0" (номер:correct/user)
	 // Решаем, ставим ли галочку
       if($q->attributes()->status=="notAnswered"){
            $cell = 'Не ответил';   // совпало
    } else  $cell = 'X';
    
   if($q->attributes()->status=="correct")
    {if ($correct === $user) {
        $cell = '✓';   // совпало
    } else {
        $cell = '';    // не совпало — пустая ячейка
    }}


    $cellContent = (++$count) . ':' . $correct . '/' . $user." ".$cell;

    // Выводим ячейку
    $table=$table."<td>{$cellContent}</td>";

    // Если набрали 20 ячеек, закрываем строку и начинаем новую
    if ($count % 15 === 0) {
        $table=$table.'</tr><tr>';
    }
}
$remainder = $count % 15;
if ($remainder !== 0) {
    $toAdd = 15 - $remainder;
    for ($i = 0; $i < $toAdd; $i++) {
         $table=$table. '<td></td>';
    }
}
// Закрываем последний <tr> и всю таблицу
$table =$table.'</tr></table>';


function generateProtocolHTML($userName, $testName, $score, $percent, $isPassed,$rspec,$ppsp,$zz,$tabl) {
    $passStatus = $isPassed ? '«сдано»' : '«не сдано»';
    $passCriteria = $isPassed ? $ppsp.'% и более' : ($ppsp-1).'% и менее';
    $currentDate = date('d.m.Y');
    
    $html = <<<HTML
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Протокол 1 этапа ГЭК</title>
    <style>
        body { font-family: Times New Roman, serif; margin: 2cm; }
        h1 { text-align: center; font-size: 14pt; margin-bottom: 0.5cm; }
        .protocol { border-collapse: collapse; width: 100%; margin-top: 1cm; }
        .protocol td { padding: 5pt; vertical-align: top; }
        .underline { border-bottom: 1px solid black; display: inline-block; min-width: 10cm; }
        .signature { margin-top: 1cm; width: 8cm; display: inline-block; }
        .footer { margin-top: 2cm; }
        .date { text-align: right; margin-bottom: 1cm; }
    </style>
</head>
<body>
    <div class="date">Дата: $currentDate</div>
    
    <h1>Протокол</h1>
    <h1>1 этапа государственного экзамена (теоретический)</h1>
    <h1>Специальность $rspec</h1>
    

    <table class="protocol" class="full-width">
        <tr>
            <td><strong>ФИО студента:</strong></td>
            <td><span class="underline">$userName</span></td>
        </tr>
        <tr>
            <td><strong>Количество баллов:</strong></td>
            <td><span class="underline">$score из {$zz}</span></td>
        </tr>
        <tr>
            <td><strong>Процент выполнения:</strong></td>
            <td><span class="underline">$percent%</span></td>
        </tr>
        <tr>
            <td><strong>Результат:</strong></td>
            <td><span class="underline">$passStatus</span></td>
        </tr>
        <tr>
            <td colspan="2">
                <em>$passStatus при результате $passCriteria правильных ответов</em>
            </td>
        </tr>
    </table>
	{$tabl}
    <div class="footer">
        <div class="signature">Председатель ГЭК<br>_________________ /_________________/</div>
        <div class="signature">Зам председателя<br>_________________ /_________________/</div>
        <div style="clear: both;"></div>
        
        <div>Члены ГЭК</div>
        <div class="signature">_________________ /_________________/</div>
        <div class="signature">_________________ /_________________/</div>
        <div class="signature">_________________ /_________________/</div>
    </div>
</body>
</html>
HTML;

    return $html;
}

// Получение данных для протокола
$userName = $row_pop['fio']; // Здесь нужно получить ФИО из сессии или БД
$testName = "Теоретический экзамен"; // Название теста из qt
$iz=$row_pop['sp']/($row_pop['psp']/100);
$score = $row_pop['proydeno']; // Набранные баллы

$percent = round($row_pop['proydeno']/$iz*100); // Процент правильных ответов
$isPassed = ($percent >=  $row_pop['psp']); // Определение статуса
$rspec=$row_pop['nazv'];
// Генерация HTML
$htmlContent = generateProtocolHTML($userName, $testName, $score, $percent, $isPassed,$rspec,$row_pop['psp'],$iz,$table);

// Вывод файла
echo $htmlContent;

?>
 <style>
 table.full-width {
  width: 100%;            /* растянуть на всю ширину контейнера */ 
  table-layout: fixed;    /* фиксированная раскладка колонок */ :contentReference[oaicite:0]{index=0}
  border-collapse: collapse;
}
table.full-width td {
  overflow: hidden;       /* обрезать содержимое при переполнении */
  text-overflow: ellipsis;
  white-space: nowrap;
}
 </style>
