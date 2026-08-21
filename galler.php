<?php require_once('Connections/testmed.php'); ?>
<?php 
$lim=0;
if (isset($_GET['lim']))
$lim=(int)$_GET['lim'];

	
$query_spec = "SELECT 
  `tm_nmo_razd_media`.`id`,
  `tm_nmo_user_file`.`path`,
  `tm_nmo_user_file`.`comment`,
  `tm_nmo_user_file`.`dat`, `tm_nmo_user_file`.`tip`
FROM
  `tm_nmo_user_file`
  INNER JOIN `tm_nmo_razd_media` ON (`tm_nmo_user_file`.`inn` = `tm_nmo_razd_media`.`id`)
WHERE
  `tm_nmo_razd_media`.`gal` = 1 AND 
  (`tm_nmo_user_file`.`tip` = 2 or `tm_nmo_user_file`.`tip` = 3) order by dat desc limit $lim,9";
$razd='';
if (isset($_GET['razd']))
{
	$razd=(int)$_GET['razd'];
$query_spec = "	SELECT 
  `tm_nmo_razd_media`.`id`,
  `tm_nmo_user_file`.`path`,
  `tm_nmo_user_file`.`comment`,
  `tm_nmo_user_file`.`dat`, `tm_nmo_user_file`.`tip`
FROM
  `tm_nmo_user_file`
  INNER JOIN `tm_nmo_razd_media` ON (`tm_nmo_user_file`.`inn` = `tm_nmo_razd_media`.`id`)
  INNER JOIN `tm_teg_img` ON (`tm_teg_img`.`img` = `tm_nmo_user_file`.`num`)
  INNER JOIN `tm_teg` ON (`tm_teg`.`id` = `tm_teg_img`.`tag`)
WHERE
  `tm_nmo_razd_media`.`gal` = 1 AND 
    (`tm_nmo_user_file`.`tip` = 2 or `tm_nmo_user_file`.`tip` = 3) AND 
  `tm_teg`.`id` = $razd 
order by dat desc limit $lim,9";
	
	
}


$img1 =  /* fixed MMiC */ DB::Query($query_spec, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_img1 =  /* fixed MMiC */ mysqli_fetch_assoc($img1);
$totalRows_img1=  /* fixed MMiC */ mysqli_num_rows($img1);
if ($totalRows_img1<1) exit();
?>

    <div class="panel-body">     
      <div class="row" >
   <?php      do {
/*$sql="SELECT 
  COUNT(`tm_user_pr_rt`.`num`) AS col,
  `tm_user_pr_rt`.`inn`,
  SUM(`tm_user_pr_rt`.`rt`) AS sm
FROM
  `tm_user_pr_rt`
WHERE
  `tm_user_pr_rt`.`inn` =".(int)$row_img1['num']."
GROUP BY
  `tm_user_pr_rt`.`inn`" ;
	$itog =   DB::Query($sql, $testmed) or die(  mysqli_error(DB::$link));
	$itog =   mysqli_fetch_assoc($itog);	  
	@$itog1=round($itog['sm']/$itog['col']);*/	  
		  
		  ?>
<div class="col-sm-6 col-md-4" id="thm<?php echo $row_pr_temy_f['num']; ?>">
    <div class="thumbnail thumb" >
  <?php  if ($row_img1['tip']==2){ ?> 
		  <a class="fancyimage" data-fancybox="images" href="./usrimg/<?php echo $row_img1['path']; ?>"> 
  <img class="img-responsive" src="get_img.php?img=usrimg/<?php echo $row_img1['path']; ?>&w=300&h=200" /> 
</a> <?php } ?> 
		 <?php  if ($row_img1['tip']==3){ ?> 
		 <a data-fancybox="images" href="<?php echo $row_img1['path']; ?>">
  <img class="img-fluid" src="https://img.youtube.com/vi/<?php echo substr($row_img1['path'],strripos($row_img1['path'],'/')+1); ?>/mqdefault.jpg">
</a>
</a> <?php } ?> 
   <div align="center">  
     <p style="font-size:small"> <?php echo $row_img1['comment']; ?></p>

      </div>      
    </div>
  </div>
  <?php } while ($row_img1 = mysqli_fetch_assoc($img1)); ?>
   </div>
  </div>