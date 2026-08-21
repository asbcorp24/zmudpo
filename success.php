<?php require_once('Connections/testmed.php'); ?>
<?
	
// регистрационная информация (пароль #1)
// registration info (password #1)
$mrh_pass1 = "X6bMR4fYoA9o3I4NxmmV";
$tm=getdate(time()+9*3600);
$date="$tm[year]-$tm[mon]-$tm[mday] $tm[hours]:$tm[minutes]:$tm[seconds]";
///	file_put_contents("./tmp.txt", $date);
	$results = print_r($_REQUEST, true); // $results теперь содержит вывод print_r
	//	file_put_contents("./tmp.txt", 	$results, FILE_APPEND | LOCK_EX);
// чтение параметров
// read parameters
$out_summ = $_REQUEST["OutSum"];
$inv_id = $_REQUEST["InvId"];

$crc = $_REQUEST["SignatureValue"];

$crc = strtoupper($crc);

$my_crc = strtoupper(md5("$out_summ:$inv_id:$mrh_pass1:Shp_Id=$dopid"));

//	file_put_contents("./tmp.txt", 	$my_crc , FILE_APPEND | LOCK_EX);
// проверка корректности подписи
// check signature
if ($my_crc != $crc)
{
  echo "bad sign\n";
 // exit();
}

//	$.post('nmo.php', {'kr':'1', 'id' :deff1,'name':deff2},		function(data) {		});	
$Shp_Id= $_REQUEST["Shp_Id"];
	file_put_contents("./tmp.txt",$Shp_Id, FILE_APPEND | LOCK_EX);	
if ($Shp_Id>0) {
	
	$insertSQL="INSERT INTO `tm_nmo_user_media_opl` (`num`, `user`, `media_razd`, `value`, `dat`) VALUES (NULL, $inv_id, $Shp_Id, 1, '$date')";
//	file_put_contents("./tmp.txt", $insertSQL, FILE_APPEND | LOCK_EX);	
		DB::Query($insertSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link)); 
} else
// INSERT INTO `tm_nmo_user_media_opl` (`num`, `user`, `media_razd`, `value`, `dat`) VALUES (NULL, 12, 12, 1, '2020-02-29')


{
$insertSQL="update tm_user set oplata=1,data_opl='$date' where num=$inv_id";
//	echo $insertSQL;
	DB::Query($insertSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link)); }
	

	
echo "OK$inv_id\n";	
exit();



// проверка наличия номера счета в истории операций
// check of number of the order info in history of operations


?>
