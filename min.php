	<?php require_once('Connections/testmed.php'); 
include('classSimpleImage.php');
if (!isset($_SESSION)) {
  session_start();
}
?>
<?php 

 function getExtension1($filename) {
	 
	
    return end(explode(".", $filename));
  }
	$image = new SimpleImage();
	$sql="SELECT 
  `tm_nmo_user_file`.`num`,
  `tm_nmo_user_file`.`path`,  `tm_nmo_user_file`.`dat` as da,
  (TO_DAYS(CURDATE()) -  TO_DAYS(`tm_nmo_user_file`.`dat`)) AS dat
FROM
  `tm_nmo_user_file`
  INNER JOIN `tm_nmo_razd_media` ON (`tm_nmo_user_file`.`inn` = `tm_nmo_razd_media`.`id`)
WHERE
  `tm_nmo_user_file`.`opt` = 0 AND 
  `tm_nmo_user_file`.`tip` = 2 AND dat>9 and
  `tm_nmo_razd_media`.`gal` <> 1  order by dat desc limit 50";
  echo 	$sql;
	$result2 =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row2 = mysqli_fetch_assoc($result2);

  do {  
	  
if ($row2['dat']>9)	{  

if (file_exists('./usrimg/'.$row2['path'])) 
{
$size = filesize('./usrimg/'.$row2['path']);

	$image->load('./usrimg/'.$row2['path']);
	if ($image->getWidth()>300){
		$image->resizeToWidth(300);
		if (getExtension1($row2['path'])=='jpg'){
				$image->save('./usrimg/'.$row2['path']);
			}
		else $image->savewebpmin('./usrimg/'.$row2['path']);
	}
	
//	$size2 = filesize('./usrimg/'.$row2['path']);
	
	$sql="update tm_nmo_user_file set opt=1 where num=".$row2['num'];
	DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
echo $sql."<br>";
 $s=$row2['da'];$ss= $row2['dat'];
  	echo "<span style='font-size:50px'>-".$s.":". $ss."</span><br>";
}
  }
 
} while ($row2 = mysqli_fetch_assoc($result2)	);

echo "jre";
	?>

