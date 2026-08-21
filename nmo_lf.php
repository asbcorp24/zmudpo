<?php

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




require_once('Connections/testmed.php');
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists(" DB::escape") ? DB::escape($theValue) :   DB::escape($theValue);

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


$user=$_SESSION['MM_Username1'];
$id=$_POST['id'];
	
	
	if($_FILES['fnm'])
{
	
	require_once __DIR__.'/vendor/autoload.php';

$disk = new Arhitector\Yandex\Disk('AQAAAAAmkuNoAAT9n0jIekCEw04piPSK6-_930g');


	 {
		{
		
		if 	($_FILES['fnm']['size']>30000000) {continue;}
		
		$ttmp=pathinfo($_FILES['fnm']['name']);
		$ttmp=$ttmp['extension'];
	$filenameimg =uniqid().".".$ttmp;
	$finfo = finfo_open(FILEINFO_MIME_TYPE); // return mime type ala mimetype extension
$ext=finfo_file($finfo, $_FILES['fnm']['tmp_name']);
	$gext='application/pdf;application/msword;application/vnd.ms-powerpoint;application/vnd.visio;application/vnd.ms-excel;application/vnd.openxmlformats-officedocument.wordprocessingml.document;application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;application/vnd.openxmlformats-officedocument.presentationml.presentation;application/vnd.ms-access';
 if (strripos($gext,$ext)===false){echo $ext;exit();}
finfo_close($finfo);
	move_uploaded_file($_FILES['fnm']['tmp_name'],'./pract_user/file/'.$filenameimg);
$resource = $disk->getResource('app:/'.$filenameimg);
$has = $resource->has(); // проверить есть ли ресурс на диске.

if ( ! $has)	

{

	// загружает локальный файл на диск. второй параметр отвечает за перезапись файла, если такой есть на диске.
	// загружает удаленный файл на диск, передайте ссылку http на удаленный файл.
	$resource->upload(__DIR__.'/pract_user/file/'.$filenameimg,true);
	
	$resource->setPublish(); 
	$urll=$resource->public_url; // URL адрес

	unlink('pract_user/file/'.$filenameimg);
	$filenameimg=$urll;
$sql="update `tm_nmo_razd_user` set dop_file='".$filenameimg."' where id=".$id;
	DB::Query($sql, $testmed) or die(  mysqli_error(DB::$link)); 
	echo $filenameimg;
}}}
			

		}



?>
