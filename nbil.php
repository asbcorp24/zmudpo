<?php require_once('Connections/testmed.php'); ?>
<?php $slq="SELECT 
  `tm_nmo_bil`.`num`
FROM
  `tm_nmo_bil`
WHERE
  `tm_nmo_bil`.`user` =$username_test  AND 
  `tm_nmo_bil`.`mrazdel` = $razdel";
$nbb =  /* fixed MMiC */ DB::Query($slq, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_nbb =  /* fixed MMiC */ mysqli_fetch_assoc($nbb);
$totalrow_nbb =  /* fixed MMiC */ mysqli_num_rows($nbb);




/*for($ii=0;$ii<=$kko;$ii++)$bil[$ii]=$ii;

$slq="SELECT 
  `tm_nmo_bil`.`num`,
  `tm_user`.`grupp`
FROM
  `tm_nmo_bil`
  INNER JOIN `tm_user` ON (`tm_nmo_bil`.`user` = `tm_user`.`num`)
WHERE
 `tm_nmo_bil`.`mrazdel` = $username_test and `tm_user`.`grupp`=$ugr";
$nbb =   DB::Query($slq, $testmed) or die(  mysqli_error(DB::$link));
$row_nbb = mysqli_fetch_assoc($nbb);
 unset($bil[4]);
echo $ugr;
print_r(($bil));*/
if ($totalrow_nbb<1){

?>
<button class="btn btn-info gbil" data-kko="<?php echo $kko;?>" data-rzd="<?php echo $razdel;?>" data-gr="<?php echo $ugr;?>">получить номер</button>
<?php } else { ?>
<div> Ваш номер билета - <?php echo $row_nbb['num'];?></div>
<?php } ?>
<script>
$('.gbil').on('click',function(){
	nm=$(this);
$.post('nmo.php', {'gbil':'1', 'rzd' :$(this).data('rzd'),'gr':$(this).data('gr'),'kko':$(this).data('kko')},		function(data) {
	
	nm.parent().html(' Ваш номер билета - '+data)
	
});	
	
	
	
});
</script>