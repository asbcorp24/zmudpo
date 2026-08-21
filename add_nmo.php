<?php require_once('Connections/testmed.php'); ?>
<?php



if (!isset($_SESSION)) {
  session_start();
}


$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function getsokr($slovo){
$res="";	
$pieces = explode(" ", $slovo);
foreach($pieces as $val){
$res=$res.mb_substr($val,0,3)." ";	
}	
return $res;	
}
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

$MM_restrictGoTo = "index.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
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

 function getExtension1($filename) {
    return end(explode(".", $filename));
  }
if (isset($_POST['izmser'])){
	$media=intval($_POST['media']);
	$sql="update tm_nmo_sert_test set nazv='".$_POST['sert']."', `tm_nmo_sert_test`.`text`='".$_POST['sert_text']."',chas='".$_POST['sert_chas']."' where id=".$_POST['id'];
 	$nma =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));

echo $sql;
	exit();
}

if (isset($_POST['izm'])){
	$media=intval($_POST['media']);
	$sql="SELECT 
  `tm_nmo_sert_test`.`id`,
  `tm_nmo_sert_test`.`nazv`,
  `tm_nmo_sert_test`.`text`,
  `tm_nmo_sert_test`.`media`,
  `tm_nmo_sert_test`.`chas`
FROM
  `tm_nmo_sert_test`
WHERE
  `tm_nmo_sert_test`.`media` = $media";
	$nma =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_nma =  /* fixed MMiC */ mysqli_fetch_assoc($nma);
	
	$tmp='<div class="form-group"> <label for="nazv">Сертификата</label> <input name="sert" class="form-control"  placeholder="Введите название" value="'.$row_nma['nazv'].'"></div><div class="form-group"> <label for="comment">Содержимое сертификата</label> <textarea name="sert_text" type="number" min="0" class="form-control"  placeholder="Содержимое">'.$row_nma['text'].'</textarea></div><div class="form-group"> <label for="nazv">часов</label> <input name="sert_chas" class="form-control"  placeholder="Количество часов" type="number" value="'.$row_nma['chas'].'"> </div><input type="hidden" name="id" value="'.$row_nma['id'].'"><input type="hidden" name="izmser" value="'.$media.'">';echo $tmp;
	exit();
}


if ((isset($_POST["nlist"])))
{
	
	
	$insertSQL = "delete from `tm_nmo_razd_media_list` where  `tm_nmo_razd_media_list`.tm_nmo_razd_media=".$_POST["tm_nmo_razd_media"];
	$Result1 =  /* fixed MMiC */ DB::Query($insertSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
	$ss=$_POST['nlist'];
	  $res=explode(PHP_EOL,$ss);
	 
	 for ($i=0;$i<=count($res);$i++){
  $insertSQL = sprintf("INSERT INTO `tm_nmo_razd_media_list` (`id`, `tm_nmo_razd_media`, `tex`) VALUES (NULL, %s, %s)",
                      GetSQLValueString($_POST["tm_nmo_razd_media"], "int"),
					   GetSQLValueString($res[$i], "text"));	
   /* fixed MMiC */ mysqli_select_db(DB::$link, $testmed);
  $Result1 =  /* fixed MMiC */ DB::Query($insertSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));

	} 
	
	$insertSQL = "delete from `tm_nmo_razd_media_list` where  `tm_nmo_razd_media_list`.tex is NULL";
	$Result1 =  /* fixed MMiC */ DB::Query($insertSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
	exit(0);
	
}
if ((isset($_POST["list"]))){

$sql='SELECT 
  `tm_nmo_razd_media_list`.`id`,
  `tm_nmo_razd_media_list`.`tm_nmo_razd_media`,
  `tm_nmo_razd_media_list`.`tex`
FROM
  `tm_nmo_razd_media_list`
WHERE
  `tm_nmo_razd_media_list`.`tm_nmo_razd_media` = '.GetSQLValueString($_POST['list'], "int");

$nma =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_nma =  /* fixed MMiC */ mysqli_fetch_assoc($nma);
$totalRows_nma =  /* fixed MMiC */ mysqli_num_rows($nma);
	
	$t='';
do {  

  $t=$t.$row_nma['tex']."\n";       
      
} while ($row_nma =  /* fixed MMiC */ mysqli_fetch_assoc($nma));
	
echo '<div class="form-group">
    <label for="nlist">Список</label>
	  <textarea name="nlist"  class="form-control" id="nazv" rows="10" placeholder="Введите название">'.$t.'</textarea>
  </div>
 
 <input type="hidden" name="tm_nmo_razd_media" value="'.GetSQLValueString($_POST['list'], "int").'">	';
	exit(0);
}

function reArrayFiles($file)
{
    $file_ary = array();
    $file_count = count($file['name']);
    $file_key = array_keys($file);
    
    for($i=0;$i<$file_count;$i++)
    {
        foreach($file_key as $val)
        {
            $file_ary[$i][$val] = $file[$val][$i];
        }
    }
    return $file_ary;
}


if ((isset($_POST["tip"]))) {
	
if ($_POST["tip"]==1){
	
$path = $_FILES['path'];

if(!empty($path))
{
    $path_desc = reArrayFiles($path);
   // print_r($path_desc);
    
    foreach($path_desc as $val)
    {
		echo $val['name'];
       // $newname = date('YmdHis',time()).mt_rand().'.jpg';
      //  move_uploaded_file($val['tmp_name'],'./uploads/'.$newname);

	
	
	
	
	if (getExtension1($val['name'])=="zip")	{
	
	$filenamep=uniqid().'/';
	$zip = new ZipArchive; 
   $zip->open($val['tmp_name']); 
   $zip->extractTo('../nmo/doc/'.$filenamep); 
	 $filotv =$filenamep.'index.html';
    $zip->close(); 
} else{
	$filotv =uniqid().'.pdf';
if (isset($val))
		move_uploaded_file($val['tmp_name'],'../nmo/doc/'.$filotv);	
	}
		if ($_POST['nazv']='')$_POST['nazv']=$val['name'];
		if (count($path_desc)>1){$_POST['nazv']=$val['name'];}
	 $insertSQL = sprintf("INSERT INTO `tm_nmo_razd_media` (`tm_nmo_razd`, `path`, `tip`, `act`, `obyaz`, `num`, `comment`, `dop_file`, `nazv`) 
	 VALUES (%s, %s, 1, %s, NULL, %s, %s, NULL, %s)",
                       GetSQLValueString($_POST['tm_nmo_razd'], "int"),
                       GetSQLValueString($filotv, "text"),
	                    GetSQLValueString(($_POST['act']=="on") ? "1" : "0","int" ),
						   GetSQLValueString($_POST['num'], "int"),
					 GetSQLValueString($_POST['comment'], "text"),
						 GetSQLValueString($_POST['nazv'], "text"));
	//echo $insertSQL;
	DB::Query($insertSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link)); 
	    }
}	

	}	
	
	if ($_POST["tip"]==15){
	
if (getExtension1($_FILES['path']['name'])=="zip")	{
	
	$filenamep=uniqid().'/';
	$zip = new ZipArchive; 
   $zip->open($_FILES['path']['tmp_name']); 
   $zip->extractTo('../nmo/test/'.$filenamep); 
	 $filotv =$filenamep.'index.html';
    $zip->close(); 
} else {	
	
	$filotv =uniqid().'.html';
if (isset($_FILES['path']))
		move_uploaded_file($_FILES['path']['tmp_name'],'../nmo/test/'.$filotv);	
}
        $filenames='NULL';
if (($_FILES['spath']['tmp_name']!='')){

    $filenames=uniqid().'.jpg';
    move_uploaded_file($_FILES['spath']['tmp_name'],'../nmo/'.$filenames);
    $filenames= GetSQLValueString($filenames, "text");
}
	if (empty($_POST['nazv']))$_POST['nazv']=$_FILES['path']['name'];
	
	 $insertSQL = sprintf("INSERT INTO `tm_nmo_razd_media` (`tm_nmo_razd`, `path`, `tip`, `act`, `obyaz`, `num`, `comment`, `dop_file`, `nazv`,data_act) 
	 VALUES (%s, %s, 15, %s, %s, %s, %s, $filenames, %s,%s)",
                       GetSQLValueString($_POST['tm_nmo_razd'], "int"),
                       GetSQLValueString($filotv, "text"),
	                    GetSQLValueString(($_POST['act']=="on") ? "1" : "0","int" ),
						    GetSQLValueString(($_POST['obyaz']=="on") ? "1" : "0","int" ),
						   GetSQLValueString($_POST['num'], "int"),
					 GetSQLValueString($_POST['comment'], "text"),
						 GetSQLValueString($_POST['nazv'], "text"), GetSQLValueString($_POST['data_act'], "date"));
	//echo $insertSQL;
	DB::Query($insertSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link)); 
$sql="select max(id) as mid from `tm_nmo_razd_media`"	;
			$result1 =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
	echo $sql;
	$row = mysqli_fetch_assoc($result1);
	$totalRows_cpr =  /* fixed MMiC */ mysqli_num_rows($result1);
$sql="INSERT INTO `tm_nmo_sert_test` (`id`, `nazv`, `text`, `media`, `chas`) VALUES (NULL, '".(addslashes($_POST['sert']))."', '".(addslashes($_POST['sert_text']))."', ".$row['mid'].",".intval($_POST['sert_chas']).")";
				$result1 =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
		echo $sql;
		exit();
	}	
	
	
	if ($_POST["tip"]==22){
	
if (getExtension1($_FILES['path']['name'])=="zip")	{
	
	$filenamep=uniqid().'/';
	$zip = new ZipArchive; 
   $zip->open($_FILES['path']['tmp_name']); 
   $zip->extractTo('../nmo/test/'.$filenamep); 
	 $filotv =$filenamep.'index.html';
    $zip->close(); 
} else {	
	
	$filotv =uniqid().'.html';
if (isset($_FILES['path']))
		move_uploaded_file($_FILES['path']['tmp_name'],'../nmo/test/'.$filotv);	
}
        $filenames='NULL';
if (($_FILES['spath']['tmp_name']!='')){

    $filenames=uniqid().'.jpg';
    move_uploaded_file($_FILES['spath']['tmp_name'],'../nmo/'.$filenames);
    $filenames= GetSQLValueString($filenames, "text");
}
	if (empty($_POST['nazv']))$_POST['nazv']=$_FILES['path']['name'];
	
	 $insertSQL = sprintf("INSERT INTO `tm_nmo_razd_media` (`tm_nmo_razd`, `path`, `tip`, `act`, `obyaz`, `num`, `comment`, `dop_file`, `nazv`,data_act) 
	 VALUES (%s, %s, 22, %s, %s, %s, %s, $filenames, %s,%s)",
                       GetSQLValueString($_POST['tm_nmo_razd'], "int"),
                       GetSQLValueString($filotv, "text"),
	                    GetSQLValueString(($_POST['act']=="on") ? "1" : "0","int" ),
						    GetSQLValueString(($_POST['obyaz']=="on") ? "1" : "0","int" ),
						   GetSQLValueString($_POST['num'], "int"),
					 GetSQLValueString($_POST['comment'], "text"),
						 GetSQLValueString($_POST['nazv'], "text"), GetSQLValueString($_POST['data_act'], "date"));
	//echo $insertSQL;
	DB::Query($insertSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link)); 
$sql="select max(id) as mid from `tm_nmo_razd_media`"	;
			$result1 =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
	echo $sql;
	$row = mysqli_fetch_assoc($result1);
	$totalRows_cpr =  /* fixed MMiC */ mysqli_num_rows($result1);
