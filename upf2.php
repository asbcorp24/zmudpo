<?php require_once('Connections/testmed.php'); ?>
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
if (!((isset($_SESSION['MM_Username2020'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username2020'], $_SESSION['MM_UserGroup2020'])))) {   
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
$disk->setAccessToken('y0__xCh7NkIGMWzFSCE8u6oFq3cJaCFPXEv2NIfaVl8uOE9gH4_');//AQAAAAABFnYhAAVZxQzZDx09O058likkI-Hbk2Q
//AQAAAAABFnYhAAVZxfPOIpyFtE2tvGXmEm4bYN0
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
if (isset($_SESSION['MM_UserGroup2020'])) {
  $colname_test = $_SESSION['MM_UserGroup2020'];
}
$username_test = "0";
if (isset($_SESSION['MM_Username2020'])) {
  $username_test = $_SESSION['MM_Username2020'];
}
 function getExtension1($filename) {
	 
	
    return end(explode(".", $filename));
  }
  
 
$mr=-1;
if (isset($_POST['mediarazd']))$mr=(int)$_POST['mediarazd'];






$mim='PDF,DOCX,PPTX,JPG,PPT,DOC,PNG';
$mim=str_replace(' ','',$mim);
$mim=str_replace('PDF','application/pdf',$mim);

$mim=str_replace('DOCX','application/vnd.openxmlformats-officedocument.wordprocessingml.document',$mim);

$mim=str_replace('PPTX','application/vnd.openxmlformats-officedocument.presentationml.presentation',$mim);
$mim=str_replace('JPG','image/jpeg',$mim);
$mim=str_replace('PPT','application/vnd.ms-powerpoint',$mim);
$mim=str_replace('DOC','application/msword',$mim);
$mim=str_replace('PNG','image/png',$mim);
$mim=str_replace('ZIP','application/zip',$mim);

$pieces = explode(",", $mim);

$finfo = finfo_open(FILEINFO_MIME_TYPE); // return mime type ala mimetype extension

if (isset($_POST['del'])){
$num=(int)$_POST['num'];
	
$sql="select * from tm_nmo_razd_media where id=$num";

$uf =   DB::Query($sql, $testmed) or die(  mysqli_error(DB::$link));
$row_uf =   mysqli_fetch_assoc($uf);
	
$sql="delete from tm_nmo_razd_media where id=$num";	

DB::Query($sql, $testmed) or die(  mysqli_error(DB::$link));

$resource = $disk->getResource('app:/'.$row_uf['yname']);
	$has = $resource ->has(); // проверить есть ли ресурс на диске.
echo $has;



	
	?>ok<?php 
	
	exit();
}
  	 

if (isset($_FILES['fileuser'])){
$fl=0;
$finfo=finfo_file($finfo, $_FILES['fileuser']['tmp_name']);

foreach ($pieces as &$value) {
	if ($finfo==$value)	$fl=1;
		
	}
	if (($fl==0)or(($_FILES['fileuser']['size'] > 25*1024*1024)) ){

if($fl==0)$ot="Этот формат $finfo неразрешен, выберите форматы из ".$row_uf['path'];
		if(($_FILES['fileuser']['size'] > 25*1024*1024))$ot="<td colspan=6>Размер файла больше 25 мб не поддерживается - отправьте ссылку на документ в облаке</td>";
?> 

<?php echo $ot; } else {


//print_r($finfo);
	$fname=uniqid().".".getExtension1($_FILES['fileuser']["name"]);
	move_uploaded_file( $_FILES['fileuser']["tmp_name"],'./files/'.$fname ) ;
	
	$resource = $disk->getResource('app:/'.$fname);

$has = $resource->has(); // проверить есть ли ресурс на диске.
	
if ( ! $has)	

{
	//cho 	$fname;
	// загружает локальный файл на диск. второй параметр отвечает за перезапись файла, если такой есть на диске.
	// загружает удаленный файл на диск, передайте ссылку http на удаленный файл.
	$resource->upload('./files/'.$fname ,true);
	;
	//esource->custom_index_1 = $fname; // добавить метаинформацию в "custom_properties"
	//rint_r(	$resource);
$resource->setPublish(); 
	$urll=$resource->public_url; // URL адрес	
///ho 	$urll;
	unlink('./files/'.$fname );	
		
	$sql="INSERT INTO `tm_nmo_razd_media` (`id`, `tm_nmo_razd`, `path`, `tip`, `act`, `obyaz`, `num`, `comment`, `dop_file`, `nazv`, `povt`, `data_act`, `data_okon`, `avtor`) VALUES (NULL, $mr, '$urll', 13, 1, 1, NULL, NULL, NULL, '".$_FILES['fileuser']["name"]."', 0, NULL, NULL, 1)";
	
	//$sql="INSERT INTO `tm_konf_user_files` (`num`, `user`, `media`, `path`, `name`,yname) VALUES (NULL, $mr, $mr, '$urll','".$_FILES['fileuser']["name"]."','$fname')";
$testz =   DB::Query($sql, $testmed) or die(  mysqli_error(DB::$link));
	
}
//

$sql="select max(id) as id from tm_nmo_razd_media";
$spec =   DB::Query($sql, $testmed) or die(  mysqli_error(DB::$link));
$row_spec =  mysqli_fetch_assoc($spec);
	$specc=$row_spec['id'];
	echo '
				    <td align="center" class="npc"><button data-id="1" class="sho"><i class="glyphicon glyphicon-th"></i></button></td>
				    <td data-num="'.$specc.'" data-name="num"></td>
					 
	  	         <td data-num="'.$specc.'" data-name="nazv">'.$_FILES['fileuser']["name"].'</td>
				 <td >Ссылка</td>
				   <td >'.$urll.'</td>
	  	         <td></td> <td></td>
  	           ';

 } }    ?>