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
 $theValue=htmlspecialchars($theValue);
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


$sql="SELECT 
  `tm_user`.`num`,
  `tm_user`.`fio`,
  `tm_user`.`spec`,
  `tm_user`.`passw`,
  `tm_user`.`act`,
  `tm_user`.`mail`,
  `tm_user`.`mail_pod`,
  `tm_user`.`rss`,
  `tm_user`.`data_nach`,
  `tm_user`.`zav`,
  `tm_user`.`urlico`,
  `tm_user`.`ur_parent`
FROM
  `tm_user`
WHERE
  `tm_user`.`num` = ".$_SESSION['MM_Username1'];

$tza =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_tza =  /* fixed MMiC */ mysqli_fetch_assoc($tza);
$fl=1;
if ($row_tza['zav']==0)header("Location: nmo.php"); 
if (isset($_POST['addr'])){
$sql='delete from `tm_addr_otprav` where inn='.$_SESSION['MM_Username1'];
	DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$SQL = sprintf("INSERT INTO `tm_addr_otprav` (`num`, `inn`, `oblast`, `rayon`, `gorod`, `ulica`, `dom`, `kv`, `Fam`, `Name`, `Otch`, `ind`, `comment`) VALUES (NULL,%s, %s, %s, %s,%s, %s, %s,%s, %s, %s, %s,%s)",
					  GetSQLValueString($_SESSION['MM_Username1'], "int"),
                       GetSQLValueString($_POST['oblast'], "text"),
					  GetSQLValueString($_POST['rayon'], "text"),
                       GetSQLValueString($_POST['gorod'], "text"),
                       GetSQLValueString($_POST['ulica'], "text"),
                       GetSQLValueString($_POST['dom'], "text"),
					    GetSQLValueString($_POST['kv'], "text"),
					    GetSQLValueString($_POST['Fam'], "text"),
					    GetSQLValueString($_POST['Name'], "text"),
                       GetSQLValueString($_POST['Otch'], "text"),
					 GetSQLValueString($_POST['ind'], "text"),
					    GetSQLValueString($_POST['comment'], "text"));
//	echo $SQL;
	DB::Query($SQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
	
	$sql="UPDATE `tm_user` SET `post`= 2 WHERE `num` = ".$_SESSION['MM_Username1'];	
	DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
	exit();
}
if (!isset($_POST['fm'])){
if (isset($_POST['radio'])){
	if ($_POST['radio']==1){
$sql="UPDATE `tm_user` SET `post`= 1 WHERE `num` = ".$_SESSION['MM_Username1'];	
	DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
		$fl=0;
		exit();
	}
	if ($_POST['radio']==2){
$sql="UPDATE `tm_user` SET `post`= 2 WHERE `num` = ".$_SESSION['MM_Username1'];	
	DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
		$fl=0;
			exit();
	}}
	
}

?>
<!doctype html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Курсы</title>
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
    <script src="js/vendor/modernizr-2.8.3.min.js"></script>
	<style>
	input::placeholder {
  color: #FFFFFF;
  font-size: 1.2em;
  font-style: italic;
}
		.shadow {
    margin-top: 50px;
    box-shadow: 0 0 30px black;
    padding:0 15px 0 15px;
}
		.shadow-sm {
    margin-top: 50px;
    box-shadow: 0 0 10px black;
    padding:0 15px 0 15px;
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
		  <header class="header-area">
          
			 <?php include('header.php');?>
        </header>
        
        <!-- mobile-menu-area start -->
        
        <!-- mobile-menu-area end -->
        <!-- End of header area -->
		<?php if ($_POST['radio']==1){?>
        <section class="top-courses pt-110 pb-80">
            <div class="container">
				<div class="row shadow-sm " style="min-height: 150px" id="z"  >
					<div class="col-md-12 col-lg-12 col-sm-12">
                    <span style="font-size: 15pt;text-align: center"><?php echo $row_tza['fio'];?> вы выбрали личное получение документа об образовании, как только оригинал документа будет готов, наши менеджеры свяжутся с вами</span>
                    </div><br>
				 <div  style="padding-top: 60px;align-content:center">
                                        <a class="button " href="nmo.php" id="zpost">
                                            <span>Вернуться</span>
											
                                        </a>
                                    </div>
				</div>
              
                
            </div>
        </section><?php } ?>
        <!-- Start page content -->
		
		
        <section class="top-courses pt-110 pb-80">
            <div class="container">
				<div class="row shadow " style="background:url('images/bg/11.jpg')" id="gitAddress" >
					<div class="col-md-12 col-lg-12 col-sm-12">
                      <h3 style="color: aliceblue">Заполнение адреса доставки</h3>
                    </div>
				 <form class="ordering" method="post" id="fz"> 
				<div class="col-md-4 col-lg-4 col-sm-6">
					<input type="hidden" name="addr" value="1">
                       <div class="orderby-wrapper">
										<label>Фамилия</label>
                                    <input name="Fam" type="text" required="required" placeholder="фамилия"  style="color: white" value="" id="Fam">
                                    </div> 
                    </div>
							<div class="col-md-4 col-lg-4 col-sm-6">
                       <div class="orderby-wrapper">
										<label>Имя</label>
                                     <input name="Name" type="text" required="required"  style="color: white" value="" id="Name">
                                    </div> 
                    </div>
							<div class="col-md-4 col-lg-4 col-sm-6">
                       <div class="orderby-wrapper">
									  <label>Отчество</label>
                                    <input name="Otch" type="text" required="required"  style="color: white" value="" id="Otch">
                                    </div> 
                    </div>
					 
					 <div class="col-md-4 col-lg-3 col-sm-6">
                       <div class="orderby-wrapper">
										<label>Область</label>
                                         <select name="oblast" class="orderby2 input-sm" id="id1" data-id="1" style="color: white;background: transparent none repeat scroll 0 0;
    border: 1px solid #c1c1c1;" >
											
                                      
             
            
        
                                    </select>
									
                                    </div> 
                    </div>
							<div class="col-md-4 col-lg-3 col-sm-6">
                       <div class="orderby-wrapper">
									<label>Район</label>
                                         <select name="rayon" class="orderby2 input-sm" id="id2" data-id="2"  style="color: white;background: transparent none repeat scroll 0 0;
    border: 1px solid #c1c1c1;" >
                                      
            
            
        
                                    </select>
                                    </div> 
                    </div>
					 
					 
							<div class="col-md-4 col-lg-3 col-sm-6">
                       <div class="orderby-wrapper">
									 <label>Населенный пункт</label>
                                         <select name="gorod" class="orderby2 input-sm" id="id3" data-id="3" style="color: white;background: transparent none repeat scroll 0 0;
    border: 1px solid #c1c1c1;">
                                      
              
            
        
                                    </select>
                                    </div> 
                    </div>
					 	<div class="col-md-4 col-lg-3 col-sm-6">
                       <div class="orderby-wrapper">
									<label>Улица</label>
                                         <select name="ulica" class="orderby2 input-sm" id="id4" data-id="4"  style="color: white;background: transparent none repeat scroll 0 0;
    border: 1px solid #c1c1c1;" >
                                      
              
            
        
                                    </select>
                                    </div> 
                    </div>
					 
					  <div class="col-md-4 col-lg-4 col-sm-6">
                       <div class="orderby-wrapper">
					     <label>Дом</label>
                                    <input name="dom" type="text"  style="color: white" value="" size="30"  length="20" id="dom">
                                    </div> 
                    </div>
					 <div class="col-md-4 col-lg-4 col-sm-6">
                       <div class="orderby-wrapper">
					     <label>квартира</label>
                                    <input name="kv" type="text"  style="color: white" value="" size="30"  length="20" id="kv">
                                    </div> 
                    </div>
					 
					 <div class="col-md-4 col-lg-4 col-sm-6">
                       <div class="orderby-wrapper">
									  <label>Индекс</label>
                                    <input type="text" value=""  style="color: white" name="index" readonly id="ind">
                                    </div> 
                    </div>
					 
					 <div class="col-md-4 col-lg-4 col-sm-6">
                       <div class="orderby-wrapper">
									  <label>Комментарий</label>
						   <textarea name="comment" id="comment"  style="color: white;background: transparent none repeat scroll 0 0;
    border: 1px solid #c1c1c1;"></textarea>
                                    
                                    </div> 
					  <div class="col-md-12 col-lg-12 col-sm-12">
					 	 <div class="orderby-wrapper">
                               
									
								
									
                                    <div class="chosen-submit">
									
                                        <a class="button " href="#" id="zakaz">
                                            <span>Заказать</span>
											
                                        </a>
                                    </div>
									
                                    </div>
						     </div>
					 </form>
				</div>
              
                
            </div>
        </section>
        <!-- End page content -->
        <!-- Start footer area -->
         <?php include('footer.php');  ?>
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
<script src="js/jquery-1.11.3.min.js"></script> 
<!-- Include all compiled plugins (below), or include individual files as needed --> 
<script src="js/bootstrap.js"></script>
    <!-- ajax-mail JS
    ============================================ -->		
   
	 <script type="text/javascript">
      var base = "https://GitDataOrg.github.io/AddressRU/data/";
      var socrbase = {};
      function gitData(code, index,callback, id){
		  console.log(code, index,callback, id);
        $.ajax({
          type: "GET",
          url: base + code + ".json",
          dataType: "json",
          success: function(data) {
            if (code == "socrbase") {
              socrbase = data;
               if (callback) callback();
            } else {
              data.sort(sortObject);
				
				id=$('#'+id);
			//	console.log(id);
             $(id).prop('code',code == "00" ? "" : code);
				 $(id).prop('index',index);
        //   console.log(id);
		
                      for (i=0,c=data.length; i<c; ++i) {
						
                item = data[i];
                var option = document.createElement("option");
                name = item[1];
                if (socrbase[item[2]]) name += " " + socrbase[item[2]];
                option.value = item[0];
                option.text = name;
                option.setAttribute("postcode",item[3]); 
						   option.setAttribute("style", "color: black;"); 
					
			///	  console.log(option,item[3]);
                $(id).append(option);
              };
								
           
			//	 jQuery('.orderby').chosen({disable_search: true, width: "auto"});
              $(id).on('change',onSelect);
				 if (callback) callback();
              
            }
          },
          error: function(err){
            console.log("Error:", err);
          },
        });
      };
      function sortObject(a, b){
        return (a[1] < b[1]) ? -1 : 1;
      };
      function onSelect() {
		  obj=$(this);
	  console.log(obj);
        var index2 = obj.prop("index") || 0;
        var code = obj.prop("code") + obj.val();
		   
        var elems =$(".orderby2");
		 
        
       elems.each(function(index, elem){
		

         
        if ($(elem).data("id") > index2) {
		  //console.log( elem.parentNode);
        $(elem).empty();
}
        });
       asd=$(this).find('option:selected');
		 
      asd=asd.attr("postcode"); 
		  
		 if (asd!='undefined') $("#ind").val(asd);
        gitData(code, index2 + 1,null,"id"+(index2+1));
      };
      gitData("socrbase", 0,gitData("00",1,'','id1'),'');
		 $('#zakaz').click(function(e){
			 e.preventDefault; 
		

			
	$.post('pochta.php', {'oblast':$('#id1 :selected').html(),'rayon':$('#id2 :selected').html(),'gorod':$('#id3 :selected').html(), 'ulica':$('#id4 :selected').html(), 'Fam':$('#Fam').val(),'dom':$('#dom').val(),'kv':$('#kv').val(),'Name':$('#Name').val(), 'Otch':$('#Otch').val(), 'ind':$('#ind').val(), 'comment':$('#comment').val(),'addr':"1"},
		function(data) {
	$(location).attr('href','nmo.php');
	});

			 
		 });
    </script>

</body>

</html>