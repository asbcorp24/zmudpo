<?php require_once('Connections/testmed.php'); ?>
<?php
if ((isset($_POST["opis"])) && ($_POST["opis"] == "1")) {
	
	$id=(int)$_POST["id"];
	if ($_POST["tip"]>1) {$sql="update `tm_nmo_user_file` set comment='".$_POST["tex"]."' where num=".$id;
				$tmp= explode(" ", $_POST["tex"]); 		  
				foreach ($tmp as &$value) {
					$value=mb_strtoupper($value);
				 if(strlen($value) > 0 && $value[0] === '#'){
  $sql2="SELECT 
  `tm_teg`.`id`,
  `tm_teg`.`tag`
FROM
  `tm_teg`
WHERE
  `tm_teg`.`tag` = '$value'";
															 echo  $sql2;
	$testfa =  /* fixed MMiC */ DB::Query($sql2, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_testfa =  /* fixed MMiC */ mysqli_fetch_assoc($testfa);	
$totalRows_testfa =  /* fixed MMiC */ mysqli_num_rows($testfa);
	$id2=$row_testfa['id'];														 
if ($totalRows_testfa<1){$sql2="INSERT INTO `tm_teg` (`id`, `tag`) VALUES (NULL, '$value')";
						 
 DB::Query($sql2, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));					
$sql4="select max(id) as id from tm_teg";						
$testfa2 =  /* fixed MMiC */ DB::Query($sql4, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_testfa2 =  /* fixed MMiC */ mysqli_fetch_assoc($testfa2);	
$id2=$row_testfa2['id'];	}			
	  $sql7="select id from `tm_teg_img` where img=$id and tag=$id2 ";
	$testfa7 =  /* fixed MMiC */ DB::Query($sql7, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
		$totalRows_testfa7 =  /* fixed MMiC */ mysqli_num_rows($testfa7);
			if($totalRows_testfa7<1){		 
	$sql5="INSERT INTO `tm_teg_img` (`id`, `img`, `tag`) VALUES (NULL, $id, $id2)";					  
		DB::Query($sql5, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));				  
			}
						 } }	}	
	else $sql="update `tm_nmo_user_file` set path='".$_POST["tex"]."' where num=".$id;	
//	echo $sql;
DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link)); 
exit(0);		
}



/*$konf=-1;
if(isset($_GET['konf']))$konf=(int)$_GET['konf'];*/
$query_test="
  SELECT 
  `tm_nmo_user_file`.`num`,
  `tm_nmo_user_file`.`user`,
  `tm_nmo_user_file`.`tip`,
  `tm_nmo_user_file`.`path`,
  `tm_nmo_user_file`.`comment`,
  `tm_nmo_user_file`.`inn`
FROM
  `tm_nmo_user_file`
WHERE
  `tm_nmo_user_file`.`user` = $username_test AND 
  `tm_nmo_user_file`.`inn` = ".$row_razd['id'];
