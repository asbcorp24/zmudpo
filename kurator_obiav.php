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
function make_seed()
{
    list($usec, $sec) = explode(' ', microtime());
    return $sec + $usec * 1000000;
}
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

//echo $query_spec;

if ((isset($_POST["del"])) && ($_POST["del"] == "1")) {
$sql="delete from `tm_obiav` where id=".$_POST["num"];	
DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
	//echo $sql;
	
	exit(0);	
	
}


if (isset($_POST['inso'])){
	$ddat=GetSQLValueString($_POST['ddat'],"date");
$expir=GetSQLValueString($_POST['expir'],"date");
$tex=GetSQLValueString($_POST['tex'],"text");
$spec=GetSQLValueString($_POST['spec'],"int");
$grupp=GetSQLValueString($_POST['grupp'],"int");
$sql="INSERT INTO `tm_obiav` (`id`, `tex`, `dat`, `expir`, `kurator`, `spec`, `grupp`) VALUES (NULL, $tex,$ddat, $expir, $user, $spec, $grupp)";	
$result1 =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));	
echo '<tr>
		  <td></td>
        <td>'.$_POST['ddat'].'</td>
        <td style="font-size: small">'.$_POST['tex'].'</td>
        <td>'.$_POST['expir'].'</td>
        <td></td>
        <td></td>
        <td></td>
      </tr>';
exit();	
}


$sql="SELECT DISTINCT 
  `tm_spec`.`num`,
  `tm_spec`.`nazv`
FROM
  `tm_spec`
WHERE
  `tm_spec`.`actiiv` = 1 AND 
  `tm_spec`.`kr` > 0";
$result1 =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row = mysqli_fetch_assoc($result1);

$sql="SELECT 
  `tm_grupp`.`id`,
  `tm_grupp`.`nazv`
FROM
  `tm_grupp`";
$result2 =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row2 = mysqli_fetch_assoc($result2);




$sql="SELECT 
  `tm_obiav`.`id`,
  `tm_obiav`.`tex`,
  `tm_obiav`.`dat`,
  `tm_obiav`.`expir`,
  `tm_obiav`.`kurator`,
  `tm_obiav`.`spec`,
  `tm_obiav`.`grupp`,
  `tm_spec`.`nazv` as snazv,
  `tm_grupp`.`nazv` as gnazv,
  `tm_prepod`.`fio`
FROM
  `tm_obiav`
  LEFT OUTER JOIN `tm_spec` ON (`tm_obiav`.`spec` = `tm_spec`.`num`)
  LEFT OUTER JOIN `tm_grupp` ON (`tm_obiav`.`grupp` = `tm_grupp`.`id`)
  INNER JOIN `tm_prepod` ON (`tm_obiav`.`kurator` = `tm_prepod`.`num`)
ORDER BY
  `tm_obiav`.`dat`";
$result3 =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row3 = mysqli_fetch_assoc($result3);
	
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
  <style>
        .thumb img {
            -webkit-filter: grayscale(0);
            filter: none;
            border-radius: 5px;
            background-color: #fff;
            border: 1px solid #ddd;
            padding: 10px;
        }

        .thumb img:hover {
            -webkit-filter: grayscale(1);
            filter: grayscale(1);
        }

        .thumb {
            padding: 5px;
        }
    </style> 

</head>
<body>
	<div id="myModalBox" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- Заголовок модального окна -->
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title">Объявления</h4>
      </div>
      <!-- Основное содержимое модального окна -->
      <div class="modal-body">
       <form action="kurator_obiav.php" method="post" enctype="multipart/form-data" name="fmm" id="fmm" autocomplete="off">
	 <div class="form-group"> <label for="nazv">Дата объявления</label> <input name="ddat" class="form-control" id="nazv" placeholder="Дата" type="date"> </div>
		    <div class="form-group"> <label for="nazv">Дата окончания</label> <input name="expir" class="form-control" id="nazv" placeholder="Дата" type="date" required> </div>
		  <div class="form-group"> <label for="comment">Текст объявления</label> <textarea name="tex" type="number" min="0" class="form-control" id="comment" placeholder="содержимое"></textarea> </div>
		  		    <div class="form-group"> <label for="nazv">Специальности</label> 
						<input type="hidden" value="ins" name="inso">
		   <select name="spec" class="form-control">
						<option value="-1">Все</option>
		<?php	   do {  ?>

       <option value="<?php echo $row['num'];?>"><?php echo $row['nazv'];?></option>
			   <?php 
} while ($row = mysqli_fetch_assoc($result1)	);?>
						</select>
		   
		   </div>
		   
		 <input name="grupp" type="hidden" value="-1">
		 
		  </form>
		  
      </div>
      <!-- Футер модального окна -->
      <div class="modal-footer">
        <button type="button" class="btn btn-info sav">сохранить</button>
       
      </div>
    </div>
  </div>
</div>	
<?php include("header.php");?>

<hr>
	<!--панель скопирования элементов-->

	
	
	
	
 <div class="container">
  <div class="row text-center">
	
     <?php include("kb.php");?>  

	</div>  
	</div>  
<div class="container">
 <div class="row ">
    <div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">обьявление</h3>
  </div>
  <div class="panel-body">
    <button class="btn btn-info" id="add">Добавить</button>
  </div> 

  <table border="1" class="table table-bordered table-striped" id="obia">
    <tbody>
      <tr>
		  <th></th>
        <th scope="col">Дата</th>
        <th scope="col">Текст</th>
        <th scope="col">Окончание</th>
        <th scope="col">Автор</th>
        <th scope="col">Спец</th>
        <th scope="col">груп</th>
      </tr>
		<?php	   do {  ?>

      <tr>
		  <td>
			 <?php if($row3['kurator']==$user){?> 
			  <button data-id="<?php echo $row3['id'];?>" class="sho"><i class="glyphicon glyphicon-minus"></i></button>
		  <?php }?>
		  </td>
        <td><?php echo $row3['dat'];?></td>
        <td style="font-size: small"><?php echo $row3['tex'];?></td>
        <td><?php echo $row3['expir'];?></td>
        <td><?php echo $row3['fio'];?></td>
        <td><?php echo $row3['snazv'];?></td>
        <td><?php echo $row3['gnazv'];?></td>
      </tr>
			   <?php 
} while ($row3 = mysqli_fetch_assoc($result3)	);?>
    
     
    </tbody>
  </table>
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

		$('#add').on('click',function(){	
				deff=$(this).data('id');
			$("#myModalBox").modal('show');
	//$.post('kurator_add.php', {'izma':1,'id':deff},function(data) {
			//console.log(data);
//			$('#dfm').html(data);
///	$('.sluch').data('id',deff);
	
	//		});
				});
		
			$('.sav').on('click',function(){	
				deff=$(this).data('id');
			
			$.ajax({	
			url: $("#fmm").attr('action'),
			data: $("#fmm").serialize(),
			type: 'POST',
			success: function(data){
					$('#obia').append(data);
				$("#myModalBox").modal('hide');
			}
		});

	//$.post('kurator_add.php', {'izma':1,'id':deff},function(data) {
			//console.log(data);
//			$('#dfm').html(data);
///	$('.sluch').data('id',deff);
	
	//		});
				});
	 $('.sho').on('click', function() {
        th = $(this);
        num = $(this).data('id');
        
            $.post("kurator_obiav.php", {
                'del': 1,
                'num': num
            }, function(data) {
                th.parent().parent().hide();
                // alert( "success" );
            }); });
	


});
//	/(".hello").clone().appendTo(".container");
	
	</script>
</body>
</html>
<?php
 /* fixed MMiC */ mysqli_free_result($spec);
?>