$sql="INSERT INTO `tm_nmo_sert_test` (`id`, `nazv`, `text`, `media`, `chas`) VALUES (NULL, '".(addslashes($_POST['sert']))."', '".(addslashes($_POST['sert_text']))."', ".$row['mid'].",".intval($_POST['sert_chas']).")";
				$result1 =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
		echo $sql;
		exit();
	}	
if ($_POST["tip"]==19){
	
 {	
	
	$filotv =uniqid().'.csv';
if (isset($_FILES['path']))
		move_uploaded_file($_FILES['path']['tmp_name'],'../nmo/csv/'.$filotv);	
}
	if (empty($_POST['nazv']))$_POST['nazv']=$_FILES['path']['name'];
	
	 $insertSQL = sprintf("INSERT INTO `tm_nmo_razd_media` (`tm_nmo_razd`, `path`, `tip`, `act`, `obyaz`, `num`, `comment`, `dop_file`, `nazv`,data_act) 
	 VALUES (%s, %s, 19, %s, %s, %s, %s, NULL, %s,%s)",
                       GetSQLValueString($_POST['tm_nmo_razd'], "int"),
                       GetSQLValueString($filotv, "text"),
	                    GetSQLValueString(($_POST['act']=="on") ? "1" : "0","int" ),
						    GetSQLValueString(($_POST['obyaz']=="on") ? "1" : "0","int" ),
						   GetSQLValueString($_POST['num'], "int"),
					 GetSQLValueString($_POST['comment'], "text"),
						 GetSQLValueString($_POST['nazv'], "text"), GetSQLValueString($_POST['data_act'], "date"));
	//echo $insertSQL;
	DB::Query($insertSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link)); 
	
	}


    if ($_POST["tip"]==21){

        if (getExtension1($_FILES['path']['name'])=="zip")	{

            $filenamep=uniqid().'/';
            $zip = new ZipArchive;
            $zip->open($_FILES['path']['tmp_name']);
            $zip->extractTo('../nmo/test/'.$filenamep);
            $filotv =$filenamep.'index.html';
            $zip->close();
        } else {

            $filotv =uniqid().'.html';
            if (isset($_FILES['path']))
                move_uploaded_file($_FILES['path']['tmp_name'],'../nmo/test/'.$filotv);
        }

        $fipdfotv =uniqid().'.pdf';
        if (isset($_FILES['pathotv'])){
                move_uploaded_file($_FILES['pathotv']['tmp_name'],'../nmo/doc/'.$fipdfotv);
        }
        if (empty($_POST['nazv']))$_POST['nazv']=$_FILES['path']['name'];

        $insertSQL = sprintf("INSERT INTO `tm_nmo_razd_media` (`tm_nmo_razd`, `path`, `tip`, `act`, `obyaz`, `num`, `comment`, `dop_file`, `nazv`,data_act) 
	 VALUES (%s, %s, 21, %s, %s, %s, %s, %s, %s,%s)",
            GetSQLValueString($_POST['tm_nmo_razd'], "int"),
            GetSQLValueString($filotv, "text"),
            GetSQLValueString(($_POST['act']=="on") ? "1" : "0","int" ),
            GetSQLValueString(($_POST['obyaz']=="on") ? "1" : "0","int" ),
            GetSQLValueString($_POST['num'], "int"),
            GetSQLValueString($_POST['comment'], "text"),
             GetSQLValueString($fipdfotv, "text"),
            GetSQLValueString($_POST['nazv'], "text"), GetSQLValueString($_POST['data_act'], "date"));
        echo $insertSQL;
        DB::Query($insertSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));

    }



    if ($_POST["tip"]==3){
	
if (getExtension1($_FILES['path']['name'])=="zip")	{
	
	$filenamep=uniqid().'/';
	$zip = new ZipArchive; 
   $zip->open($_FILES['path']['tmp_name']); 
   $zip->extractTo('../nmo/test/'.$filenamep); 
	 $filotv =$filenamep.'index.html';
    $zip->close(); 
} else {	
	
	$filotv =uniqid().'.html';
if (isset($_FILES['path']))
		move_uploaded_file($_FILES['path']['tmp_name'],'../nmo/test/'.$filotv);	
}
	if (empty($_POST['nazv']))$_POST['nazv']=$_FILES['path']['name'];
	
	 $insertSQL = sprintf("INSERT INTO `tm_nmo_razd_media` (`tm_nmo_razd`, `path`, `tip`, `act`, `obyaz`, `num`, `comment`, `dop_file`, `nazv`,data_act) 
	 VALUES (%s, %s, 3, %s, %s, %s, %s, NULL, %s,%s)",
                       GetSQLValueString($_POST['tm_nmo_razd'], "int"),
                       GetSQLValueString($filotv, "text"),
	                    GetSQLValueString(($_POST['act']=="on") ? "1" : "0","int" ),
						    GetSQLValueString(($_POST['obyaz']=="on") ? "1" : "0","int" ),
						   GetSQLValueString($_POST['num'], "int"),
					 GetSQLValueString($_POST['comment'], "text"),
						 GetSQLValueString($_POST['nazv'], "text"), GetSQLValueString($_POST['data_act'], "date"));
	//echo $insertSQL;
	DB::Query($insertSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link)); 
	
	}
	
if ($_POST["tip"]==18){
	
if (getExtension1($_FILES['path']['name'])=="zip")	{
	$filenameobr="null";
	if (isset($_FILES['comment'])){
		$filenameobr=uniqid().'.php';
		move_uploaded_file($_FILES['comment']['tmp_name'],'../nmo/obr/'.$filenameobr);	
}
	
	
	$filenamep=uniqid().'/';
	$zip = new ZipArchive; 
   $zip->open($_FILES['path']['tmp_name']); 
   $zip->extractTo('../nmo/test/'.$filenamep); 
	 $filotv =$filenamep.'index.html';
    $zip->close(); 
} else {	
	
	$filotv =uniqid().'.html';
if (isset($_FILES['path']))
		move_uploaded_file($_FILES['path']['tmp_name'],'../nmo/test/'.$filotv);	
}
	if (empty($_POST['nazv']))$_POST['nazv']=$_FILES['path']['name'];
	
	 $insertSQL = sprintf("INSERT INTO `tm_nmo_razd_media` (`tm_nmo_razd`, `path`, `tip`, `act`, `obyaz`, `num`, `comment`, `dop_file`, `nazv`,data_act) 
	 VALUES (%s, %s,18, %s, %s, %s, %s, NULL, %s,%s)",
                       GetSQLValueString($_POST['tm_nmo_razd'], "int"),
                       GetSQLValueString($filotv, "text"),
	                    GetSQLValueString(($_POST['act']=="on") ? "1" : "0","int" ),
						    GetSQLValueString(($_POST['obyaz']=="on") ? "1" : "0","int" ),
						   GetSQLValueString($_POST['num'], "int"),
					 GetSQLValueString($filenameobr, "text"),
						 GetSQLValueString($_POST['nazv'], "text"), GetSQLValueString($_POST['data_act'], "date"));
	//echo $insertSQL;
	DB::Query($insertSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link)); 
	
	}
	
if ($_POST["tip"]==17){
	$filenamep=uniqid().'/';	
if (getExtension1($_FILES['path']['name'])=="zip")	{
$path=$_FILES['path']['tmp_name'];
$zip = new ZipArchive;
      mkdir('../tetrad/shabl/'.$filenamep, 0777, true);
         mkdir('../tetrad/shabl/'.$filenamep."/index.files", 0777, true);
   //   echo '../tetrad/shabl/'.$filenamep;
if ($zip->open($path) === true) {
    for($i = 0; $i < $zip->numFiles; $i++) {
        $filename = $zip->getNameIndex($i);
          $filename = str_replace(array("/", "\\"), DIRECTORY_SEPARATOR, $filename);
         $buf =   $zip->getFromIndex($i);
   file_put_contents( '../tetrad/shabl/'.$filenamep."/".$filename, $buf);
 //  echo  '../tetrad/shabl/'.$filenamep."/".$filename;
        //$fileinfo = pathinfo($filename);
     
      //  copy("zip://".$path."#".$filename, "/your/new/destination/".$fileinfo['basename']);
    }                  
    $zip->close();  
	
	
}	
	
	
/*
	$filenamep=uniqid().'/';
	$zip = new ZipArchive; 
   $zip->open($_FILES['path']['tmp_name']); 
   $zip->extractTo('../tetrad/shabl/'.$filenamep); 
	 $filotv =$filenamep.'index.html';
    $zip->close(); 
	
	*/
	 $filotv =$filenamep.'index.html';
  //  $zip->close(); 
} 
	if (empty($_POST['nazv']))$_POST['nazv']=$_FILES['path']['name'];
	
	 $insertSQL = sprintf("INSERT INTO `tm_nmo_razd_media` (`tm_nmo_razd`, `path`, `tip`, `act`, `obyaz`, `num`, `comment`, `dop_file`, `nazv`,data_act) 
	 VALUES (%s, %s, 17, %s, %s, %s, %s, NULL, %s,%s)",
                       GetSQLValueString($_POST['tm_nmo_razd'], "int"),
                       GetSQLValueString($filotv, "text"),
	                    GetSQLValueString(($_POST['act']=="on") ? "1" : "0","int" ),
						    GetSQLValueString(($_POST['obyaz']=="on") ? "1" : "0","int" ),
						   GetSQLValueString($_POST['num'], "int"),
					 GetSQLValueString($_POST['comment'], "text"),
						 GetSQLValueString($_POST['nazv'], "text"), GetSQLValueString($_POST['data_act'], "date"));
	//echo $insertSQL;
	DB::Query($insertSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link)); 
	
	}		
	
	
	
if ($_POST["tip"]==2){
	
	 $insertSQL = sprintf("INSERT INTO `tm_nmo_razd_media` (`tm_nmo_razd`, `path`, `tip`, `act`, `obyaz`, `num`, `comment`, `dop_file`, `nazv`) 
	 VALUES (%s, %s,2, %s, NULL, %s, %s, NULL, %s)",
                       GetSQLValueString($_POST['tm_nmo_razd'], "int"),
                       GetSQLValueString($_POST['path'], "text"),
	                    GetSQLValueString(($_POST['act']=="on") ? "1" : "0","int" ),
						   GetSQLValueString($_POST['num'], "int"),
					 GetSQLValueString($_POST['comment'], "text"),
						 GetSQLValueString($_POST['nazv'], "text"));
	//echo $insertSQL;
	DB::Query($insertSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link)); 
	
	}	
	
	

	
	
if ($_POST["tip"]==4){
	
	$insertSQL = sprintf("INSERT INTO `tm_nmo_razd_media` (`tm_nmo_razd`, `path`, `tip`, `act`, `obyaz`, `num`, `comment`, `dop_file`, `nazv`) 
	 VALUES (%s, NULL,4, %s, NULL, %s, %s, NULL, %s)",
                       GetSQLValueString($_POST['tm_nmo_razd'], "int"),
                        GetSQLValueString(($_POST['act']=="on") ? "1" : "0","int" ),
						   GetSQLValueString($_POST['num'], "int"),
					 GetSQLValueString($_POST['comment'], "text"),
						 GetSQLValueString($_POST['nazv'], "text"));
	//echo $insertSQL;
	DB::Query($insertSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link)); 
	
	
