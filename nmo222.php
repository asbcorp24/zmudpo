<?php require_once('Connections/testmed.php'); ?>
<?php
$js1=0;
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

function yn($oplat1,$s){
foreach ($oplat1 as &$value) {
   if ($value[0]==$s) return($value[1]);
}	
return(-1);	
}
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

$colname_test = "-1";
if (isset($_SESSION['MM_UserGroup'])) {
  $colname_test = $_SESSION['MM_UserGroup'];
}

$username_test = "0";
if (isset($_SESSION['MM_Username1'])) {
  $username_test = $_SESSION['MM_Username1'];
}


if(isset($_POST['gbil']))

{
$kko=	$_POST['kko'];
	
	for($ii=0;$ii<=$kko;$ii++)$bil[$ii]=$ii;
$ugr=$_POST['gr'];
 $rzd=$_POST['rzd'];
$slq="SELECT 
  `tm_nmo_bil`.`num`,
  `tm_user`.`grupp`
FROM
  `tm_nmo_bil`
  INNER JOIN `tm_user` ON (`tm_nmo_bil`.`user` = `tm_user`.`num`)
WHERE
 `tm_nmo_bil`.`mrazdel` = $rzd and `tm_user`.`grupp`=$ugr";
//echo $slq;
$nbb =   DB::Query($slq, $testmed) or die(  mysqli_error(DB::$link));
$row_nbb = mysqli_fetch_assoc($nbb);
	do {
		echo $bil[$row_nbb['num']];
		 unset($bil[$row_nbb['num']]);
	} while ($row_nbb =  /* fixed MMiC */ mysqli_fetch_assoc($nbb));
	$bil=array_values($bil); 
	//print_r($bil);
 $xb=$bil[rand(1, count($bil)-1)];
	if ($xb=="") {echo "нет свободных билетов";exit;}
$sql="INSERT INTO `tm_nmo_bil` (`id`, `user`, `mrazdel`, `num`) VALUES (NULL, $username_test, $rzd, $xb)";
	  DB::Query($sql, $testmed) or die(  mysqli_error(DB::$link));
echo $xb;
exit;
}



if (isset($_POST['alert']) and ($_POST['alert']==1)){
	$sql="INSERT INTO `tm_user_obiav` (`id`, `user`, `obiav`) VALUES (NULL,   $username_test, ".intval($_POST['id']).") ";
	echo $sql ;
	DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
	exit();
	
};
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

if (isset($_POST['psw']) and ($_POST['psw']==1)){
	$num=(int)$_POST["num"];
$sql="select passw from nmo_test_pass where media_razd=".$num;
	$nmo1 =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_nmo1 =  /* fixed MMiC */ mysqli_fetch_assoc($nmo1);
	if (strripos($row_nmo1['passw'],$_POST['pass'].",")===false){echo "no";exit;}
	$bodytag = str_replace($_POST['pass'], generate_password(10), $row_nmo1['passw']);
	$sql="update nmo_test_pass set passw='".$bodytag."' where media_razd=".$num;
	DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
	echo "yes";
	exit;
}

if (isset($_POST['ank']) and ($_POST['ank']==3)){
	$num=(int)$_POST["num"];
	$sql="select * from tm_typsv_konf_user where user=$username_test and ank=".$num;
	$nmo1 =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_nmo1 =  /* fixed MMiC */ mysqli_fetch_assoc($nmo1);
$row_nmo1=$row_nmo1['value'];
	if (file_exists('ankfile/'.$row_nmo1)) unlink('ankfile/'.$row_nmo1);
	
	
$delsql="delete from tm_typsv_konf_user where user=$username_test and ank=".$num;
	DB::Query($delsql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link)); 	
	
	?>
<div class="input-group">
		  <span class="input-group-addon">
             файло      </span>
	 	 
			<div class="progress prog" id="prog" style="display: none;height: 40px;">
    <div class="progress-bar progress-bar-striped progress-bar-warning pb " role="progressbar" style="width: 0%;" aria-valuenow="14" aria-valuemin="0" aria-valuemax="100" id="pb"></div>
</div> <input type="file" class="form-control fank" list="22" name="fil" value="" data-num="<?php echo $num;  ?>" accept="image/jpeg">       	 	     </div>



<?php
	exit;
}

if (isset($_POST['ank']) and ($_POST['ank']!=3))
{

	if (count($_FILES)>0){
	//	var_dump($_FILES);
include('classSimpleImage.php');	
		//print_r($_FILES);
		
		  $finfo = new finfo(FILEINFO_MIME_TYPE);
    if (false === $ext = array_search(
        $finfo->file($_FILES['0']['tmp_name']),
        array(
            'jpg' => 'image/jpeg',
            'png' => 'image/png',
                 ),
        true
    )) {
       echo "er-jpg";
    
       exit();
    }

/////
	$sql="SELECT 
  `tm_user`.`fio`
FROM
  `tm_user`
WHERE
  `tm_user`.`num` = $username_test
LIMIT 1";

$nmo1 =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_nmo1 =  /* fixed MMiC */ mysqli_fetch_assoc($nmo1);
$row_nmo1=$row_nmo1['fio'];
		
		
		
		
		$fnm=uniqid().'.jpg';
	$image = new SimpleImage();
	$image->load($_FILES['0']['tmp_name']);
			
		$image->resizeToWidth(1980);
			//$image->resizeToHeight(1024);
			$image->addwatemark(10,30,$row_nmo1);
			$filenameimg =uniqid().'.jpg';//.getExtension1($_FILES['fscreen']['name'][$key]);
	$filenameimg2 =uniqid().'.webp';
		$image->save('ankfile/'.$filenameimg,IMAGETYPE_JPEG,60);
	//$_FILES['0']['error']	
		echo $filenameimg;
		
		$insertSQL = sprintf("INSERT INTO `tm_typsv_konf_user` (`num`, `user`, `ank`, `value`,`razdel`) VALUES (NULL, %s,%s, %s,%s)",
                       GetSQLValueString($username_test, "int"),
					  GetSQLValueString($_POST["num"], "int"),
 					  GetSQLValueString($filenameimg, "text"),  GetSQLValueString($_POST["razdel"], "int"));
		DB::Query($insertSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link)); 
//	echo $insertSQL;
		exit;
		
	}
	
	
	$delsql="delete from tm_typsv_konf_user where user=$username_test and ank=".$_POST["num"]." and razdel=". GetSQLValueString($_POST["razdel"], "int");
	DB::Query($delsql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link)); 
	$insertSQL = sprintf("INSERT INTO `tm_typsv_konf_user` (`num`, `user`, `ank`, `value`,`razdel`) VALUES (NULL, %s,%s, %s,%s)",
                       GetSQLValueString($username_test, "int"),
					  GetSQLValueString($_POST["num"], "int"),
 					  GetSQLValueString($_POST["val"], "text"),  GetSQLValueString($_POST["razdel"], "int"));
		DB::Query($insertSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link)); 
	echo $insertSQL;
	exit;
	
}



$sql="SELECT 
  `tm_user`.`num`,
  `tm_user`.`fio`,
  `tm_user`.`spec`,
  `tm_user`.`passw`,
  `tm_user`.`act`,
  `tm_user`.`mail`,
  `tm_user`.`mail_pod`,
  `tm_user`.`rss`,
  `tm_user`.`data_nach`,
  `tm_user`.`zav`,
  `tm_user`.`urlico`,
  `tm_user`.`ur_parent`,
  `tm_user`.`post`,
  `tm_user`.`post_addr`,`tm_user`.`personal`,`tm_user`.`grupp`
FROM
  `tm_user`
WHERE
  `tm_user`.`num` = ".$_SESSION['MM_Username1'];

$tzaz =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_tzaz =  /* fixed MMiC */ mysqli_fetch_assoc($tzaz);
if ($row_tzaz['urlico']==1) {header("Location: urlico.php"); exit;  }



if ($_POST["zav"]==1){
//	$.post('nmo.php', {'kr':'1', 'id' :deff1,'name':deff2},		function(data) {		});			
	
$insertSQL="update tm_user set zav=1 where num=$username_test";
//	echo $insertSQL;
	DB::Query($insertSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
	
	include('add_arh.php');
//exit();

}
if (isset($_POST["chdat"])){
//	$.post('nmo.php', {'kr':'1', 'id' :deff1,'name':deff2},		function(data) {		});			
	$razdl=intval($_POST["chdat"]);
$insertSQL="INSERT INTO `nmo_otm_pos` (`num`, `user`, `razdel`, `dat`) VALUES (NULL, $username_test, $razdl, '".date("Y-m-d H:i:s")."') ";
//	echo $insertSQL;
	DB::Query($insertSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
	echo '<span class="badge badge-warning pull-right" style="background-color: #8DA3EC; font-size: 9px">'.date("Y-m-d H:i:s").'</span>';
exit();

}


if ($_POST["personal"]==1){
//	$.post('nmo.php', {'kr':'1', 'id' :deff1,'name':deff2},		function(data) {		});			
	
$insertSQL="update tm_user set personal=1 where num=$username_test";
//	echo $insertSQL;
	DB::Query($insertSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link)); 
exit();

}

if (isset($_FILES['fname'])){
	
$filenameimgf =uniqid().'.jpg';
$finfo = finfo_open(FILEINFO_MIME_TYPE); // return mime type ala mimetype extension
	$finfo=finfo_file($finfo, $_FILES['fname']['tmp_name']);
	if ($finfo=='image/jpeg'){
	try {
   $image = new Imagick($_FILES['fname']['tmp_name']);

	$image->adaptiveResizeImage(800,1200);

	$data = $image->getImageBlob(); 
file_put_contents ('usrimg/'.$filenameimgf, $data); 
		$stash=(int)$_POST['stash'];
		$sql=" INSERT INTO `tm_nmo_user_sam` (`num`, `user`, `stash`, `path`, `filename`) VALUES (NULL, $username_test, $stash, '$filenameimgf', 'стаж')";
		$tpr =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
} catch (Exception $e) {
   // echo 'Выброшено исключение: ',  $e->getMessage(), "\n";
}

}}


if ($_POST["kr"]==1){
//	$.post('nmo.php', {'kr':'1', 'id' :deff1,'name':deff2},		function(data) {		});			
	$insertSQL = sprintf("INSERT INTO `tm_nmo_razd_user` (`id`, `user`, `razdel`, `proydeno`,dat,dop) VALUES (NULL, %s, %s,%s, %s,%s)",
                       GetSQLValueString($username_test, "int"),
					  GetSQLValueString($_POST["razd"], "int"),
 					  GetSQLValueString($_POST["id"], "int"),
					 GetSQLValueString(date('Y-m-d'), "date"),
						  GetSQLValueString($_POST["name"], "text"));

	echo $insertSQL;
	DB::Query($insertSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link)); 
exit(0);

}
if ($_POST["arr"]==1){
	
$zapros="SELECT 
  COUNT(`tm_nmo_user_dat`.`num`) AS `colv`,
  `tm_nmo_prepod_dat`.`vm_chel`
FROM
  `tm_nmo_prepod_spec`
  INNER JOIN `tm_nmo_prepod_dat` ON (`tm_nmo_prepod_spec`.`num` = `tm_nmo_prepod_dat`.`nmo_prepod_spec`)
  INNER JOIN `tm_nmo_user_dat` ON (`tm_nmo_prepod_spec`.`num` = `tm_nmo_user_dat`.`prepod_spec`)
  AND (`tm_nmo_user_dat`.`dat` = `tm_nmo_prepod_dat`.`num`)
WHERE
  `tm_nmo_prepod_spec`.`num` = ".$_POST["psn"]." AND 
  `tm_nmo_prepod_dat`.`nomer_zan` =".$_POST["id"]."  AND 
  `tm_nmo_prepod_dat`.`num` =".$_POST["dat"]." GROUP BY
  `tm_nmo_prepod_dat`.`vm_chel`
"	;
	
$tpr =  /* fixed MMiC */ DB::Query($zapros, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_tpr =  /* fixed MMiC */ mysqli_fetch_assoc($tpr);
if (($row_tpr['colv']>=$row_tpr['vm_chel']) and (mysqli_num_rows($test)>0)){
//echo $zapros;
	
	echo "--0";
}
	
	else {
	
//	$.post('nmo.php', {'kr':'1', 'id' :deff1,'name':deff2},		function(data) {		});			
	$insertSQL = sprintf("INSERT INTO `tm_nmo_user_dat` (`num`, `user`, `dat`,zan,prepod_spec) VALUES (NULL,%s,%s,%s,%s)",
                       GetSQLValueString($username_test, "int"),
					  GetSQLValueString($_POST["dat"], "int"),
 					  GetSQLValueString($_POST["id"], "int"),GetSQLValueString($_POST["psn"], "int"));
		DB::Query($insertSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link)); 
		echo $insertSQL;
}
	
	//
exit(0);

}
//	$.post('nmo.php', {'arr':'1', 'id' :deff,'dat':deff1},	

///////////////

$sqlpr="SELECT DISTINCT 
  COUNT(`tm_nmo_prepod_dat`.`nomer_zan`) AS mx