//echo $query_test;
$testfa =  /* fixed MMiC */ DB::Query($query_test, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_testfa =  /* fixed MMiC */ mysqli_fetch_assoc($testfa);
$totalRows_testfa =  /* fixed MMiC */ mysqli_num_rows($testfa);


//	$opt=$opt.'<option value="'.$row_tps['num'].'">'.$row_tps['name'].'</option>';


?>
<style>
/* CSS */
.btn-rounded {
    border-radius: 19px;
    padding-top: 3px;
    padding-bottom: 3px;
    padding-left: 3px;
}
</style>

<?php $js=$js+1; if ($js<2) { ?>
<script type="text/javascript">
			function sendAjaxForm22(result_form, ajax_form, url1,fpr) {

   var form =($('#'+ajax_form)[0]);
	var formData = new FormData(form);
	//console.log(form);
	var request = new XMLHttpRequest();
	request.upload.onprogress = function(event) {
		pr=event.loaded/ event.total*100;
	$('#pb'+fpr).css('width',pr+"%");
	//	console.log($('#pb'+fpr));
		
 // console.log(event.loaded + ' / ' + event.total);
  }
function reqReadyStateChange() {
	if (request.readyState == 4 && request.status == 200){
  		//console.log('dssdsd');
		$(form).html(request.responseText);
		//console.log(request.responseText);
			//location.href = "nmo.php?row="
	}
}

request.open("POST", url1);
request.onreadystatechange = reqReadyStateChange;
request.send(formData);
}	
	$(function() {
		
		
		$('.inris').on('click',function(){
			id=$(this).data('id');
			rn=Math.floor(Math.random()*10000);
			ss=$("#ferry"+id ).clone().appendTo( "#ferpole"+id );
			ss.prop('id',"f"+rn); 
			 
			//console.log(ss.find('.fsend'));
			ss.find('.fsend').data('fs',rn);
			ss.find('.prog').prop('id',"prog"+rn);
			ss.find('.pb').prop('id',"pb"+rn);
			ss.find('.dld').prop('id',"dld"+rn);
			ss.show();
		
		});	
	$('.intext').on('click',function(){
			id=$(this).data('id');
				rn=Math.floor(Math.random()*10000);
				ss=$("#ferrytext"+id ).clone().appendTo( "#ferpole"+id );
			ss.prop('id',"f"+rn); 
			 
			//console.log(ss.find('.fsend'));
			ss.find('.tsend').data('fs',rn);
			ss.find('.prog').prop('id',"prog"+rn);
			ss.find('.pb').prop('id',"pb"+rn);
			ss.find('.dld').prop('id',"dld"+rn);
			ss.show();
	
		});	
		$('.inutub').on('click',function(){
			id=$(this).data('id');
				rn=Math.floor(Math.random()*10000);
				ss=$("#ferryutub"+id ).clone().appendTo( "#ferpole"+id );
			ss.prop('id',"f"+rn); 
			 
			//console.log(ss.find('.fsend'));
			ss.find('.tsend').data('fs',rn);
			ss.find('.prog').prop('id',"prog"+rn);
			ss.find('.pb').prop('id',"pb"+rn);
			ss.find('.dld').prop('id',"dld"+rn);
			ss.show();
	
		});	
		//
	$('body').on('click', '.fsend',function(e){
		 e.preventDefault();
		fm=($(this).data('fs'));
/*fm="form"+$(this).data('num');
//	console.log($("#prog"+fm));
$("#prog"+$(this).data('num')).show();*/
	//	console.log($('#prog'+fm));
		$('#prog'+fm).show();
		$('#dld'+fm).hide();
		console.log(fm);
sendAjaxForm22('',"f"+fm,'upfsk.php',fm);
//	$('#bgd'+$(this).data('num')).html('');
})
		
		$('body').on('click', '.tsend',function(e){
		 e.preventDefault();
		fm=($(this).data('fs'));
/*fm="form"+$(this).data('num');
//	console.log($("#prog"+fm));
$("#prog"+$(this).data('num')).show();*/
	//	console.log($('#prog'+fm));
		$('#prog'+fm).show();
		$('#dld'+fm).hide();
		console.log(fm);
sendAjaxForm22('',"f"+fm,'upfsk.php',fm);
//	$('#bgd'+$(this).data('num')).html('');
})
		
	$('body').on('change', '.opis',function(e){
		
		fm=($(this).data('com'));
		tip=($(this).data('tip'));
/*fm="form"+$(this).data('num');
//	console.log($("#prog"+fm));
$("#prog"+$(this).data('num')).show();*/
	//	console.log($('#prog'+fm));
	res=$(this).val();
	
$.post('nmo_userf.php', {'opis':'1', 'id' :fm,'tex':res,'tip':tip});
		

//	$('#bgd'+$(this).data('num')).html('');
})

		$('body').on('click', '.prpr',function(e){
		//$('.prpr').on('click',function(e){
		 e.preventDefault();
		fm=($(this).prop('href'));
		
/*fm="form"+$(this).data('num');
//	console.log($("#prog"+fm));
$("#prog"+$(this).data('num')).show();*/
	//	console.log($('#prog'+fm));
	res=$(this).val();
	
$('#dfm2').html(' <img src="'+fm+'" width="100%"></img>');		
$("#myModalBox2").modal('show');		

//	$('#bgd'+$(this).data('num')).html('');
})
		
		/*$('#dfm2').html( deff);		
//	https://www.youtube.com/embed/qssHvCCePaY" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>				 
		if (deff!='')
	*/
			
	});
</script>
<?php } ?>
<div class="btn-group" data-toggle="buttons">
	<button type="button" class="btn btn-info btn-rounded inris" id="z<?php echo $row_razd['id']; ?>" data-id="<?php echo $row_razd['id']; ?>"><i class="icofont icofont-image rounded-circle bg-white mr-1 " style="padding: 7px 6px;"></i>Рисунок</button>
		<button type="button" class="btn btn-info btn-rounded intext" id="zt<?php echo $row_razd['id']; ?>" data-id="<?php echo $row_razd['id']; ?>"><i class="icofont icofont-license rounded-circle bg-white mr-1 " style="padding: 7px 6px;"></i>Текст</button>
		<button type="button" class="btn btn-info btn-rounded inutub" id="zt<?php echo $row_razd['id']; ?>" data-id="<?php echo $row_razd['id']; ?>"><i class="icofont icofont-license rounded-circle bg-white mr-1 " style="padding: 7px 6px;"></i>Ссылка</button>
    </div>

<br><br>
<?php 
if ($totalRows_testfa>0)
do{ ?>
<?php if($row_testfa['tip']==1){?>
  <div id="dload16" style="" class="dld"><div class="input-group">
		
		
    <input type="text" value="<?php echo $row_testfa['path']; ?>"  class="form-control opis" placeholder="введите описание" data-com="<?php echo $row_testfa['num']; ?>" data-tip="1">
		<input type="hidden" name="stash" value="16">
		<input type="hidden" value="<?php echo $row_razd['id']; ?>" name="mediarazd">
        <span class="input-group-addon info">
      <a href="getuserf.php?num=<?php echo $row_testfa['num']; ?>" class="prpr" >  <i class="icofont icofont-license"></i> прочитать
            </a></span>
    </div> 
	  
</div><br>
<?php } ?>

<?php if($row_testfa['tip']==2){?>
  <div id="dload16" style="" class="dld"><div class="input-group">
		
	 
      <input type="text" value="<?php echo $row_testfa['comment']; ?>"  class="form-control opis" placeholder="введите описание" data-com="<?php echo $row_testfa['num']; ?>" data-tip="2">
		<input type="hidden" name="stash" value="16">
		<input type="hidden" value="<?php echo $row_razd['id']; ?>" name="mediarazd">
        <span class="input-group-addon info">
      <a href="getuserf.php?num=<?php echo $row_testfa['num']; ?>" class="prpr" >  <i class="icofont icofont-image"></i> Посмотреть
            </a></span>
    </div> 
	  
</div><br>
<?php } ?>
<?php if($row_testfa['tip']==3){?>
  <div id="dload16" style="" class="dld"><div class="input-group">
		
	 
      <input type="text" value="<?php echo $row_testfa['comment']; ?>"  class="form-control opis" placeholder="введите описание" data-com="<?php echo $row_testfa['num']; ?>" data-tip="3">
		<input type="hidden" name="stash" value="16">
		<input type="hidden" value="<?php echo $row_razd['id']; ?>" name="mediarazd">
        <span class="input-group-addon info">
      <a href="<?php echo $row_testfa['path']; ?>" target="_blank">  <i class="icofont icofont-image"></i> Посмотреть
            </a></span>
    </div> 
	  
</div><br>
<?php } ?>

<?php 

} while ($row_testfa =  /* fixed MMiC */ mysqli_fetch_assoc($testfa));

?>


<div id="ferpole<?php echo $row_razd['id']; ?>"></div>
<form id="ferry<?php echo $row_razd['id']; ?>" method="post" enctype="multipart/form-data" style="display: none" >
		<div class="progress prog" id="prog" style="display: none">
    <div class="progress-bar progress-bar-striped progress-bar-warning pb" role="progressbar" style="width: 0% " aria-valuenow="14" aria-valuemin="0" aria-valuemax="100" id="pb"></div>
</div>
    <div id="dload16" style="" class="dld"><div class="input-group">
		
		<span class="input-group-addon">
             <i class="icofont icofont-image"></i>
      </span>
      <input type="file" class="form-control" name="fileuser" accept="image/jpeg" id="fnna16">
		<input type="hidden" name="tip" value="1">
		<input type="hidden" value="<?php echo $row_razd['id']; ?>" name="mediarazd">
        <span class="input-group-addon info">
      <a href="" class="fsend" > <i class="icofont icofont-upload"></i> Загрузить
            </a></span>
    </div> 
	  
	  </div><br></form>
<form id="ferrytext<?php echo $row_razd['id']; ?>" method="post" enctype="multipart/form-data" style="display: none" >
    <div id="dload16" style=""><div class="input-group">
		
		<span class="input-group-addon">
             <i class="icofont icofont-license"></i>
      </span>
     <textarea class="form-control" name="txr"></textarea>
		<input type="hidden" name="tip" value="2">
		<input type="hidden" value="<?php echo $row_razd['id']; ?>" name="mediarazd">
        <span class="input-group-addon info">
      <a href="#" class="tsend" data-num="16"> <i class="icofont icofont-upload"></i> Загрузить
            </a></span>
    </div> 
	  
	  </div><br></form>

<form id="ferryutub<?php echo $row_razd['id']; ?>" method="post" enctype="multipart/form-data" style="display: none" >
    <div id="dload16" style=""><div class="input-group">
		
		<span class="input-group-addon">
             <i class="icofont icofont-license"></i>
      </span>
     <input class="form-control" name="utub" placeholder="https://youtu.be/5DqN05ThitI">
		<input type="hidden" name="tip" value="3">
		<input type="hidden" value="<?php echo $row_razd['id']; ?>" name="mediarazd">
        <span class="input-group-addon info">
      <a href="#" class="tsend" data-num="16"> <i class="icofont icofont-upload"></i> Загрузить
            </a></span>
    </div> 
	  
	  </div><br></form>
