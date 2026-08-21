<?php require_once('Connections/testmed.php'); 

if (!isset($_SESSION)) {
  session_start();
}
?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

function yn($oplat1,$s){
foreach ($oplat1 as &$value) {
   if ($value[0]==$s) return($value[1]);
}	
return(-1);	
}
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

$MM_restrictGoTo = "loginpr.php";
if (!((isset($_SESSION['MM_Username2020'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username2020'], $_SESSION['MM_UserGroup2020'])))) {   
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
$user=$_SESSION['MM_Username2020'];
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

 /* fixed MMiC */ mysqli_select_db(DB::$link, $database_testmed);


if ((isset($_POST["ren"])) && ($_POST["ren"] == "1")) {
	$num=intval($_POST["num"]);
$sql="update `tm_nmo_razd` set nazv='".$_POST['name']."' where id=".$num;	
DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
	echo $sql;
	
	exit(0);	
	
}

if ((isset($_POST["upd"])) && ($_POST["upd"] == "1")) {
$sql="update `tm_nmo_razd_media` set ".$_POST["name"]."='".$_POST["val"]."' where id=".$_POST["num"];	
DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
	echo $sql;
	
	exit(0);	
	
}
if ((isset($_POST["cpr"])) && ($_POST["cpr"] == "1")) {
	$id=intval($_POST["id"]);
	$td=intval($_POST["td"]);
$sql="select * from `tm_nmo_razd_media` where id=$id";	
$rrow=DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
	$rowrow =  /* fixed MMiC */ mysqli_fetch_assoc($rrow);
	$sql="INSERT INTO `tm_nmo_razd_media` (`id`, `tm_nmo_razd`, `path`, `tip`, `act`, `obyaz`, `num`, `comment`, `dop_file`, `nazv`, `povt`, `data_act`, `data_okon`, `avtor`, `tippr`, `kvn`) 
	VALUES (NULL, $td, '".$rowrow['path']."', '".$rowrow['tip']."','".$rowrow['act']."', '".$rowrow['obyaz']."', '".$rowrow['num']."', '".$rowrow['comment']."', '".$rowrow['dop_file']."', '".$rowrow['nazv']."', '".$rowrow['povt']."', '".$rowrow['data_act']."', '".$rowrow['data_okon']."', 1, '".$rowrow['tippr']."', '".$rowrow['kvn']."')";

DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
	echo $sql;
	
	exit(0);	
	
}
//$.post('kurator_add.php', {'cpr':'1', 'id' :num,'td':name},

if ((isset($_POST["del"])) ) {
	$del=intval($_POST["del"]);
$sql="update `tm_nmo_razd_media` set tm_nmo_razd=-10  where  id=".$del;	
DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
	echo $sql;
	
	exit(0);	
	
}

if ((isset($_POST["add"]))) {

	$razd=intval($_POST['razd']);
$sql="INSERT INTO `tm_nmo_razd_media` (`id`, `tm_nmo_razd`, `path`, `tip`, `act`, `obyaz`, `num`, `comment`, `dop_file`, `nazv`, `povt`, `data_act`, `data_okon`, `avtor`) VALUES (NULL, $razd, 'Добавте ссылку', 12, 1, 1, NULL, '', NULL, NULL, 0, NULL, NULL, 1)";

DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$sql="select max(id) as id from tm_nmo_razd_media";
$spec =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_spec =  /* fixed MMiC */ mysqli_fetch_assoc($spec);
	$specc=$row_spec['id'];
	echo '<tr  class="info">
	   <td></td>
				    <td align="center" class="npc"><button data-id="1" class="sho"><i class="glyphicon glyphicon-th"></i></button></td>
				    <td data-num="'.$specc.'" data-name="num">0</td>
					 
	  	         <td data-num="'.$specc.'" data-name="nazv"></td>
				 <td >Ссылка</td>
				   <td data-num="'.$specc.'" data-name="path"></td>
	  	         <td></td> <td></td>
  	           </tr>';
	exit;
}


$query_spec = "SELECT 
  `tm_nmo_razd`.`id`,
  `tm_nmo_razd`.`nazv`,
  `tm_spec`.`nazv` as spnazv
FROM
  `tm_nmo_razd`
  INNER JOIN `tm_spec` ON (`tm_nmo_razd`.`spec` = `tm_spec`.`num`)
WHERE
  `tm_nmo_razd`.`prepod` = $user
  union
  
  SELECT 
  `tm_nmo_razd`.`id`,
  `tm_nmo_razd`.`nazv`,
  `tm_spec`.`nazv`  AS `spnazv`
FROM
  `tm_nmo_razd_dop_prepod`
  INNER JOIN `tm_nmo_razd` ON (`tm_nmo_razd_dop_prepod`.`razdel` = `tm_nmo_razd`.`id`)
  INNER JOIN `tm_spec` ON (`tm_nmo_razd`.`spec` = `tm_spec`.`num`)
WHERE
  `tm_nmo_razd_dop_prepod`.`prepod` = $user
  
  ";
//echo $query_spec;
$spec =  /* fixed MMiC */ DB::Query($query_spec, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_spec =  /* fixed MMiC */ mysqli_fetch_assoc($spec);
$totalRows_spec =  /* fixed MMiC */ mysqli_num_rows($spec);




function getsokr($slovo){
$res="";	
$pieces = explode(" ", $slovo);
foreach($pieces as $val){
$res=$res.mb_substr($val,0,3)." ";	
}	
return $res;	
}

//echo $query_spec;
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Работа куратором</title>

<!-- Bootstrap -->
<link rel="stylesheet" href="css/bootstrap.css">

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
<style>
/* CSS */
.btn-circle {
    width: 38px;
    height: 38px;
    border-radius: 19px;
    text-align: center;
    padding-left: 0;
    padding-right: 0;
    font-size: 16px;
}
	
</style>    
  <style> 
        input.largerCheckbox { 
            width: 20px; 
            height: 20px; 
        } 
    </style>  


</head>
<body>
<?php include("header.php");?>

<hr>
	<!--панель скопирования элементов-->
	<div class="panel panel-default" style="max-width: 400px;display: none" id="scpy">
  <div class="panel-body">
		
		<div><select style="font-size: 10px" style="max-width: 300px" >
			<?php do{?>
			<option value="<?php echo ($row_spec['id']); ?>">[<?php echo getsokr($row_spec['spnazv']); ?>] <?php echo getsokr($row_spec['nazv']); ?></option>
			<?php } while ($row_spec =  /* fixed MMiC */ mysqli_fetch_assoc($spec));
		mysqli_data_seek($spec,0); 
			$row_spec =  /* fixed MMiC */ mysqli_fetch_assoc($spec)
			?>
			
			</select><button class="cpnp" style="font-size: 10px">скоп</button><button class="ocpnp" style="font-size: 10px">отм</button></div>
		</div>
</div>
	<!--панель скопирования элементов-->
	<form id="form_00" enctype="multipart/form-data" style="display: none">
			<h3> выберите файл с расширением doc,docx,pdf,jpg,pptx,ppt</h3>
		<div class="progress" id="prog_0" style="display: none">
    <div class="progress-bar progress-bar-striped progress-bar-warning" role="progressbar" style="width: 0% " aria-valuenow="14" aria-valuemin="0" aria-valuemax="100" id="pb"></div>
</div>
<div id="drop-area">
 </div>
		<div id='bgd_0'>
	
			
  <input type="file" title="Click to add Files" class="form-control" name="fileuser" accept="application/pdf,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.openxmlformats-officedocument.presentationml.presentation,image/jpeg;application/vnd.ms-powerpoint,application/msword;image/png" >
	<br>
		<input type="hidden" value="0_0" name="mediarazd" id="mediarazd">
	<button class="btn btn-info form-control send"  type="button" >Отправить</button>
		</div>
</form>
	
	
	
	
	
 <div class="container">
  <div class="row text-center">
	
     <?php include("kb.php");?>  

	</div>  
	</div>  
<div class="container">
 <div class="row ">
	  <br>
	  	<div class="panel-group" id="accordion">
  
  
    
  <!-- 2 панель -->
  <div class="panel panel-default">
    <!-- Заголовок 2 панели -->
    <div class="panel-heading">
      <h4 class="panel-title">
     
		 
			     <a data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $row_spec['id']; ?>">Мусорка</a>
		 
      </h4>
    </div>
    <div id="collapse<?php echo $row_spec['id']; ?>" class="panel-collapse collapse in">
      <!-- Содержимое 2 панели -->
      <div class="panel-body">
		    
		  
		 
		  <hr>
	
		  <?php $sql="SELECT 
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
  DATE_FORMAT( `tm_nmo_razd_media`.`data_act`, '%Y-%m-%dT%H:%i') as data_act,
  `tm_nmo_razd_media`.`data_okon`, `tm_nmo_razd_media`.`avtor`,`tm_nmo_razd_media`.`tippr`,`tm_nmo_razd_media`.`kvn`,`tm_nmo_razd_media`.`pop`
FROM
  `tm_nmo_razd_media`
WHERE
  `tm_nmo_razd_media`.`tm_nmo_razd` = -10 order by num"; 
		  $media =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_media =  /* fixed MMiC */ mysqli_fetch_assoc($media);
$totalRows_media =  /* fixed MMiC */ mysqli_num_rows($media);
//	echo $totalRows_media;	  
		  ?>
		  
		   <div class="table-responsive"> 
<table class="table table-bordered table-striped" id="pepe">
	  	     <tbody id="tb<?php echo $row_spec['id']; ?>">
	  	       <tr> <th class="npc"></th>
				   <th class="npc"></th>
				    <th>Номер</th>
	  	         <th>Название</th>
				   <th>Тип</th>
	  	         <th>Файл</th>
				    <th>Комментарий</th> 
  	           </tr>
				   <?php do {
			  $td=$row_media['tip'];
				 if ($row_media['tip']==1)$td="Докум";	
if ($row_media['tip']==2)$td="Видео";	
	if ($row_media['tip']==3)$td="Тест";	
	if ($row_media['tip']==4)$td="Контроль";
		if ($row_media['tip']==5)$td="завер";
			if ($row_media['tip']==6)$td="анкета";
		if ($row_media['tip']==7)$td="Файл";
	if ($row_media['tip']==10)$td="скриншоты или текст";
		if ($row_media['tip']==11)$td="оплата";
	if ($row_media['tip']==12)$td="ссылка";
			  if ($row_media['tip']==13)$td="облако";
			  
if  ($row_media['avtor']!=1){  
				 ?>

	  	       <tr>
				 <?php if (($row_media['tip']!=6)and($row_media['tip']!=10)) {?>   <td align="center" class="npc"><button data-id="<?php echo $row_media['id']; ?>" class="cpo" <?php  if ($row_media['tip']==13) echo 'data-d="y"' ?>><i class="glyphicon glyphicon-list-alt"></i></button></td><?php } else {?><td></td> <?php }?>
				     <td></td>
				    <td data-num="<?php echo $row_media['id']; ?>" data-name="num" class="info"><?php echo $row_media['num']; ?></td>
	  	       	  	         <td data-num="<?php echo $row_media['id']; ?>" data-name="nazv" class="info"><?php echo $row_media['nazv']; ?></td>
				 
				   <td><?php echo $td;?></td> 
	  	        <?php  if ($row_media['tip']=='0') {?> <td></td><?php } ?>
				     <?php  if ($row_media['tip']=='') {?> <td></td><?php } ?>
				<?php  if ($row_media['tip']==1) {?> <td><a href="nmo/doc/<?php echo $row_media['path']; ?>" target="_blank"><?php echo $row_media['path']; ?></a></td><?php } ?>
				   	<?php  if ($row_media['tip']==3) {?> <td><a href="nmo/test/<?php echo $row_media['path']; ?>"  target="_blank"><?php echo $row_media['path']; ?></a></td><?php } ?>
				     	<?php  if ($row_media['tip']==2) {?> <td><a href="<?php echo $row_media['path']; ?>"  target="_blank"><?php echo $row_media['path']; ?></a></td><?php } ?>
				   	<?php  if ($row_media['tip']==12) {?> <td><a href="<?php echo $row_media['path']; ?>"  target="_blank"><?php echo $row_media['path']; ?></a></td><?php } ?>
				    	<?php  if ($row_media['tip']==6) {?> <td><a href="<?php echo $row_media['path']; ?>"  target="_blank"><?php echo $row_media['path']; ?></a></td><?php } ?>
				   	<?php  if ($row_media['tip']==10) {?> <td><a href="<?php echo $row_media['path']; ?>"  target="_blank"><?php echo $row_media['path']; ?></a></td><?php } ?>
				   	<?php  if ($row_media['tip']==13) {?> <td><a href="<?php echo $row_media['path']; ?>"  target="_blank"><?php echo $row_media['path']; ?></a></td><?php } ?>
				      	<?php  if ($row_media['tip']==15) {?> <td><a href="nmo/test/<?php echo $row_media['path']; ?>"  target="_blank"><?php echo $row_media['path']; ?></a></td><?php } ?>
				     <td data-num="<?php echo $row_media['id']; ?>" data-name="comment"  class="info"><?php echo $row_media['comment']; ?></td>
				    
				   
  	           </tr>
	  	     <?php } else { ?><tr class="info">
				  <?php if (($row_media['tip']!=6)and($row_media['tip']!=10)) {?>   <td align="center" class="npc"><button data-id="<?php echo $row_media['id']; ?>" class="cpo" <?php  if ($row_media['tip']==13) echo 'data-d="y"' ?>><i class="glyphicon glyphicon-list-alt"></i></button></td><?php } else {?><td></td> <?php }?>
     <td></td>
				    <td data-num="<?php echo $row_media['id']; ?>" data-name="num"><?php echo $row_media['num']; ?></td>
	  	         <td data-num="<?php echo $row_media['id']; ?>" data-name="nazv"><?php echo $row_media['nazv']; ?></td>
				 
				 
				   <td><?php echo $td;?></td>
				      <?php  if ($row_media['tip']=='') {?> <td></td><?php } ?>
				 <?php  if ($row_media['tip']=='0') {?> <td></td><?php } ?>
				<?php  if ($row_media['tip']==1) {?> <td><a href="nmo/doc/<?php echo $row_media['path']; ?>" target="_blank"><?php echo $row_media['path']; ?></a></td><?php } ?>
				   	<?php  if ($row_media['tip']==3) {?> <td><a href="nmo/test/<?php echo $row_media['path']; ?>"  target="_blank"><?php echo $row_media['path']; ?></a></td><?php } ?>
				     	<?php  if ($row_media['tip']==2) {?> <td><a href="<?php echo $row_media['path']; ?>"  target="_blank"><?php echo $row_media['path']; ?></a></td><?php } ?>
				   	<?php  if ($row_media['tip']==12) {?> <td><a href="<?php echo $row_media['path']; ?>"  target="_blank"><?php echo $row_media['path']; ?></a></td><?php } ?>
				 	<?php  if ($row_media['tip']==6) {?> <td><a href="<?php echo $row_media['path']; ?>"  target="_blank"><?php echo $row_media['path']; ?></a></td><?php } ?>
				  	<?php  if ($row_media['tip']==10) {?> <td><a href="<?php echo $row_media['path']; ?>"  target="_blank"><?php echo $row_media['path']; ?></a></td><?php } ?>
				  	<?php  if ($row_media['tip']==13) {?> <td><a href="<?php echo $row_media['path']; ?>"  target="_blank"><?php echo $row_media['path']; ?></a></td><?php } ?>
				  	   	<?php  if ($row_media['tip']==15) {?> <td><a href="nmo/test/<?php echo $row_media['path']; ?>"  target="_blank"><?php echo $row_media['path']; ?></a></td><?php } ?>
				   <td data-num="<?php echo $row_media['id']; ?>" data-name="comment"  class="info"><?php echo $row_media['comment']; ?></td>
				
  	           </tr>
	  	     <?php } ?>
				 
        <?php } while ($row_media =  /* fixed MMiC */ mysqli_fetch_assoc($media)); ?>
  </tbody>
</table>
	  </div>  
		  
		  
      </div>
    </div>
  </div>
	
  <!-- 3 мусорка -->
 





</div> 
	 </div>
</div>
	  	 <br>

<hr>

<hr>
<h2 class="text-center">&nbsp;</h2>
<footer class="text-center">

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) --> 
<script src="js/jquery-1.11.3.min.js"></script> 
<!-- Include all compiled plugins (below), or include individual files as needed --> 
<script src="js/bootstrap.js"></script>
	   <script src="printThis.js"></script>
  <script type="text/javascript">
	$(function() {

    function sendAjaxForm2(result_form, ajax_form, url1, fpr) {

        var form = ($('#' + ajax_form)[0]);
        var formData = new FormData(form);
        //console.log(form);
        var request = new XMLHttpRequest();
        request.upload.onprogress = function(event) {
            pr = event.loaded / event.total * 100;
            $('#pb' + fpr).css('width', pr + "%");
            console.log($('#pb' + fpr));

            console.log(event.loaded + ' / ' + event.total);
        }

        function reqReadyStateChange() {
            if (request.readyState == 4 && request.status == 200) {
                //console.log('dssdsd');
                $(form).parent().parent().html(request.responseText);
                //console.log(request.responseText);
                //location.href = "nmo.php?row="
            }
        }

        request.open("POST", url1);
        request.onreadystatechange = reqReadyStateChange;
        request.send(formData);
    }



    $('#pch').on('click', function() {

        $('.npc').hide();
        $('#pepe').printThis({
            afterPrint: function() {
                $('.npc').show();
            }
        });
        //	$('.npc').show();
    });
    $('#pch2').on('click', function() {


        $('#addra').printThis({
            afterPrint: function() {}
        });
        //	$('.npc').show();
    });



    fl = 0;
    mr = 0;
    bl = 0;

    $('body').on('click', '.cpnp', function() {
        th = $(this);
        id = $(this).data('id');
        td = $(this).parent().find('select').val();
        console.log(td);
        $.post('kurator_add.php', {
                'cpr': '1',
                'id': id,
                'td': td
            },
            function(data) {
    bl = 0;
                $(th).parent().parent().remove();
            });

    });
    $('body').on('click', '.ocpnp', function() {

        $(this).parent().parent().remove();
        bl = 0;
    });

    $('.cpo').on('click', function() {
        if (bl == 1) return;
        id = $(this).data('id');
        bl = 1;
        tf = $("#scpy").clone();
        tf.show();
        tf.css("position", "absolute");
        tf.css("z-index", "1000");
        tf.find('.cpnp').first().data('id', id);
        //console.log(tf.find('.cpnp').first().data('id'));//.
        $(this).parent().append(tf);
        //$('body').append(tf);
        console.log(tf);
        // bottom: 15px; /* Положение от нижнего края */
        //  right: 15px; 	
    });


    $('.ccb').on('click', function() {
        num = $(this).data('num')
        name = $(this).data('name');
        if ($(this).prop('checked')) val = 1;
        else val = 0;
        //$(this).parent().children().first().val();




        $.post('kurator_add.php', {
                'upd': '1',
                'num': num,
                'name': name,
                'val': val
            },
            function(data) {


            });
    });

    $('.ccb2').on('click', function() {
        num = $(this).data('num')
        name = $(this).data('name');
        if ($(this).prop('checked')) val = 1;
        else val = 0;
        //$(this).parent().children().first().val();




        $.post('kurator_add.php', {
                'upd': '1',
                'num': num,
                'name': name,
                'val': val
            },
            function(data) {


            });
    });


    $('.sho').on('click', function() {
        th = $(this);
        num = $(this).data('id');
        d = $(this).data('d');
        if (d == "y") {
            $.post("upf2.php", {
                'del': 1,
                'num': num
            }, function(data) {
                th.parent().parent().hide();
                // alert( "success" );
            });


        } else
            $.post("kurator_add.php", {
                'del': num,
                'dat': 1
            }, function(data) {
                th.parent().parent().hide();
                // alert( "success" );
            });

    });


    $('body').on('click', '.add_r', function() {

        num = $(this).data('razd')

        $.post('kurator_add.php', {
                'add': '1',
                'razd': num
            },
            function(data) {
                $('#tb' + num).append(data);


            });
    });

    $('body').on('click', '.add_f', function() {
        mr++;
        num = $(this).data('razd')
        mr = num + mr;

        tf = $("#form_00").clone();
        $(tf).prop('id', "form_" + mr);

        $(tf).find('#mediarazd').val(num);
        $(tf).find('#pb').prop('id', 'pb' + mr);
        $(tf).find('#bgd_0').prop('id', 'bgd_' + mr);
        $(tf).find('#prog_0').prop('id', 'prog_' + mr);
        $(tf).find('.send').prop("id", mr);

        $('#tb' + num).append('<tr><td colspan=6 id="ttb' + mr + '"></td></tr>');
        $('#ttb' + mr).append(tf);
        $(tf).show();

    });

    $('body').on('blur', '.names', function() {
        id = $(this).data('id');
        text = $(this).val();

        $.post('kurator_add.php', {
            'ren': '1',
            'num': id,
            'name': text
        }, function() {

        });
    });


    $('body').on('click', '.send', function() {
        $(this).hide();
        fm = "form_" + $(this).prop('id');
        console.log($('#' + fm));
        $("#prog_" + $(this).prop('id')).show();
        sendAjaxForm2('', fm, 'upf2.php', $(this).prop('id'));
        $('#bgd' + $(this).prop('id')).html('');
    })

    $('body').on('click', '#btg', function(e) {
        e.stopPropagation();
        num = $(this).parent().data('num')
        name = $(this).parent().data('name');
        val = $(this).parent().children().first().val();
        par = $(this).parent();
        fl = 0;
        console.log(par);
        $(par).html('');
        $(par).append(val)
        $.post('kurator_add.php', {
                'upd': '1',
                'num': num,
                'name': name,
                'val': val
            },
            function(data) {


            });
    });

});
//	/(".hello").clone().appendTo(".container");
	
	</script>
</body>
</html>
<?php
 /* fixed MMiC */ mysqli_free_result($spec);
?>