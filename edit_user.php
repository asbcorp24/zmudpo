<?php 
require_once('Connections/testmed.php'); 
function imageresize($outfile,$infile,$neww,$newh,$quality) {

    $im=imagecreatefromjpeg($infile);
    $im1=imagecreatetruecolor($neww,$newh);
    imagecopyresampled($im1,$im,0,0,0,0,$neww,$newh,imagesx($im),imagesy($im));

    imagejpeg($im1,$outfile,$quality);
    imagedestroy($im);
    imagedestroy($im1);
    }


if ($_POST['pole']=='fio') { $sql="UPDATE `tm_user` SET `fio`= '".$_POST['nam']."' WHERE `num` =".$_POST['num'];
$test =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link)); exit();}
if ($_POST['pole']=='mail') { $sql="UPDATE `tm_user` SET `mail`= '".$_POST['nam']."' WHERE `num` =".$_POST['num'];
$test =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));exit();}
if ($_POST['unum']==0){
$nams=$_POST['nam'];
	$fil=$_FILES['file'];
	if(isset($_FILES['file'])){
	$fi =uniqid().'.jpg';
			move_uploaded_file($fil['tmp_name'],'usrimg/'.$fi);	
			imageresize('usrimg/'.$fi,'usrimg/'.$fi,800,600,75);
$nams=$fi;
	}
	

$sql="INSERT INTO `tm_user_sv` (`inn`, `tm_typsv`, `value`) VALUES (".$_POST['num'].",".$_POST['pole']." , '".$nams."')";
$test =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$sql="SELECT LAST_INSERT_ID() as ls";
$test =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));	
$row =  /* fixed MMiC */ mysqli_fetch_assoc($test);
	echo($row[ls]);echo("|");
	if(isset($fi)) echo $fi; else echo "0";
	exit();} else {
$nams=$_POST['nam'];	
	if(isset($_FILES['file'])){
	$fi =uniqid().'.jpg';
	$fil=$_FILES['file'];
	move_uploaded_file($fil['tmp_name'],'usrimg/'.$fi);	
	imageresize('usrimg/'.$fi,'usrimg/'.$fi,800,600,75);
$nams=$fi;
	}
 $sql="UPDATE `tm_user_sv` SET `value`= '".$nams."' WHERE `num` =".$_POST['unum'];
$test =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
	echo($_POST['unum']);echo("|");
	if(isset($fi)) echo $fi; else echo "0";
	exit();
}
	


	
?>
<?php echo "ok";?>