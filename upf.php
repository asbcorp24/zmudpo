<?php 
require_once('Connections/testmed.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";


//i_set('error_reporting', E_ALL);
//i_set('display_errors', 1);
//i_set('display_startup_errors', 1);


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

require_once __DIR__.'/vendor/autoload.php';

$disk = new Arhitector\Yandex\Disk();
$disk->setAccessToken('AQAAAAABFnYhAAVZxeGGdjyyYE57o2KYoFB9Gis');//AQAAAAABFnYhAAVZxQzZDx09O058likkI-Hbk2Q

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
}?>
<?php 
$colname_test = "-1";
if (isset($_SESSION['MM_UserGroup'])) {
  $colname_test = $_SESSION['MM_UserGroup'];
}
$username_test = "0";
if (isset($_SESSION['MM_Username1'])) {
  $username_test = $_SESSION['MM_Username1'];
}
 function getExtension1($filename) {
	 
	
    return end(explode(".", $filename));
  }
$mr=-1;
if (isset($_POST['mediarazd']))$mr=(int)$_POST['mediarazd'];




$mr=-1;
if (isset($_POST['mediarazd']))$mr=(int)$_POST['mediarazd'];
$sql="SELECT 
  `tm_nmo_razd_media`.`id`,
  `tm_nmo_razd_media`.`path`
FROM
  `tm_nmo_razd_media`
WHERE
  `tm_nmo_razd_media`.`id` = $mr";

  $uf =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_uf =  /* fixed MMiC */ mysqli_fetch_assoc($uf);
$mim=$row_uf['path'];$mim=strtoupper($mim);
$mim=str_replace(' ','',$mim);
$mim=str_replace('PDF','application/pdf',$mim);

$mim=str_replace('DOCX','application/vnd.openxmlformats-officedocument.wordprocessingml.document',$mim);

$mim=str_replace('PPTX','application/vnd.openxmlformats-officedocument.presentationml.presentation',$mim);
$mim=str_replace('JPG','image/jpeg',$mim);
$mim=str_replace('PPT','application/vnd.ms-powerpoint',$mim);
$mim=str_replace('DOC','application/msword',$mim);
$mim=str_replace('JPG','image/png',$mim);
$mim=str_replace('ZIP','application/zip',$mim);

$pieces = explode(",", $mim);

$finfo = finfo_open(FILEINFO_MIME_TYPE); // return mime type ala mimetype extension

if (isset($_POST['del'])){
$num=(int)$_POST['num'];
	
$sql="select * from tm_konf_user_files where num=$num and user= $username_test";

$uf =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_uf =  /* fixed MMiC */ mysqli_fetch_assoc($uf);
//$resource = $disk->getResource('app:/'.$row_uf['yname']);
//	$has = $resource ->has(); // проверить есть ли ресурс на диске.
//echo $has;
//if ($has){
	
//$resource->delete(false);	
//	$disk->cleanTrash();
//}
	
$sql="INSERT INTO `tm_konf_user_files_arh` (`num`, `user`, `media`, `path`, `name`, `yname`, `old`) VALUES (NULL,".$row_uf['user'].", ".$row_uf['media'].", '".$row_uf['path']."',  '".$row_uf['name']."',  '".$row_uf['yname']."', ".$row_uf['num'].")";
 DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));	
$sql="delete from tm_konf_user_files where num=$num and user= $username_test";	
DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
	?><form id="form<?php echo $mr ?>" enctype="multipart/form-data">
	<h3> <?php echo $ot;?></h3>
		<div class="progress" style="display: none" id="prog<?php echo $mr ?>">
    <div class="progress-bar progress-bar-striped progress-bar-warning" role="progressbar" style="width: 0% " aria-valuenow="14" aria-valuemin="0" aria-valuemax="100" id="pb<?php echo $mr ?>"></div>
</div>
<div id="drop-area">
 </div>
		<div id='bgd<?php echo $mr ?>'>
				<div class="input-group">
		  <span class="input-group-addon">
            Ссылка     </span>
	  <input type="url" class="form-control" list="22" value="" data-num="7" name="url">       	 	     </div><br>
  <input type="file" title="Click to add Files" class="form-control" name="fileuser" accept="<?php echo $mim; ?>">
	<br>
		<input type="hidden" value="<?php echo $mr ?>" name="mediarazd">
	<button class="btn btn-info form-control send" data-num="<?php echo $mr ?>"  type="button" >Отправить</button>
</div>
</form> <?php 
	
	exit();
}
 	 
