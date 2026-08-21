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

 /* fixed MMiC */ mysqli_select_db(DB::$link, $database_testmed);
$query_spec = "SELECT 
  `tm_typsv`.`num` AS `tnum`,
  `tm_typsv`.`nazv`,
  `tm_typsv`.`typ`,
  `tm_user_sv`.`num` AS `unum`,
  `tm_user_sv`.`value`,
  `tm_user_sv`.`inn`
FROM
  `tm_typsv`
  LEFT OUTER JOIN `tm_user_sv` ON (`tm_typsv`.`num` = `tm_user_sv`.`tm_typsv`)
WHERE
  `tm_user_sv`.`inn` = ".$_SESSION['MM_Username1']."
  UNION
SELECT 
  `tm_typsv`.`num` AS `tnum`,
  `tm_typsv`.`nazv`,
  `tm_typsv`.`typ`,
  0 AS `unum`,
  0 AS `value`,
  0 AS `inn`
FROM
  `tm_typsv`
WHERE
  `tm_typsv`.`num` NOT IN (SELECT 
 `tm_user_sv`.`tm_typsv`
FROM
  `tm_user_sv`
WHERE
  `tm_user_sv`.`inn` = ".$_SESSION['MM_Username1'].")";
$spec =  /* fixed MMiC */ DB::Query($query_spec, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_spec =  /* fixed MMiC */ mysqli_fetch_assoc($spec);
$totalRows_spec =  /* fixed MMiC */ mysqli_num_rows($spec);

$query_spec = "SELECT 
  `tm_user`.`fio`,
  `tm_user`.`mail`,
  `tm_user`.`num`
FROM
  `tm_user`
WHERE
  `tm_user`.`num` = ".$_SESSION['MM_Username1'];
$spec1 =  /* fixed MMiC */ DB::Query($query_spec, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_spec1 =  /* fixed MMiC */ mysqli_fetch_assoc($spec1);
$totalRows_spec1 =  /* fixed MMiC */ mysqli_num_rows($spec1);


?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Отделение допольнительного образования ЗМУ</title>

<!-- Bootstrap -->
<link rel="stylesheet" href="css/bootstrap.css">
<link rel="stylesheet" href="fancybox/jquery.fancybox.css" type="text/css" media="screen" />
<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<?php include("header.php");?>

<!-- HEADER -->
<header>
 
</header>
<!-- / HEADER --> 
<section>
 <div class="container">
<div class="btn-group">
      <?php { ?>
        
      <div class="row">
      <div class="well">
        <h3 class="text-center">Данные пользователя</h3>
        <div class="card-container">
            <dv class="card card-shadow col-xs-10 col-xs-offset-1 col-md-8 col-md-offset-2 reveal">
        <form action="new_user.php" class="form-horizontal" method="post" enctype="multipart/form-data" >
          <div class="form-group">
            <label for="pricefrom" class="control-label">ФИО</label>
            <div class="input-group">
               <span class="input-group-btn">
             <input type="button" class="btn btn-primary" value="изменить">
             
      </span>

              <input type="text" class="form-control" id="pricefrom" placeholder="Фамилия Имя Отчество" aria-describedby="basic-addon1" name="fio" readonly value="<?php echo $row_spec1['fio']; ?>">
            </div>
                                     <label for="pricefrom" class="control-label">EMAIL</label>
            <div class="input-group">
               <span class="input-group-btn">
             <input type="button" class="btn btn-primary" value="изменить">
             
      </span>
              <input type="email" placeholder="user@gmail.com" class="form-control" id="pricefrom" aria-describedby="basic-addon1" name="mail" readonly value="<?php echo $row_spec1['mail']; ?>">
            </div>
              
              
             <?php do { ?>

                                    
                                     <?php if ($row_spec['typ']==0){?>
                                     <label for="pricefrom" class="control-label"><?php echo $row_spec['nazv']; ?></label>
            <div class="input-group">
             <span class="input-group-btn">
             <input type="button" class="btn btn-<?php if(($row_spec['unum']=='0') ){ echo "danger";} else {echo "primary";} ?>" value="изменить">
             
      </span>
              <input list="data<?php echo $row_spec['tnum']; ?>" type="text" class="form-control" id="<?php echo $row_spec['unum']; ?>" aria-describedby="basic-addon1" name="<?php echo $row_spec['tnum']; ?>" readonly value="<?php echo $row_spec['value']; ?>">
           <?php 
				
			$sql="SELECT DISTINCT
  `tm_user_sv`.`value`
FROM
  `tm_user_sv`
  INNER JOIN `tm_typsv` ON (`tm_user_sv`.`tm_typsv` = `tm_typsv`.`num`)
WHERE
  `tm_typsv`.`poi` = 1  and `tm_user_sv`.`tm_typsv` = ".$row_spec['tnum'];
				$spec11 =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));

				?>
      <datalist id="data<?php echo $row_spec['tnum']; ?>">
       <?php do {?>
                                                             
			<option value="<?php echo $ro1['value'] ?>"/>
			<?php } while ($ro1 = mysqli_fetch_assoc($spec11))?>
			
		</datalist>

                                   
              </div><?php } ?>
                                   
                                    <?php if ($row_spec['typ']==2){?>
                                     <label for="pricefrom" class="control-label"><?php echo $row_spec['nazv']; ?></label>
            <div class="input-group">
               <span class="input-group-btn">
             <input type="button" class="btn btn-<?php if(($row_spec['unum']=='0') ){ echo "danger";} else {echo "primary";} ?>" value="изменить">
             
      </span>
              <input type="date" class="form-control" id="<?php echo $row_spec['unum']; ?>" aria-describedby="basic-addon1" name="<?php echo $row_spec['tnum']; ?>" readonly  value="<?php echo $row_spec['value']; ?>">
            </div><?php } ?>
            
              <?php if ($row_spec['typ']==3){?>
                                     <label for="pricefrom" class="control-label"><?php echo $row_spec['nazv']; ?></label>
            <div class="input-group">
 <span class="input-group-btn"><input type="button" class="btn btn-<?php if(($row_spec['unum']=='0') ){ echo "danger";} else {echo "primary";} ?>" value="изменить"></span>
               <input type="file" class="form-control" id="<?php echo $row_spec['unum']; ?>" aria-describedby="basic-addon1" name="<?php echo $row_spec['tnum']; ?>" accept="image/jpeg" value="<?php echo $row_spec['value']; ?>" style="pointer-events: none">
               <span class="input-group-btn"><input id="bt<?php echo $row_spec['unum']; ?>" type="button" class="btn btn-primary fancybox-manual-c"  name="usrimg/<?php echo $row_spec['value']; ?>" value="cm">
               </span>
 				
                       </div>
                       <a class="fancyimage" data-fancybox-group="group" title="<?php echo $row_spec['nazv']; ?>" href="usrimg/<?php echo $row_spec['value']; ?>">  
				</a> <?php } ?>
            
            
             <?php if ($row_spec['typ']==1){?>
                                     <label for="pricefrom" class="control-label"><?php echo $row_spec['nazv']; ?></label>
            <div class="input-group">
 <span class="input-group-btn">
             <input type="button" class="btn btn-<?php if(($row_spec['unum']=='0') ){ echo "danger";} else {echo "primary";} ?>" value="изменить">
             
      </span>
              <input type="number" class="form-control" id="<?php echo $row_spec['unum']; ?>" aria-describedby="basic-addon1" min="15" max="100" name="<?php echo $row_spec['tnum']; ?>" readonly  value="<?php echo $row_spec['value']; ?>">
            </div><?php } ?>
            
            <?php } while ($row_spec =  /* fixed MMiC */ mysqli_fetch_assoc($spec)); ?>
        </div>
          <p class="text-center">

</p>
        </form>
        </div></div>
      </div>
      <hr>
    <h3 class="text-center">&nbsp;</h3>
</div>
<?php } ?>
	</div>
	</div>