FROM
  `tm_nmo_prepod_dat`
  INNER JOIN `tm_nmo_prepod_spec` ON (`tm_nmo_prepod_dat`.`nmo_prepod_spec` = `tm_nmo_prepod_spec`.`num`)
WHERE
  `tm_nmo_prepod_spec`.`spec` =$colname_test";
$tpr =  /* fixed MMiC */ DB::Query($sqlpr, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_tpr =  /* fixed MMiC */ mysqli_fetch_assoc($tpr);
$colv_pr=$row_tpr['mx'];
//echo $sqlpr;

$sqq="SELECT DISTINCT 
  `tm_nmo_prepod_spec`.`num`
FROM
  `tm_nmo_prepod_spec`
WHERE
  `tm_nmo_prepod_spec`.`spec` = $colname_test";

$tza =  /* fixed MMiC */ DB::Query($sqq, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_tza =  /* fixed MMiC */ mysqli_fetch_assoc($tza);

///////////////////

$query_test="SELECT 
  `tm_nmo_razd`.`id`,
  `tm_nmo_razd`.`spec`,
  `tm_nmo_razd`.`nazv`,
  `tm_nmo_razd`.`activ`,
  `tm_nmo_razd`.`comment`,
  `tm_nmo_razd`.`num`,
  `tm_nmo_razd`.`img`,
  `tm_nmo_razd`.`prepod`,
  `tm_prepod`.`fio`
FROM
  `tm_nmo_razd`
  LEFT OUTER JOIN `tm_prepod` ON (`tm_nmo_razd`.`prepod` = `tm_prepod`.`num`)
WHERE
  `tm_nmo_razd`.`spec` = $colname_test and activ=1
ORDER BY
  `num`";
$test =  /* fixed MMiC */ DB::Query($query_test, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_test =  /* fixed MMiC */ mysqli_fetch_assoc($test);
$totalRows_test =  /* fixed MMiC */ mysqli_num_rows($test);

//
$sql_name="SELECT 
  `tm_spec`.`nazv`,`tm_spec`.`img`,`tm_spec`.`cena`
FROM
  `tm_spec`
WHERE
  `tm_spec`.`num` = $colname_test";

$test_name =  /* fixed MMiC */ DB::Query($sql_name, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_name =  /* fixed MMiC */ mysqli_fetch_assoc($test_name);
$row_fz =  /* fixed MMiC */ mysqli_fetch_assoc($test_name);

$sql="select * from tm_user where num=$username_test";
$su =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_su =  /* fixed MMiC */ mysqli_fetch_assoc($su);


$sql="SELECT 
  `tm_docs`.`path`,
  `tm_docs`.`nazv`,
  `tm_docs`.`dat`
FROM
  `tm_doc_spec`
  INNER JOIN `tm_docs` ON (`tm_doc_spec`.`doc` = `tm_docs`.`num`)
WHERE
  `tm_doc_spec`.`spec` =  $colname_test AND 
  `tm_docs`.`typ_doc` = 1";
$doca =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_doca =  /* fixed MMiC */ mysqli_fetch_assoc($doca);
$totalrow_doca =  /* fixed MMiC */ mysqli_num_rows($doca);


$sql="SELECT 
  `tm_docs`.`path`,
  `tm_docs`.`nazv`,
  `tm_docs`.`dat`
FROM
  `tm_doc_spec`
  INNER JOIN `tm_docs` ON (`tm_doc_spec`.`doc` = `tm_docs`.`num`)
WHERE
  `tm_doc_spec`.`spec` =  $colname_test AND 
  `tm_docs`.`typ_doc` = 0";
$doca2 =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_doca2 =  /* fixed MMiC */ mysqli_fetch_assoc($doca2);
$totalrow_doca2 =  /* fixed MMiC */ mysqli_num_rows($doca2);
/////

$sql="SELECT 
  `tm_addr_otprav`.`num`,
  `tm_addr_otprav`.`inn`,
  `tm_addr_otprav`.`oblast`,
  `tm_addr_otprav`.`rayon`,
  `tm_addr_otprav`.`gorod`,
  `tm_addr_otprav`.`dom`,
  `tm_addr_otprav`.`kv`,
  `tm_addr_otprav`.`Fam`,
  `tm_addr_otprav`.`Name`,
  `tm_addr_otprav`.`Otch`,
  `tm_addr_otprav`.`ind`,
  `tm_addr_otprav`.`comment`,
  `tm_addr_otprav`.`ulica`
FROM
  `tm_addr_otprav`
WHERE
  `tm_addr_otprav`.`inn` = ".$_SESSION['MM_Username1'];
$otprav =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_otprav =  /* fixed MMiC */ mysqli_fetch_assoc($otprav);
$totalrow_otprav =  /* fixed MMiC */ mysqli_num_rows($otprav);


$sql="SELECT 
  `tm_sert`.`num`,
  `tm_sert`.`nazv`,
  `tm_sert`.`path`
FROM
  `tm_spec`
  INNER JOIN `tm_sert` ON (`tm_spec`.`sert` = `tm_sert`.`num`)
WHERE
  `tm_spec`.`num` = $colname_test";
$sert =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_sert =  /* fixed MMiC */ mysqli_fetch_assoc($sert);
$totalrow_sert =  /* fixed MMiC */ mysqli_num_rows($sert);

$sql="SELECT 
  `tm_obiav`.`id`,
  `tm_obiav`.`tex`,
  `tm_obiav`.`dat`,
  `tm_obiav`.`expir`,
  `tm_obiav`.`kurator`,
  `tm_obiav`.`spec`,
  `tm_obiav`.`grupp`,
  `tm_prepod`.`fio`
FROM
  `tm_obiav`
  INNER JOIN `tm_prepod` ON (`tm_obiav`.`kurator` = `tm_prepod`.`num`)
WHERE (`tm_obiav`.`expir`>='".date("Y-m-d")."') and
  `tm_obiav`.`id` NOT IN (SELECT `tm_user_obiav`.`obiav` FROM `tm_user_obiav` WHERE `tm_user_obiav`.`user` = $username_test) AND 
  (`tm_obiav`.`spec` = $colname_test or `tm_obiav`.`spec` = -1)";
$tm_obiav =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_tm_obiav =  /* fixed MMiC */ mysqli_fetch_assoc($tm_obiav);
$totalrow_tm_obiav =  /* fixed MMiC */ mysqli_num_rows($tm_obiav);
//echo $sql;

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo $row_name['nazv']; ?></title>

<!-- Bootstrap -->
<link rel="stylesheet" href="css/bootstrap.css">
<link rel="stylesheet" href="css/icofont.css">
	<script src="js/jquery-1.11.3.min.js"></script> 
	<script src="js/bootstrap.js"></script>
<style>




    .drop-shadow {
        -webkit-box-shadow: 0 0 2px 1px rgba(0, 0, 0, .5);
        box-shadow: 0 0 2px 1px rgba(0, 0, 0, .5);
    }
    .container.drop-shadow {
        padding-left:0;
        padding-right:0;
    }
</style>
<link rel="stylesheet" href="nmo.css">
	   <style type="text/css">
   
    .input-group-addon.primary {
    color: rgb(255, 255, 255);
    background-color: rgb(50, 118, 177);
    border-color: rgb(40, 94, 142);
}
.input-group-addon.success {
    color: rgb(255, 255, 255);
    background-color: rgb(92, 184, 92);
    border-color: rgb(76, 174, 76);
}
.input-group-addon.info {
    color: rgb(255, 255, 255);
    background-color: rgb(57, 179, 215);
    border-color: rgb(38, 154, 188);
}
.input-group-addon.warning {
    color: rgb(255, 255, 255);
    background-color: rgb(240, 173, 78);
    border-color: rgb(238, 162, 54);
}
.input-group-addon.danger {
    color: rgb(255, 255, 255);
    background-color: rgb(217, 83, 79);
    border-color: rgb(212, 63, 58);
}    
	.checkbox {
	position: absolute;
	z-index: -1;
	opacity: 0;
	margin: 10px 0 0 20px;
}
.checkbox + label {
	position: relative;
	padding: 0 0 0 60px;
	cursor: pointer;
}
.checkbox + label:before {
	content: '';
	position: absolute;
	top: -4px;
	left: 0;
	width: 50px;
	height: 26px;
	border-radius: 13px;
	background: #CDD1DA;
	box-shadow: inset 0 2px 3px rgba(0,0,0,.2);
	transition: .2s;
}
.checkbox + label:after {
	content: '';
	position: absolute;
	top: -2px;
	left: 2px;
	width: 22px;
	height: 22px;
	border-radius: 10px;
	background: #FFF;
	box-shadow: 0 2px 5px rgba(0,0,0,.3);
	transition: .2s;
}
.checkbox:checked + label:before {
	background: #9FD468;
}
.checkbox:checked + label:after {
	left: 26px;
}
.checkbox:focus + label:before {
	box-shadow: inset 0 2px 3px rgba(0,0,0,.2), 0 0 0 3px rgba(255,255,0,.7);
}
.radio {
	position: absolute;
	z-index: -1;
	opacity: 0;
	margin: 10px 0 0 7px;
}	
.radio + label {
	position: relative;
	padding: 0 0 0 35px;
	cursor: pointer;
}
.radio + label:before {
	content: '';
	position: absolute;
	top: -3px;
	left: 0;
	width: 22px;
	height: 22px;
	border: 1px solid #CDD1DA;
	border-radius: 50%;
	background: #FFF;
}
.radio + label:after {
	content: '';
	position: absolute;
	top: 1px;
	left: 4px;
	width: 16px;
	height: 16px;
	border-radius: 50%;
	background: #9FD468;
	box-shadow: inset 0 1px 1px rgba(0,0,0,.5);
	opacity: 0;
	transition: .2s;
}
.radio:checked + label:after {
	opacity: 1;
}
.radio:focus + label:before {
	box-shadow: 0 0 0 3px rgba(255,255,0,.7);
}		
.disabledbutton {
    pointer-events: none;
    opacity: 0.8;
}	
.panel-default{
<?php if ($row_tzaz['personal']=='') echo "display:none;"; ?>	
	
}
		   .aler{
			   max-width: 420px;
			   position: fixed;
			   z-index: 10000;
			   margin: 20px;
		   }
</style>
<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <?php if ($colname_test==73) echo '<script src="http://code-ya.jivosite.com/widget/1VrVB2zl1z" async></script>';?>
</head>
<body>
<?php if ($colname_test!=73) include("header.php");?>
<?php if ($colname_test==73) include("aheader.php");?>
<div class="aler">

					
		<?php	if($totalrow_tm_obiav>0)   do {  ?>

		<div class="alert alert-info alert-dismissible" role="alert" data-id=" <?php echo $row_tm_obiav['id'];?>">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span></button>
			  <h4 class="alert-heading">  <?php echo $row_tm_obiav['fio'];?></h4>
			
			
   <?php echo $row_tm_obiav['tex'];?>
</div>
       
			   <?php 
} while ($row_tm_obiav = mysqli_fetch_assoc($tm_obiav)	);?>

	</div>
	
	
	<div id="myModalBox" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- Заголовок модального окна -->
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title">Показ видео</h4>
      </div>
      <!-- Основное содержимое модального окна -->
      <div class="modal-body">
       
		 <div id="dfm"></div>
		  
		
		  
      </div>
      <!-- Футер модального окна -->
      <div class="modal-footer">
        <button type="button" class="btn btn-info" data-dismiss="modal">Закрыть</button>
       
      </div>
    </div>
  </div>
</div>	
	<div id="myModalBox2" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- Заголовок модального окна -->
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title">Предупреждение</h4>
      </div>
      <!-- Основное содержимое модального окна -->
      <div class="modal-body">
       
		 <div id="dfm2"></div>
		  
		
		  
      </div>
      <!-- Футер модального окна -->
      <div class="modal-footer">
        <button type="button" class="btn btn-info" data-dismiss="modal">Закрыть</button>
       
      </div>
    </div>
  </div>
</div>
	
	
    <!--[if lt IE 8]>
    <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->  
    <!-- Body main wrapper start -->
    <div class="wrapper">
        <!-- Start of header area -->
        
        <!-- mobile-menu-area start -->
         <header class="header-area">
   
        </header>
        <!-- mobile-menu-area end -->
        <!-- End of header area -->
        <section class="breadcrumbs-area bg-3 ptb-110 bg-opacity bg-relative">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <div class="breadcrumbs">
                            <h2 class="page-title"><?php echo $row_name['nazv']; ?></h2>
                          
                        </div>
                    </div>
                </div>
            </div>
        </section>
		
		<section class="courses-details pt-110 pb-80">
  <div class="row">
    <div class="col-lg-12 page-header text-center">
  
    </div>
  </div>
  <div class="container">
    <div class="row">
    
   
    </div>
    <div class="row">
    

   
		<?php if(($row_us['oplata']!=1) and ($row_name['cena']>0)) { ?>
		<div class="panel panel-info">
				<div class="panel-heading">Оплата обучения</div>
  <div class="panel-body"> <div class="form-inline">	<h3>Стоимость обучения <?php echo $row_name['cena']; ?> руб</h3><?php include('opl.php');?>
	<br>
	  После оплаты вам будут доступно обучение
	  </div>
</div>
</div><?php }  else { ?>
		
    	<div class="panel panel-danger">
  <div class="panel-body">	<input type="checkbox" class="checkbox" id="ccb" <?php if ($row_tzaz['personal']!='') echo "checked disabled"; ?>/> <label for="ccb">Я согласен на обработку моих персональных данных (<a href="http://medzel.ru/spravki/pers.html#" target="_blank">полный текст соглашения</a>)</label>
</div>
</div>
    	
	  <?php 
		if ($row_su['zav']==1){
			?>
			<div class="panel panel-default">
  <div class="panel-body"><h3>Поздравляем, вы прошли обучение, за получением оригинала сертификата обращайтесь к нашим менеджерам</h3>
	
	  <form name="post" action="pochta.php" method="post" id="post">
    <div class="col-md-4 col-lg-4 col-sm-6">
		 <input type="hidden" name="fm" value="1">
		<?php  if($row_tzaz['post_addr']==''){?>
                      <input type="radio" class="radio" id="radio1"   name="radio" value="1" <?php  if($row_tzaz['post']==1) echo 'checked="checked"';?>/>
<label for="radio1"> Я хочу получить сертификат лично</label>
		<br><?php } ?>
    <input   name="radio" type="radio" class="radio" id="radio2" value="2" <?php  if($row_tzaz['post']==2) echo 'checked="checked"';?>/>
<label for="radio2">Я хочу получить сертификат по почте</label>

                    </div>
	 <?php  if($row_tzaz['post_addr']==''){?>  <div class="col-md-4 col-lg-4 col-sm-6">
                        <div class="">
                                        <a class="button btn btn-info" href="#" id="zpost" style=" <?php   if($row_tzaz['post']==1){ echo ' display: none';} ?>">
                                            <span><?php if ($totalrow_otprav>0) echo "Исправить адрес доставки";else echo "Заказать"; ?></span>
											
                                        </a>
                                    </div>
                    </div><?php } ?>
	  
	  </form>
	 
	  <table class="table table-striped" id="taddr" style="height: auto; <?php   if($row_tzaz['post']==1){ echo ' display: none';} ?>">

  <tbody>
    <tr >
      <td>Фамилия</td>
      <td><?php echo $row_otprav['Fam'];?></td>
      
    </tr>
    <tr>
      <td>Имя</td>
      <td><?php echo $row_otprav['Name'];?></td>
      
    </tr>
    <tr>
      <td>Отчество</td>
      <td><?php echo $row_otprav['Otch'];?></td>
      
    </tr>
	   <tr>
      <td>Адрес</td>
      <td> <?php echo $row_otprav['ind'];?> <?php echo $row_otprav['oblast'];?>, <?php echo $row_otprav['rayon'];?>, <?php echo $row_otprav['gorod'];?>, <?php echo $row_otprav['ulica'];?>, дом:<?php echo $row_otprav['dom'];?>, кв:<?php echo $row_otprav['kv'];?></td>
      
    
    <tr>
      <td>Комментарий</td>
      <td><?php echo $row_otprav['comment'];?></td>
      
    </tr>
	  
	   <?php  if($row_tzaz['post_addr']!=''){?> <tr>
      <td >Трек посылки:  <?php echo $row_tzaz['post_addr'];?></td>
     
       <td  valign="middle"><span ></span><a class="button"href="https://www.pochta.ru/tracking#<?php echo $row_tzaz['post_addr'];?>"  target="_blank">
                                            <span>Проверть трек по почте</span>
											
                                        </a></td>
    </tr><?php } else { ?>
	   <tr>
      <td colspan="2">После того как сертификат будет отослан, на вашу почту будет отправлено письмо с номером трека</td>
     
      
    </tr>
	  <?php }?>
  </tbody>
</table>
	
	
     
    </div>
    <p><div class="caption"  style="padding-left: 20px">
         
            <p><a href="./sert/<?php echo $row_sert['path'];?>?stud=<?php echo $row_su['num'];?>&pr " class="btn btn-primary" role="button"><span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span> Сохранить</a></p>
        </div></p>
      <div class="thumbnail"> <img src="./sert/<?php echo $row_sert['path'];?>?stud=<?php echo $row_su['num'];?>" alt="" class="img-responsive">
        
  </div>
</div>
			
			<?
		}
		else {
		
		?>
		
		<div class="panel panel-info">
  <div class="panel-heading">Документы к курсу</div>
  <div class="panel-body">
    <?php if ($totalrow_doca2>0){ ?>
   
     	  <?php do { ?>
    <div class="input-group">
		  <span class="input-group-addon">
             <i class="icofont icofont-book"></i>
      </span>
      <input type="text" class="form-control" value="<?php echo $row_doca2['nazv'];?>">
        <span class="input-group-addon info">
      <a href="docs/<?php echo $row_doca2['path'];?>" target="_blank"> <i class="icofont icofont-learn"></i>скачать
            </span></a>
    </div> <br>
	 <?php } while ($row_doca2 =  /* fixed MMiC */ mysqli_fetch_assoc($doca2));
			 mysqli_data_seek($doca2,1);				  
		  ?>
	  <?php }?>
  </div>
</div>
					
	  <div class="panel-group" id="accordion">
<?php 
			if ($colv_pr>0)
do {
			
			
			
$zann="SELECT DISTINCT 
  `tm_nmo_prepod_dat`.`nomer_zan`,
  `tm_nmo_prepod_spec`.`num` AS `psn`, `tm_nmo_prepod_spec`.`comment` AS `comment`,
  `tm_nmo_prepod_spec`.`predmet`,
  `tm_prepod`.`fio`
FROM
  `tm_nmo_prepod_dat`
  INNER JOIN `tm_nmo_prepod_spec` ON (`tm_nmo_prepod_dat`.`nmo_prepod_spec` = `tm_nmo_prepod_spec`.`num`)
  INNER JOIN `tm_prepod` ON (`tm_nmo_prepod_spec`.`prepod` = `tm_prepod`.`num`)
WHERE
  `tm_nmo_prepod_spec`.`spec` =  $colname_test 
  and  `tm_nmo_prepod_spec`.`num`=".$row_tza['num']."
  ";
$tpra =  /* fixed MMiC */ DB::Query($zann, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_tpra =  /* fixed MMiC */ mysqli_fetch_assoc($tpra);

//echo $zann;		  
		  ?>  
	 		  
		  <div class="panel panel-default">
  <div class="panel-heading">Стажировка: <?php echo $row_tpra['fio']; ?>-<?php echo $row_tpra['predmet']; ?></div>
  <div class="panel-body ">
	  <p>
	  
	  <?php
		$sqlaa="SELECT 
  `tm_nmo_user_sam`.`num`,
  `tm_nmo_user_sam`.`user`,
  `tm_nmo_user_sam`.`stash`,
  `tm_nmo_user_sam`.`path`,
  `tm_nmo_user_sam`.`filename`
FROM
  `tm_nmo_user_sam`
WHERE
  `tm_nmo_user_sam`.`stash` = ".$row_tza['num']." AND 
  `tm_nmo_user_sam`.`user` = $username_test";
	//echo $sqlaa;
	$sasa =  /* fixed MMiC */ DB::Query($sqlaa, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_sasa =  /* fixed MMiC */ mysqli_fetch_assoc($sasa);
	$totalrow_sasa =  /* fixed MMiC */ mysqli_num_rows($sasa);
		?>
		 <?php if ($totalrow_sasa>0){?>	
		 
		<div>
		<input type="checkbox" class="checkbox" disabled="disabled" checked/> <label for="checkbox<?php echo $row_tpra['psn']; ?>">Я прохожу стажировку самостоятельно</label>
		
		<div class="input-group">
		  <span class="input-group-addon">
             <i class="icofont icofont-book"></i>
      </span>
      <input type="text" class="form-control" value="Заполненный лист стажировки">
        <span class="input-group-addon info">
      <a  href="usrimg/<?php echo $row_sasa['path'];?>" target="_blank"> <i class="icofont icofont-learn"></i>скачать
            </span></a>
    </div>	
			
			<br>
		<?php }?>	
     
	 <?php if ($totalrow_sasa<1){?>	
      <input type="checkbox" class="checkbox samo" id="checkbox<?php echo $row_tpra['psn']; ?>" data-num="<?php echo $row_tpra['psn']; ?>"/>
      <label for="checkbox<?php echo $row_tpra['psn']; ?>">Я прохожу стажировку самостоятельно</label>
    </p>
	  <form id="form<?php echo $row_tpra['psn']; ?>" method="post" enctype="multipart/form-data">
    <div id="dload<?php echo $row_tpra['psn']; ?>" style='display: none'><div class="input-group">
		
		<span class="input-group-addon">
             <i class="icofont icofont-book"></i>
      </span>
      <input type="file" class="form-control" name="fname"  accept="image/jpeg" id="fnna<?php echo $row_tpra['psn']; ?>">
		<input type="hidden" name="stash" value="<?php echo $row_tpra['psn']; ?>" >
        <span class="input-group-addon info">
      <a href="#"  class="zdounload" data-num="<?php echo $row_tpra['psn']; ?>"> <i class="icofont icofont-upload"></i> Загрузить
            </span></a>
    </div> 
	  
	  <p>
		  
	 Загрузите заполненный и отсканированный лист стажировки с печатью и подписью руководителя. формат -JPG</p>
	  </div></form>
      <p>
		  
	 
	 
	  <?php echo $row_tpra['comment']; ?>
    </p>
	 
	  <div id="dstach2<?php echo $row_tpra['psn']; ?>">Выберите доступное для вас время стажировки, если вариантов стажировки нет, следует обратится к куратору</div>
	  <br>
	  
<div class="row form-inline" id="dstach<?php echo $row_tpra['psn']; ?>">
			   <?php do { ?>	 
	  <form>
  <div class="col-xs-12 col-sm-4" style="min-height:40px">
	  
	  
	  
	  
<div class="input-group"  style=" margin:5px; border-top-right-radius: 6px;   
            border-bottom-right-radius: 6px; border-right: solid 1px #ccc;">
  

	<span class="input-group-addon info">Занятие № <?php echo $row_tpra['nomer_zan']; ?></span>
	 
	 
<?
	 $sql="
	 SELECT 
  `tm_nmo_prepod_dat`.`dat`, `tm_nmo_prepod_dat`.`time`, `tm_nmo_prepod_dat`.`comment`,
  `tm_nmo_user_dat`.`num`,
  1 AS `vbr`,
  `tm_nmo_user_dat`.`dat` as saa
FROM
  `tm_nmo_user_dat`
  INNER JOIN `tm_nmo_prepod_dat` ON (`tm_nmo_user_dat`.`dat` = `tm_nmo_prepod_dat`.`num`)
WHERE
  `tm_nmo_user_dat`.`zan` =".$row_tpra['nomer_zan']." AND 
  `tm_nmo_user_dat`.`user` = $username_test
 AND
 `tm_nmo_prepod_dat`.`nmo_prepod_spec`=".$row_tza['num']."
UNION

SELECT 
  `tm_nmo_prepod_dat`.`dat`,`tm_nmo_prepod_dat`.`time`,`tm_nmo_prepod_dat`.`comment`,
  `tm_nmo_prepod_dat`.`num`,
  0 AS `vbr`,
 0 as saa
FROM
  `tm_nmo_prepod_dat`
  INNER JOIN `tm_nmo_prepod_spec` ON (`tm_nmo_prepod_dat`.`nmo_prepod_spec` = `tm_nmo_prepod_spec`.`num`)
WHERE
  `tm_nmo_prepod_dat`.`num` not in (SELECT  `tm_nmo_user_dat`.`dat`
FROM
  `tm_nmo_user_dat` WHERE
  `tm_nmo_user_dat`.`zan` =".$row_tpra['nomer_zan']." AND 
  `tm_nmo_user_dat`.`user` = $username_test) AND
  `tm_nmo_prepod_dat`.`nomer_zan` =".$row_tpra['nomer_zan']." AND 
  `tm_nmo_prepod_spec`.`spec` =  $colname_test
   AND
 `tm_nmo_prepod_dat`.`nmo_prepod_spec`=".$row_tza['num']."
  and `tm_nmo_prepod_dat`.`dat`>'".date("Y-m-d")."'
  
  ";
	//echo  $sql;
	$testz =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_testz =  /* fixed MMiC */ mysqli_fetch_assoc($testz);
$totalRows_testz =  /* fixed MMiC */ mysqli_num_rows($testz);	 
		$ffl=0; 
	 ?> 
	
	<?php if ($row_testz['vbr']==1) {$ffl=1;?>  <input type="text" class="form-control" value="<?php echo $row_testz['dat']; ?>  <?php echo date ('H:i',strtotime($row_testz['time'])); ?>"  disabled><?php  }else { ?>
	<?php if ($totalRows_testz>0){?>
	<select name="lista" class="form-control" id="li<?php echo $row_tpra['nomer_zan'];?>_<?php echo $row_tza['num'];?>">
	  <?php  do { ?>	
	 
		 <?php 
		 $tst="SELECT 
  COUNT(`tm_nmo_user_dat`.`num`) AS `colv`,
  `tm_nmo_prepod_dat`.`vm_chel`
FROM
  `tm_nmo_prepod_spec`
  INNER JOIN `tm_nmo_prepod_dat` ON (`tm_nmo_prepod_spec`.`num` = `tm_nmo_prepod_dat`.`nmo_prepod_spec`)
  INNER JOIN `tm_nmo_user_dat` ON (`tm_nmo_prepod_spec`.`num` = `tm_nmo_user_dat`.`prepod_spec`)
  AND (`tm_nmo_user_dat`.`dat` = `tm_nmo_prepod_dat`.`num`)
WHERE
  `tm_nmo_prepod_spec`.`num` = ".$row_tpra['psn']." AND 
  `tm_nmo_prepod_dat`.`nomer_zan` = ".$row_tpra['nomer_zan']." AND 
  `tm_nmo_prepod_dat`.`num` = ".$row_testz['num']."
GROUP BY
  `tm_nmo_prepod_dat`.`vm_chel`";
	//	 echo $tst;
	$tz =  /* fixed MMiC */ DB::Query($tst, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_tz =  /* fixed MMiC */ mysqli_fetch_assoc($tz);
		 $totalRows_tz =  /* fixed MMiC */ mysqli_num_rows($tz);	
		 if (($row_tz['colv']<$row_tz['vm_chel']) or ($totalRows_tz<1)){
		 ?>
		 
            <option value="<?php echo $row_testz['num']; ?>"><?php echo $row_testz['dat']; ?> <?php echo date ('H:i',strtotime($row_testz['time'])); ?></option><?php }  ?>
	 <?php } while ($row_testz =  /* fixed MMiC */ mysqli_fetch_assoc($testz)); ?>	
	  </select>
	
	<?php } else {
		 
		?><p class="form-control">нет вариантов</p> <?php 
	 }?>
	
	
	<?php } ?>
	 <?php if (($row_testz['vbr']==1) and ($row_testz['dat']<=date("Y-m-d"))){?><span class="input-group-addon danger"><span class="icofont icofont-calendar" aria-hidden="true"></span></span><?php }?>
 <?php if (($row_testz['vbr']==1) and ($row_testz['dat']>date("Y-m-d"))){?><span class="input-group-addon info"><a href="#"  data-comment="<?php echo $row_testz['comment'];?>" class="shw"><span class="icofont icofont-calendar" aria-hidden="true"></span></a></span><?php }?>
</div>

	
		 
  
 <?php if (($ffl==0) and ($totalRows_testz>0)){?>  
     
	  
 <button class="btn btn-danger dsel" type="button" data-num="<?php echo $row_tpra['nomer_zan'];?>" data-psn="<?php echo $row_tpra['psn'];?>">сохр</button>
  	<?php } ?>

	 </div> </form>
			   <?php } while ($row_tpra =  /* fixed MMiC */ mysqli_fetch_assoc($tpra)); ?>	
        <br>
	
</div>
	<?php } ?>  
	  	
			<br>
	  <?php if ($totalrow_doca>0){ ?>
   
     	  <?php do { ?>
     	  <?php if($row_doca['path']!=''){ ?>
    <div class="input-group">
		  <span class="input-group-addon">
             <i class="icofont icofont-book"></i>
      </span>
      <input type="text" class="form-control" value="<?php echo $row_doca['nazv'];?>">
        <span class="input-group-addon info">
      <a href="docs/<?php echo $row_doca['path'];?>" target="_blank"> <i class="icofont icofont-learn"></i>скачать
            </span></a>
    </div> <br><?php } ?>
	 <?php } while ($row_doca =  /* fixed MMiC */ mysqli_fetch_assoc($doca));
			 mysqli_data_seek($doca,0);				  
		  ?>
	  <?php }?>
  </div>
		  
</div>
	 <?php } while ($row_tza =  /* fixed MMiC */ mysqli_fetch_assoc($tza));	  ?>		
  
		  
		    <?php
		  
		  
		  
		  $x=$row_test['id']; 
		  if (isset($_GET['row']))$x=$_GET['row']; else  $_GET['row']=$x;
		  
		  $stop=0; 
			
			
			do {
	
		  ?> </div>
  <!-- 1 панель тут мы начинаем радел -->
  <div class="panel panel-default drop-shadow" id="allp<?php echo $row_test['id']; ?>">
    <!-- Заголовок 1 панели -->
    <div class="panel-heading sidebar-title">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne<?php echo $row_test['id']; ?>" ><span style="font-size: small;"><?php echo $row_test['nazv']; ?></span></a>
		  <span class="badge badge-warning pull-right" style="background-color: #b48dec; font-size: 9px"> <?php echo $row_test['fio']; ?></span>
		  <?php
		   $sql="SELECT 
  `tm_prepod`.`fio`
FROM
  `tm_nmo_razd_dop_prepod`
  INNER JOIN `tm_prepod` ON (`tm_nmo_razd_dop_prepod`.`prepod` = `tm_prepod`.`num`)
WHERE
  `tm_nmo_razd_dop_prepod`.`razdel` = ".$row_test['id'];
		  $tzpr =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_tzpr =  /* fixed MMiC */ mysqli_fetch_assoc($tzpr);
		do{ echo '<span class="badge badge-warning pull-right" style="background-color: #b48dec; font-size: 9px">'.$row_tzpr['fio'].'</span>';	 } while ($row_tzpr =  /* fixed MMiC */ mysqli_fetch_assoc($tzpr));	  ?>	
	
		  
		 
      </h4>
    </div>
    <div id="collapseOne<?php echo $row_test['id']; ?>" class="panel-collapse collapse <?php// if($row_test['id']==$x) echo "in"; ?>">
      <!-- Содержимое 1 панели -->
      <div class="panel-body">
		<!--были ли на паре  -->	  
		<div class="panel panel-default">
  <div class="panel-heading"><input type="checkbox" class="chepar checkbox" 
						<?php 	
			 $sql="SELECT 
  `nmo_otm_pos`.`dat`
FROM
  `nmo_otm_pos`
WHERE
  `nmo_otm_pos`.`user` = $username_test AND 
  `nmo_otm_pos`.`razdel` = ".$row_test['id']." and `nmo_otm_pos`.`dat` >  '".date("Y-m-d H:0:1")."' and `nmo_otm_pos`.`dat`<'".date("Y-m-d H:59:59")."'"; 
  
				  $tzpr3 =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));

			 if (mysqli_num_rows($tzpr3)>0) echo "disabled checked"	
			
									
									?>
									
									id="opr<?php echo $row_test['id']; ?>" data-id="<?php echo $row_test['id']; ?>">
<label for="opr<?php echo $row_test['id']; ?>">отметка о нахождении на паре</label></div>
  <div class="panel-body" id="bopr<?php echo $row_test['id']; ?>">
    <?php  $sql="SELECT 
  `nmo_otm_pos`.`dat`
FROM
  `nmo_otm_pos`
WHERE
  `nmo_otm_pos`.`user` = $username_test AND 
  `nmo_otm_pos`.`razdel` = ".$row_test['id']."
ORDER BY
  `nmo_otm_pos`.`dat`";
				  $tzpr2 =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_tzpr2 =  /* fixed MMiC */ mysqli_fetch_assoc($tzpr2);
		do{ echo '<span class="badge badge-warning pull-right" style="background-color: #68A2E8; font-size: 9px">'.$row_tzpr2['dat'].'</span>';	 } while ($row_tzpr2 =  /* fixed MMiC */ mysqli_fetch_assoc($tzpr2));	  ?>	
	 
  </div>
</div>  
		<!--были ли на паре  -->
		  
		  
		  
		   <?php //if ( $stop==0)
		  
		  
		  {?> 

		  
		  <!-- Содержимое Блока коммент -->  
	  <div class="wp-block property list">
        <div class="wp-block-body">
			
			<?php if($row_test['img']!='0') {?>
          <div class="wp-block-img">
          <?php if(strripos($row_test['img'],'minimg')>0){ ?> <img src="<?php echo $row_test['img']; ?>" alt=""><?php } else { ?>
              <img src="../nmo/img/<?php echo $row_test['img']; ?>" alt=""><?php } ?>
          
          </div>
			   <?php } ?>
          <div class="wp-block-content">
            <small>
<span class="icofont icofont-calendar" aria-hidden="true"></span> <?php echo date("d.m.y");?></small>
            <h4 class="content-title"><?php echo $row_test['nazv']; ?></h4>
            <p class="description"><?php echo $row_test['comment']; ?></p>
           
          
          </div>
        </div>
     
      </div>	  
		  
				  <div class="btn-group" data-toggle="buttons">
    <label class="btn btn-warning active lp1" data-id="<?php echo $row_test['id'];?>">
        <input type="radio"  name="options" autocomplete="off" checked> лекс/прак
    </label>
    <label class="btn btn-warning lp2" data-id="<?php echo $row_test['id'];?>">
        <input type="radio"  name="options"  autocomplete="off"> лекции
    </label>
    <label class="btn btn-warning lp3" data-id="<?php echo $row_test['id'];?>">
        <input type="radio"  name="options"   autocomplete="off"> практ
    </label>
</div>  
			  <p>
			  
	<?php 
if (!isset($row_test['id']))$row_test['id']=-1;
/*
проверка на оплату

*/
		   
	$sql="SELECT 
  `tm_nmo_razd_media`.`id`,
  `tm_nmo_razd_media`.`tm_nmo_razd`,
  `tm_nmo_razd_media`.`path`,
  `tm_nmo_razd_media`.`tip`,
  `tm_nmo_razd_media`.`act`,
  `tm_nmo_razd_media`.`obyaz`,
  `tm_nmo_razd_media`.`num`,
  `tm_nmo_razd_media`.`comment`,
  `tm_nmo_razd_media`.`dop_file`,
  `tm_nmo_razd_media`.`nazv`,
  `tm_nmo_razd_media`.`povt`,
  `tm_nmo_razd_media`.`data_act`,
  `tm_nmo_razd_media`.`data_okon`, `tm_nmo_razd_media`.`kvn`,`tm_nmo_razd_media`.`passw`
FROM
  `tm_nmo_razd_media`
WHERE
  `tm_nmo_razd_media`.`tip` = 11 AND 
  `tm_nmo_razd_media`.`tm_nmo_razd` =".$row_test['id']." AND 
  `tm_nmo_razd_media`.`id` NOT IN (SELECT 
  `tm_nmo_user_media_opl`.`media_razd`
FROM
  `tm_nmo_user_media_opl`
WHERE
  `tm_nmo_user_media_opl`.`user` = $username_test)";
$opl =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_opl =  /* fixed MMiC */ mysqli_fetch_assoc($opl);
$totalrow_opl =  /* fixed MMiC */ mysqli_num_rows($opl);
		   $oplt = explode(",", $row_opl['path']);
		foreach ($oplt as $val) {
   $oplat[]=[];
	 $oplat[count($oplat)-1][0]=$val;
	$oplat[count($oplat)-1][1]=$row_opl['id'];		
}
	 
//print_r($oplat);		   
/**/		   
		   
		   
		   
		   
		$sql="SELECT 
  `tm_nmo_razd_media`.`id`,
  `tm_nmo_razd_media`.`tm_nmo_razd`,
  `tm_nmo_razd_media`.`path`,
  `tm_nmo_razd_media`.`tip`,
  `tm_nmo_razd_media`.`act`,
  `tm_nmo_razd_media`.`obyaz`,
  `tm_nmo_razd_media`.`num`,
  `tm_nmo_razd_media`.`comment`,
  `tm_nmo_razd_media`.`dop_file`,
  `tm_nmo_razd_media`.`nazv`,
  `tm_nmo_razd_media`.`povt`,
  `tm_nmo_razd_media`.`data_act`,
  `tm_nmo_razd_media`.`data_okon`,`tm_nmo_razd_media`.`tippr`, `tm_nmo_razd_media`.`kvn`,`tm_nmo_razd_media`.`pop`,`tm_nmo_razd_media`.`passw`
FROM
  `tm_nmo_razd_media`
WHERE
  `tm_nmo_razd_media`.`tm_nmo_razd` =".$row_test['id']." order by  `tm_nmo_razd_media`.`num`"; 
	//	echo 	$sql;
					  $razd =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_razd =  /* fixed MMiC */ mysqli_fetch_assoc($razd);
$totalRows_razd =  /* fixed MMiC */ mysqli_num_rows($razd);
			  ?>
			  
		  <?php   $stop=0; do {  ?>
                  <?php if ($row_razd['tip']==21) {?> <div class="panel  panel-warning  <?php echo $row_razd['tippr']; ?>"><?php }?>
                      <?php if ($row_razd['tip']==20) {?> <div class="panel  panel-info  <?php echo $row_razd['tippr']; ?>"><?php }?>
		 <?php if ($row_razd['tip']==1) {?> <div class="panel  panel-info  <?php echo $row_razd['tippr']; ?>"><?php }?>
		   <?php if ($row_razd['tip']==2) {?> <div class="panel panel-success lb_<?php echo $row_razd['tippr']; ?>"><?php }?>
		    <?php if ($row_razd['tip']==3) {?> <div class="panel panel-danger lb_<?php echo $row_razd['tippr']; ?>"><?php }?>
		  		   
		    <?php if ($row_razd['tip']==4) {?> <div class="panel panel-warning lb_<?php echo $row_razd['tippr']; ?>"><?php }?>
		   <?php if ($row_razd['tip']==5) {?> <div class="panel panel-danger lb_<?php echo $row_razd['tippr']; ?>"><?php }?>
		       <?php if ($row_razd['tip']==6) {?> <div class="panel panel-info lb_<?php echo $row_razd['tippr']; ?>"><?php }?>
		        <?php if ($row_razd['tip']==7) {?> <div class="panel panel-warning lb_<?php echo $row_razd['tippr']; ?>"><?php }?>
		  <?php if ($row_razd['tip']==10) {?> <div class="panel panel-warning lb_<?php echo $row_razd['tippr']; ?>"><?php }?>
		    <?php if ($row_razd['tip']==11) {?> <div class="panel panel-warning lb_<?php echo $row_razd['tippr']; ?>"><?php }?>
		    <?php if ($row_razd['tip']==12) {?> <div class="panel panel-info lb_<?php echo $row_razd['tippr']; ?>"><?php }?>
		    <?php if ($row_razd['tip']==13) {?> <div class="panel panel-success lb_<?php echo $row_razd['tippr']; ?>"><?php }?>
		  	    <?php if ($row_razd['tip']==15) {?> <div class="panel panel-default lb_<?php echo $row_razd['tippr']; ?>"><?php }?>
		    <?php if ($row_razd['tip']==16) {?> <div class="panel panel-warning lb_<?php echo $row_razd['tippr']; ?>"><?php }?>
		  		    <?php if ($row_razd['tip']==17) {?> <div class="panel panel-warning lb_<?php echo $row_razd['tippr']; ?>"><?php }?>
		  		  		    <?php if ($row_razd['tip']==18) {?> <div class="panel panel-warning lb_<?php echo $row_razd['tippr']; ?>"><?php }?>
		  		  		    <?php if ($row_razd['tip']==19) {?> <div class="panel panel-warning lb_<?php echo $row_razd['tippr']; ?>"><?php }?>
  <div class="panel-heading">
      <?php if ($row_razd['tip']==21){?> <i class="icofont icofont-question"></i><?php } ?>
<?php if ($row_razd['tip']==1){?> <i class="icofont icofont-question"></i><?php } ?>
	<?php if ($row_razd['tip']==1){?> <i class="icofont icofont-book"></i><?php } ?>
	  	<?php if ($row_razd['tip']==2){?> <i class="icofont icofont-video"></i><?php } ?> 
	  	<?php if ($row_razd['tip']==3){?> <i class="icofont icofont-question"></i><?php } ?>
	  	<?php if ($row_razd['tip']==18){?> <i class="icofont icofont-question-square"></i><?php } ?>
	  	<?php if ($row_razd['tip']==4){?> <i class="icofont icofont-file"></i><?php } ?> 
	  	<?php if ($row_razd['tip']==10){?> <i class="icofont icofont-license"></i><?php } ?> 
	  	<?php if ($row_razd['tip']==12){?> <i class="icofont icofont-addons"></i><?php } ?> 
	    	<?php if ($row_razd['tip']==13){?> <i class="icofont icofont-addons"></i><?php } ?> 
	  	<?php if ($row_razd['tip']==15){?> <i class="icofont icofont-bars"></i><?php } ?> 
	  	  	<?php if ($row_razd['tip']==16){?> <i class="icofont icofont-address-book"></i><?php } ?> 
	  <?php if ($row_razd['tip']==19){?> <i class="icofont icofont-address-book"></i><?php } ?> 
	    	<?php if ($row_razd['tip']==11){?><a name="opl<?php echo $row_razd['id']; ?>"></a> <i class="icofont icofont-cart"></i><?php } ?> 
	<?php if ($row_razd['tip']==17){?> <i class="icofont icofont-address-book"></i><?php } ?> 	<?php if ($row_razd['tip']==20){?> <i class="icofont icofont-address-book"></i><?php } ?> 
	  <?php echo $row_razd['nazv']; ?>
	   <span class="badge pull-right">  <?php echo $row_razd['num']; ?></span>
	
	  
	
	  
	  
	<?php //if ( $row_razd['tip']==0){
  	if (($row_razd['data_act']!=null) and(  date("Y-m-d H:i:s")<$row_razd['data_act'])){
  		
  		?> 
  		<span class="badge badge-warning pull-right" style="background-color: #b48dec;">Раздел будет доступен через:<span data-countdown=" <?php echo $row_razd['data_act']; ?>"></span></span>
	  
	  <?php }?>
	  	<?php //if ( $row_razd['tip']==0){
  	if (($row_razd['data_okon']!=null) and( date("Y-m-d H:i:s")<$row_razd['data_okon'])){
  		
  		?> 
  		<span class="badge badge-warning pull-right" style="background-color: #b48dec;">Раздел будет доступен eще:<span data-countdown=" <?php echo $row_razd['data_okon']; ?>"></span></span>
	  
	  <?php }?>
	 
	  
	  
		  </div>
		  
		  
		  
		  
		<?php if ($row_razd['passw']==1){ ?> <div class="input-group" id="ig<?php echo $row_razd['id'];?>">
		  <span class="input-group-addon" >
             Пароль      </span>
	 	 
		       	 	  <input type="text" class="form-control" value=""  placeholder="Введите пароль доступа" id="puser<?php echo $row_razd['id'];?>">
	<span class="input-group-addon info">
      <a href="" class="fpass" data-num="<?php echo $row_razd['id'];?>">  Отправить
            </a></span>
	
    </div> <?php } ?>
		  
		  
  <div class="panel-body" <?php if ($row_razd['passw']==1)echo "style='display: none;'" ?> id="bod<?php echo $row_razd['id'];?>">
	  
	  
  		 <?php  $zee=yn($oplat,$row_razd['num']);  if (($zee)>0 ){?>Этот раздел не оплачен<a class="btn btn-danger btn-sm pull-right" href="#opl<?php echo $zee; ?>">Нажмите чтобы оплатить</a> <?php  }  else {?>
	<?php if (( $stop>=0) and ($row_razd['kvn']!=1))
	{ ?> 
	  	<?php //if ( $row_razd['tip']==0){
	  	
	if ((($row_razd['data_okon']!=null) and (($row_razd['data_okon'])>0)) and( date("Y-m-d H:i:s")>=$row_razd['data_okon'])){
  		
  		?> 
  		<p>Время доступности раздела завершено 
  		
	<span class="badge badge-warning pull-right" style="background-color: #c1549d;"><?php echo $row_razd['data_okon']; ?><?php echo $row_razd['data_okon']<1; ?></span></p>
  		<?php } else	
	  	
  	if ( date("Y-m-d H:i:s")<$row_razd['data_act']){
  		
  		?> 
  	
  		<?php }  
  		
  		  		else {  ?>
	 <?php if ($row_razd['tip']==1) {?>
          <a href="nmo/doc/<?php echo $row_razd['path']; ?>" class="btn btn-info form-control" target="_blank">скачать</a>
       <?php }?>
	  
	  	 <?php if ($row_razd['tip']==12) {?>
          <a href="<?php echo $row_razd['path']; ?>" class="btn btn-info form-control" target="_blank">перейти</a>
       <?php }?>
	  
	  	 <?php if ($row_razd['tip']==13) {?>
          <a href="<?php echo $row_razd['path']; ?>" class="btn btn-info form-control" target="_blank">перейти в облако</a>
       <?php }?>
	  <?php if ($row_razd['tip']==5) {
	   if ($row_razd['act']<1){
	  ?>
	  <p>Поздравляем, вы закончили курс обучения! нажмите завершить, чтобы перейти к оформлению документов</p>
	  <form method="post">
		  <input type="hidden" value="1" name="zav">
		  
		  
		   <input type="submit" class="btn btn-info form-control" value="завершить">
         
		  </form>
       <?php } else echo '<h4 class="content-title">Раздел временно отключен</h4>'; }?>
       
        <?php if ($row_razd['tip']==7) {?>
	  
	  
	  <?php
	  $sql="SELECT 
  `tm_konf_user_files`.`num`,
  `tm_konf_user_files`.`user`,
  `tm_konf_user_files`.`media`,
  `tm_konf_user_files`.`path`,
  `tm_konf_user_files`.`name`
FROM
  `tm_konf_user_files`
WHERE
  `tm_konf_user_files`.`media` = ".$row_razd['id']." AND 
  `tm_konf_user_files`.`user` = $username_test";
					
				    $uf =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_uf =  /* fixed MMiC */ mysqli_fetch_assoc($uf);
$totalRows_uf =  /* fixed MMiC */ mysqli_num_rows($uf);
	if ($totalRows_uf>0){
	
	
	  ?>
	  <div id="df<?php echo $row_uf['num']; ?>">
	  <div class="input-group">
		  <span class="input-group-addon">
           <a href="<?php echo $row_uf['path']; ?>" target="_blank">Скачать</a>     </span>
	  <input type="text" class="form-control ank" list="22" value="<?php echo $row_uf['path']; ?>" data-num="7"> <?php if($row_razd['act']<1){ ?>  <span class="input-group-addon danger">
      <a href="#" data-num="<?php echo $row_uf['num']; ?>" class="de"  data-mediarazd="<?php echo $row_razd['id']; ?>"> <i class="icofont icofont-delete"></i> Удалить
            </a></span> <?php } ?>  	 	     </div>
		  </div>
	  <?php } else { if ($row_razd['act']<1){
	  
	  	$mim=$row_razd['path'];$mim=strtoupper($mim);
	 // 	echo $mim;
$mim=str_replace(' ','',$mim);
$mim=str_replace('PDF','application/pdf',$mim);

$mim=str_replace('DOCX','application/vnd.openxmlformats-officedocument.wordprocessingml.document',$mim);

$mim=str_replace('PPTX','application/vnd.openxmlformats-officedocument.presentationml.presentation',$mim);
$mim=str_replace('JPG','image/jpeg',$mim);
$mim=str_replace('DOC','application/msword',$mim);
$mim=str_replace('PPT','application/vnd.ms-powerpoint',$mim);
$mim=str_replace('JPG','image/png',$mim);
$mim=str_replace('ZIP','application/zip',$mim);
//echo $mim;
	  
	  ?>
	<form id="form<?php echo $row_razd['id']; ?>" enctype="multipart/form-data">
			<h3>  выберите файл с расширением <?php echo $row_razd['path'];?></h3>
		<div class="progress" id="prog<?php echo $row_razd['id']; ?>" style="display: none">
    <div class="progress-bar progress-bar-striped progress-bar-warning" role="progressbar" style="width: 0% " aria-valuenow="14" aria-valuemin="0" aria-valuemax="100" id="pb<?php echo $row_razd['id']; ?>"></div>
</div>
<div id="drop-area">
 </div>
		<div id='bgd<?php echo $row_razd['id']; ?>'>
<br>
			
  <input type="file" title="Click to add Files" class="form-control" name="fileuser" accept="<?php echo $mim;?>">
	<br>
		<input type="hidden" value="<?php echo $row_razd['id']; ?>" name="mediarazd">
	<button class="btn btn-info form-control send" data-num="<?php echo $row_razd['id']; ?>"  type="button" >Отправить</button>
		</div>
</form><?php }else { echo '<h4><i class="icofont icofont-close-circled"></i>  Редактирование заблокировано</h4>';}}?>
	  <?php
									
				  
				//  $konf= $row_razd['path'];
				//					   include('nmo_ank.php');?>
	  
	  
	  <?php }?>
       
       
       
         <?php if ($row_razd['tip']==6) {?>
	
	  <?php  
									   $razdel=$row_test['id'];
									   $konf= $row_razd['path'];
									//   echo $row_razd['act'];
						if($row_razd['act']>0)echo '<div class="disabledbutton"><h4>Редактирование завершено</h4>';	
									
									   include('nmo_ank.php');
				
							  if($row_razd['act']>1)echo '</div >';				   
									   
									   ?>

	  
	  <?php }?>
	   <?php if ($row_razd['tip']==20) {?>
	
	  <?php  
									   $razdel=$row_test['id'];
									  $kko=$row_razd['dop_file'];
					$ugr=$row_tzaz['grupp'];
									//   echo $row_razd['act'];
						if($row_razd['act']>0)echo '<div class="disabledbutton"><h4>Редактирование завершено</h4>';	
									
									   include('nbil.php');
				
							  if($row_razd['act']>1)echo '</div >';				   
									   
									   ?>

	  
	  <?php }?>
	  
	  
	  
	         <?php if ($row_razd['tip']==10) {?>
	
	  <?php
									   $konf= $row_razd['path'];
									//   echo $row_razd['act'];
						if($row_razd['act']>0)echo '<div class="disabledbutton"><h4>Редактирование завершено</h4>';	
								
									   include('nmo_userf.php');
							  if($row_razd['act']>1)echo '</div >';				   
									   
									   ?>

	  
	  <?php }?>
	  
	   <?php if ($row_razd['tip']==19) {?>
	  <a class="btn btn-info form-control" href="nmo_prakt_tbl.php?media=<?php echo $row_razd['id'];?>" target="_blank">Перейти к просмотру таблицы</a>
    
	  
       <?php }?>
	     <?php if ($row_razd['tip']==16) {?>
	  <a class="btn btn-info form-control" href="nmo_prakt_dnev.php?media=<?php echo $row_razd['id'];?>">Перейти к заполнению дневника</a>
    
	  
       <?php }?>
	     <?php if ($row_razd['tip']==17) {?>
	  <a class="btn btn-info form-control" href="nmo_prakt_sh.php?media=<?php echo $row_razd['id'];?>">Перейти к заполнению Рабочей тетради</a>
    
	  
       <?php }?>
	   <?php if ($row_razd['tip']==2) {?>
	  
      <input type="button" class="btn btn-info form-control vdo" value="Посмотреть" data-vdo="<?php echo $row_razd['path']; ?>">
       <?php }?>
	  
	    <?php if ($row_razd['tip']==11) {
	    
	    	   $oplt2 = explode(",", $row_opl['path']);$row_name['cena']= $row_razd['dop_file'];;
	    	   
	    	 if (count($oplt2)<2){ echo "Раздел оплачен";}else {
	    ?>
	  <p>стоимость обучения:<?php echo $row_razd['dop_file']; ?>, октрываются разделы 
		<?php
	
	
		   $dopid=$row_razd['id'];
		foreach ($oplt2 as $val) {
  echo '<span class="badge" style="background-color: chocolate">  '.$val.'</span>';// pull-right
		}
		  ?>  
		 <span class="pull-right"  ><?php include('opl.php');?></span> </p>
		  
 
       <?php }}?>
	  <?php  //echo "----------------->".$row_razd['tip'];?>
	<?php if ($row_razd['tip']==15) {
	  $sqlu="SELECT 
  `tm_nmo_razd_user`.`dat`,
  `tm_nmo_razd_user`.`proydeno`,
  `tm_nmo_razd_user`.`dop_file`,`tm_nmo_razd_user`.`pop`
FROM
  `tm_nmo_razd_user`
WHERE
  `tm_nmo_razd_user`.`proydeno` > 0 AND 
  `tm_nmo_razd_user`.`user` = $username_test AND 
  `tm_nmo_razd_user`.`razdel` = ".$row_razd['id'];
				//  echo $sqlu;
				   $sqlu =  /* fixed MMiC */ DB::Query($sqlu, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
	  $row_sqlu =  /* fixed MMiC */ mysqli_fetch_assoc($sqlu);
$totalRows_sqlu =  /* fixed MMiC */ mysqli_num_rows($sqlu);
	  
	  
	  
	  
	  ?>
	 <?php if (($totalRows_sqlu>0) ) { // echo $row_razd['pop']."-".$row_sqlu['pop'];
	   $stop=0;
	  ?>
	  
	  
	     <div class="wp-block-content">
       
<span class="icofont icofont-calendar" aria-hidden="true"></span> <?php echo date("d.m.y");?></small>
            Тест пройден<span class="glyphicon glyphicon-bookmark pull-right" aria-hidden="true">  правильных ответов <?php echo $row_sqlu['proydeno']; ?> попыток-<?php echo  $row_razd['pop']-$row_sqlu['pop']; ?></span>
	 <a href="nmo_get_sert.php?num=<?php echo $row_razd['id']; ?>" class="btn btn-info form-control">Скачать сертификат</a>
  <hr>
	      <?php 
		  $sqal="SELECT 
  `tmo_nmo_test_dat`.`num`,
  `tmo_nmo_test_dat`.`user`,
  `tmo_nmo_test_dat`.`test`,
  `tmo_nmo_test_dat`.`dat`
FROM
  `tmo_nmo_test_dat`
WHERE
  `tmo_nmo_test_dat`.`user` =  $username_test AND 
  `tmo_nmo_test_dat`.`test` =". $row_razd['id'];
	 $sqtd =  /* fixed MMiC */ DB::Query($sqal, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_sqtd =  /* fixed MMiC */ mysqli_fetch_assoc($sqtd);	  
		  
	//s	  echo "<div  style='margin-top: 10px;'>";
		  do {
		echo 	'<span class="badge badge-warning pull-left" style="background-color: #5588E3;">'.$row_sqtd['dat'].'</span>&nbsp; ';  
			  
		  }
	while ($row_sqtd =  /* fixed MMiC */ mysqli_fetch_assoc($sqtd));	  
		//  echo "</div"; 
		  ?>	  
		  
    
	  
	  <?php } else {
			  $stop=1;
		  $ussql="SELECT 
  `tm_nmo_razd_media_user_act_test`.`datact`
FROM
  `tm_nmo_razd_media_user_act_test`
WHERE
  `tm_nmo_razd_media_user_act_test`.`razd_media_test` = ".$row_razd['id']." AND 
  `tm_nmo_razd_media_user_act_test`.`user` = $username_test";
					   $sqluus =  /* fixed MMiC */ DB::Query($ussql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
	  $row_sqluus =  /* fixed MMiC */ mysqli_fetch_assoc($sqluus);  
		
		if ($row_sqluus['datact']<=date(("Y-m-d"))){  
		  
		  ?>
          <a href="nmo_get_test.php?num=<?php echo $row_razd['id']; ?>&test=<?php echo $row_razd['path']; ?>" class="btn btn-info form-control">Пройти тест</a>
		  <?php } else {?> <span class="glyphicon glyphicon-calendar" aria-hidden="true"></span>  тест будет доступен  <?php echo $row_sqluus['datact'];?></small><?php }?>
		  <?php 
		  $sqal="SELECT 
  `tmo_nmo_test_dat`.`num`,
  `tmo_nmo_test_dat`.`user`,
  `tmo_nmo_test_dat`.`test`,
  `tmo_nmo_test_dat`.`dat`
FROM
  `tmo_nmo_test_dat`
WHERE
  `tmo_nmo_test_dat`.`user` =  $username_test AND 
  `tmo_nmo_test_dat`.`test` =". $row_razd['id'];
	 $sqtd =  /* fixed MMiC */ DB::Query($sqal, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_sqtd =  /* fixed MMiC */ mysqli_fetch_assoc($sqtd);	  
		  
		  echo "<div  style='margin-top: 10px;'>";
		  do {
		echo 	'<span class="badge badge-warning pull-left" style="background-color: #5588E3;">'.$row_sqtd['dat'].'</span>&nbsp; ';  
			  
		  }
	while ($row_sqtd =  /* fixed MMiC */ mysqli_fetch_assoc($sqtd));	  
		  echo "</div";
		  ?>
		  
		  
       <?php }?>
        </div>
	  <?php }?>	 
	  
	  <?php if ($row_razd['tip']==18) {
					$row_razd['comment']='';
	  $sqlu="SELECT 
  `tm_nmo_razd_user`.`dat`,
  `tm_nmo_razd_user`.`proydeno`,
  `tm_nmo_razd_user`.`dop_file`,`tm_nmo_razd_user`.`pop`
FROM
  `tm_nmo_razd_user`
WHERE
   
  `tm_nmo_razd_user`.`user` = $username_test AND 
  `tm_nmo_razd_user`.`razdel` = ".$row_razd['id'];
			 // echo $sqlu;
				   $sqlu =  /* fixed MMiC */ DB::Query($sqlu, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
	  $row_sqlu =  /* fixed MMiC */ mysqli_fetch_assoc($sqlu);
$totalRows_sqlu =  /* fixed MMiC */ mysqli_num_rows($sqlu);
	  
	  
	  
	  
	  ?>
	 <?php if (($totalRows_sqlu>0) ) { // echo $row_razd['pop']."-".$row_sqlu['pop'];
	   $stop=0;
	  ?>
	  
	  
	     <div class="wp-block-content">
       
<span class="icofont icofont-calendar" aria-hidden="true"></span> <?php echo date("d.m.y");?></small>
           Анкетный Тест пройден<span class="glyphicon glyphicon-bookmark pull-right" aria-hidden="true"></span>
	 <?php if($row_razd['pop']>$row_sqlu['pop']) { ?> <a href="nmo_get_test.php?num=<?php echo $row_razd['id']; ?>&test=<?php echo $row_razd['path']; ?>" class="btn btn-info form-control">Пройти тест еще раз</a><?php } ?>
  <hr>
	      <?php 
		  $sqal="SELECT 
  `tmo_nmo_test_dat`.`num`,
  `tmo_nmo_test_dat`.`user`,
  `tmo_nmo_test_dat`.`test`,
  `tmo_nmo_test_dat`.`dat`
FROM
  `tmo_nmo_test_dat`
WHERE
  `tmo_nmo_test_dat`.`user` =  $username_test AND 
  `tmo_nmo_test_dat`.`test` =". $row_razd['id'];
	 $sqtd =  /* fixed MMiC */ DB::Query($sqal, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_sqtd =  /* fixed MMiC */ mysqli_fetch_assoc($sqtd);	  
		  
	//s	  echo "<div  style='margin-top: 10px;'>";
		  do {
		echo 	'<span class="badge badge-warning pull-left" style="background-color: #5588E3;">'.$row_sqtd['dat'].'</span>&nbsp; ';  
			  
		  }
	while ($row_sqtd =  /* fixed MMiC */ mysqli_fetch_assoc($sqtd));	  
		//  echo "</div"; 
		  ?>	  
		  
    
	  
	  <?php } else {
			  $stop=1;
		  $ussql="SELECT 
  `tm_nmo_razd_media_user_act_test`.`datact`
FROM
  `tm_nmo_razd_media_user_act_test`
WHERE
  `tm_nmo_razd_media_user_act_test`.`razd_media_test` = ".$row_razd['id']." AND 
  `tm_nmo_razd_media_user_act_test`.`user` = $username_test";
					   $sqluus =  /* fixed MMiC */ DB::Query($ussql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
	  $row_sqluus =  /* fixed MMiC */ mysqli_fetch_assoc($sqluus);  
		
		if ($row_sqluus['datact']<=date(("Y-m-d"))){  
		  
		  ?>
          <a href="nmo_get_test.php?num=<?php echo $row_razd['id']; ?>&test=<?php echo $row_razd['path']; ?>" class="btn btn-info form-control">Пройти Анкетный тест</a>
		  <?php } else {?> <span class="glyphicon glyphicon-calendar" aria-hidden="true"></span> Анкетный тест будет доступен  <?php echo $row_sqluus['datact'];?></small><?php }?>
		  <?php 
		  $sqal="SELECT 
  `tmo_nmo_test_dat`.`num`,
  `tmo_nmo_test_dat`.`user`,
  `tmo_nmo_test_dat`.`test`,
  `tmo_nmo_test_dat`.`dat`
FROM
  `tmo_nmo_test_dat`
WHERE
  `tmo_nmo_test_dat`.`user` =  $username_test AND 
  `tmo_nmo_test_dat`.`test` =". $row_razd['id'];
	 $sqtd =  /* fixed MMiC */ DB::Query($sqal, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_sqtd =  /* fixed MMiC */ mysqli_fetch_assoc($sqtd);	  
		  
		  echo "<div  style='margin-top: 10px;'>";
		  do {
		echo 	'<span class="badge badge-warning pull-left" style="background-color: #5588E3;">'.$row_sqtd['dat'].'</span>&nbsp; ';  
			  
		  }
	while ($row_sqtd =  /* fixed MMiC */ mysqli_fetch_assoc($sqtd));	  
		  echo "</div";
		  ?>
		  
		  
       <?php }?>
        </div>   
	  <?php }?>	  

                    <?php if ($row_razd['tip']==21) {
                 ;
                        if($row_razd['act']==0){
                        $sqlu="SELECT 
  `tm_nmo_razd_user`.`dat`,
  `tm_nmo_razd_user`.`proydeno`,
  `tm_nmo_razd_user`.`dop_file`,`tm_nmo_razd_user`.`pop`
FROM
  `tm_nmo_razd_user`
WHERE
  `tm_nmo_razd_user`.`proydeno` > 0 AND 
  `tm_nmo_razd_user`.`user` = $username_test AND 
  `tm_nmo_razd_user`.`razdel` = ".$row_razd['id'];
                        //  echo $sqlu;
                        $sqlu =  /* fixed MMiC */ DB::Query($sqlu, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
                        $row_sqlu =  /* fixed MMiC */ mysqli_fetch_assoc($sqlu);
                        $totalRows_sqlu =  /* fixed MMiC */ mysqli_num_rows($sqlu);




                        ?>
                        <?php if (($totalRows_sqlu>0) ) { // echo $row_razd['pop']."-".$row_sqlu['pop'];
                            $stop=0;
                            ?>


                            <div class="wp-block-content">

                            <span class="icofont icofont-calendar" aria-hidden="true"></span> <?php echo date("d.m.y");?></small>
                            Тест пройден<span class="glyphicon glyphicon-bookmark pull-right" aria-hidden="true">  правильных ответов <?php echo $row_sqlu['proydeno']; ?> попыток-<?php echo  $row_razd['pop']-$row_sqlu['pop']; ?></span>
                            <?php if($row_razd['pop']>$row_sqlu['pop']) { ?> <a href="nmo_get_test.php?num=<?php echo $row_razd['id']; ?>&test=<?php echo $row_razd['path']; ?>" class="btn btn-info form-control">Пройти тест еще раз</a><?php } ?>
                            <hr>
                            <?php
                            $sqal="SELECT 
  `tmo_nmo_test_dat`.`num`,
  `tmo_nmo_test_dat`.`user`,
  `tmo_nmo_test_dat`.`test`,
  `tmo_nmo_test_dat`.`dat`
FROM
  `tmo_nmo_test_dat`
WHERE
  `tmo_nmo_test_dat`.`user` =  $username_test AND 
  `tmo_nmo_test_dat`.`test` =". $row_razd['id'];
                            $sqtd =  /* fixed MMiC */ DB::Query($sqal, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
                            $row_sqtd =  /* fixed MMiC */ mysqli_fetch_assoc($sqtd);

                            //s	  echo "<div  style='margin-top: 10px;'>";
                            do {
                                echo 	'<span class="badge badge-warning pull-left" style="background-color: #5588E3;">'.$row_sqtd['dat'].'</span>&nbsp; ';

                            }
                            while ($row_sqtd =  /* fixed MMiC */ mysqli_fetch_assoc($sqtd));
                            //  echo "</div";
                            ?>



                        <?php } else {
                            $stop=1;
                            $ussql="SELECT 
  `tm_nmo_razd_media_user_act_test`.`datact`
FROM
  `tm_nmo_razd_media_user_act_test`
WHERE
  `tm_nmo_razd_media_user_act_test`.`razd_media_test` = ".$row_razd['id']." AND 
  `tm_nmo_razd_media_user_act_test`.`user` = $username_test";
                            $sqluus =  /* fixed MMiC */ DB::Query($ussql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
                            $row_sqluus =  /* fixed MMiC */ mysqli_fetch_assoc($sqluus);

                            if ($row_sqluus['datact']<=date(("Y-m-d"))){

                                ?>
                                <a href="nmo_get_test.php?num=<?php echo $row_razd['id']; ?>&test=<?php echo $row_razd['path']; ?>" class="btn btn-info form-control">Пройти тест</a>
                            <?php } else {?> <span class="glyphicon glyphicon-calendar" aria-hidden="true"></span>  тест будет доступен  <?php echo $row_sqluus['datact'];?></small><?php }?>
                            <?php
                            $sqal="SELECT 
  `tmo_nmo_test_dat`.`num`,
  `tmo_nmo_test_dat`.`user`,
  `tmo_nmo_test_dat`.`test`,
  `tmo_nmo_test_dat`.`dat`
FROM
  `tmo_nmo_test_dat`
WHERE
  `tmo_nmo_test_dat`.`user` =  $username_test AND 
  `tmo_nmo_test_dat`.`test` =". $row_razd['id'];
                            $sqtd =  /* fixed MMiC */ DB::Query($sqal, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
                            $row_sqtd =  /* fixed MMiC */ mysqli_fetch_assoc($sqtd);

                            echo "<div  style='margin-top: 10px;'>";
                            do {
                                echo 	'<span class="badge badge-warning pull-left" style="background-color: #5588E3;">'.$row_sqtd['dat'].'</span>&nbsp; ';

                            }
                            while ($row_sqtd =  /* fixed MMiC */ mysqli_fetch_assoc($sqtd));
                            echo "</div";
                            ?>


                        <?php }?>
                        </div>
                    <?php }

                    else
                  {?>
                        <a href="nmo/doc/<?php echo $row_razd['dop_file']; ?>" class="btn btn-info form-control" target="_blank">просмотреть ответы</a>
                    <?php }


                    }?>

	  
	   <?php if ($row_razd['tip']==3) {
	  $sqlu="SELECT 
  `tm_nmo_razd_user`.`dat`,
  `tm_nmo_razd_user`.`proydeno`,
  `tm_nmo_razd_user`.`dop_file`,`tm_nmo_razd_user`.`pop`
FROM
  `tm_nmo_razd_user`
WHERE
  `tm_nmo_razd_user`.`proydeno` > 0 AND 
  `tm_nmo_razd_user`.`user` = $username_test AND 
  `tm_nmo_razd_user`.`razdel` = ".$row_razd['id'];
				//  echo $sqlu;
				   $sqlu =  /* fixed MMiC */ DB::Query($sqlu, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
	  $row_sqlu =  /* fixed MMiC */ mysqli_fetch_assoc($sqlu);
$totalRows_sqlu =  /* fixed MMiC */ mysqli_num_rows($sqlu);
	  
	  
	  
	  
	  ?>
	 <?php if (($totalRows_sqlu>0) ) { // echo $row_razd['pop']."-".$row_sqlu['pop'];
	   $stop=0;
	  ?>
	  
	  
	     <div class="wp-block-content">
       
<span class="icofont icofont-calendar" aria-hidden="true"></span> <?php echo date("d.m.y");?></small>
            Тест пройден<span class="glyphicon glyphicon-bookmark pull-right" aria-hidden="true">  правильных ответов <?php echo $row_sqlu['proydeno']; ?> попыток-<?php echo  $row_razd['pop']-$row_sqlu['pop']; ?></span>
	 <?php if($row_razd['pop']>$row_sqlu['pop']) { ?> <a href="nmo_get_test.php?num=<?php echo $row_razd['id']; ?>&test=<?php echo $row_razd['path']; ?>" class="btn btn-info form-control">Пройти тест еще раз</a><?php } ?>
  <hr>
	      <?php 
		  $sqal="SELECT 
  `tmo_nmo_test_dat`.`num`,
  `tmo_nmo_test_dat`.`user`,
  `tmo_nmo_test_dat`.`test`,
  `tmo_nmo_test_dat`.`dat`
FROM
  `tmo_nmo_test_dat`
WHERE
  `tmo_nmo_test_dat`.`user` =  $username_test AND 
  `tmo_nmo_test_dat`.`test` =". $row_razd['id'];
	 $sqtd =  /* fixed MMiC */ DB::Query($sqal, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_sqtd =  /* fixed MMiC */ mysqli_fetch_assoc($sqtd);	  
		  
	//s	  echo "<div  style='margin-top: 10px;'>";
		  do {
		echo 	'<span class="badge badge-warning pull-left" style="background-color: #5588E3;">'.$row_sqtd['dat'].'</span>&nbsp; ';  
			  
		  }
	while ($row_sqtd =  /* fixed MMiC */ mysqli_fetch_assoc($sqtd));	  
		//  echo "</div"; 
		  ?>	  
		  
    
	  
	  <?php } else {
			  $stop=1;
		  $ussql="SELECT 
  `tm_nmo_razd_media_user_act_test`.`datact`
FROM
  `tm_nmo_razd_media_user_act_test`
WHERE
  `tm_nmo_razd_media_user_act_test`.`razd_media_test` = ".$row_razd['id']." AND 
  `tm_nmo_razd_media_user_act_test`.`user` = $username_test";
					   $sqluus =  /* fixed MMiC */ DB::Query($ussql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
	  $row_sqluus =  /* fixed MMiC */ mysqli_fetch_assoc($sqluus);  
		
		if ($row_sqluus['datact']<=date(("Y-m-d"))){  
		  
		  ?>
          <a href="nmo_get_test.php?num=<?php echo $row_razd['id']; ?>&test=<?php echo $row_razd['path']; ?>" class="btn btn-info form-control">Пройти тест</a>
		  <?php } else {?> <span class="glyphicon glyphicon-calendar" aria-hidden="true"></span>  тест будет доступен  <?php echo $row_sqluus['datact'];?></small><?php }?>
		  <?php 
		  $sqal="SELECT 
  `tmo_nmo_test_dat`.`num`,
  `tmo_nmo_test_dat`.`user`,
  `tmo_nmo_test_dat`.`test`,
  `tmo_nmo_test_dat`.`dat`
FROM
  `tmo_nmo_test_dat`
WHERE
  `tmo_nmo_test_dat`.`user` =  $username_test AND 
  `tmo_nmo_test_dat`.`test` =". $row_razd['id'];
	 $sqtd =  /* fixed MMiC */ DB::Query($sqal, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_sqtd =  /* fixed MMiC */ mysqli_fetch_assoc($sqtd);	  
		  
		  echo "<div  style='margin-top: 10px;'>";
		  do {
		echo 	'<span class="badge badge-warning pull-left" style="background-color: #5588E3;">'.$row_sqtd['dat'].'</span>&nbsp; ';  
			  
		  }
	while ($row_sqtd =  /* fixed MMiC */ mysqli_fetch_assoc($sqtd));	  
		  echo "</div";
		  ?>
		  
		  
       <?php }?>
        </div>
	  <?php }?>	  
	  <?php if ($row_razd['tip']==4) {
					
					
					
	$sqkvr="SELECT 
  `tm_nmo_razd_user`.`id`,
  `tm_nmo_razd_user`.`user`,
  `tm_nmo_razd_user`.`razdel`,
  `tm_nmo_razd_user`.`proydeno`,
  `tm_nmo_razd_user`.`dop_file`,
  `tm_nmo_razd_user`.`dat`,
  `tm_nmo_razd_user`.`dop`
FROM
  `tm_nmo_razd_user`
WHERE
  `tm_nmo_razd_user`.`razdel` = ".$row_razd['id']." AND 
  `tm_nmo_razd_user`.`user` = $username_test"; 
				  
				
	 $sqkvr =  /* fixed MMiC */ DB::Query($sqkvr, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_sqkvr =  /* fixed MMiC */ mysqli_fetch_assoc($sqkvr);
$totalRows_sqkvr =  /* fixed MMiC */ mysqli_num_rows($sqkvr);  			  
				  
if (($totalRows_sqkvr>0)and ($row_sqkvr['dop_file']=='')){?>
	<form action="nmo.php" method="post" enctype="multipart/form-data" id="fm<?php echo $row_sqkvr['id'];?>">	  
	<p class="form-control"><?php echo $row_sqkvr['dop'];?><small>
<span class="glyphicon glyphicon-calendar pull-right" aria-hidden="true"><?php echo $row_sqkvr['dat'];?></span></small></p>	  
	<input type="file" name="fnm" class="form-control">	  
		<input type="hidden" name="id" value="<?php echo $row_sqkvr['id'];?>">
		<div class="progress">

</div>
		
	<input type="button" class="btn-danger form-control gf" id="gf<?php echo $row_sqkvr['id'];?>" value="Отправить" data-id="<?php echo $row_sqkvr['id'];?>">
		</form>  
		  <?php }
		if (($totalRows_sqkvr>0)and ($row_sqkvr['dop_file']!='')){?>
		  <p class="form-control"><?php echo $row_sqkvr['dop'];?><small>
<span class="glyphicon glyphicon-calendar pull-right" aria-hidden="true"><?php echo $row_sqkvr['dat'];?></span></small></p>	 
	<a class="btn-danger form-control" href="<?php echo $row_sqkvr['dop_file'];?>">Посмотреть</a>

		
		  <?php }		  
				  
				  if ($totalRows_sqkvr<1){
				  
				  
				  
	  $sqllist="SELECT 
  `tm_nmo_razd_media_list`.`id`,
  `tm_nmo_razd_media_list`.`tm_nmo_razd_media`,
  `tm_nmo_razd_media_list`.`tex`
FROM
  `tm_nmo_razd_media_list`
WHERE
  `tm_nmo_razd_media_list`.`tm_nmo_razd_media` =".$row_razd['id'];
		  $list =  /* fixed MMiC */ DB::Query($sqllist, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_list =  /* fixed MMiC */ mysqli_fetch_assoc($list);
$totalRows_list =  /* fixed MMiC */ mysqli_num_rows($list);  
	  
	  
	  ?>
          <select name="list" id="list<?php echo $row_razd['id']; ?>" class="form-control">
			   <?php do { ?>	 
            <option value="<?php echo $row_list['id']; ?>"><?php echo $row_list['tex']; ?></option>
			   <?php } while ($row_list =  /* fixed MMiC */ mysqli_fetch_assoc($list)); ?>	
          </select><br>
	     <input type="button" class="btn btn-info form-control vdos" value="Выбрать" data-id="<?php echo $row_razd['id']; ?>" data-url="<?php echo $row_test['id'];?> ">
       <?php }}?>
       <?php if (isset($row_razd['comment'])) { ?>
      <p class="form-control-static"><?php echo $row_razd['comment']; ?></p><?php } ?>
		    <?php } /* конец проверки на дату активации*/
	 
	  ?>
		  <?php } else { ?>  Временно недоступно <?php }} ?>
      </div>
    
  </div>

	   <?php } while ($row_razd =  /* fixed MMiC */ mysqli_fetch_assoc($razd));
							  
		  ?>	  
		  </p>
		  	 
	   <?php } /* конец проверки на стоп*/
	 
	  ?>
	  

	 
      </div>
    </div>
  
    <?php
		
				
		
			
			} while ($row_test =  /* fixed MMiC */ mysqli_fetch_assoc($test));
		  
  //echo ">>>".$totalRows_test ;
		  
		  ?>
  <!-- 3 панель -->


	  
	<?php }
		  		
		  
		  ?>  
    </div>
    <div class="row"></div>
    
  </div>

  <!-- / CONTAINER--> 
</section>
<?php }?>
<!-- FOOTER -->
<div class="container">
  <div class="row"></div>
</div>
<footer class="text-center">
  <div class="container">
    <div class="row">
      <div class="col-xs-12">
 <?php //echo $query_test;?>
        <p>Cделал ASBcorp24</p>
      </div>
    </div>
  </div>
</footer>
<!-- / FOOTER --> 
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) --> 

<!-- Include all compiled plugins (below), or include individual files as needed --> 

<script src="js/jquery.countdown.min.js"></script>	
<script type="text/javascript">
	
	$(function() {
		$('[data-countdown]').each(function() {
  var $this = $(this), finalDate = $(this).data('countdown');
  $this.countdown(finalDate, function(event) {
    $this.html(event.strftime('%D Дней %H:%M:%S')).on('finish.countdown', function(event) {
	location.href = "nmo.php";
});;
  });
});
		
				
			function sendAjaxForm2(result_form, ajax_form, url1,fpr) {

   var form =($('#'+ajax_form)[0]);
	var formData = new FormData(form);
	//console.log(form);
	var request = new XMLHttpRequest();
	request.upload.onprogress = function(event) {
		pr=event.loaded/ event.total*100;
	$('#pb'+fpr).css('width',pr+"%");
	//	console.log($('#pb'+fpr));
		
 // console.log(event.loaded + ' / ' + event.total);
  }
function reqReadyStateChange() {
	if (request.readyState == 4 && request.status == 200){
  		//console.log('dssdsd');
		$(form).html(request.responseText);
		//console.log(request.responseText);
			//location.href = "nmo.php?row="
	}
}

request.open("POST", url1);
request.onreadystatechange = reqReadyStateChange;
request.send(formData);
}	
$('body').on('click', '.send',function(){
fm="form"+$(this).data('num');
//	console.log($("#prog"+fm));
$("#prog"+$(this).data('num')).show();
sendAjaxForm2('',fm,'upf.php',$(this).data('num'));
	$('#bgd'+$(this).data('num')).html('');
})		

	$('.de').on('click',function(e){
		 e.preventDefault();
			num=$(this).data('num');
		mediarazd=$(this).data('mediarazd');
		$(this).hide();	
		$.post('upf.php', {'del':'1', 'num' :num,'mediarazd':mediarazd},		function(data) {
			console.log($("#df"+num));
			$("#df"+num).html(data);
			
		});
			
			
		});
		
		
		
		function sendAjaxForm(result_form, ajax_form, url1,fpr='') {

   var form =($('#'+ajax_form)[0]);
	var formData = new FormData(form);
	
	var request = new XMLHttpRequest();
	request.upload.onprogress = function(event) {
  console.log(event.loaded + ' / ' + event.total);
  }
function reqReadyStateChange() {
	if (request.readyState == 4 && request.status == 200){
  		
		console.log($('#'+result_form).parent());
			location.href = "nmo.php?row="+<?php if (!isset( $_GET['row'])) $_GET['row']=-1;echo $_GET['row']; ?>;
	}
}

request.open("POST", url1);
request.onreadystatechange = reqReadyStateChange;
request.send(formData);
}	
		
		$('.gf').on('click',function(){
			deff=$(this).data('id');
			
			$(this).val('Отправляем');
		sendAjaxForm('gf'+deff,'fm'+deff,'nmo_lf.php','pb'+deff);
			
			
		});	
$('#ccb').one('change',function(e){
	$.post('nmo.php', {'personal':'1'},		function(data) {
$('#ccb').prop("disabled", true);
$('.panel-default').show();
	});	

	
});
	/// кнопка о наличии на паре
		$('.chepar').one('change',function(e){
			$(this).prop('disabled','disabled');
			id=$(this).data('id');
	$.post('nmo.php', {'chdat':id},		function(data) {
//$('#ccb').prop("disabled", true);
$("#bopr"+id).append(data);
	});	

console.log(this);	
});
		
		
///кнопки лекций практики		
$('.lp3').on('click',function(e){
	tmp=$(this).data('id');
//	console.log($('#allp'+tmp).find('.lb_0'));
	$('#allp'+tmp).find('.lb_0').hide();
				$('#allp'+tmp).find('.lb_1').show();
	//console.log('tmp');
	
})	;
		$('.lp1').on('click',function(e){
	tmp=$(this).data('id');
//	console.log($('#allp'+tmp).find('.lb_0'));
	$('#allp'+tmp).find('.lb_0').show();
				$('#allp'+tmp).find('.lb_1').show();
	//console.log('tmp');
	
})	;
		$('.lp2').on('click',function(e){
	tmp=$(this).data('id');
//	console.log($('#allp'+tmp).find('.lb_1'));
	$('#allp'+tmp).find('.lb_1').hide();
			$('#allp'+tmp).find('.lb_0').show();
	//console.log('tmp');
	
})	;
$('#zpost').on('click',function(e){
	e.preventDefault;
		 $("#post").submit();	
		});		
		
		
$('#radio1').on('click',function(e){
		 $('#taddr').hide(); 
	
	$.post('pochta.php', {'radio':'1'},		function(data) {});			
	 $('#zpost').hide();
		});		
			
	$('#radio2').on('click',function(e){
		 $('#taddr').show(); 
		$.post('pochta.php', {'radio':'2'},		function(data) {});	
		 $('#zpost').show();
		$('#zpost').html('<span>исправить адрес доставки</span>')
		});		
				
$('.zdounload').on('click',function(e){
	 e.preventDefault();
			deff=$(this).data('num');
	 var ddd= $("#fnna"+deff).val();
	     if(ddd==""){
            alert("Вы не выбрали файл");
          
            }
        else{
           $("#form"+deff).submit();
            }
 

			});	
$('.shw').on('click',function(e){
	 e.preventDefault();
			deff=$(this).data('comment');
	$('#dfm2').html( deff);		
//	https://www.youtube.com/embed/qssHvCCePaY" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>				 
		if (deff!='')
	$("#myModalBox2").modal('show');

			});	
				
$('.samo').on('click',function(){
			deff=$(this).data('num');
	if ($(this).prop('checked')==true){	$('#dstach'+deff).hide();$('#dstach2'+deff).hide();
									  $('#dload'+deff).show();
									  
									  
									  } else {	$('#dstach'+deff).show(); 
											  
											  $('#dstach2'+deff).show();
											  
											  $('#dload'+deff).hide();}
	
	});	
$('.dpr').on('click',function(e){
			e.preventDefault();
	tr=$(this);
	num=$(this).data('num');
	$.post('nmo.php', {'ank':'3', 'num' :num},		function(data) {
		
	  location.reload();

		
	});
			 });		
$('.fank').on('change',function(e){
val=$(this).val();
		num=$(this).data('num');
		razdel=$(this).data('razdel');
	 files = this.files;
	selo=this;
	$(this).parent().find('#prog').show();
	//$(this).parent().find('#zell').hide();
	  console.log(selo);
	$(selo).hide();
	 var data = new FormData();
    $.each( files, function( key, value ){
        data.append( key, value );
    });
	data.append('num',num);
		data.append('razdel',razdel);
	data.append('ank',1);
	 $.ajax({
        url: 'nmo.php',
        type: 'POST',
        data: data,
        cache: false,
        xhr: function(){
        var xhr = $.ajaxSettings.xhr(); // получаем объект XMLHttpRequest
        xhr.upload.addEventListener('progress', function(evt){ // добавляем обработчик события progress (onprogress)
          if(evt.lengthComputable) { // если известно количество байт
            // высчитываем процент загруженного
            var percentComplete = Math.ceil(evt.loaded / evt.total * 100);
            // устанавливаем значение в атрибут value тега <progress>
            // и это же значение альтернативным текстом для браузеров, не поддерживающих <progress>
			// 
			  $(selo).parent().find('#prog').find('.pb').css('width',percentComplete+"%");
          //  progressBar.val(percentComplete).text('Загружено ' + percentComplete + '%');
          }
        }, false);
        return xhr;
      },
        processData: false, // Не обрабатываем файлы (Don't process the files)
        contentType: false, // Так jQuery скажет серверу что это строковой запрос
        success: function( respond, textStatus, jqXHR ){
 
            // Если все ОК
 
            if( typeof respond.error === 'undefined' ){
                // Файлы успешно загружены, делаем что нибудь здесь
 if (respond=='er-jpg'){alert('Загрузка только jpg'); location.reload();}
                $(selo).parent().find('#prog').hide();
 				$(selo).parent().append("<a target='_blank' href='ankfile/"+respond+"' class='form-control'>просмотр</a>");
                console.log(respond);
                var html = '';
              
            }
            else{
                console.log('ОШИБКИ ОТВЕТА сервера: ' + respond.error );
            }
        },
        error: function( jqXHR, textStatus, errorThrown ){
            console.log('ОШИБКИ AJAX запроса: ' + textStatus );
        }
    });
	
	//$.post('nmo.php', {'ank':'1', 'num' :num,'val':val},		function(data) {});
	//e.preventDefault;
		 //$("#post").submit();	
		});				
$('.ank').on('blur',function(e){
val=$(this).val();
		num=$(this).data('num');
		razdel=$(this).data('razdel');
	$.post('nmo.php', {'ank':'1', 'num' :num,'val':val,'razdel':razdel},		function(data) {});
	//e.preventDefault;
		 //$("#post").submit();	
		});		
		
$('.schb').on('click',function(e){
//	 e.preventDefault();
			deff=$(this).data('comment');
$('#rfd').hide();

			});	
$('.shw').on('click',function(e){
	 e.preventDefault();
			deff=$(this).data('comment');
	$('#dfm2').html( deff);		
//	https://www.youtube.com/embed/qssHvCCePaY" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>				 
		if (deff!='')
	$("#myModalBox2").modal('show');

			});	
				
		
$('.dsel').on('click',function(){
			deff=$(this).data('num');
		psn=$(this).data('psn');
	tt=$(this);	
deff1=$("#li"+deff+"_"+psn+" :selected").val();

	$.post('nmo.php', {'arr':'1', 'id' :deff,'dat':deff1,'psn':psn},		function(data) {

		if(data=='--0'){$('#dfm2').html('к сожалению на эту дату все места заняты');	$("#myModalBox2").modal('show');
			$("#li"+deff+"_"+psn+" :selected").remove();		   
					   } else
			{
	$("#li"+deff+"_"+psn).prop('disabled','disabled')
		tt.remove();}
console.log(data);
	});		});	
		
		
		$('.vdos').on('click',function(){
			deff=$(this).data('id');	
		url=$(this).data('url');	
deff1=$("#list"+deff+" :selected").val();
deff2=$("#list"+deff+" :selected").text();				
	$.post('nmo.php', {'kr':'1', 'id' :deff1,'name':deff2,'razd':deff},		function(data) {
	
	location.href = "nmo.php?row="+url;
	});			
		});
	$('.vdo').on('click',function(){
			deff=$(this).data('vdo');
		//$.post('add_nmo.php', {'list':deff},		function(data) {
	
		x=deff.lastIndexOf(".be");	console.log(x);
		deff=deff.substring(x+3);
			deff="https://www.youtube.com/embed/"+deff;	
		console.log(deff);
	$('#dfm').html( '<iframe width="100%" height="545" src="'+deff+'" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>');		
//	https://www.youtube.com/embed/qssHvCCePaY" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>				 
		$("#myModalBox").modal('show');

	});	
	 $("#myModalBox").on('hidden.bs.modal', function(){

 	 $('#dfm').html('');
  });		
		
	   $('.alert').on('close.bs.alert', function(){
        console.log();
		$.post('nmo.php', {'alert':'1', 'id' :$(this).data('id')},function(data) {});				   
    });	
		
	$('.fpass').on('click',function(e){
			e.preventDefault();
	tr=$(this);
	num=$(this).data('num');
		pass=$('#puser'+num).val();
		
	$.post('nmo.php', {'psw':'1', 'num' :num,'pass':pass},		function(data) {
		
	if(data=="yes"){$('#bod'+num).show();
				  $('#ig'+num).hide(); 
				   }
		
		
		
	});
			 });		
		
		
	});
	
	</script>
</body>
</html>
<?php
 /* fixed MMiC */ mysqli_free_result($test);
?>