$sql='select max(id) as id from tm_nmo_razd_media';
$result =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row = mysqli_fetch_row($result);	
	
	$dit=$row[0];
	
	$ss=$_POST['path'];
	  $res=explode(PHP_EOL,$ss);
	 
	 for ($i=0;$i<=count($res);$i++){
  $insertSQL = sprintf("INSERT INTO `tm_nmo_razd_media_list` (`id`, `tm_nmo_razd_media`, `tex`) VALUES (NULL, %s, %s)",
                      GetSQLValueString($dit, "int"),
					   GetSQLValueString($res[$i], "text"));	
   /* fixed MMiC */ mysqli_select_db(DB::$link, $testmed);
  $Result1 =  /* fixed MMiC */ DB::Query($insertSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));

	} 
	
	
	
	
	 
	
	}
	
if ($_POST["tip"]==5){
	
	$insertSQL = sprintf("INSERT INTO `tm_nmo_razd_media` (`tm_nmo_razd`, `path`, `tip`, `act`, `obyaz`, `num`, `comment`, `dop_file`, `nazv`) 
	 VALUES (%s, NULL,5, %s, NULL, %s, %s, NULL, %s)",
                       GetSQLValueString($_POST['tm_nmo_razd'], "int"),
                        GetSQLValueString(($_POST['act']=="on") ? "1" : "0","int" ),
						   GetSQLValueString($_POST['num'], "int"),
					 GetSQLValueString($_POST['comment'], "text"),
						 GetSQLValueString('Завершить обучение', "text"));
	//echo $insertSQL;
	DB::Query($insertSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link)); 

	}	
	if ($_POST["tip"]==6){
	
	$insertSQL = sprintf("INSERT INTO `tm_nmo_razd_media` (`tm_nmo_razd`, `path`, `tip`, `act`, `obyaz`, `num`, `comment`, `dop_file`, `nazv`) 
	 VALUES (%s, %s,6, %s, NULL, %s, %s, NULL, %s)",
                       GetSQLValueString($_POST['tm_nmo_razd'], "int"),
						   GetSQLValueString($_POST['tpsv'], "text"),
                        GetSQLValueString(($_POST['act']=="on") ? "1" : "0","int" ),
						   GetSQLValueString($_POST['num'], "int"),
					 GetSQLValueString($_POST['comment'], "text"),
						 GetSQLValueString($_POST['nazv'], "text"));
	//echo $insertSQL;
	DB::Query($insertSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link)); 

	}	
	
		if ($_POST["tip"]==12){
	
	$insertSQL = sprintf("INSERT INTO `tm_nmo_razd_media` (`tm_nmo_razd`, `path`, `tip`, `act`, `obyaz`, `num`, `comment`, `dop_file`, `nazv`) 
	 VALUES (%s, %s,12, %s, NULL, %s, %s, NULL, %s)",
                       GetSQLValueString($_POST['tm_nmo_razd'], "int"),
						   GetSQLValueString($_POST['path'], "text"),
                        GetSQLValueString(($_POST['act']=="on") ? "1" : "0","int" ),
						   GetSQLValueString($_POST['num'], "int"),
					 GetSQLValueString($_POST['comment'], "text"),
						 GetSQLValueString($_POST['nazv'], "text"));
	//echo $insertSQL;
	DB::Query($insertSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link)); 

	}	
		if ($_POST["tip"]==16){
	
	$insertSQL = sprintf("INSERT INTO `tm_nmo_razd_media` (`tm_nmo_razd`, `path`, `tip`, `act`, `obyaz`, `num`, `comment`, `dop_file`, `nazv`) 
	 VALUES (%s, NULL,16, %s, NULL, %s, %s, NULL, %s)",
                       GetSQLValueString($_POST['tm_nmo_razd'], "int"),
						
                        GetSQLValueString(($_POST['act']=="on") ? "1" : "0","int" ),
						   GetSQLValueString($_POST['num'], "int"),
					 GetSQLValueString($_POST['comment'], "text"),
						 GetSQLValueString($_POST['nazv'], "text"));
	//echo $insertSQL;
	DB::Query($insertSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link)); 

	}	
	
	
	
	if ($_POST["tip"]==7){
	
	$insertSQL = sprintf("INSERT INTO `tm_nmo_razd_media` (`tm_nmo_razd`, `path`, `tip`, `act`, `obyaz`, `num`, `comment`, `dop_file`, `nazv`) 
	 VALUES (%s, %s,7, %s, NULL, %s, %s, NULL, %s)",
                       GetSQLValueString($_POST['tm_nmo_razd'], "int"),
						   GetSQLValueString($_POST['tpsv'], "text"),
                        GetSQLValueString(($_POST['act']=="on") ? "1" : "0","int" ),
						   GetSQLValueString($_POST['num'], "int"),
					 GetSQLValueString($_POST['comment'], "text"),
						 GetSQLValueString($_POST['nazv'], "text"));
	//echo $insertSQL;
	DB::Query($insertSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link)); 

	}	
		if ($_POST["tip"]==10){
	
	$insertSQL = sprintf("INSERT INTO `tm_nmo_razd_media` (`tm_nmo_razd`, `path`, `tip`, `act`, `obyaz`, `num`, `comment`, `dop_file`, `nazv`) 
	 VALUES (%s, %s,10, %s, NULL, %s, %s, NULL, %s)",
                       GetSQLValueString($_POST['tm_nmo_razd'], "int"),
						   GetSQLValueString($_POST['tpsv'], "text"),
                        GetSQLValueString(($_POST['act']=="on") ? "1" : "0","int" ),
						   GetSQLValueString($_POST['num'], "int"),
					 GetSQLValueString($_POST['comment'], "text"),
						 GetSQLValueString($_POST['nazv'], "text"));
	//echo $insertSQL;
	DB::Query($insertSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link)); 

	}	
		if ($_POST["tip"]==11){
	
	$insertSQL = sprintf("INSERT INTO `tm_nmo_razd_media` (`tm_nmo_razd`, `path`, `tip`, `act`, `obyaz`, `num`, `comment`, `dop_file`, `nazv`) 
	 VALUES (%s, %s,11, %s, NULL, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['tm_nmo_razd'], "int"),
						   GetSQLValueString($_POST['tpsv'], "text"),
                        GetSQLValueString(($_POST['act']=="on") ? "1" : "0","int" ),
						   GetSQLValueString($_POST['num'], "int"),
					 GetSQLValueString($_POST['comment'], "text"), GetSQLValueString($_POST['cena'], "text"),
						 GetSQLValueString($_POST['nazv'], "text"));
	//echo $insertSQL;
	DB::Query($insertSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link)); 

	}
		if ($_POST["tip"]==20){
	
	$insertSQL = sprintf("INSERT INTO `tm_nmo_razd_media` (`tm_nmo_razd`, `path`, `tip`, `act`, `obyaz`, `num`, `comment`, `dop_file`, `nazv`) 
	 VALUES (%s, %s,20, %s, NULL, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['tm_nmo_razd'], "int"),
						   GetSQLValueString($_POST['tpsv'], "text"),
                        GetSQLValueString(($_POST['act']=="on") ? "1" : "0","int" ),
						   GetSQLValueString($_POST['num'], "int"),
					 GetSQLValueString($_POST['comment'], "text"), GetSQLValueString($_POST['cena'], "text"),
						 GetSQLValueString($_POST['nazv'], "text"));
	//echo $insertSQL;
	DB::Query($insertSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link)); 

	}
	
$sql='select max(id) as id from tm_nmo_razd_media';
$result =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$finfo = mysqli_fetch_field($result);	
	
echo '<tr><td></td><td>'.$_POST['num'].'</td>
      <td data-base="tm_nmo_razd_media" data-num="'.$finfo->id.'" data-name="nazv" data-t="0" style="font-size: 6pt; margin: 5px;padding: 5px"  >'.$_POST['nazv'].'</td>
      <td>9</td>
      <td>Видео</td>
      <td data-base="tm_nmo_razd_media" data-num="'.$finfo->id.'" data-name="comment" data-t="2" data-com="'. GetSQLValueString($_POST['comment'], "text").'" style="font-size: 6pt; margin: 5px;padding: 5px">'. GetSQLValueString($_POST['comment'], "text").'</td>
      <td></td>
      <td data-base="tm_nmo_razd_media" data-num="'.$finfo->id.'" data-name="obyaz" data-t="3">'.GetSQLValueString(($_POST['obyaz']=="on") ? "1" : "0","int" ).'</td>
      <td data-base="tm_nmo_razd_media" data-num="'.$finfo->id.'" data-name="act" data-t="3">'.GetSQLValueString(($_POST['act']=="on") ? "1" : "0","int" ).'</td>
      <td>'. GetSQLValueString($_POST['path'], "text").'</td>
    </tr>"';
	
	
	
exit(0);
}


if ((isset($_POST["deln"])) ) {
$sql="delete from tm_nmo_razd_media where id=".GetSQLValueString($_POST["deln"],"int");	
$Result1 =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
echo $_POST["deln"];
	exit();	
	
	
}
if ((isset($_POST["addscr"])) ) {
	$_GET['spec']=$_POST['spec'];
	$sql="SELECT 
  `tm_nmo_razd`.`id`,
  `tm_nmo_razd`.`nazv`
FROM
  `tm_nmo_razd`
WHERE
  `tm_nmo_razd`.`spec` = ". GetSQLValueString($_GET['spec'], "int")." AND 
  `tm_nmo_razd`.`id` NOT IN (SELECT 
  `tm_nmo_razd`.`id`
FROM
  `tm_nmo_razd`
  INNER JOIN `tm_nmo_razd_media` ON (`tm_nmo_razd`.`id` = `tm_nmo_razd_media`.`tm_nmo_razd`)
WHERE
  `tm_nmo_razd_media`.`tip` = 10 AND 
  `tm_nmo_razd`.`spec` = ". GetSQLValueString($_GET['spec'], "int").")";
	$result1 =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
	echo $sql;
	$row = mysqli_fetch_assoc($result1);
	$totalRows_cpr =  /* fixed MMiC */ mysqli_num_rows($result1);
	if ($totalRows_cpr>0)
	do {  
$sql="INSERT INTO `tm_nmo_razd_media` (`id`, `tm_nmo_razd`, `path`, `tip`, `act`, `obyaz`, `num`, `comment`, `dop_file`, `nazv`, `povt`, `data_act`, `data_okon`)
VALUES (NULL, ".$row["id"].", NULL, 10,0, 1, 1000,'добавляйте скриншоты или текст',NULL,'Решение заданий', NULL, NULL, NULL)";
	echo $sql."|";
DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));    
       
} while ($row = mysqli_fetch_assoc($result1)	);
exit();	
}
if ((isset($_POST["addanc"])) ) {
	$_GET['spec']=$_POST['spec'];
		$ank=$_POST['ank'];
	$sql="SELECT 
  `tm_nmo_razd`.`id`,
  `tm_nmo_razd`.`nazv`
FROM
  `tm_nmo_razd`
WHERE
  `tm_nmo_razd`.`spec` = ". GetSQLValueString($_GET['spec'], "int")." AND 
  `tm_nmo_razd`.`id` NOT IN (SELECT 
  `tm_nmo_razd`.`id`
FROM
  `tm_nmo_razd`
  INNER JOIN `tm_nmo_razd_media` ON (`tm_nmo_razd`.`id` = `tm_nmo_razd_media`.`tm_nmo_razd`)
WHERE
  `tm_nmo_razd_media`.`tip` = 6 AND 
  `tm_nmo_razd`.`spec` = ". GetSQLValueString($_GET['spec'], "int").")";
	$result1 =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
	echo $sql;
	$row = mysqli_fetch_assoc($result1);
	$totalRows_cpr =  /* fixed MMiC */ mysqli_num_rows($result1);
	if ($totalRows_cpr>0)
	do {  
$sql="INSERT INTO `tm_nmo_razd_media` (`id`, `tm_nmo_razd`, `path`, `tip`, `act`, `obyaz`, `num`, `comment`, `dop_file`, `nazv`, `povt`, `data_act`, `data_okon`)
VALUES (NULL, ".$row["id"].",$ank, 6,0, 1, 1000,'заполните поля',NULL,'Анкета', NULL, NULL, NULL)";
	echo $sql."|";
DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));    
       
} while ($row = mysqli_fetch_assoc($result1)	);
exit();	
}


