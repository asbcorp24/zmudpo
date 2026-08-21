<?php require_once('Connections/testmed.php'); 
if (!isset($_SESSION)) {
  session_start();
}
?>
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
 
if(isset($_POST['json'])){
  if($_POST['passw']!='6392524035') exit;
  
  $jsn= str_replace("\r", "",$_POST['json']);
  $jarray=json_decode($jsn);
  

  $num=intval($_POST['num']);
  
foreach($jarray as $val){
if($val->num==null){
  $insertSQL = sprintf("INSERT INTO tm_user (fio, spec,passw) VALUES ('%s', %s,'%s')", $val->fio,$num, generate_password(8));
  $spec =  /* fixed MMiC */ DB::Query($insertSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));                     
				
 
} 

  if($val->del!=null){ 

  $insertSQL = sprintf("delete from tm_user where num=%s", $val->num);
  $spec =  /* fixed MMiC */ DB::Query($insertSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));     
  
  }
  if($val->num!=null){ 
    $insertSQL = sprintf("UPDATE tm_user SET fio='%s' WHERE num=%s and fio<>'%s'",$val->fio, $val->num,$val->fio);
  $spec =  /* fixed MMiC */ DB::Query($insertSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));  
                       $insertSQL = sprintf("UPDATE tm_user SET passw='%s' WHERE num=%s and passw<>'%s'",$val->passw, $val->num,$val->passw);
  $spec =  /* fixed MMiC */ DB::Query($insertSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));              
        

  //if($val->act==1)
  
  {  
    $insertSQL = sprintf("UPDATE tm_user SET act=%s WHERE num=%s and act<>%s",$val->act, $val->num,$val->act);
  $spec =  /* fixed MMiC */ DB::Query($insertSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link)); 
 
  }
                  
                      
                      
                     }
}

  print_r($jarray);

  exit;

}


if(isset($_POST['num'])){
  $sql="SELECT num,fio,passw,act FROM tm_user where spec=".intval($_POST['num']);;
$spec =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_spec =  /* fixed MMiC */ mysqli_fetch_assoc($spec);

$totalRows_spec =  /* fixed MMiC */ mysqli_num_rows($spec);
$ret=[];
do{
$ret[]=$row_spec ;
} while ($row_spec =mysqli_fetch_assoc($spec));
echo json_encode($ret,JSON_UNESCAPED_UNICODE);  
exit;
}

 /* fixed MMiC */ mysqli_select_db(DB::$link, $database_testmed);
$query_spec = "SELECT * FROM tm_spec WHERE zap = 1  order by dat ";
$spec =  /* fixed MMiC */ DB::Query($query_spec, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_spec =  /* fixed MMiC */ mysqli_fetch_assoc($spec);

$totalRows_spec =  /* fixed MMiC */ mysqli_num_rows($spec);
$ret=[];
do{
$ret[]=$row_spec ;
} while ($row_spec =mysqli_fetch_assoc($spec));
echo json_encode($ret,JSON_UNESCAPED_UNICODE);

?>