<?php require_once('Connections/testmed.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

//  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

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

$nt_spam = "-1";
if (isset($_POST['tst'])) {
  $nt_spam = $_POST['tst'];
}
$xx=0;
$query_spam = sprintf("SELECT    `tm_spec_test`.`nazvanie`,   `tm_user`.`fio`,   `tm_user`.`mail`,   `tm_spec_test`.`num` FROM   `tm_spec_test`   INNER JOIN `tm_user` ON (`tm_spec_test`.`inn` = `tm_user`.`spec`) WHERE   `tm_spec_test`.`num` = %s  AND    `tm_user`.`mail` IS NOT NULL", GetSQLValueString($nt_spam, "int"));
 $spam =  /* fixed MMiC */ DB::Query($query_spam, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
//$spam = mysql_query($query_spam, $loc) or die(mysql_error());
$totalRows_spam = mysqli_num_rows($spam);

?>
<?php do { 
 $to  = $row_spam['mail'] ;
$xx++;
$subject = "Прохождение тестирования по ДПО"; 

$message = ' 
<html> 
    <head> 
        <title>Тестирование </title> 
    </head> 
    <body> 
        <table width="100%" border="0">
          <tr>
            <td width="19%" bgcolor="#505CA0"><font color="#FFFFFF"> 
            <h2>Здравствуйте '.$row_spam['fio'].'</h2>
открыто тестирование по предмету "'.$row_spam['nazvanie'].'" 
             вы можете пройти его на сайте <strong></font><a href="http://zmudpo.ru/">ZMUDPO.ru </a></td>
            <td width="81%" bgcolor="#505CA0"><img src="http://zmudpo.ru/log.png" alt="" width="103" height="101"></td>
          </tr>
        </table>
      
</body> 
</html>';  

$headers  = "Content-type: text/html; charset=utf-8 \r\n"; 
$headers .= "From: <Zelmu@yandex.ru>\r\n"; 
mail($to, $subject, $message, $headers); 
 } while ($row_spam = mysqli_fetch_assoc($spam));
 echo $row_spam['nazvanie']."отправлено ".$xx;
mysqli_free_result($spam); ?>
