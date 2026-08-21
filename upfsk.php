<?php require_once('Connections/testmed.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

include('classSimpleImage.php');
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
function resize($file_input, $file_output, $w_o, $h_o, $percent = false) {
    list($w_i, $h_i, $type) = getimagesize($file_input);
    if (!$w_i || !$h_i) {
        echo 'Невозможно получить длину и ширину изображения';
        return;
    }
    $types = array('','gif','jpeg','png');
    $ext = $types[$type];
    if ($ext) {
        $func = 'imagecreatefrom'.$ext;
        $img = $func($file_input);
    } else {
        echo 'Некорректный формат файла';
        return;
    }
    if ($percent) {
        $w_o *= $w_i / 100;
        $h_o *= $h_i / 100;
    }
    if (!$h_o) $h_o = $w_o/($w_i/$h_i);
    if (!$w_o) $w_o = $h_o/($h_i/$w_i);
    $img_o = imagecreatetruecolor($w_o, $h_o);
    imagecopyresampled($img_o, $img, 0, 0, 0, 0, $w_o, $h_o, $w_i, $h_i);
    if ($type == 2) {
        return imagejpeg($img_o,$file_output,100);
    } else {
        $func = 'image'.$ext;
        return $func($img_o,$file_output);
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

$sql="SELECT 
  `tm_user`.`fio`
FROM
  `tm_user`
WHERE
  `tm_user`.`num` = $username_test
LIMIT 1";
$nmo3 =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_nmo3 =  /* fixed MMiC */ mysqli_fetch_assoc($nmo3);
$row_nmo3=$row_nmo3['fio'];
 function getExtension1($filename) {
	 
	
    return end(explode(".", $filename));
  }
$mr=-1;
if (isset($_POST['mediarazd']))$mr=(int)$_POST['mediarazd'];




$mim='JPG,PNG';
$mim=str_replace(' ','',$mim);

$mim=str_replace('JPG','image/jpeg',$mim);
$mim=str_replace('PNG','image/png',$mim);


$pieces = explode(",", $mim);

$finfo = finfo_open(FILEINFO_MIME_TYPE); // return mime type ala mimetype extension

if (isset($_POST['del'])){
$num=(int)$_POST['num'];
	

	
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
 
if (isset($_POST['utub'])){
	$txr=($_POST['utub']);
	$sql="INSERT INTO `tm_nmo_user_file` (`num`, `user`, `tip`, `path`, `comment`, `inn`,dat) VALUES (NULL, $username_test, 3, '$txr', NULL, $mr,'".date("Y-m-d H:i:s")."')";
	 DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));

	$sql="SELECT 
  max(`tm_nmo_user_file`.`num`) as num
FROM
  `tm_nmo_user_file`";
	$nmo =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_nmo =  /* fixed MMiC */ mysqli_fetch_assoc($nmo);
$row_nmo=$row_nmo['num'];
	
?>
	
    <div id="dload16" style="" class="dld"><div class="input-group">
		
		
      <input type="text" value=""  class="form-control opis" placeholder="введите описание"  data-com="<?php echo $row_nmo; ?>" data-tip="3">
		<input type="hidden" name="stash" value="16">
		<input type="hidden" value="<?php echo $row_razd['id']; ?>" name="mediarazd">
        <span class="input-group-addon info">
      <a href="<?php echo $txr; ?>" target="_blank">  <i class="icofont icofont-image"></i> Посмотреть
            </a></span>
    </div> 
	  
	  </div><br>

<?php	
	
}	
	
	
if (isset($_POST['txr'])){
	$txr=addslashes($_POST['txr']);
	$sql="INSERT INTO `tm_nmo_user_file` (`num`, `user`, `tip`, `path`, `comment`, `inn`,`dat`) VALUES (NULL, $username_test, 1, '', '$txr', $mr,'".date("Y-m-d H:i:s")."')";
	 DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
	$sql="SELECT 
  max(`tm_nmo_user_file`.`num`) as num
FROM
  `tm_nmo_user_file`";
	$nmo =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_nmo =  /* fixed MMiC */ mysqli_fetch_assoc($nmo);
$row_nmo=$row_nmo['num'];
	
?>
	
    <div id="dload16" style="" class="dld"><div class="input-group">
		
		
      <input type="text" value=""  class="form-control opis" placeholder="введите описание"  data-com="<?php echo $row_nmo; ?>" data-tip="1">
		<input type="hidden" name="stash" value="16">
		<input type="hidden" value="<?php echo $row_razd['id']; ?>" name="mediarazd">
        <span class="input-group-addon info">
      <a href="getuserf.php?num=<?php echo $row_nmo; ?>" class="prpr" >  <i class="icofont icofont-image"></i> Посмотреть
            </a></span>
    </div> 
	  
	  </div><br>

<?php	
	
}
if (isset($_FILES['fileuser'])){
//	print_r($_FILES);
$fl=0;
$finfo=finfo_file($finfo, $_FILES['fileuser']['tmp_name']);
 
foreach ($pieces as &$value) {
	//echo $value.":".$finfo."<br>";
	if ($finfo==$value)	$fl=1;
		
	}
	if (($fl==0)or(($_FILES['fileuser']['size'] > 10*1024*1024)) ){

if($fl==0)$ot="Этот формат $finfo неразрешен";
		if(($_FILES['fileuser']['size'] > 10*1024*1024))$ot="Размер файла больше 10 мб не поддерживается";
		//echo $ot;
		?>
 <div id="dload16" style="" class="form-control ">
	<?php echo $ot; ?>	
		
    
		
       
  
	  
	  </div><br>
<?php
	exit;
	}
	$dir=$mr;
	mkdir('./usrimg/'.$dir, 0777, true);
	$fnm=uniqid().'.jpg';
	$image = new SimpleImage();
	$image->load($_FILES['fileuser']['tmp_name']);
	$wee=	$image->getWidth();
	
 if ($wee<400) {  echo '<div id="dload16" style="" class="form-control ">Слишком маленькое изображение '.$wee.' минимум 400</div><br>';
	exit;}		
 if ($wee>3000) 	$image->resizeToWidth(2500);
			//$image->resizeToHeight(1024);
			$image->addwatemark(10,30,$row_nmo3);
	$image->adddata();
	$image->addtext($row_nmo3);
			$filenameimg =uniqid().'.jpg';//.getExtension1($_FILES['fscreen']['name'][$key]);
	$filenameimg2 =uniqid().'.webp';
	
	 if ($wee<1000){//$image->savewebpmin('usrimg/'.$filenameimg2);
	 $filenameimg2=$fnm;
	 	$image->save('usrimg/'.$dir.'/'.$fnm);
	 } else
			$image->savewebp('usrimg/'.$dir.'/'.$filenameimg2);
		$full=$dir.'/'.$filenameimg2;	
	//$image->savewebp('usrimg/'.$filenameimg2,75);
//resize($_FILES['fileuser']['tmp_name'],'usrimg/'.$fnm,1500,null);	]
	$sql="INSERT INTO `tm_nmo_user_file` (`num`, `user`, `tip`, `path`, `comment`, `inn`,dat) VALUES (NULL, $username_test, 2, '$full', NULL, $mr,'".date("Y-m-d H:i:s")."')";
	 DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
	$sql="SELECT 
  max(`tm_nmo_user_file`.`num`) as num
FROM
  `tm_nmo_user_file`";
	$nmo =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_nmo =  /* fixed MMiC */ mysqli_fetch_assoc($nmo);
$row_nmo=$row_nmo['num'];
	
?> 

		
    <div id="dload16" style="" class="dld"><div class="input-group">
		
		
      <input type="text" value=""  class="form-control opis" placeholder="введите описание"  data-com="<?php echo $row_nmo; ?>"  data-tip="2">
		<input type="hidden" name="stash" value="16">
		<input type="hidden" value="<?php echo $row_razd['id']; ?>" name="mediarazd">
        <span class="input-group-addon info">
      <a href="getuserf.php?num=<?php echo $row_nmo; ?>" class="prpr" >  <i class="icofont icofont-image"></i> Посмотреть
            </a></span>
    </div> 
	  
	  </div><br>
<?php  }
//
?>
