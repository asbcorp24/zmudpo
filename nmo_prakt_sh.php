<?php require_once('Connections/testmed.php'); ?>
<?php
$js1=0;
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

$MM_restrictGoTo = "login.php";
if (!((isset($_SESSION['MM_Username1'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username1'], $_SESSION['MM_UserGroup'])))) {   
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

$un=$_SESSION['MM_Username1'];
if (isset($_POST['kcb'])) {

$num=intval($_POST['num']);
$val=$_POST['val'];
$sql="delete from  `tm_user_sh` where user= $un and media=$num";	
$Result1 =  /* fixed MMiC */ DB::Query($sql, $localhost) or die( /* fixed MMiC */ mysqli_error(DB::$link));		
	$fname=$num."_".$un;
	$zip = new ZipArchive();
	if ($zip->open('./tetrad/user/'.$fname.".zip", ZipArchive::CREATE)!==TRUE) {
    exit('не удалось./tetrad/user/'.$fname.".zip");
}
$zip->addFromString($fname, $val);
	$zip->close();
 // $Result1 =  /* fixed MMiC */ DB::Query($sql, $localhost) or die( /* fixed MMiC */ mysqli_error(DB::$link));	
$sql="	INSERT INTO `tm_user_sh` (`id`, `user`, `media`, `path`, `dat`) VALUES (NULL, $un,$num, '$fname', '".date('Y-m-d')."')";

echo $sql;
  $Result1 =  /* fixed MMiC */ DB::Query($sql, $localhost) or die( /* fixed MMiC */ mysqli_error(DB::$link));	
exit();	
	
}

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
  `tm_nmo_razd_media`.`data_okon`,
  `tm_nmo_razd_media`.`avtor`,
  `tm_nmo_razd_media`.`tippr`,
  `tm_nmo_razd_media`.`kvn`,
  `tm_nmo_razd_media`.`pop`,
  `tm_nmo_razd_media`.`gal`
FROM
  `tm_nmo_razd_media`
WHERE
  `tm_nmo_razd_media`.`id` = ".intval($_GET['media']);
	 $sqkvr =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_sqkvr =  /* fixed MMiC */ mysqli_fetch_assoc($sqkvr);
$sql="SELECT 
  `tm_user_sh`.`path`
FROM
  `tm_user_sh`
WHERE
  `tm_user_sh`.`user` = $un AND 
  `tm_user_sh`.`media` = ".$row_sqkvr['id'];
 $sqkvr2 =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_sqkvr2 =  /* fixed MMiC */ mysqli_fetch_assoc($sqkvr2);
$totalRows_sqkvr2 =  /* fixed MMiC */ mysqli_num_rows($sqkvr2); 
//echo $sql;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo $row_name['nazv']; ?></title>

<!-- Bootstrap -->
<link rel="stylesheet" href="css/bootstrap.css">
<link rel="stylesheet" href="css/icofont.css">
	<script src="js/jquery-1.11.3.min.js"></script> 
	<script src="js/bootstrap.js"></script>

<link rel="stylesheet" href="nmo.css">
<style>
	
	table { 
    width: 85%; /* Ширина таблицы */
    border: 1px double black; /* Рамка вокруг таблицы */
    border-collapse: collapse; /* Отображать только одинарные линии */
   }
   input{ 
   	
   	line-height: normal;
   }
   
</style>	  
<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<?php include("header.php");?>
	


	
	
    <!--[if lt IE 8]>
    <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->  
    <!-- Body main wrapper start -->
    <div class="wrapper">
        <!-- Start of header area -->
        
        <!-- mobile-menu-area start -->
         <header class="header-area">
   
        </header>
        <!-- mobile-menu-area end -->
        <!-- End of header area -->
        <section class="breadcrumbs-area bg-3 ptb-110 bg-opacity bg-relative">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <div class="breadcrumbs">
                            <h2 class="page-title"><?php echo  $row_sqkvr['nazv']; ?></h2>
                          	<div class="panel panel-default">
  <div class="panel-heading">Тетрадь</div>
  <div class="panel-body">
	    <button type="button" class="btn btn-danger btn-sm pull-left" id="sav" data-id="<?php echo $row_sqkvr['id']; ?>">Сохранить</button>
	        <button type="button" class="btn btn-info btn-sm pull-left" id="pech" data-id="<?php echo $row_sqkvr['id']; ?>" style="display:none">Печать</button><br><br>
	  <hr>
	  <div id="pepe">
	  	<style>
	#pepe {background:  url(logb.jpg) repeat-y;background-position: center; /* Center the image */}
	table { 
    width: 80%; /* Ширина таблицы */
    border: 1px double black; /* Рамка вокруг таблицы */
    border-collapse: collapse; /* Отображать только одинарные линии */
   }
   input{ 
   	 width: 95%;
   	line-height: normal;
   }
   td{min-width:30px;
   	text-align:center;
   }
     span{ text-align: justify; margin:5px;
    }
   
</style>	  
 <?php 
		if($totalRows_sqkvr2>0){
		//	echo "-------------->";
				$zip = new ZipArchive();
			$zip = zip_open('./tetrad/user/'.$row_sqkvr2['path'].".zip");
			if ($zip) {
    while ($zip_entry = zip_read($zip)) {
           if (zip_entry_open($zip, $zip_entry, "r")) {
         
            $buf = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
           $sts= base64_decode($buf);
         //  date
         $sts = str_replace('type="data"', 'type="text"',$sts);
    $sts = str_replace('type="datе"', 'type="date"',$sts);
        $sts = str_replace('h1', 'B',$sts);        $sts = str_replace('h2', 'B',$sts);
    
echo $sts;
            zip_entry_close($zip_entry);
        }
       

    }

    zip_close($zip);

}
			
		}
		  else {
	  $path_parts = pathinfo($row_sqkvr['path']);
	  $string=file_get_contents('./tetrad/shabl/'.$row_sqkvr['path']);
	  $string = str_replace("index.files", "./tetrad/shabl/".$path_parts['dirname']."/index.files",$string);
	  
	  $tmp = iconv("Windows-1251","UTF-8", $string);
	 echo $tmp;}?>
		  </div>
  </div>
</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
		
<div class="container">
  <div class="row">

	
	
	
	</div>
</div>	

<!-- FOOTER -->
<div class="container">
  <div class="row"></div>
</div>
<footer class="text-center">
  <div class="container">
    <div class="row">
      <div class="col-xs-12">

        <p>Cделал ASBcorp24</p>
      </div>
    </div>
  </div>
</footer>
<!-- / FOOTER --> 
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) --> 

<!-- Include all compiled plugins (below), or include individual files as needed --> 

 <script src="printThis.js"></script>
<script type="text/javascript">
		  	  
function b64EncodeUnicode(str) {
    // first we use encodeURIComponent to get percent-encoded UTF-8,
    // then we convert the percent encodings into raw bytes which
    // can be fed into btoa.
    return btoa(encodeURIComponent(str).replace(/%([0-9A-F]{2})/g,
        function toSolidBytes(match, p1) {
            return String.fromCharCode('0x' + p1);
    }));
}


function b64DecodeUnicode(str) {
    // Going backwards: from bytestream, to percent-encoding, to original string.
    return decodeURIComponent(atob(str).split('').map(function(c) {
        return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
    }).join(''));
}


function imageToDataUri(img, width, height,callback,nn) {

 var sourceImage = new Image();

    sourceImage.onload = function() {
        // Create a canvas with the desired dimensions
        var canvas = document.createElement("canvas");
        canvas.width = width;
        canvas.height = height;

        // Scale and draw the source image to the canvas
        canvas.getContext("2d").drawImage(sourceImage, 0, 0, width, height);

        // Convert the canvas to a data URL in PNG format
      callback(canvas.toDataURL('image/jpeg', 0.3),nn);
         //  console.log(canvas.toDataURL('image/jpeg'));
          //return canvas.toDataURL('image/jpeg')
    }

    sourceImage.src = img;

}
function callback1(img,nn){
	$(nn).replaceWith('<div><img src="'+img+'"><button class="delm">Удалить</button><div>');		
}

	$(function() {
$('body').on('click', '.delm',function(){
	$(this).parent().replaceWith('<input id="fileField" type="file" accept="image/jpeg" name="fileField">');	
});		
		
		fl=0;
$('body').on('change','input:file',function(){
	  var fr = new FileReader();
nn=$(this);
    fr.addEventListener("load", function () {
   imageToDataUri(fr.result,200,200,callback1,nn);
    
  //  console.log(img);
    //	$(nn).replaceWith('<img src="'+img+'">');	
       }, false);

    fr.readAsDataURL($(this).prop('files')[0]);
	
	
	
	
});		
$('#sav').click(function(){
	$('#pech').show();
$('input[type="date"]').each(function() {
  var v=this.value; 
  $(this).attr("magicmagic_value",v).removeAttr("value").val(v);
});	
	
$('input:text, input:hidden, input:password').each(function() {
  var v=this.value; 
  $(this).attr("magicmagic_value",v).removeAttr("value").val(v);
});
$('input:checkbox,input:radio').each(function() {
  var v=this.checked; 
  if(v) $(this).attr("magicmagic_checked","checked"); 
  $(this).removeAttr("checked"); 
  if(v) this.checked=true; 
});
$('select option').each(function() { 
  var v=this.selected; 
  if(v) $(this).attr("magicmagic_selected","selected"); 
  $(this).removeAttr("selected");
  if(v) this.selected=true; 
});
$('textarea').each(function() { 
  $(this).html(this.value); 
});
//=$('#pepe').html().replace(/magicmagic_/g,"");
var magic=$("#pepe").html().replace(/magicmagic_/g,"");
$('[magicmagic_value]').removeAttr('magicmagic_value');
$('[magicmagic_checked]').attr("checked","checked"). removeAttr('magicmagic_checked');
$('[magicmagic_selected]').attr("selected","selected").removeAttr('magicmagic_selected');
console.log(magic);
var ss=b64EncodeUnicode(magic);
	id=$(this).data('id');
	 //var str = s.serializeToString(get( 0 ));
   $.post('nmo_prakt_sh.php', {'kcb':'1', 'num' :id,'val':ss},		function(data) {});	
});			
		
		
		$('#pech').on('click',function(){
			$('#sav').hide();
		$('#pepe').find ('input,textarea,select').each(function() {
  // добавим новое свойство к объекту $data
  // имя свойства – значение атрибута name элемента
  // значение свойства – значение свойство value элемента
// console.log($(this).get(0).tagName);
  if ($(this).prop('type')=='checkbox'){
  if ($(this).is(':checked')) { $(this).replaceWith("<span data-id='"+$(this).prop('type')+">V</span>"); } else  $(this).replaceWith("<span data-id='"+$(this).prop('type')+"'>&nbsp;</span>"); 
  	
  } else 
  $(this).replaceWith("<span data-id='"+$(this).prop('type')+"'> "+$(this).val()+"</span>");
  if ($(this).tagName=='textarea')  $(this).replaceWith("<span data-id='"+$(this).prop('type')+"'> "+$(this).html()+"</span>");

});

		
		
		
			$('#pepe').printThis({ importCSS: true,            // import parent page css
    importStyle: true,removeInline: false,  afterPrint:function(){
			
				
			}});
		//	$('.npc').show();
	});	
			
		
	
	});
	
	</script>
</body>
</html>