if (isset($_POST['url']) && ($_POST['url']!="")){
	
	if (!filter_var($_POST['url'], FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED)){
		
		?><form id="form<?php echo $mr ?>" enctype="multipart/form-data">
	<h3> Неправильный URL</h3>
		<div class="progress"  style="display: none" id="prog<?php echo $mr ?>">
    <div class="progress-bar progress-bar-striped progress-bar-warning" role="progressbar" style="width: 0% " aria-valuenow="14" aria-valuemin="0" aria-valuemax="100" id="pb<?php echo $mr ?>"></div>
</div>
<div id="drop-area">
 </div>
		<div id='bgd<?php echo $mr ?>'>
				<div class="input-group">
		  <span class="input-group-addon">
            Ссылка     </span>
	  <input type="url" class="form-control" list="22" value="" data-num="7" name="url">       	 	     </div><br>
  <input type="file" title="Click to add Files" class="form-control" name="fileuser" accept="<?php echo $mim; ?>">
	<br>
		<input type="hidden" value="<?php echo $mr ?>" name="mediarazd">
	<button class="btn btn-default form-control send" data-num="<?php echo $mr ?>"  type="button" >Отправить</button>
</div>
</form> <?php exit(0);
		
	} else {
		$url=$_POST['url'];
		$sql="INSERT INTO `tm_konf_user_files` (`num`, `user`, `media`, `path`, `name`) VALUES (NULL, $username_test, $mr, '$url','".$url."')";
		
$testz =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
	echo '<div class="input-group">
		  <span class="input-group-addon">
             <a href="'.$url.'">Скачать</a>      </span>
	  <input type="text" class="form-control ank" list="22" value="'.$url.'" data-num="7"> </div>';	

		 exit(0);
	}
	
}
if (isset($_FILES['fileuser'])){
$fl=0;
$finfo=finfo_file($finfo, $_FILES['fileuser']['tmp_name']);

foreach ($pieces as &$value) {
	if ($finfo==$value)	$fl=1;
		
	}
	if (($fl==0)or(($_FILES['fileuser']['size'] > 50*1024*1024)) ){

if($fl==0)$ot="Этот формат $finfo неразрешен, выберите форматы из ".$row_uf['path'];
		if(($_FILES['fileuser']['size'] > 50*1024*1024))$ot="Размер файла больше 50 мб не поддерживается - отправьте ссылку на документ в облаке";
?> 
<form id="form<?php echo $mr ?>" enctype="multipart/form-data">
	<h3> <?php echo $ot;?></h3>
		<div class="progress" style="display: none" id="prog<?php echo $mr ?>">
    <div class="progress-bar progress-bar-striped progress-bar-warning" role="progressbar" style="width: 0% " aria-valuenow="14" aria-valuemin="0" aria-valuemax="100" id="pb<?php echo $mr ?>"></div>
</div>
<div id="drop-area">
 </div>
		<div id='bgd<?php echo $mr ?>'>
				<div class="input-group">
		  <span class="input-group-addon">
            Ссылка     </span>
	  <input type="url" class="form-control" list="22" value="" data-num="7" name="url">       	 	     </div><br>
  <input type="file" title="Click to add Files" class="form-control" name="fileuser" accept="<?php echo $mim; ?>">
	<br>
		<input type="hidden" value="<?php echo $mr ?>" name="mediarazd">
	<button class="btn btn-info form-control send" data-num="<?php echo $mr ?>"  type="button" >Отправить</button>
</div>
</form> 
<?php } else {

//print_r($finfo);
	$fname=uniqid().".".getExtension1($_FILES['fileuser']["name"]);
	move_uploaded_file( $_FILES['fileuser']["tmp_name"],'./files/'.$fname ) ;
		
	$resource = $disk->getResource('app:/'.$fname);

$has = $resource->has(); // проверить есть ли ресурс на диске.
	
if ( ! $has)	

{

	// загружает локальный файл на диск. второй параметр отвечает за перезапись файла, если такой есть на диске.
	// загружает удаленный файл на диск, передайте ссылку http на удаленный файл.
	$resource->upload('./files/'.$fname ,true);

	//esource->custom_index_1 = $fname; // добавить метаинформацию в "custom_properties"
	//rint_r(	$resource);
$resource->setPublish(); 
	$urll=$resource->public_url; // URL адрес	
///ho 	$urll;
	unlink('./files/'.$fname );	
		
		
	$sql="INSERT INTO `tm_konf_user_files` (`num`, `user`, `media`, `path`, `name`,yname) VALUES (NULL, $username_test, $mr, '$urll','".$_FILES['fileuser']["name"]."','$fname')";
$testz =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
	
}
//
?>
<div class="input-group">
		  <span class="input-group-addon">
             <a href="<?php echo $urll; ?>">Скачать</a>      </span>
	  <input type="text" class="form-control ank" list="22" value="<?php echo $urll; ?>" data-num="7">       	 	     </div>
<?php } }?>