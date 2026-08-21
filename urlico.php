<?php require_once('Connections/testmed.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

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
$users=$_SESSION['MM_Username1'];
if ((isset($_POST["del"])) && ($_POST["del"] == "1")) {
$num=(int)$_POST["num"];
$sql="delete from `tm_user`  where num=".$num ." and ur_parent=$users";	
DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));	
	
//echo $sql;
	exit();
}
if ((isset($_POST["pod"])) && ($_POST["pod"] == "1")) {
	
$query_Recordset1 = sprintf("SELECT * FROM tm_user WHERE num = %s", GetSQLValueString($_POST["num"], "int"));
$Recordset1 =  /* fixed MMiC */ DB::Query($query_Recordset1, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_Recordset1 =  /* fixed MMiC */ mysqli_fetch_assoc($Recordset1);
$num=(int)$_POST["num"];
	
$sql="update `tm_user` set rss=1 where num=".$num;	

DB::Query($sql, $testmed) or die(  mysqli_error(DB::$link));


$to=$row_Recordset1['mail'];

$subject ='Регистрация ГАПОУ "Зеленодольское медицинское училище" '; 

$message = ' 
<html>
<head>
<meta charset="utf-8">
<title>Регистрация на сайте дополнительного образования ГАПОУ "Зеленодольское медицинское училище"</title>
</head>

<body bgcolor="#6178E4" text="#F8F8F8">
<p>Здравствуйте '.$row_Recordset1['fio'].'</p>
<p>
На ваше имя создана учетная запись, после подтверждения вы сможете начать обучение
</p>
<p>
Ваша пароль - '.$row_Recordset1['passw'].'
</p>
</body>
</html>'; 

$headers  = "Content-type: text/html; charset=utf-8 \r\n"; 
$headers .= 'From: ГАПОУ "Зеленодольское медицинское училище" <dpo@zmudpo.ru>\r\n'; 
//echo  $to.$subject. $message. $headers;
mail($to, $subject, $message, $headers,"-f dpo@zmudpo.ru");


exit;

	
}



if ((isset($_POST["upd"])) && ($_POST["upd"] == "1")) {

	
if ($_POST["num"]==-1)	{$sql="INSERT INTO `tm_user` (".$_POST["name"].",ur_parent,spec) VALUES ('".$_POST["val"]."',$users,".$_SESSION['MM_UserGroup'].")";
	DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
	$sql="select max(num) as mn from `tm_user`"	;	
						 $ss =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_ss =  /* fixed MMiC */ mysqli_fetch_assoc($ss);
						 echo $row_ss['mn'];exit(0);	
						}
	else {
		
		$num=(int)$_POST["num"];
$sql="update `tm_user` set ".$_POST["name"]."='".$_POST["val"]."' where num=".$num;	}
DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
echo $_POST["num"];
	exit(0);	
	
}
 /* fixed MMiC */ mysqli_select_db(DB::$link, $database_testmed);
$query_spec = "SELECT * FROM tm_typsv";
$spec =  /* fixed MMiC */ DB::Query($query_spec, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_spec =  /* fixed MMiC */ mysqli_fetch_assoc($spec);
$totalRows_spec =  /* fixed MMiC */ mysqli_num_rows($spec);


$sqlspec="SELECT 
  `tm_user`.`fio`,
  `tm_spec`.`nazv`,
  `tm_spec`.`chas`,
  `tm_spec`.`cena`,
  `tm_user`.`num`,`tm_spec`.`nazv`
FROM
  `tm_user`
  INNER JOIN `tm_spec` ON (`tm_user`.`spec` = `tm_spec`.`num`)
WHERE
  `tm_user`.`num` = $users AND 
  `tm_user`.`urlico` = 1";
$url =  /* fixed MMiC */ DB::Query($sqlspec, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_url =  /* fixed MMiC */ mysqli_fetch_assoc($url);
$totalRows_url =  /* fixed MMiC */ mysqli_num_rows($url);
if ($totalRows_url<1) { header("Location: ". $MM_restrictGoTo); 
  exit;}
$sql="SELECT 
*
FROM
  `tm_user`
WHERE
  `tm_user`.`ur_parent` =$users ";
$urla =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_urla =  /* fixed MMiC */ mysqli_fetch_assoc($urla);
$totalRows_urla =  /* fixed MMiC */ mysqli_num_rows($urla);

$sql="SELECT 
  `tm_docs`.`path`,
  `tm_docs`.`nazv`,
  `tm_docs`.`dat`
FROM
  `tm_doc_spec`
  INNER JOIN `tm_docs` ON (`tm_doc_spec`.`doc` = `tm_docs`.`num`)
WHERE
  `tm_doc_spec`.`spec` =  ".$_SESSION['MM_UserGroup']." AND 
  `tm_docs`.`typ_doc` = 2";
$doca2 =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_doca2 =  /* fixed MMiC */ mysqli_fetch_assoc($doca2);
$totalrow_doca2 =  /* fixed MMiC */ mysqli_num_rows($doca2);
?>

<!doctype html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Новости</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="images/favicon.jpg">
    
    <!-- All css files are included here. -->
    <!-- Bootstrap fremwork main css -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- This core.css file contents all plugings css file. -->
    <link rel="stylesheet" href="css/core.css">
    <!-- Theme shortcodes/elements style -->
    <link rel="stylesheet" href="css/shortcode/shortcodes.css">
    <!-- Theme main style -->
    <link rel="stylesheet" href="style.css">
    <!-- Responsive css -->
    <link rel="stylesheet" href="css/responsive.css">
    <!-- Style customizer (Remove these two lines please) -->
    <link rel="stylesheet" href="css/color/color-1.css">
    
    <!-- Modernizr JS -->
<link rel="stylesheet" href="css/bootstrap.css">
<link rel="stylesheet" href="css/icofont.css">
<style>


	.container {
 font-family: 'Forum', -apple-system, system-ui, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
}

    .drop-shadow {
        -webkit-box-shadow: 0 0 2px 1px rgba(0, 0, 0, .5);
        box-shadow: 0 0 2px 1px rgba(0, 0, 0, .5);
    }
    .container.drop-shadow {
        padding-left:0;
        padding-right:0;
    }
</style>
<link rel="stylesheet" href="nmo.css">
	   <style type="text/css">
   
    .input-group-addon.primary {
    color: rgb(255, 255, 255);
    background-color: rgb(50, 118, 177);
    border-color: rgb(40, 94, 142);
}
.input-group-addon.success {
    color: rgb(255, 255, 255);
    background-color: rgb(92, 184, 92);
    border-color: rgb(76, 174, 76);
}
.input-group-addon.info {
    color: rgb(255, 255, 255);
    background-color: rgb(57, 179, 215);
    border-color: rgb(38, 154, 188);
}
.input-group-addon.warning {
    color: rgb(255, 255, 255);
    background-color: rgb(240, 173, 78);
    border-color: rgb(238, 162, 54);
}
.input-group-addon.danger {
    color: rgb(255, 255, 255);
    background-color: rgb(217, 83, 79);
    border-color: rgb(212, 63, 58);
}    
	.checkbox {
	position: absolute;
	z-index: -1;
	opacity: 0;
	margin: 10px 0 0 20px;
}
.checkbox + label {
	position: relative;
	padding: 0 0 0 60px;
	cursor: pointer;
}
.checkbox + label:before {
	content: '';
	position: absolute;
	top: -4px;
	left: 0;
	width: 50px;
	height: 26px;
	border-radius: 13px;
	background: #CDD1DA;
	box-shadow: inset 0 2px 3px rgba(0,0,0,.2);
	transition: .2s;
}
.checkbox + label:after {
	content: '';
	position: absolute;
	top: -2px;
	left: 2px;
	width: 22px;
	height: 22px;
	border-radius: 10px;
	background: #FFF;
	box-shadow: 0 2px 5px rgba(0,0,0,.3);
	transition: .2s;
}
.checkbox:checked + label:before {
	background: #9FD468;
}
.checkbox:checked + label:after {
	left: 26px;
}
.checkbox:focus + label:before {
	box-shadow: inset 0 2px 3px rgba(0,0,0,.2), 0 0 0 3px rgba(255,255,0,.7);
}
.radio {
	position: absolute;
	z-index: -1;
	opacity: 0;
	margin: 10px 0 0 7px;
}	
.radio + label {
	position: relative;
	padding: 0 0 0 35px;
	cursor: pointer;
}
.radio + label:before {
	content: '';
	position: absolute;
	top: -3px;
	left: 0;
	width: 22px;
	height: 22px;
	border: 1px solid #CDD1DA;
	border-radius: 50%;
	background: #FFF;
}
.radio + label:after {
	content: '';
	position: absolute;
	top: 1px;
	left: 4px;
	width: 16px;
	height: 16px;
	border-radius: 50%;
	background: #9FD468;
	box-shadow: inset 0 1px 1px rgba(0,0,0,.5);
	opacity: 0;
	transition: .2s;
}
.radio:checked + label:after {
	opacity: 1;
}
.radio:focus + label:before {
	box-shadow: 0 0 0 3px rgba(255,255,0,.7);
}		
	

</style>
</head>

<body>
    <!--[if lt IE 8]>
    <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->  
    <!-- Body main wrapper start -->
    <div class="wrapper">
        <!-- Start of header area -->
 
          
			 <?php include('header.php');?>
     
        <!-- mobile-menu-area start -->
       
        <!-- End of header area -->
         <?php if ($totalrow_doca2>0){ ?>
            <div class="container"> 
               <div class="row "
   <div class="panel panel-info">
  <div class="panel-heading">Документы к курсу</div>
  <div class="panel-body">
     	  <?php do { ?>
    <div class="input-group">
		  <span class="input-group-addon">
             <i class="icofont icofont-book"></i>
      </span>
      <input type="text" class="form-control" value="<?php echo $row_doca2['nazv'];?>">
        <span class="input-group-addon info">
      <a href="docs/<?php echo $row_doca2['path'];?>" target="_blank"> <i class="icofont icofont-learn"></i>скачать
            </span></a>
    </div> <br>
	 <?php } while ($row_doca2 =  /* fixed MMiC */ mysqli_fetch_assoc($doca2));
			 mysqli_data_seek($doca2,1);				  
		  ?></div>
</div>  </div>  </div>
	  <?php }?>     
        <!-- Start page content -->
        <section class="news-page-area ptb-110">
            <div class="container"> <h3 style="text-align: center"><?php echo $row_url['nazv']; ?> </h3>
                <div class="row ">
                 
			
			
                   
                               
         <div class="col-md-4 col-lg-4 col-sm-4">
                           <a class="button small button-black mb-20" href="#" id="adds" data-cena="<?php echo $row_url['cena']; ?>"><span>Добавить студента </span><i class="fa fa-plus"></i></a>
                    </div>
					 <div class="col-md-4 col-lg-4 col-sm-4 text-center">
                   
                        </div>
	 <div class="col-md-4 col-lg-4 col-sm-4 text-center">
                      <div>
                             <h3>  Цена <span class="counter cnt-two" id="cena" style="color: black"><?php echo $row_url['cena']*$totalRows_urla; ?></span></h3></div>
                            
                        </div>
                 
              <div> 
					<div class="table-responsive" style="overflow-x: visible;"> 
					 
                    <table class="table table-bordered ">
  <thead>
    <tr>
      <th>Фио</th>
      <th>Email</th>
      <th>Пароль</th>
		 <th> <button type="button" id="popover-event-hidden" class="btn btn-sm btn-info" data-toggle="popover" style="padding: 0px 18px;">Действия <i class="icofont icofont- icofont-question-circle"></i></button>  </th>
    </tr>
  </thead>
  <tbody>
  <?php  $i=0;do { $i++; ?> 
    <tr data-num="<?php echo $row_urla['num'] ?>">
      <td data-name='fio'><?php echo $row_urla['fio'] ?></td>
      <td data-name='mail' id="mail<?php echo $row_urla['num'] ?>" ><?php echo $row_urla['mail'] ?></td>
		 <td data-name='passw' data-ps="1"><?php echo $row_urla['passw'] ?></td>
		<td><div class="btn-group">
			<?php if ( $row_urla['act']==1){?> <button type="button" class="btn btn-info" style="padding: 0px 18px;"><i class="icofont icofont-check-circled"></button></i><?php }else{?>
  <button type="button" class="btn btn-danger deluser" data-num="<?php echo $row_urla['num'] ?>" data-cena="<?php echo $row_url['cena']; ?>" style="padding: 0px 18px;" ><span class="icofont icofont-delete " aria-hidden="true"></span></button>
		<?php }?>	<?php if ($row_urla['rss']<1){?>
 <button type="button" class="btn btn-primary pod" data-num="<?php echo $row_urla['num'] ?>" style="padding: 0px 18px;"><span class="icofont icofont-mail" aria-hidden="true"></span></button>
<?php } ?>
<?php if ( $row_urla['zav']==1){?> <button type="button" class="btn btn-success" style="padding: 0px 18px;"><i class="icofont icofont-notification"></i></button><?php }?>			
</div></td>
    </tr>
   
         
					
					
					
              
               
            
            <?php } while (    $row_urla =  /* fixed MMiC */ mysqli_fetch_assoc($urla)); ?>
					
			  </tbody>
</table>
         		
     </div>          
                 
                 
                </div>
              
            </div>
        </section>
        <!-- End page content -->
        <!-- Start footer area -->
        
        <!-- End footer area -->
        <!-- start scrollUp
        ============================================ -->
        <div id="toTop">
            <i class="fa fa-chevron-up"></i>
        </div>
    </div>
    <!-- Body main wrapper end -->
    
    
    
    
    <!-- Placed js at the end of the document so the pages load faster -->
    <!-- jquery latest version -->
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) --> 
<script src="js/jquery-1.11.3.min.js"></script> 
<!-- Include all compiled plugins (below), or include individual files as needed --> 
<script src="js/bootstrap.js"></script>
	<script type="text/javascript">
		fl=0;
	function buildElement(tagName, props) {
  var element = document.createElement(tagName);
  for (var propName in props) element[propName] = props[propName];
  return element;
}
 
function submit(link, props) {
  var form = buildElement('form', {
      method: 'post',
      action: link
    });
  for (var propName in props) form.appendChild(
    buildElement('input', {
      type: 'hidden',
      name: propName,
      value: props[propName]
    })
  );
  form.style.display = 'none';
  document.body.appendChild(form);
  form.submit();
}
	function gen_password(len){
    var password = "";
    var symbols = "0123456789";
    for (var i = 0; i < len; i++){
        password += symbols.charAt(Math.floor(Math.random() * symbols.length));     
    }
    return password;
}
	$(function() {
		
		 $('#popover-event-hidden').popover({
    title: 'Подсказки',
	html:true,
    content: '<span class="icofont icofont-delete" aria-hidden="true"  style="color: red"></span>- удалить<br><span class="icofont icofont-mail" aria-hidden="true"  style="color: blue"></span>-отправить на почту<br><i class="icofont icofont-check-circled"  style="color: blue"></i>-слушатель подтвержден<br><i class="icofont icofont-notification" style="color: green"></i>-закончил обучение',
    trigger: 'hover',
    placement: 'top'
  });
		$('body').on('click','.bca',function(e){
			 e.preventDefault();
		//	console.log($("#spec [value='"+$(this).data('num')+"']"));
		
				$("#spec").attr("selected", "");
			$("#spec [value='"+$(this).data('num')+"']").attr("selected", "selected");
			
			    elementClick = "#top";
			$("#spec").trigger("chosen:updated");
  destination =$("#top").offset().top;
	//		console.log(destination);
   $("html:not(:animated),body:not(:animated)").animate({scrollTop: destination -150}, 1100);

			
			
    });
	$('body').on('click','td',function(){
		if ($(this).data('name')==undefined) return;
			if (fl==0){
			fl=1;
				console.log($(this));
	//	console.log($('#p'+$(this).prop('name')));
				if ($(this).data('tip')==undefined)tip="text"; else tip= $(this).data('tip');
				if ($(this).data('ps')!=undefined)$(this)[0].innerHTML='<div class="input-group"><input type="'+tip+'" class="form-control" value="'+$(this)[0].innerText+'" id="textfield">	  <span class="input-group-addon info"><a href="#" class="shw" id="btg" style="color: #3875d7;"><span class="icofont icofont-tick-mark" aria-hidden="true"></span></a></span><span class="input-group-addon info"><a href="#" class="shw" id="btgp" style="color: #e04a4a;">создать пароль</a></span></div>'; else
$(this)[0].innerHTML='<div class="input-group"><input type="'+tip+'" class="form-control" value="'+$(this)[0].innerText+'" id="textfield">	  <span class="input-group-addon danger"><a href="#" class="shw" id="btg" style="color: #3875d7;"><span class="icofont icofont-tick-mark" aria-hidden="true"></span></a></span></div>'
			
			}
		});
	$('body').on('click', '#btg',function(e){
			e.preventDefault();
			num=$(this).parent().parent().parent().parent().data('num');
		tr=$(this).parent().parent().parent().parent();
						name=$(this).parent().parent().parent().data('name');
				val=$(this).parent().parent().children().first().val();
		
			par=	$(this).parent().parent().parent();
			$(par).children().remove();$(par).append(val);
	
			$.post('urlico.php', {'upd':'1', 'num' :num,'name':name,'val':val},
		function(data) {
			fl=0;
	$(tr).data('num',data);
		});});	
		
	$('body').on('click', '.deluser',function(e){
			e.preventDefault();
			num=$(this).data('num');
		cena=$(this).data('cena');
		xx=parseInt($('#cena').text());
		xx=xx-cena;$('#cena').text(xx);
		if (num=-1)num=$(this).parent().parent().parent().data('num');
		console.log($(this).parent().parent().parent());
		$(this).parent().parent().parent().remove();
		
		
			$.post('urlico.php', {'del':'1', 'num' :num});		
		
		});		
		
		$('body').on('click', '.pod',function(e){
			e.preventDefault();
			num=$(this).data('num');
			
			var pattern = /^[a-z0-9_-]+@[a-z0-9-]+\.[a-z]{2,6}$/i;
			mail=$('#mail'+num).text();
			console.log(mail);
			if(mail == ''){alert('Поле почты пустое');return;}
			 if(mail.search(pattern) != 0){alert('Поле почты неправильное');return;}
		$(this).hide();
			$.post('urlico.php', {'pod':'1', 'num' :num});		
		
		});			
		
		
		
	$('body').on('click', '#btgp',function(e){
			e.preventDefault();
		$('#textfield').val(gen_password(10));
		});		
			
			
	$('#adds').click( function(e) {
		e.preventDefault();
		cena=$(this).data('cena');
		xx=parseInt($('#cena').text());
		xx=xx+cena;$('#cena').text(xx);
		pass=" <div class='btn-group'><button type='button' class='btn btn-danger deluser' data-num='-1' data-cena='<?php echo $row_url['cena']; ?>' style='padding: 0px 18px;'><span class='icofont icofont-delete' aria-hidden='true'></span></button></div>";
		   $(" <tr data-num='-1'><td data-name='fio' >Введите ФИО</td><td data-name='mail'>Введите почту</td><td  data-name='passw' data-ps='1'></td><td>"+pass+"</td></tr>").insertAfter($("tr:first"));

});	
		
		});	
	</script>

</body>

</html>