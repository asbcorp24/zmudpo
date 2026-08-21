<?php require_once('Connections/testmed.php'); ?>
<?php

/*$konf=-1;
if(isset($_GET['konf']))$konf=(int)$_GET['konf'];*/
$query_test="(SELECT 
  `tm_typsv_konf`.`num`,
  `tm_typsv_konf`.`nazv`,
  `tm_typsv_konf`.`typ`,
  `tm_typsv_konf`.`poi`,
  `tm_typsv_konf`.`konf`,
  `tm_typsv_konf`.`polosh`,
  `tm_typsv_konf`.`list`,
  `tm_typsv_konf_user`.`value`,  `tm_typsv_konf_user`.`razdel`
FROM
  `tm_typsv_konf`
  INNER JOIN `tm_typsv_konf_user` ON (`tm_typsv_konf`.`num` = `tm_typsv_konf_user`.`ank`)
WHERE
  `tm_typsv_konf`.`konf` = $konf AND  `tm_typsv_konf_user`.`razdel`=$razdel and 
  `tm_typsv_konf_user`.`user` =$username_test)
union
(SELECT 
  `tm_typsv_konf`.`num`,
  `tm_typsv_konf`.`nazv`,
  `tm_typsv_konf`.`typ`,
  `tm_typsv_konf`.`poi`,
  `tm_typsv_konf`.`konf`,
  `tm_typsv_konf`.`polosh`,
  `tm_typsv_konf`.`list`,
 '' as `value`,-1 as `razdel`
FROM
  `tm_typsv_konf`

WHERE
  `tm_typsv_konf`.`konf` = $konf and    `tm_typsv_konf`.`num` not in (
SELECT 
  `tm_typsv_konf_user`.`ank`
FROM
  `tm_typsv_konf_user`
WHERE
  `tm_typsv_konf_user`.`user` = $username_test and  `tm_typsv_konf_user`.`razdel`=$razdel))
  order by polosh";
//echo $query_test;
$testan =  /* fixed MMiC */ DB::Query($query_test, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_testan =  /* fixed MMiC */ mysqli_fetch_assoc($testan);
$totalRows_testan =  /* fixed MMiC */ mysqli_num_rows($testan);


	$opt=$opt.'<option value="'.$row_tps['num'].'">'.$row_tps['name'].'</option>';

do{$row_testan['razdel']=$razdel;
?>
<label> <?php echo $row_testan['nazv'];  ?></label>
<div class="input-group">
  
		  <span class="input-group-addon" id="zell">
            $
      </span>
	 <?php if ($row_testan['typ']==0){ ?> <input type="text" class="form-control ank" list="22" value="<?php echo $row_testan['value'];  ?>" data-num="<?php echo $row_testan['num'];  ?>" data-razdel="<?php echo $row_testan['razdel'];  ?>"><?php }?>
	 
	<?php if (($row_testan['typ']==3)and($row_testan['value']!='') ){ ?><a href='ankfile/<?php echo $row_testan['value'];  ?>' class='form-control btn btn-info' data-num="<?php echo $row_testan['num'];  ?>" target="_blank"  data-razdel="<?php echo $row_testan['razdel'];  ?>"> <i class="icofont icofont-camera"></i> 
            </a></a><span class="input-group-addon danger">
      <a href="" class="dpr" data-num="<?php echo $row_testan['num'];  ?>">  <i class="icofont icofont-ui-delete"></i> 
            </a></span>
	<?php }?>
	<?php if (($row_testan['typ']==3)and($row_testan['value']=='') ){ ?>	<div class="progress prog" id="prog" style="display: none;height: 40px;">
    <div class="progress-bar progress-bar-striped progress-bar-warning pb " role="progressbar" style="width: 0%;" aria-valuenow="14" aria-valuemin="0" aria-valuemax="100" id="pb"></div>
</div> <input type="file" class="form-control fank" list="22" name="fil" value="<?php echo $row_testan['value'];  ?>" data-num="<?php echo $row_testan['num'];  ?>" accept="image/jpeg"  data-razdel="<?php echo $row_testan['razdel'];  ?>"><?php }?>
       	 <?php if ($row_testan['typ']==1){ ?> <input type="date" class="form-control ank" value="<?php echo $row_testan['value'];  ?>"  data-num="<?php echo $row_testan['num'];  ?>"  data-razdel="<?php echo $row_testan['razdel'];  ?>"><?php }?>
	 <?php if ($row_testan['typ']==4){ ?> <input type="text" class="form-control ank" value="<?php echo $row_testan['value'];  ?>" list="phonesList<?php echo $row_testan['num'];  ?>"  data-num="<?php echo $row_testan['num'];  ?>"  data-razdel="<?php echo $row_testan['razdel'];  ?>">
	
	<datalist id="phonesList<?php echo $row_testan['num'];  ?>">
		<?php $res=explode(PHP_EOL,$row_testan['list']);
	
	 for ($i=0;$i<=count($res);$i++){ ?>
			<option><?php  echo $res[$i];?></option>
			<?php }?>
		</datalist>
<?php }?>
    </div>
<br>
<?php 
} while ($row_testan =  /* fixed MMiC */ mysqli_fetch_assoc($testan));

?>