</section>
<!--  SECTION-1 -->
<div class="well"> </div>

<!-- FOOTER -->
<footer class="text-center">
  <div class="container">
    <div class="row"></div>
  </div>
</footer>
<!-- / FOOTER --> 
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) --> 
<script src="js/jquery-1.11.3.min.js"></script> 
<!-- Include all compiled plugins (below), or include individual files as needed --> 
<script src="js/bootstrap.js"></script>

<script src="fancybox/jquery.fancybox.pack.js"></script>
<script type="text/javascript">  
$(function() { 
	  $("a.fancyimage").fancybox(); 
$(".fancybox-manual-c").click(function() {
console.log($(this)[0].name);
				$.fancybox.open($(this)[0].name)});
	 
    $(".btn").not(".fancybox-manual-c").click(function(){
    	if ($(this).val()=='изменить'){$(this).val("сохранить") ;
				arr=$(this).parent().parent().find('input').not('.btn');
arr[0].readOnly=false;
arr[0].style="pointer-events:auto";
									  }
		else {$(this).val("изменить") ;
				arr=$(this).parent().parent().find('input').not('.btn');
arr[0].readOnly=true;
res=arr[0].name;
if (arr[0].type=="file")	{
	var fd = new FormData();
	fd.append( 'file', arr[0].files[0] );
    fd.append('num','<?php echo $_SESSION['MM_Username']?>');
	fd.append('pole',res);
	fd.append('unum',arr[0].id);
$.ajax({
  url: 'edit_user.php',
  data: fd,
  processData: false,
  contentType: false,
  type: 'POST',
  success: function(data){
   var aa = data.split('|');
	arr[0].id=aa[0];
	  
	//  console.log(tmp+":"+arr[0].id);
	//  console.log(">>"+aa[1]);
	  $("#bt"+arr[0].id)[0].name="usrimg/"+aa[1];
//ds=$(this).parent().parent().find('.fancybox-manual-c');
	

	//  $(this).parent().parent().find('.btn.btn-default.fancybox-manual-c').name=;  
  }
});	
	
	
}	else {	  
	
	 $.post('edit_user.php', {pole:res,nam:arr[0].value,'num':<?php echo $_SESSION['MM_Username1']?>,unum:arr[0].id},
							  function(data) {
								  var aa = data.split('|');
	arr[0].id=aa[0];
	  
							  });
}

			 
			 }
	
    
	});        
    
});  
</script>
</body>
</html>
<?php
 /* fixed MMiC */ mysqli_free_result($spec);
?>