if ((isset($_POST["copyto"])) ) {
	print_r($_POST);
$sql="SELECT 
  `tm_nmo_razd`.`id`,
  `tm_nmo_razd`.`spec`,
  `tm_nmo_razd`.`nazv`,
  `tm_nmo_razd`.`activ`,
  `tm_nmo_razd`.`comment`,
  `tm_nmo_razd`.`num`,
  `tm_nmo_razd`.`img`, `tm_nmo_razd`.`prepod`
FROM
  `tm_nmo_razd`
WHERE
  `tm_nmo_razd`.`id` =".GetSQLValueString($_POST["copyto"],"int");
	
	$cpt=$_POST["to"];
$result1 =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
	$row = mysqli_fetch_assoc($result1);	
	$sql=" INSERT INTO `tm_nmo_razd` (`id`, `spec`, `nazv`, `activ`, `comment`, `num`, `img`,`prepod`) VALUES (NULL, $cpt, '".$row['nazv']."', 1, '".$row['comment']."', 0,'".$row['img']."','".$row['prepod']."')";
	echo $slq.'|';
	$Result1 =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
	$sql='select max(id) as id from tm_nmo_razd';
$result =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$finfo = mysqli_fetch_assoc($result);	
	//$finfo->id;
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
  `tm_nmo_razd_media`.`data_okon`, `tm_nmo_razd_media`.`gal`
FROM
  `tm_nmo_razd_media`
WHERE
  `tm_nmo_razd_media`.`tm_nmo_razd` = ".GetSQLValueString($_POST["copyto"],"int");	
	$result =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
	$fo = mysqli_fetch_assoc($result);	
	//echo $sql;
do {  
$sql="INSERT INTO `tm_nmo_razd_media` (`id`, `tm_nmo_razd`, `path`, `tip`, `act`, `obyaz`, `num`, `comment`, `dop_file`, `nazv`, `povt`, `data_act`, `data_okon`)
VALUES (NULL, ".$finfo["id"].", '".$fo['path']."', ".$fo['tip'].",".$fo['act'].", '".$fo['obyaz']."', ".$fo['num'].", '".$fo['comment']."', '".$fo['dop_file']."', '".$fo['nazv']."', NULL, NULL, NULL)";
	echo $sql."|";
DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));    
       
} while ($fo = mysqli_fetch_assoc($result)	);
		
	
	
	
	exit();	
// 
	
}


if ((isset($_POST["copytos"])) ) {
	print_r($_POST);
	$cb=json_decode($_POST['cb']);
	foreach ( $cb as $value ) {



	$to=intval($_POST['to']);

	//$finfo->id;
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
  `tm_nmo_razd_media`.`data_okon`,`tm_nmo_razd_media`.`avtor`,`tm_nmo_razd_media`.`tippr`
FROM
  `tm_nmo_razd_media`
WHERE
  `tm_nmo_razd_media`.`id` = ".$value;	
	$result =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
	$fo = mysqli_fetch_assoc($result);	
	//echo $sql;
if (!isset($fo['avtor']))$fo['avtor']='NULL';
$sql="INSERT INTO `tm_nmo_razd_media` (`id`, `tm_nmo_razd`, `path`, `tip`, `act`, `obyaz`, `num`, `comment`, `dop_file`, `nazv`, `povt`, `data_act`, `data_okon`,avtor,tippr)
VALUES (NULL, ".$to.", '".$fo['path']."', ".$fo['tip'].",".$fo['act'].", '".$fo['obyaz']."', ".$fo['num'].", '".$fo['comment']."', '".$fo['dop_file']."', '".$fo['nazv']."', NULL, NULL, NULL,".$fo['avtor'].",".$fo['tippr'].")";
	echo $sql."|";
DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));  
		
if ($fo['tip']==15)		{
	$sql='select max(id) as id from tm_nmo_razd_media';
$result =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$finfo = mysqli_fetch_assoc($result);		
$sql="SELECT * FROM `tm_nmo_sert_test` WHERE media=$value";
$result11 =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
	$fo11 = mysqli_fetch_assoc($result11);	
$sqlt="INSERT INTO `tm_nmo_sert_test` (`id`, `nazv`, `text`, `media`, `chas`) VALUES (NULL, '".$fo11['nazv']."', '".$fo11['text']."', '".$finfo['id']."', '".$fo11['chas']."');";
	DB::Query($sqlt, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
}
	}
	exit;
}

if ((isset($_POST["deldop"])) ) {
	$id=intval($_POST['id']);
	
	

	//$finfo->id;
$sql="delete from `tm_nmo_razd_dop_prepod`  where num=$id";	
//	echo $sql;
	$result =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));

	exit();	
// 
	
}

if ((isset($_POST["adddpopor"])) ) {
	$id=intval($_POST['id']);
	$prepod=intval($_POST['pr']);
	

	//$finfo->id;
$sql="INSERT INTO `tm_nmo_razd_dop_prepod` (`num`, `razdel`, `prepod`) VALUES (NULL, $id, $prepod)";	
//	echo $sql;
	$result =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$sql="SELECT 
  `tm_nmo_razd_dop_prepod`.`num`,
  `tm_prepod`.`fio`
FROM
  `tm_nmo_razd_dop_prepod`,
 `tm_prepod` 
WHERE
  `tm_nmo_razd_dop_prepod`.`razdel` = $id and `tm_nmo_razd_dop_prepod`.`prepod` = `tm_prepod`.`num`
 order by num desc limit 1";
	 $dfo =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
	$dfo = mysqli_fetch_assoc($dfo);	
	echo $sql;
	echo   "<tr>
   
      <td>".$dfo['fio']."</td>   <td>&nbsp;</td>
    </tr>";
	
	exit();	
// 
	
}



if ((isset($_POST["delna"])) ) {
	$sql="delete from tm_nmo_razd_media where tm_nmo_razd=".GetSQLValueString($_POST["delna"],"int");	
$Result1 =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$sql="delete from tm_nmo_razd where id=".GetSQLValueString($_POST["delna"],"int");	
$Result1 =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
echo $_POST["delna"];
	exit();	
	
	
}

if ((isset($_POST["upd"])) && ($_POST["upd"] == "1")) {
$sql="update ".$_POST["bas"]." set ".$_POST["name"]."='".$_POST["val"]."' where id=".GetSQLValueString($_POST["num"],"int");	

	echo $sql;
  $Result1 =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
	exit();
}

