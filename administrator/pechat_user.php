<?php
 if (ob_get_level()) {
      ob_end_clean();
    }
    // заставляем браузер показать окно сохранения файла
//header('Content-Description: File Transfer');
 //   header('Content-Type: application/octet-stream');
 //   header('Content-Transfer-Encoding: binary');
 //   header('Expires: 0');
 //   header('Cache-Control: must-revalidate');
 //   header('Pragma: public');
 $imm=array();
 require_once('Connections/testmed.php');

include_once('MhtFileMaker.php');
function getWordDocument($content, $absolutePath = "", $isEraseLink = false,$imag)
{
    $mht = new MhtFileMaker();
    if ($isEraseLink) {
        $content = preg_replace('/<a\\s*.*?\\s*>(\\s*.*?\\s*)<\\/a>/i', '$1', $content);
    }
    $images = array();
    $files = array();
    $matches = array();//
    if (preg_match_all('/<img[^>]*src\\s*=\\s*?[\\"\'](.*?)[\\"\'](.*?)\\/>/i', $content, $matches)) {
      
		$arrPath = $imag;
		//print_r($arrPath);
        for ($i = 0; $i < count($arrPath); $i++) {
            $path = $arrPath[$i];
            $imgPath = trim($path);
            if ($imgPath != "") {
                $files[] = $imgPath;
                if (substr($imgPath, 0, 7) == 'http://') {
                } else {
                    $imgPath = "http://" . $_SERVER['HTTP_HOST'] . "/" . $imgPath;
                }
                $images[] = $imgPath;
            }
        }
    }
//print_r($images);
   	$mht->AddContents("tmp.html", $mht->GetMimeType("tmp.html"), $content);
    for ($i = 0; $i < count($images); $i++) {
        $image = $images[$i];
        if (@fopen($image, 'r')) {
            $imgcontent = @file_get_contents($image);
            if ($content) {
	
                $mht->AddContents($files[$i], $mht->GetMimeType($image), $imgcontent);
            }
        } else {
            echo "file:" . $image . " not exist!<br />";
        }
    }
    return $mht->GetFile();
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

$colname_user = "-1";
if (isset($_GET['spec'])) {
  $colname_user = $_GET['spec'];
}

$query_user = sprintf("SELECT * FROM tm_user WHERE spec = %s ORDER BY fio ASC", GetSQLValueString($colname_user, "int"));
$user=  /* fixed MMiC */ DB::Query($query_user, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));

//$user = mysql_query($query_user, $loc) or die(mysql_error());
$row_user = mysqli_fetch_assoc($user);
$totalRows_user = mysqli_num_rows($user);

$colname_spec = "-1";
if (isset($_GET['spec'])) {
  $colname_spec = $_GET['spec'];
}
$query_spec = sprintf("SELECT nazv, dat FROM tm_spec WHERE num = %s", GetSQLValueString($colname_spec, "int"));
$spec=  /* fixed MMiC */ DB::Query($query_spec, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
//$spec = mysql_query($query_spec, $loc) or die(mysql_error());
$row_spec = mysqli_fetch_assoc($spec);
$totalRows_spec = mysqli_num_rows($spec);
//header('Content-Disposition: attachment; filename='.$row_spec['nazv'].".doc" );

?>
<?php $sa='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
$sa=$sa.'<html xmlns="http://www.w3.org/1999/xhtml">';
$sa=$sa.'<head>';
$sa=$sa.'<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
$sa=$sa.'<title>'.$row_spec['nazv'].'</title>';
$sa=$sa.'</head>';
$sa=$sa.'<body>';
$sa=$sa.'<h1>'.$row_spec['nazv'].'</h1>';
$sa=$sa.'<h3>Дата проведения:'.$row_spec['dat'].'</h3>';
 do { $sa=$sa.'<table width="99%" border="1">';
 $sa=$sa.' <tr>';
 $sa=$sa.'  <td colspan="2">&nbsp;</td>';
 $sa=$sa.'   </tr>';
$sa=$sa.'    <tr>';
$sa=$sa.'      <td>ФИО</td>';
$sa=$sa.'      <td><strong>'.$row_user['fio'].'</strong></td>';
$sa=$sa.'    </tr>';
$sa=$sa.'    <tr>';
$sa=$sa.'      <td>Почта </td>';
$sa=$sa.'      <td><em><strong>'.$row_user['mail'].'</strong></em></td>';
$sa=$sa.'    </tr>';
$sa=$sa.'    <tr>';
 $sa=$sa.'<td>АКТИВ</td>';
 $sa=$sa.'     <td>'.$row_user['act'].'</td>';
 $sa=$sa.'   </tr>';
 $sa=$sa.'   <tr>';
 $sa=$sa.'<td colspan="2">Доп сведения</td>';
 $sa=$sa.'</tr>';

      
       
 $query_dopsv = "SELECT    `tm_typsv`.`nazv`,   `tm_user_sv`.`value`,   `tm_typsv`.`typ` FROM   `tm_user_sv`   INNER JOIN `tm_typsv` ON (`tm_user_sv`.`tm_typsv` = `tm_typsv`.`num`) WHERE   `tm_user_sv`.`inn` = ".$row_user['num'];
//echo  $query_dopsv;
$dopsv=  /* fixed MMiC */ DB::Query($query_dopsv, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));

//		  $dopsv = mysql_query($query_dopsv, $loc) or die(mysql_error());
$row_dopsv = mysqli_fetch_assoc($dopsv);
$totalRows_dopsv = mysqli_num_rows($dopsv); 
	
if ($totalRows_dopsv>0)
          do { 
          $sa=$sa.'  <tr>';
          
               if (file_exists("../usrimg/".$row_dopsv['value'])){
          $sa=$sa.'<td colspan="2">'.$row_dopsv['nazv'].'</td></tr>';
			array_push($imm,'/usrimg/'.$row_dopsv['value']);
				   
				   $sa=$sa.'<tr><td colspan="2">'.PHP_EOL.'<img src="/usrimg/'.$row_dopsv['value'].'"></td>';
           } else {
				   $sa=$sa.'<td>'.$row_dopsv['nazv'].'</td>';
               $sa=$sa.'<td>'.$row_dopsv['value'].'</td>';}

$sa=$sa.'            </tr>';
            } while ($row_dopsv = mysqli_fetch_assoc($dopsv));
$sa=$sa.'</table>';
$sa=$sa.'<br />';
$sa=$sa.'<br />';
$sa=$sa.'<br />';
    } while ($row_user = mysqli_fetch_assoc($user));

$sa=$sa.'</body>';
$sa=$sa.'</html>';
echo $sa;
//echo getWordDocument($sa,nil,true,$imm);
mysqli_free_result($user);

mysqli_free_result($spec);

mysqli_free_result($dopsv);
?>