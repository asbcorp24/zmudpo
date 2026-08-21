<?php require_once('Connections/testmed.php'); ?>
<?php
ini_set('display_errors', 'Off'); // теперь сообщений НЕ будет
if (!isset($_SESSION)) {
  session_start();
}
include('classSimpleImage.php');
function er($st){
	$er1="php|php3|php4|php5|php6|phtml|pl|asp|aspx|cgi|dll|exe|shtm|shtml|fcg|fcgi|fpl|asmx|pht|py|psp|rb|var";
if (strripos($er1,$st)!=false)	$st=$st.'_';
	return($st);
}
 function getExtension1($filename) {
    return end(explode(".", $filename));
  }
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
 

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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
$username_test = "0";
if (isset($_SESSION['MM_Username1'])) {
  $username_test = $_SESSION['MM_Username1'];
}
 
$filen="NULL";
$filen1="NULL";
//print_r($_POST);
//print_r($_FILES);
$sql="SELECT   `tm_user`.`fio`, `tm_user`.`mail` FROM  `tm_user` WHERE  `tm_user`.`num` =".$username_test;
$Result1 =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
	$unm=mysqli_fetch_assoc($Result1);
	$fio=$unm['fio'];
$mail=$unm['mail'];

	if($_FILES['fscreen'])
{  $image = new SimpleImage();
	foreach ($_FILES["fscreen"]["error"] as $key => $error) {
		if ($error == UPLOAD_ERR_OK) {
	
		if (!in_array(strtoupper(getExtension1($_FILES['fscreen']['name'][$key])),array('JPG','PNG'))) continue;
 
			$image->load($_FILES['fscreen']['tmp_name'][$key]);
			
		$image->resizeToWidth(1980);
			$image->addwatemark(10,30,$fio);
			$filenameimg =uniqid().'.jpg';//.getExtension1($_FILES['fscreen']['name'][$key]);
		$image->save('./pract_user/img/'.$filenameimg,IMAGETYPE_JPEG,80);
	//	move_uploaded_file($_FILES['fscreen']['tmp_name'][$key],'./pract_user/img/'.$filenameimg);
		$insertSQL = sprintf("INSERT INTO tm_user_pract (`user`, tema_pr, tema, `file`, img, otv) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($username_test, "int"),
                       GetSQLValueString($_POST['tnum'], "int"),
                       GetSQLValueString(1, "int"),
                       GetSQLValueString("NULL", "text"),
                       GetSQLValueString($filenameimg, "text"),
                       GetSQLValueString("NULL", "text"));

$pr =  /* fixed MMiC */ DB::Query($insertSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
		$zap="select max(num) as num from tm_user_pract";
	$Result1 =  /* fixed MMiC */ DB::Query($zap, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
	$lastid=mysqli_fetch_assoc($Result1);
	$lastid=$lastid['num'];		
			
		?>
	
  <div class="col-sm-6 col-md-4" id="thm<?php echo $lastid; ?>">
    <div class="thumbnail">
      <a class="fancyimage" data-fancybox-group="group" href="./pract_user/img/<?php echo $filenameimg; ?>"> 
  <img class="img-responsive" src="get_img.php?img=pract_user/img/<?php echo $filenameimg; ?>&w=300&h=200" /> 
</a>  
      <div class="caption">
        <div class="btn-group btn-group-justified">
       <a href="#" class="btn btn-primary" role="button">Сохранить</a> <a href="#" class="btn btn-danger dell" role="button" name="<?php echo $lastid; ?>">Удалить</a>
		  </div></div>
    </div>
  </div>

		
		<?php 
		//echo($insertSQL);	
		}
	}
}

	if($_FILES['ffile'])
{
	
	foreach ($_FILES["ffile"]["error"] as $key => $error) {
		if ($error == UPLOAD_ERR_OK) {
			
			
			$filenameimg =uniqid().'.'.getExtension1($_FILES['ffile']['name'][$key]);
			if 	($_FILES['ffile']['size'][$key]>1000000) {continue;}
		move_uploaded_file($_FILES['ffile']['tmp_name'][$key],'./pract_user/file/'.$filenameimg);
		$insertSQL = sprintf("INSERT INTO tm_user_pract (`user`, tema_pr, tema, `file`, img, otv) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($username_test, "int"),
                       GetSQLValueString($_POST['tnum'], "int"),
                       GetSQLValueString(2, "int"),
                       GetSQLValueString($filenameimg, "text"),
                       GetSQLValueString("NULL", "text"),
                       GetSQLValueString("NULL", "text"));

$pr =  /* fixed MMiC */ DB::Query($insertSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
			$zap="select max(num) as num from tm_user_pract";
	$Result1 =  /* fixed MMiC */ DB::Query($zap, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
	$lastid=mysqli_fetch_assoc($Result1);
	$lastid=$lastid['num'];		
			
		?>
	
  <div class="col-sm-6 col-md-4" id="thm<?php echo $lastid; ?>">
    <div class="thumbnail">
      <a class="fancyimage" data-fancybox-group="group" href="./pract_user/file/<?php echo $filenameimg; ?>"> 
  <img class="img-responsive" src="15.jpg" /> 
</a>  
      <div class="caption">
        <div class="btn-group btn-group-justified">
        <a href="#" class="btn btn-primary" role="button">Сохранить</a> <a href="#" class="btn btn-danger dell" role="button" name="<?php echo $lastid; ?>">Удалить</a>
		  </div></div>
    </div>
  </div>

		
		<?php 
			//	echo($insertSQL);	
		}
	}
}


	if($_POST['ftextr'])
{
	foreach ($_POST["ftextr"] as $key ) {
		 
		
		$insertSQL = sprintf("INSERT INTO tm_user_pract (`user`, tema_pr, tema, `file`, img, otv) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($username_test, "int"),
                       GetSQLValueString($_POST['tnum'], "int"),
                       GetSQLValueString(3, "int"),
                       GetSQLValueString("NULL", "text"),
                       GetSQLValueString("NULL", "text"),
                       GetSQLValueString(htmlspecialchars($key,ENT_QUOTES), "text"));

$pr =  /* fixed MMiC */ DB::Query($insertSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));

					$zap="select max(num) as num from tm_user_pract";
	$Result1 =  /* fixed MMiC */ DB::Query($zap, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
	$lastid=mysqli_fetch_assoc($Result1);
	$lastid=$lastid['num'];		
			
		?>
	
  <div class="col-sm-6 col-md-4" id="thm<?php echo $lastid; ?>">
    <div class="thumbnail">
   		  <a class="fancyimage" data-fancybox-group="group" href="#testube<?php echo $lastid; ?>">
  <img class="img-responsive" src="14.jpg" /> 
</a>  
      <div style="display:none" id="testube<?php echo $lastid; ?>">
 <!-- HTML - код ролика -->
<p><<?php echo nl2br(htmlspecialchars($key,ENT_QUOTES)); ?></p>
		</div>
</a>  
      <div class="caption">
      <div class="btn-group btn-group-justified">
        <a href="#" class="btn btn-danger dell" role="button" name="<?php echo $lastid; ?>">Удалить</a>
      </div> </div>
    </div>
  </div>

		
		<?php 
		//	echo($insertSQL);	
		
	}
}

/*	if (isset($_FILES['fscreen']) and ($_FILES['fscreen']['error']==0)) {
if 	($_FILES['fscreen']['size']>1000000) exit("-|-");
		$filen1 =uniqid().'.'.er(getExtension1($_FILES['fscreen']['name']));
		move_uploaded_file($_FILES['fscreen']['tmp_name'],'./pract_user/img/'.$filen1);
	
}  
if (isset($_FILES['ffile']) and ($_FILES['ffile']['error']==0)) {
	if 	($_FILES['ffile']['size']>1000000) exit("-|-");
		$filen =uniqid().'.'.er(getExtension1($_FILES['ffile']['name']));
		move_uploaded_file($_FILES['ffile']['tmp_name'],'./pract_user/file/'.$filen);
	
}  

$deletwsql="delete from tm_user_pract where `user`=$username_test and tema_pr=".$_POST['tnum'];

//print_r($_FILES);
$enc['ffile']=$filen;
$enc['fscreen']=$filen1;
echo json_encode($enc);
}*/

?>