if ((isset($_POST["spec"])) && ($_POST["spec"] != "")) {
		
		exit;
   //move_uploaded_file($_FILES['fimg']['tmp_name'],'timg/'.$filename);
   $filenameimg='-';
	if(isset($_FILES['img'])){
$image = new Imagick($_FILES['img']['tmp_name']);
$filenameimg =uniqid().'.jpg';
	$image->adaptiveResizeImage(140,140);

	$data = $image->getImageBlob(); 
file_put_contents ('../nmo/img/'.$filenameimg, $data); 
	
	}
	
	
 $insertSQL = sprintf("INSERT INTO tm_nmo_razd (spec, nazv,activ,comment,img) VALUES (%s, %s,%s,%s,%s)",
                       GetSQLValueString($_POST['spec'], "int"),
                       GetSQLValueString($_POST['nazv'], "text"),
	                    GetSQLValueString(($_POST['activ']=="on") ? "1" : "0","int" ),
					 GetSQLValueString($_POST['comment'], "text"),
					  GetSQLValueString($filenameimg, "text"));

   /* fixed MMiC */ mysqli_select_db(DB::$link, $database_testmed);
  $Result1 =  /* fixed MMiC */ DB::Query($insertSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));

}
echo $_SESSION['MM_Username']."333333333333";
$query_spec = "SELECT * FROM tm_spec  where kr>0 ORDER BY dat ASC";
$spec =  /* fixed MMiC */ DB::Query($query_spec, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_spec =  /* fixed MMiC */ mysqli_fetch_assoc($spec);
$totalRows_spec =  /* fixed MMiC */ mysqli_num_rows($spec);

$sql="SELECT 
  `tm_nmo_razd`.`id`,
  `tm_nmo_razd`.`spec`,
  `tm_nmo_razd`.`nazv`,
  `tm_nmo_razd`.`activ`,
  `tm_nmo_razd`.`num`, `tm_nmo_razd`.`img`, `tm_nmo_razd`.`comment`,`tm_nmo_razd`.`prepod`
FROM
  `tm_nmo_razd`
WHERE
  `tm_nmo_razd`.`spec` = ". GetSQLValueString($_GET['spec'], "int")." order by  `tm_nmo_razd`.`num`,id";

$nmo =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_nmo =  /* fixed MMiC */ mysqli_fetch_assoc($nmo);
$totalRows_nmo =  /* fixed MMiC */ mysqli_num_rows($nmo);
$nmo2 =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_nmo2 =  /* fixed MMiC */ mysqli_fetch_assoc($nmo2);
$totalRows_nmo2 =  /* fixed MMiC */ mysqli_num_rows($nmo2);


$sql="SELECT 
  `tm_typsv_name`.`num`,
  `tm_typsv_name`.`name`
FROM
  `tm_typsv_name`";
$tps =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_tps =  /* fixed MMiC */ mysqli_fetch_assoc($tps);
$totalRows_tps =  /* fixed MMiC */ mysqli_num_rows($tps);
$opt="";
do{
	$opt=$opt.'<option value="'.$row_tps['num'].'">'.$row_tps['name'].'</option>';
} while ($row_tps =  /* fixed MMiC */ mysqli_fetch_assoc($tps));

/**/

$sql="SELECT 
  `tm_prepod`.`num`,
  `tm_prepod`.`fio`
FROM
  `tm_prepod`
ORDER BY
  `tm_prepod`.`fio`";
$prepod =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_prepod =  /* fixed MMiC */ mysqli_fetch_assoc($prepod);
$totalRows_prepod =  /* fixed MMiC */ mysqli_num_rows($prepod);


$sql="SELECT  
  `tm_nmo_razd`.`nazv` AS `rnazv`,
  `tm_nmo_razd`.`id` AS `rid`,
  `tm_spec`.`nazv` AS `snazv`
FROM
  `tm_nmo_razd`
  
  INNER JOIN `tm_spec` ON (`tm_nmo_razd`.`spec` = `tm_spec`.`num`)
WHERE

  `tm_spec`.`kr` > 0 order by  snazv,rnazv";
$cpr =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_cpr =  /* fixed MMiC */ mysqli_fetch_assoc($cpr);
$totalRows_cpr =  /* fixed MMiC */ mysqli_num_rows($cpr);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="ru">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>НМО</title>
	<link rel="stylesheet" href="../css/bootstrap.css">
<link href="thrColLiq.css" rel="stylesheet" type="text/css" />
<style type="text/css">
	input {color: black;font-size: 10pt;}
	textarea {color: black;font-size: 10pt;}
</style>
	
<!--[if lte IE 7]>
<style>
.content { margin-right: -1px; } /* это отрицательное поле в 1 пиксел можно поместить в любом столбце данного макета с таким же корректирующим эффектом. */
ul.nav a { zoom: 1; }  /* свойство масштабирования предоставляет IE триггер hasLayout, необходимый для удаления лишнего пустого пространства между ссылками */
</style>
<![endif]-->
</head>

<body>

<div id="myModalBox" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- Заголовок модального окна -->
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title">ДОБАВЛЕНИЕ</h4>
      </div>
      <!-- Основное содержимое модального окна -->
      <div class="modal-body">
       <form action="add_nmo.php" method="post" enctype="multipart/form-data" name="fmm" id="fmm" autocomplete="off">
		 <div id="dfm"></div>
		  
		  </form>
		  
      </div>
      <!-- Футер модального окна -->
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">ДОБАВИТЬ</button>
       
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
        <h4 class="modal-title">ДОБАВЛЕНИЕ</h4>
      </div>
      <!-- Основное содержимое модального окна -->
      <div class="modal-body">
       <form action="add_nmo.php" method="post" enctype="multipart/form-data" name="fmm2" id="fmm2" autocomplete="off">
		 <div id="dfm2"></div>
		  
		  </form>
		  
      </div>
      <!-- Футер модального окна -->
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">ДОБАВИТЬ</button>
       
      </div>
    </div>
  </div>
</div>		
	
<div id="myModalBox3" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- Заголовок модального окна -->
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title">ДОБАВЛЕНИЕ</h4>
      </div>
      <!-- Основное содержимое модального окна -->
      <div class="modal-body">
       <form action="add_nmo.php" method="post" enctype="multipart/form-data" name="fmm3" id="fmm3" autocomplete="off">
		 <div id="dfm3"></div>
		  
		  </form>
		  
      </div>
      <!-- Футер модального окна -->
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">ДОБАВИТЬ</button>
       
      </div>
    </div>
  </div>
</div>		
	
	
	
	
	
0
<div class="container" style="width:100% ">
  <div class="sidebar1">
<?php include("menu.php"); ?>
    <p>&nbsp;</p>
    <!-- end .sidebar1 --></div>
  <div class="content">
http://zmudpo.ru/nmo.php -это сюда отправляется после теста<br>
http://zmudpo.ru/num_res_test.php -сюда обправляется резултат
    <h1>Добавление Разделов к НМО</h1>
    <form id="form1" name="form1" method="get" action="">
	
     
      <table width="90%" border="1" style="margin: 20px">
        <tbody>
          <tr>
            <td>  <select name="spec" class="form-control">
		
		   <?php
do {  
?>
          <option value="<?php echo $row_spec['num']?>"<?php if (!(strcmp($row_spec['num'], $_GET['spec']))) {echo "selected=\"selected\"";} ?>><?php echo $row_spec['nazv']?></option>
          <?php
} while ($row_spec =  /* fixed MMiC */ mysqli_fetch_assoc($spec));?>
		
		
	  </select></td>
            <td> <input type="submit" name="submit" id="submit" value="Выбрать" class="form-control"/></td>
          </tr>
          
        </tbody>
      </table>
    </form>
    <p>&nbsp;</p>
    <hr />
	<?php if (isset($_GET['spec'])) { ?>
    <form action="" method="post" enctype="multipart/form-data" name="form2" id="form2">
      <table width="100%" border="0" class="table_dark">
        <tbody>
          <tr>
            <td>Название</td>
            <td><input name="nazv" type="text" required="required" id="nazv" form="form2" placeholder="Название раздела" title="Название" size="50" /></td>
          </tr>
			 <tr>
            <td>Комментарий</td>
            <td>
				<textarea name="comment" cols="49" rows="5" id="comment" form="form2" placeholder="Комментарий к разделу" type="text" size="50" ></textarea>
				</td>
          </tr>
          <tr>
            <td>Активность</td>
            <td><input type="checkbox" name="activ" id="activ" /></td>
          </tr>
          <tr>
            <td>Рисунок</td>
            <td><input type="file" name="img" id="img" /></td>
			       <input name="spec" type="hidden" id="spec" value="<?php echo $_GET['spec']; ?>" />
          </tr>
          <tr>
            <td><input type="submit" name="submit2" id="submit2" value="Отправить" /></td>
            <td>&nbsp;</td>
          </tr>
        </tbody>
      </table>
    </form>
	<?php } ?>			
    <p>
 
 
    </p>
	  <hr><button id="addfo" data-spec="<?php echo $_GET['spec'];?>">добавить скрины</button><button id="addfo2" data-spec="<?php echo $_GET['spec'];?>">добавить анкету</button>
    <hr />
  <div class="form-control"><select id="goto">
	  <option value="-1">показать все</option>
	  <?php do {?>
	  
	  <option value="<?php echo $row_nmo2['id'];?>"><?php echo getsokr($row_nmo2['nazv']);?></option>
<?php 	 } while ($row_nmo2 =  /* fixed MMiC */ mysqli_fetch_assoc($nmo2));
	 //  mysqli_data_seek($nmo, 0);
	  ?>
	  
	  </select><button id="gtf">Фильтр</button></div>
	  
	  
	  
	    <?php 
	  if ($totalRows_nmo<1)exit(0);
do {  
?>
    <table width="100%" border="0" class="table_dark parag" id="tba<?php echo $row_nmo['id']; ?>">
      <tbody>
		   <tr>
          <th width="30">Номер </th> <th>Название</th>
          <th>акт</th>
          <th>Препод</th>
         
        </tr>
        <tr>
			
          <td data-base="tm_nmo_razd" data-num="<?php echo $row_nmo['id']; ?>" data-name="num" data-t="0"><?php echo $row_nmo['num']; ?></td>
          <td data-base="tm_nmo_razd" data-num="<?php echo $row_nmo['id']; ?>" data-name="nazv" data-t="0"><?php echo $row_nmo['nazv']; ?></td>
          <td data-base="tm_nmo_razd" data-num="<?php echo $row_nmo['id']; ?>" data-name="activ" data-t="3"><?php echo $row_nmo['activ']; ?></td>
          <td><select class="prep"  data-base="tm_nmo_razd" data-num="<?php echo $row_nmo['id']; ?>" data-name="prepod" data-t="12" id="pre<?php echo $row_nmo['id']; ?>">
          <? do {  
?>
          <option value="<?php echo $row_prepod['num']?>"<?php if (!(strcmp($row_nmo['prepod'],  $row_prepod['num']))) {echo "selected=\"selected\"";} ?>><?php echo $row_prepod['fio']?></option>
          <?php
} while ($row_prepod =  /* fixed MMiC */ mysqli_fetch_assoc($prepod));
			  mysqli_data_seek($prepod, 0);
			  
			  ?>
          </select>
			<hr>
			  <table width="200" border="1" id="tbln<?php echo $row_nmo['id']; ?>">
  <tbody>
    <tr>
      <th colspan="2" scope="col">Дополнительные преподаватели</th>
    </tr>
    <tr>
      <td ><select class="prep"  data-base="tm_nmo_razd" data-num="<?php echo $row_nmo['id']; ?>" data-name="prepod" data-t="12" id="pre2<?php echo $row_nmo['id']; ?>">
          <? do {  
?>
          <option value="<?php echo $row_prepod['num']?>"<?php if (!(strcmp($row_nmo['prepod'],  $row_prepod['num']))) {echo "selected=\"selected\"";} ?>><?php echo $row_prepod['fio']?></option>
          <?php
} while ($row_prepod =  /* fixed MMiC */ mysqli_fetch_assoc($prepod));
			  mysqli_data_seek($prepod, 0);
			  
			  ?>
          </select></td><td><button class="addprd btn btn-danger btn-xs" data-id="<?php echo $row_nmo['id']; ?>">добавить</button></td>
    </tr>
	  
	  <?php $sql="SELECT DISTINCT 
  `tm_prepod`.`fio`,
  `tm_nmo_razd_dop_prepod`.`num`
FROM
  `tm_nmo_razd_dop_prepod`
  INNER JOIN `tm_prepod` ON (`tm_nmo_razd_dop_prepod`.`prepod` = `tm_prepod`.`num`)
WHERE
  `tm_nmo_razd_dop_prepod`.`razdel` =".$row_nmo['id'];
	 $dfo =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
	$df1 = mysqli_fetch_assoc($dfo);
	do {  
?>
	  <tr>
      <td> <?php echo $df1['fio']?></td>
      <td><button class="deldop" data-id="<?php echo $df1['num']?>">уд</button></td>
    </tr>
        
          <?php
} while ($df1 =  /* fixed MMiC */ mysqli_fetch_assoc($dfo));
	  ?>
    
  </tbody>
</table>

			
			</td>
        </tr>
		    <tr>
			
          <td ><a href="../nmo/img/<?php echo $row_nmo['img']; ?>" target="new"><?php echo $row_nmo['img']; ?></a></td>
          <td data-base="tm_nmo_razd" data-num="<?php echo $row_nmo['id']; ?>" data-name="comment" data-t="0" style="font-size: 6pt; margin: 5px;padding: 5px"><?php echo  mb_substr($row_nmo['comment'],1,230); ?></td>
          <td><input type="button" name="button2" id="button2" value="Удалить"  class="btn btn-danger delma" data-id="<?php echo $row_nmo['id']; ?>"/></td>
          <td><input type="button" name="button2" id="button3" value="Скоп в нов раздел"  class="btn btn-danger copyto" data-id="<?php echo $row_nmo['id']; ?>"/>
			  <?php mysqli_data_seek($spec, 0);
	  $row_spec =  /* fixed MMiC */ mysqli_fetch_assoc($spec);
			  ?>
			  <select id="sd<?php echo $row_nmo['id']; ?>">
			  		   <?php
do {  
?>
          <option value="<?php echo $row_spec['num']?>"<?php if (!(strcmp($row_spec['num'], $_GET['spec']))) {echo "selected=\"selected\"";} ?>><?php echo getsokr($row_spec['nazv']);?></option>
          <?php
} while ($row_spec =  /* fixed MMiC */ mysqli_fetch_assoc($spec));?>
			  
			  </select>&nbsp;
				<hr>
			  
			  <input type="button" name="button2" id="button3" value="Скоп в cущ"  class="btn btn-danger copytos" data-id="<?php echo $row_nmo['id']; ?>"/>
			  <?php
	
			  ?>
			  <select id="sds<?php echo $row_nmo['id']; ?>">
			  		   <?php
mysqli_data_seek($cpr, 0); do {  
?>
          <option value="<?php echo $row_cpr ['rid']?>">[<?php echo $row_cpr['snazv']?>][<?php echo getsokr($row_cpr['rnazv'])?>]</option>
          <?php
} while ($row_cpr =  /* fixed MMiC */ mysqli_fetch_assoc($cpr));?>
			  
			  </select>&nbsp;
				</td>
        </tr>
        <tr>
          <td colspan="4" >
			  <div class="form-inline">
			  <select class="form-control" id="DC<?php echo $row_nmo['id']; ?>">
            <option value="1">Документ</option>
            <option value="2">Видео</option>
            <option value="3">Тест</option>
                  <option value="21"> Тест с ответами</option>
				        <option value="18">Анкетный Тест</option>
            <option value="4">Контрольная</option>
				    <option value="5">Завершение работы</option>
				   <option value="6">Анкета</option>
				    <option value="7">файл</option>
				  	    <option value="10">Набор скринов или текстов</option>
				     <option value="11">Оплата разделов</option>
				   <option value="12">ссылка</option>
				     <option value="15">тест с сертификатом</option>
				     <option value="16">Практика</option>
				   <option value="17">тетради</option>
				    <option value="19">Таблицы</option>
				   <option value="20">Случайное число</option>
				     <option value="22">Экзамен</option>
          </select>
          </select>
          <input type="button" name="button" id="button" value="Добавить"  class="btn btn-default sm" data-t="0" data-id="<?php echo $row_nmo['id']; ?>"/></td>
			  </div>
        </tr>
        <tr>
          <td colspan="4" >
			  
			  
			  
			  <table width="98%" border="1" id="tbl<?php echo $row_nmo['id']; ?>">
				  
	 <tr>
		   <th>+</th>
		  <th>Удал</th>
      <th>Ном</th>
      <th >Название</th>
		
		  <th>Тип</th>
		  <th>Коммент</th>
		   <th>гал</th>
		  <th>Выбор</th>
		  <th>Публ</th>
		 <th>Выкл</th> 
		 <th>Дата активации</th>
		 	 <th>Дата окончания</th>
     <th>Файл</th>
		     <th>доп</th>
		 
    </tr>			  
				  
				  
  <tbody>
<?php 
	$sql23="SELECT 
  `tm_nmo_razd_media`.`id`,
  `tm_nmo_razd_media`.`tm_nmo_razd`,
  `tm_nmo_razd_media`.`path`,
  `tm_nmo_razd_media`.`tip`,
  `tm_nmo_razd_media`.`act`,
  `tm_nmo_razd_media`.`obyaz`,
  `tm_nmo_razd_media`.`num`,
  `tm_nmo_razd_media`.`comment`,
  `tm_nmo_razd_media`.`dop_file`,
  `tm_nmo_razd_media`.`nazv`, `tm_nmo_razd_media`.`povt`,DATE_FORMAT( `tm_nmo_razd_media`.`data_act`, '%Y-%m-%dT%H:%i') as data_act,DATE_FORMAT( `tm_nmo_razd_media`.`data_okon`, '%Y-%m-%dT%H:%i') as data_okon 
FROM
  `tm_nmo_razd_media`
WHERE
  `tm_nmo_razd_media`.`tm_nmo_razd` = ".$row_nmo['id']."
ORDER BY
  `tm_nmo_razd_media`.`num`";  
	

$media1 =  /* fixed MMiC */ DB::Query($sql23, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_media1 =  /* fixed MMiC */ mysqli_fetch_assoc($media1);
$totalRows_media1 =  /* fixed MMiC */ mysqli_num_rows($media1);  
	  ?>
	    <?php
do { 
	
if ($row_media1['tip']==1)$td="Докум";	
if ($row_media1['tip']==2)$td="Видео";	
	if ($row_media1['tip']==3)$td="Тест";	
	if ($row_media1['tip']==4)$td="Контроль";
		if ($row_media1['tip']==5)$td="завер";
			if ($row_media1['tip']==6)$td="анкета";
		if ($row_media1['tip']==7)$td="Файл";
	if ($row_media1['tip']==10)$td="скриншоты или текст";
		if ($row_media1['tip']==11)$td="оплата";
		if ($row_media1['tip']==16)$td="практика";
		if ($row_media1['tip']==18)$td="Анкетный Тест";
	if ($row_media1['tip']==12)$td="ссылка";	if ($row_media1['tip']==15)$td="тест с сертификатом";
	if ($row_media1['tip']==22)$td="Экзамен";
	if ($row_media1['tip']==17)$td="Тетради";	if ($row_media1['tip']==19)$td="Таблицы";	if ($row_media1['tip']==19)$td="случ";
?>
    <tr id="tr<?php echo $row_media1['id']; ?>">
		<td><input type="checkbox" data-id="<?php echo $row_media1['id']; ?>" class="kcb" /></td>
		  <td>
		<input type="button" name="button" id="button" value="Удал"  class="btn btn-default delm" data-t="0" data-id="<?php echo $row_media1['id']; ?>"/>
	<?php	if ($row_media1['tip']==15){ ?><input type="button" name="button" id="button" value="изм"  class="btn btn-default izm" data-t="0" data-id="<?php echo $row_media1['id']; ?>"/><?php } ?>
	<?php	if ($row_media1['tip']==22){ ?><input type="button" name="button" id="button" value="изм"  class="btn btn-default izm" data-t="0" data-id="<?php echo $row_media1['id']; ?>"/><?php } ?>
		</td>
      <td data-base="tm_nmo_razd_media" data-num="<?php echo $row_media1['id']; ?>" data-name="num" data-t="0" ><?php echo $row_media1['num']; ?></td>
      <td data-base="tm_nmo_razd_media" data-num="<?php echo $row_media1['id']; ?>" data-name="nazv" data-t="0" style="font-size: 6pt; margin: 5px;padding: 5px"  ><?php echo $row_media1['nazv']; ?></td>
      
      <td><?php echo $td ?></td>
      <td data-base="tm_nmo_razd_media" data-num="<?php echo $row_media1['id']; ?>" data-name="comment" data-t="2" data-com="<?php echo $row_media1['comment']; ?>" style="font-size: 6pt; margin: 5px;padding: 5px"><?php echo mb_substr($row_media1['comment'],1,40); ?></td>
		 <td data-base="tm_nmo_razd_media" data-num="<?php echo $row_media1['id']; ?>" data-name="gal" data-t="3"><?php echo $row_media1['gal']; ?></td>
     <td data-base="tm_nmo_razd_media" data-num="<?php echo $row_media1['id']; ?>" data-name="povt" data-t="3"><?php echo $row_media1['povt']; ?></td>
      <td data-base="tm_nmo_razd_media" data-num="<?php echo $row_media1['id']; ?>" data-name="obyaz" data-t="3"><?php echo $row_media1['obyaz']; ?></td>
      <td data-base="tm_nmo_razd_media" data-num="<?php echo $row_media1['id']; ?>" data-name="act" data-t="3"><?php echo $row_media1['act']; ?></td>
		    <td data-base="tm_nmo_razd_media" data-num="<?php echo $row_media1['id']; ?>" data-name="data_act" data-t="4"><?php echo $row_media1['data_act']; ?></td>
		    <td data-base="tm_nmo_razd_media" data-num="<?php echo $row_media1['id']; ?>" data-name="data_okon" data-t="4"><?php echo $row_media1['data_okon']; ?></td>
      <td  data-base="tm_nmo_razd_media" data-num="<?php echo $row_media1['id']; ?>" data-name="path" data-t="0" style="font-size: 6pt; margin: 5px;padding: 5px; max-width: 90px"><?php echo $row_media1['path']; ?>
		        <td  style="font-size: 6pt; margin: 5px;padding: 5px"><?php echo $row_media1['dop_file']; ?>
		<?php if ($row_media1['tip']==4) {?><input type="button" name="button" id="button" value="cписок"  class="btn btn-default smsp" data-id="<?php echo $row_media1['id']; ?>"/><?php } ?>
		
		</td>
    </tr>
	     <?php
} while ($row_media1 =  /* fixed MMiC */ mysqli_fetch_assoc($media1));?>
  </tbody>
</table>
</td>
        
        </tr>
      </tbody>
            
    </table>   
          <?php
} while ($row_nmo =  /* fixed MMiC */ mysqli_fetch_assoc($nmo));?>
		
 
  <!-- end .content --></div>
    <div class="sidebar2">
    <h4>&nbsp;</h4>
    <?php include('menu_nmo.php');?>
  </div>
  <!-- end .container --></div>
   <script src="../js/jquery-1.11.3.min.js"></script>

<!-- Include all compiled plugins (below), or include individual files as needed --> 
<script src="../js/bootstrap.js"></script>
  <script type="text/javascript">
	$(function() {
					
		$('body').on('click', '.deldop',function(){
							id=$(this).data('id');
				
						th=$(this);
						
	$.post('add_nmo.php', {'deldop':1,'id':id},function(data) {
$(th).parent().parent().hide();
		
	});
		
	});	
		
			$('#addfo2').on('click',function(){
			spec=$(this).data('spec');				
				ank = prompt("номер анкеты");		
	$.post('add_nmo.php', {'addanc':1,'spec':spec,'ank':ank},function(data) {
console.log(data);
		
	});
		
	});		
		
		
			$('#addfo').on('click',function(){
			spec=$(this).data('spec');				
						
	$.post('add_nmo.php', {'addscr':1,'spec':spec},function(data) {
console.log(data);
		
	});
		
	});		
		
		$('.addprd').on('click',function(){
							id=$(this).data('id');
					cpt=$("#pre2"+id+" :selected").val();
				console.log(cpt);
						
						
	$.post('add_nmo.php', {'adddpopor':1,'id':id,'pr':cpt},function(data) {
$('#tbln'+id).append(data);
		
	});
		
	});	
		
		
		
					$('.copytos').on('click',function(){
							var mas=[];
						d=$(this).data('id');
					cpt=$("#sds"+d+" :selected").val();
			//		console.log(cpt);
						$("#tba"+d).find('.kcb').each(function(idx){//=idx;
	
						if ($(this).is(':checked')){
							dd=$(this).data('id');
							console.log($(this).data('id'));
							mas.push(dd);
							
							
						}
		
								  });	
						
						st=JSON.stringify(mas);
						
	$.post('add_nmo.php', {'copytos':d,'to':cpt,'cb':st},function(data) {
			console.log(st);
		
	});
		
	});		
		
		
		$('#gtf').on('click',function(){
			deff=$('#goto').val();;
	if (deff==-1)$('.parag').show(); else
	$('.parag').each(function(idx){//=idx;
		
						if (this.id=='tba'+deff) {$(this).show();
									    console.log(this.id,'tba'+deff);
									   } else $(this).hide();;		   
								   
								  console.log(deff);
								  
								  });	
		});	
		
		fl=0;
	deff='';	
function sendAjaxForm(result_form, ajax_form, url1,fpr='') {

   var form =($('#'+ajax_form)[0]);
	var formData = new FormData(form);
	
	var request = new XMLHttpRequest();
	request.upload.onprogress = function(event) {

  //  console.log(event.loaded + ' / ' + event.total);
  }
function reqReadyStateChange() {
	if (request.readyState == 4 && request.status == 200){
  		$('#'+result_form).append(request.responseText);
	}
}

request.open("POST", url1);
request.onreadystatechange = reqReadyStateChange;
request.send(formData);
}		
	$('.smsp').on('click',function(){
			deff=$(this).data('id');
		$.post('add_nmo.php', {'list':deff},		function(data) {
		
				
	$('#dfm2').html(data);		
			
			 $("#myModalBox2").modal('show');
			
		
		
		});
	
	
	
	});
		
		
		
		$('.delm').on('click',function(){
		d=$(this).data('id');
			$.post('add_nmo.php', {'deln':d},function(data) {
			console.log(d);
			$("#tr"+d).remove();
			});
		
	});	
			$('.delma').on('click',function(){
		d=$(this).data('id');
			$.post('add_nmo.php', {'delna':d},function(data) {
			console.log(d);
			$("#tba"+d).remove();
			});
		
	});	
		
				$('.copyto').on('click',function(){
		d=$(this).data('id');
					cpt=$("#sd"+d+" :selected").val();
			//		console.log(cpt);
			$.post('add_nmo.php', {'copyto':d,'to':cpt},function(data) {
			console.log(d);
		alert(d);
			});
		
	});	
		
				$('.prep').on('blur',function(){
		d=$(this).data('num');
					cpt=$("#pre"+d+" option:selected").val();
						num=$(this).data('num')
				name=$(this).data('name');
				bas=$(this).data('base');
		
		
				
					console.log(d,cpt);
					$.post('add_nmo.php', {'upd':'1', 'num' :num,'name':name,'val':cpt,'bas':bas},		function(data) {		});
			//$.post('add_nmo.php', {'copyto':d,'to':cpt},function(data) {
		//	console.log(d);
		//alert(d);
		//	});
		
	});	
		$('.izm').on('click',function(){	
				deff=$(this).data('id');
		
	$.post('add_nmo.php', {'izm':1,'media':deff},function(data) {
			//console.log(data);
			$('#dfm3').html(data);
		$("#myModalBox3").modal('show');
			});
				});	
		
		 $("#myModalBox3").on('hidden.bs.modal', function(){
		
		
sendAjaxForm('','fmm3','add_nmo.php','');	 
		 
  });	
		
		$('.sm').on('click',function(){
			deff=$(this).data('id');
			console.log($(this).parent().children().first().val());	
			
			if ($(this).parent().children().first().val()==19)
tmp=' <div class="form-group"> <label for="nazv">Название таблицы</label> <input name="nazv" class="form-control" id="nazv" placeholder="Введите название"> </div> <div class="form-group"> <label for="path">Файл с документом - csv</label> <input name="path" type="file" accept="text/csv" class="form-control" id="path" placeholder="Введите файл"> </div> <div class="form-group form-check"> <input type="checkbox" class="form-check-input" id="act" name="act"> <label class="form-check-label" for="act">Активный</label> </div><div class="form-group"> <label for="num">Номер</label> <input name="num" type="number" min="0" class="form-control" id="num" placeholder="Номер"> </div><div class="form-group"> <label for="comment">Комментарий</label> <textarea name="comment" type="number" min="0" class="form-control" id="comment" placeholder="Комментарий"></textarea> </div><input type="hidden" name="tip" value="19"><input type="hidden" name="tm_nmo_razd" value="'+$(this).data('id')+'">';			
			
			
			
			if ($(this).parent().children().first().val()==16)
tmp=' <div class="form-group"> <label for="nazv">Название практики</label> <input name="nazv" class="form-control" id="nazv" placeholder="Введите название"> </div>  <div class="form-group form-check"> <input type="checkbox" class="form-check-input" id="act" name="act"> <label class="form-check-label" for="act">Активный</label> </div><div class="form-group"> <label for="num">Номер</label> <input name="num" type="number" min="0" class="form-control" id="num" placeholder="Номер"> </div><div class="form-group"> <label for="comment">Комментарий</label> <textarea name="comment" type="number" min="0" class="form-control" id="comment" placeholder="Комментарий"></textarea> </div><input type="hidden" name="tip" value="16"><input type="hidden" name="tm_nmo_razd" value="'+$(this).data('id')+'">';	
				if ($(this).parent().children().first().val()==12)
tmp=' <div class="form-group"> <label for="nazv">Название документа</label> <input name="nazv" class="form-control" id="nazv" placeholder="Введите название"> </div> <div class="form-group"> <label for="path">Ссылка</label> <input name="path"  type="url" class="form-control" id="nazv" placeholder="Введите название">  </div> <div class="form-group form-check"> <input type="checkbox" class="form-check-input" id="act" name="act"> <label class="form-check-label" for="act">Активный</label> </div><div class="form-group"> <label for="num">Номер</label> <input name="num" type="number" min="0" class="form-control" id="num" placeholder="Номер"> </div><div class="form-group"> <label for="comment">Комментарий</label> <textarea name="comment" type="number" min="0" class="form-control" id="comment" placeholder="Комментарий"></textarea> </div><input type="hidden" name="tip" value="12"><input type="hidden" name="tm_nmo_razd" value="'+$(this).data('id')+'">';	
			if ($(this).parent().children().first().val()==1)
tmp=' <div class="form-group"> <label for="nazv">Название документа</label> <input name="nazv" class="form-control" id="nazv" placeholder="Введите название"> </div> <div class="form-group"> <label for="path">Файл с документом - pdf,word</label> <input name="path[]" type="file" accept="application/pdf" class="form-control" id="path" placeholder="Введите файл" multiple> </div> <div class="form-group form-check"> <input type="checkbox" class="form-check-input" id="act" name="act"> <label class="form-check-label" for="act">Активный</label> </div><div class="form-group"> <label for="num">Номер</label> <input name="num" type="number" min="0" class="form-control" id="num" placeholder="Номер"> </div><div class="form-group"> <label for="comment">Комментарий</label> <textarea name="comment" type="number" min="0" class="form-control" id="comment" placeholder="Комментарий"></textarea> </div><input type="hidden" name="tip" value="1"><input type="hidden" name="tm_nmo_razd" value="'+$(this).data('id')+'">';		
		if ($(this).parent().children().first().val()==2)
tmp=' <div class="form-group"> <label for="nazv">Название документа</label> <input name="nazv" class="form-control" id="nazv" placeholder="Введите название"> </div> <div class="form-group">  <label for="path">URL видео</label><input type="url" name="path" class="form-control" id="path" placeholder="Введите URL"> </div> <div class="form-group form-check"> <input type="checkbox" class="form-check-input" id="act" name="act"> <label class="form-check-label" for="act">Активный</label> </div><div class="form-group"> <label for="num">Номер</label> <input name="num" type="number" min="0" class="form-control" id="num" placeholder="Номер"> </div><div class="form-group"> <label for="comment">Комментарий</label> <textarea name="comment" type="number" min="0" class="form-control" id="comment" placeholder="Комментарий"></textarea> </div><input type="hidden" name="tip" value="2"><input type="hidden" name="tm_nmo_razd" value="'+$(this).data('id')+'">';				

if ($(this).parent().children().first().val()==3)
tmp=' <div class="form-group"> <label for="nazv">Название документа</label> <input name="nazv" class="form-control" id="nazv" placeholder="Введите название"> </div> <div class="form-group">  <label for="path">Файл теста</label><input name="path" type="file"  class="form-control" id="path" placeholder="Введите файл"></div> <div class="form-group form-check"> <input type="checkbox" class="form-check-input" id="act" name="act"> <label class="form-check-label" for="act">Активный</label> </div><div class="form-group form-check"><input name="obyaz" type="checkbox" class="form-check-input" id="obyaz" checked="checked">    <label  class="form-check-label" for="obyaz">Обязательный</label></div><div class="form-group"> <label for="num">Номер</label> <input name="num" type="number" min="0" class="form-control" id="num" placeholder="Номер"> </div><div class="form-group"> <label for="dad">Дата запуска теста</label> <input name="data_act" type="date" class="form-control" id="dad" placeholder="Введите название"> </div><div class="form-group"> <label for="comment">Комментарий</label> <textarea name="comment" type="number" min="0" class="form-control" id="comment" placeholder="Комментарий"></textarea> </div><input type="hidden" name="tip" value="3"><input type="hidden" name="tm_nmo_razd" value="'+$(this).data('id')+'">';
	if ($(this).parent().children().first().val()==18)
tmp=' <div class="form-group"> <label for="nazv">Название документа</label> <input name="nazv" class="form-control" id="nazv" placeholder="Введите название"> </div> <div class="form-group">  <label for="path">Файл теста</label><input name="path" type="file"  class="form-control" id="path" placeholder="Введите файл теста"></div> <div class="form-group form-check"> <input type="checkbox" class="form-check-input" id="act" name="act"> <label class="form-check-label" for="act">Активный</label> </div><div class="form-group form-check"><input name="obyaz" type="checkbox" class="form-check-input" id="obyaz" checked="checked">    <label  class="form-check-label" for="obyaz">Обязательный</label></div><div class="form-group"> <label for="num">Номер</label> <input name="num" type="number" min="0" class="form-control" id="num" placeholder="Номер"> </div><div class="form-group"> <label for="dad">Дата запуска теста</label> <input name="data_act" type="date" class="form-control" id="dad" placeholder="Введите название"> </div><div class="form-group">  <label for="path">Файл обработчик </label><input name="comment" type="file"  class="form-control" id="path" placeholder="Введите файл обработчика" accept="text/php"></div> <input type="hidden" name="tip" value="18"><input type="hidden" name="tm_nmo_razd" value="'+$(this).data('id')+'">';		
			
			
			
			
if ($(this).parent().children().first().val()==17)
tmp=' <div class="form-group"> <label for="nazv">Название журнала</label> <input name="nazv" class="form-control" id="nazv" placeholder="Введите название"> </div> <div class="form-group">  <label for="path">Файл теста</label><input name="path" type="file"  class="form-control" id="path" placeholder="Введите файл"></div> <div class="form-group form-check"> <input type="checkbox" class="form-check-input" id="act" name="act"> <label class="form-check-label" for="act">Активный</label> </div><div class="form-group form-check"><input name="obyaz" type="checkbox" class="form-check-input" id="obyaz" checked="checked">    <label  class="form-check-label" for="obyaz">Обязательный</label></div><div class="form-group"> <label for="num">Номер</label> <input name="num" type="number" min="0" class="form-control" id="num" placeholder="Номер"> </div><div class="form-group"> <label for="comment">Комментарий</label> <textarea name="comment" type="number" min="0" class="form-control" id="comment" placeholder="Комментарий"></textarea> </div><input type="hidden" name="tip" value="17"><input type="hidden" name="tm_nmo_razd" value="'+$(this).data('id')+'">';		


if ($(this).parent().children().first().val()==4)
tmp=' <div class="form-group"> <label for="nazv">Название документа</label> <input name="nazv" class="form-control" id="nazv" placeholder="Введите название"> </div> <div class="form-group">  <label for="path">Список тем</label><textarea name="path"  class="form-control" id="path" placeholder="Введите список"></textarea></div> <div class="form-group form-check"> <input type="checkbox" class="form-check-input" id="act" name="act"> <label class="form-check-label" for="act">Активный</label> </div><div class="form-group form-check"><input name="obyaz" type="checkbox" class="form-check-input" id="obyaz" checked="checked">    <label  class="form-check-label" for="obyaz">Обязательный</label></div> <div class="form-group form-check"><input name="povt" type="checkbox" class="form-check-input" id="povt" checked="checked"><label  class="form-check-label" for="povt">Разрешить выбирать одинаковые</label></div><div class="form-group"> <label for="num">Номер</label> <input name="num" type="number" min="0" class="form-control" id="num" placeholder="Номер"> </div><div class="form-group"> <label for="comment">Комментарий</label> <textarea name="comment" type="number" min="0" class="form-control" id="comment" placeholder="Комментарий"></textarea> </div><input type="hidden" name="tip" value="4"><input type="hidden" name="tm_nmo_razd" value="'+$(this).data('id')+'">';	
if ($(this).parent().children().first().val()==5)
tmp='  <div class="form-group"> <label for="comment">Комментарий</label> <textarea name="comment" type="number" min="0" class="form-control" id="comment" placeholder="Комментарий"></textarea> </div><input type="hidden" name="tip" value="5"><input type="hidden" name="tm_nmo_razd" value="'+$(this).data('id')+'">';	

			if ($(this).parent().children().first().val()==6)
tmp=' <div class="form-group"> <label for="nazv">Название документа</label> <input name="nazv" class="form-control" id="nazv" placeholder="Введите название"> </div> <div class="form-group"> <label for="path">Тип анкеты</label> <select class="form-control" name="tpsv"><?php echo $opt;?></select> </div> <div class="form-group form-check"> <input type="checkbox" class="form-check-input" id="act" name="act"> <label class="form-check-label" for="act">Активный</label> </div><div class="form-group"> <label for="num">Номер</label> <input name="num" type="number" min="0" class="form-control" id="num" placeholder="Номер"> </div><div class="form-group"> <label for="comment">Комментарий</label> <textarea name="comment" type="number" min="0" class="form-control" id="comment" placeholder="Комментарий"></textarea> </div><input type="hidden" name="tip" value="6"><input type="hidden" name="tm_nmo_razd" value="'+$(this).data('id')+'">';	if ($(this).parent().children().first().val()==7)
tmp=' <div class="form-group"> <label for="nazv">Название документа</label> <input name="nazv" class="form-control" id="nazv" placeholder="Введите название"> </div> <div class="form-group"> <label for="path">Тип Файлов через запятую:pdf,jpg,png,zip,docx,ppt,pptx,doc,ppt</label> <input name="tpsv" class="form-control" id="tpsv" placeholder="Введите расширения"> </div> <div class="form-group form-check"> <input type="checkbox" class="form-check-input" id="act" name="act"> <label class="form-check-label" for="act">Выкл</label> </div><div class="form-group"> <label for="num">Номер</label> <input name="num" type="number" min="0" class="form-control" id="num" placeholder="Номер"> </div><div class="form-group"> <label for="comment">Комментарий</label> <textarea name="comment" type="number" min="0" class="form-control" id="comment" placeholder="Комментарий"></textarea> </div><input type="hidden" name="tip" value="7"><input type="hidden" name="tm_nmo_razd" value="'+$(this).data('id')+'">';			
if ($(this).parent().children().first().val()==10)
tmp=' <div class="form-group"> <label for="nazv">Название раздела</label> <input name="nazv" class="form-control" id="nazv" placeholder="Введите название"> </div> <div class="form-group"> <label for="path">Тип возврата</label> <select name="tpsv" class="form-control"><option value="0">Скриншот</option><option value="1">Текст</option><option value="2">Скриншот или текст</option></select> </div> <div class="form-group form-check"> <input type="checkbox" class="form-check-input" id="act" name="act"> <label class="form-check-label" for="act">Выкл</label> </div><div class="form-group"> <label for="num">Номер</label> <input name="num" type="number" min="0" class="form-control" id="num" placeholder="Номер"> </div><div class="form-group"> <label for="comment">Комментарий</label> <textarea name="comment" type="number" min="0" class="form-control" id="comment" placeholder="Комментарий"></textarea> </div><input type="hidden" name="tip" value="10"><input type="hidden" name="tm_nmo_razd" value="'+$(this).data('id')+'">';		
if ($(this).parent().children().first().val()==11)
tmp=' <div class="form-group"> <label for="nazv">Название документа</label> <input name="nazv" class="form-control" id="nazv" placeholder="Введите название"> </div> <div class="form-group"> <label for="path">Номера глав через запятую: 1,3,4,7</label> <input name="tpsv" class="form-control" id="tpsv" placeholder="номера глав"> </div> <div class="form-group"> <label for="path">Стоимость</label> <input name="cena" class="form-control" id="cena" placeholder="Цена" type="number" > </div><div class="form-group form-check"> <input type="checkbox" class="form-check-input" id="act" name="act"> <label class="form-check-label" for="act">Выкл</label> </div><div class="form-group"> <label for="num">Номер</label> <input name="num" type="number" min="0" class="form-control" id="num" placeholder="Номер"> </div><div class="form-group"> <label for="comment">Комментарий</label> <textarea name="comment" type="number" min="0" class="form-control" id="comment" placeholder="Комментарий"></textarea> </div><input type="hidden" name="tip" value="11"><input type="hidden" name="tm_nmo_razd" value="'+$(this).data('id')+'">';	
	if ($(this).parent().children().first().val()==15)
tmp='<div class="form-group"> <label for="nazv">Название документа</label> <input name="nazv" class="form-control" id="nazv" placeholder="Введите название"> </div> <div class="form-group">  <label for="path">Файл теста</label><input name="path" type="file"  class="form-control" id="path" placeholder="Введите файл"></div> <div class="form-group form-check"> <input type="checkbox" class="form-check-input" id="act" name="act"> <label class="form-check-label" for="act">Активный</label> </div><div class="form-group form-check"><input name="obyaz" type="checkbox" class="form-check-input" id="obyaz" checked="checked">    <label  class="form-check-label" for="obyaz">Обязательный</label></div><div class="form-group"> <label for="num">Номер</label> <input name="num" type="number" min="0" class="form-control" id="num" placeholder="Номер"> </div><div class="form-group"> <label for="dad">Дата запуска теста</label> <input name="data_act" type="date" class="form-control" id="dad" placeholder="Введите название"> </div><div class="form-group"> <label for="comment">Комментарий</label> <textarea name="comment" type="number" min="0" class="form-control" id="comment" placeholder="Комментарий"></textarea></div><div class="form-group"> <label for="nazv">Сертификата</label> <input name="sert" class="form-control"  placeholder="Введите название"></div>' +
    '<div class="form-group">  <label for="path">Файл Сертификата 1920 x 1365px</label><input name="spath" type="file"  class="form-control" id="path" placeholder="Введите файл"></div>'+
    '<div class="form-group"> <label for="comment">Содержимое сертификата</label> <textarea name="sert_text" type="number" min="0" class="form-control"  placeholder="Содержимое"></textarea></div><div class="form-group"> <label for="nazv">часов</label> <input name="sert_chas" class="form-control"  placeholder="Количество часов" type="number"> </div><input type="hidden" name="tip" value="15"><input type="hidden" name="tm_nmo_razd" value="'+$(this).data('id')+'">';
	if ($(this).parent().children().first().val()==22)
tmp='<div class="form-group"> <label for="nazv">Название документа</label> <input name="nazv" class="form-control" id="nazv" placeholder="Введите название"> </div> <div class="form-group">  <label for="path">Файл теста</label><input name="path" type="file"  class="form-control" id="path" placeholder="Введите файл"></div> <div class="form-group form-check"> <input type="checkbox" class="form-check-input" id="act" name="act"> <label class="form-check-label" for="act">Активный</label> </div><div class="form-group form-check"><input name="obyaz" type="checkbox" class="form-check-input" id="obyaz" checked="checked">    <label  class="form-check-label" for="obyaz">Обязательный</label></div><div class="form-group"> <label for="num">Номер</label> <input name="num" type="number" min="0" class="form-control" id="num" placeholder="Номер"> </div><div class="form-group"> <label for="dad">Дата запуска теста</label> <input name="data_act" type="date" class="form-control" id="dad" placeholder="Введите название"> </div><div class="form-group"> <label for="comment">Комментарий</label> <textarea name="comment" type="number" min="0" class="form-control" id="comment" placeholder="Комментарий"></textarea></div><div class="form-group"> <label for="nazv">Сертификата</label> <input name="sert" class="form-control"  placeholder="Введите название"></div>' +
    '<div class="form-group">  <label for="path">Файл Сертификата 1920 x 1365px</label><input name="spath" type="file"  class="form-control" id="path" placeholder="Введите файл"></div>'+
    '<div class="form-group"> <label for="comment">Содержимое сертификата</label> <textarea name="sert_text" type="number" min="0" class="form-control"  placeholder="Содержимое"></textarea></div><div class="form-group"> <label for="nazv">часов</label> <input name="sert_chas" class="form-control"  placeholder="Количество часов" type="number"> </div><input type="hidden" name="tip" value="15"><input type="hidden" name="tm_nmo_razd" value="'+$(this).data('id')+'">';
if ($(this).parent().children().first().val()==20)
tmp=' <div class="form-group"> <label for="nazv">Название документа</label> <input name="nazv" class="form-control" id="nazv" placeholder="Введите название"> </div><div class="form-group"> <label for="path">количество билетов</label> <input name="cena" class="form-control" id="cena" placeholder="Цена" type="number" > </div><div class="form-group form-check"> <input type="checkbox" class="form-check-input" id="act" name="act"> <label class="form-check-label" for="act">Выкл</label> </div><div class="form-group"> <label for="num">Номер</label> <input name="num" type="number" min="0" class="form-control" id="num" placeholder="Номер"> </div><div class="form-group"> <label for="comment">Комментарий</label> <textarea name="comment" type="number" min="0" class="form-control" id="comment" placeholder="Комментарий"></textarea> </div><input type="hidden" name="tip" value="20"><input type="hidden" name="tm_nmo_razd" value="'+$(this).data('id')+'">';

            if ($(this).parent().children().first().val()==21)
                tmp=' <div class="form-group"> <label for="nazv">Название документа</label> <input name="nazv" class="form-control" id="nazv" placeholder="Введите название"> </div> <div class="form-group">  <label for="path">Файл теста</label><input name="path" type="file"  class="form-control" id="path" placeholder="Введите файл"></div>' +
                    '<div class="form-group">  <label for="path">Файл ответа</label><input name="pathotv" type="file"  class="form-control" id="pathotv" placeholder="Введите файл c ответом" accept="application/pdf"></div> <div class="form-group form-check"> <input type="checkbox" class="form-check-input" id="act" name="act"> <label class="form-check-label" for="act">Активный</label> </div><div class="form-group form-check"><input name="obyaz" type="checkbox" class="form-check-input" id="obyaz" checked="checked">    <label  class="form-check-label" for="obyaz">Обязательный</label></div><div class="form-group"> <label for="num">Номер</label> <input name="num" type="number" min="0" class="form-control" id="num" placeholder="Номер"> </div><div class="form-group"> <label for="dad">Дата запуска теста</label> <input name="data_act" type="date" class="form-control" id="dad" placeholder="Введите название"> </div><div class="form-group"> <label for="comment">Комментарий</label> <textarea name="comment" type="number" min="0" class="form-control" id="comment" placeholder="Комментарий"></textarea> </div><input type="hidden" name="tip" value="21"><input type="hidden" name="tm_nmo_razd" value="'+$(this).data('id')+'">';

console.log($(this).parent().children().first().val());
	$('#dfm').html(tmp);
			
			
			
			 $("#myModalBox").modal('show');
			
			
		})
		
	 $("#myModalBox").on('hidden.bs.modal', function(){
		
		
sendAjaxForm('tbl'+deff,'fmm','add_nmo.php','');	 
		 
  });	
	
 $("#myModalBox2").on('hidden.bs.modal', function(){
		
		
sendAjaxForm('','fmm2','add_nmo.php','');	 
		 
  });	
		
fl=0;
		$('td').on('click',function(){
		if ($(this).data('num')==undefined) return;
			if (fl==0){
			console.log($(this));fl=1;
	//	console.log($('#p'+$(this).prop('name')));
if ($(this).data('t')==0)$(this)[0].innerHTML='<input type="text" name="textfield" id="textfield" value="'+$(this)[0].innerText+'"><input type="button" name="button" id="btg" value="V">';
if ($(this).data('t')==2)$(this)[0].innerHTML='<textarea type="text" name="textfield" id="textfield" value="'+$(this)[0].innerText+'">'+$(this).data('com')+'</textarea><input type="button" name="button" id="btg" value="V">'; 
if ($(this).data('t')==4)$(this)[0].innerHTML='<input type="datetime-local" name="textfield" id="textfield" value="'+$(this)[0].innerText+'"><input type="button" name="button" id="btg" value="V">';

if ($(this).data('t')==3)
	{
		console.log($(this)[0].innerText);
		if ($(this)[0].innerText=="1")ch='checked="checked"' ; else ch='';
	$(this)[0].innerHTML='<input type="checkbox" name="textfield" id="textfield" '+ch+'><input type="button" name="button" id="btg" value="V">'; }
}
		});

	$('body').on('click', '#btg',function(){
			
			num=$(this).parent().data('num')
				name=$(this).parent().data('name');
				bas=$(this).parent().data('base');
				val=$(this).parent().children().first().val();
		
		if ($(this).parent().data('t')==3){

			if($(this).parent().children().first().prop("checked")==true)
		val=1; else val=0;
		
		}
			par=	$(this).parent();fl=0;
		$(this).parent().children().remove();$(par).append(val)
		$.post('add_nmo.php', {'upd':'1', 'num' :num,'name':name,'val':val,'bas':bas},		function(data) {		});
		
	});		
		
	});
	
	
	</script>
</body>
</html>
<?php
 /* fixed MMiC */ mysqli_free_result($spec);
?>
