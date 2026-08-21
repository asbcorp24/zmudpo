<?php require_once('Connections/testmed.php');
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



if (isset($_POST['ul'])){
if ($_POST['ul']==1){
$query_fio = sprintf("SELECT * FROM tm_user WHERE spec = %s  and act=1 and urlico=1 ORDER BY fio ASC", GetSQLValueString($_POST['spec'], "int"));
$fio =  /* fixed MMiC */ DB::Query($query_fio, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));	
  $row_fio =  /* fixed MMiC */ mysqli_fetch_assoc($fio);
do {  
     echo    '<option value="'.$row_fio['num'].'">'.$row_fio['fio'].'</option>';
     
} while ($row_fio =  /* fixed MMiC */ mysqli_fetch_assoc($fio));	
exit;	
	
}	
if ($_POST['ul']==2){
$query_fio = sprintf("SELECT * FROM tm_user WHERE spec = %s  and act=1 and (urlico=0 or  urlico IS NULL)  ORDER BY fio ASC", GetSQLValueString($_POST['spec'], "int"));
$fio =  /* fixed MMiC */ DB::Query($query_fio, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));	
  $row_fio =  /* fixed MMiC */ mysqli_fetch_assoc($fio);
do {  
     echo    '<option value="'.$row_fio['num'].'">'.$row_fio['fio'].'</option>';
     
} while ($row_fio =  /* fixed MMiC */ mysqli_fetch_assoc($fio));	
exit;	
	
}	
}


$colname_fio = "-1";
if (isset($_GET['spec'])) {
  $colname_fio = $_GET['spec'];
}

if (isset($_COOKIE['uname'])) {
	$sql="SELECT 
  `tm_user`.`num`,`tm_spec`.`kr`
FROM
  `tm_user`
  INNER JOIN `tm_spec` ON (`tm_user`.`spec` = `tm_spec`.`num`)
WHERE
  `tm_spec`.`actiiv` = 1 AND  
  `tm_spec`.`num` = ".$colname_fio." AND
  `tm_user`.`num` = ".$_COOKIE["uname"];
  
  /* fixed MMiC */ mysqli_select_db(DB::$link, $database_testmed);
$fio =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
//$row_fio =  /* fixed MMiC */ mysqli_fetch_assoc($fio);
$totalRows_fio =  /* fixed MMiC */ mysqli_num_rows($fio);
	$row_fio =  /* fixed MMiC */ mysqli_fetch_assoc($fio);
  if ($totalRows_fio>0){
	$_SESSION['MM_Username1']=$_COOKIE["uname"];
	 
	 
	  
	 
	 
	 if ($row_fio['kr']!=0){ header("Location: testy.php");}else {header("Location: nmo.php");}
	  
	  
	
echo $row_fio['kr'];
}
}


 /* fixed MMiC */ mysqli_select_db(DB::$link, $database_testmed);
$query_fio = sprintf("SELECT * FROM tm_user WHERE spec = %s  and act=1 and (urlico=0 or  urlico IS NULL) ORDER BY fio ASC", GetSQLValueString($colname_fio, "int"));
$fio =  /* fixed MMiC */ DB::Query($query_fio, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
//$row_fio =  /* fixed MMiC */ mysqli_fetch_assoc($fio);
$totalRows_fio =  /* fixed MMiC */ mysqli_num_rows($fio);
?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$query_spec = "SELECT * FROM tm_spec where 	actiiv=1 ORDER BY dat ASC";
$spec =  /* fixed MMiC */ DB::Query($query_spec, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_spec =  /* fixed MMiC */ mysqli_fetch_assoc($spec);
$totalRows_spec =  /* fixed MMiC */ mysqli_num_rows($spec);



$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['user'])) {
  $loginUsername=$_POST['user'];
  $password=$_POST['passw'];
  $MM_fldUserAuthorization = "spec";
  $MM_redirectLoginSuccess = "testy.php";
	$MM_redirectLoginSuccess2 = "nmo.php";
  $MM_redirectLoginFailed = "errorlog.html";
  $MM_redirecttoReferrer = false;
   /* fixed MMiC */ mysqli_select_db(DB::$link, $database_testmed);
  	
  $LoginRS__query=sprintf("SELECT 
  `tm_user`.`num`,
  `tm_user`.`passw`,
  `tm_spec`.`kr`,`tm_spec`.`num` as spec
FROM
  `tm_user`
  INNER JOIN `tm_spec` ON (`tm_user`.`spec` = `tm_spec`.`num`) WHERE `tm_user`.`num`=%s AND passw=%s",
  GetSQLValueString($loginUsername, "int"), GetSQLValueString($password, "text")); 
   
  $LoginRS =  /* fixed MMiC */ DB::Query($LoginRS__query, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
 //  echo  $LoginRS__query;
	$loginFoundUser =  /* fixed MMiC */ mysqli_num_rows($LoginRS);

	$row_fio =  /* fixed MMiC */ mysqli_fetch_assoc($LoginRS);
	
  if ($loginFoundUser) {
   
    $loginStrGroup  =  /* fixed MMiC */$row_fio['spec'];// DB::result($LoginRS,0,'spec');
    
	if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
    //declare two session variables and assign them
    $_SESSION['MM_Username1'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	      
if (isset($_POST['chb'])){
	setcookie("uname", $loginUsername, time()+3600*24*10);  
	
}
    if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
	  
	   $dat=date("Y-m-d H:m:s");
	  $sql="INSERT INTO `tm_login_dat` (`num`, `user`, `dat`) VALUES (NULL, $loginUsername, '$dat')";
	   DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
	  
	  
	   if ($row_fio['kr']==0){ header("Location: " . $MM_redirectLoginSuccess );}else {header("Location: " . $MM_redirectLoginSuccess2 );}
    
  }
  else {
	  
   header("Location: ". $MM_redirectLoginFailed );
  }
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
  <meta charset="utf-8">
  <title>Вход в группу</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">
  <!-- Le styles -->
  <link href="bootstrap/css/bootstrap.css" rel="stylesheet">
  <style type="text/css">
      body {
        padding-top: 40px;
        padding-bottom: 40px;
        
      }
body {
    margin: 0;
    background-attachment: fixed;
    background-image: url(../2017_01.jpg);
    background-repeat: no-repeat;
    background-position: center center;
}
      .form-signin {
        max-width: 300px;
        padding: 19px 29px 29px;
        margin: 0 auto 20px;
        background-color: #fff;
        border: 1px solid #e5e5e5;
        -webkit-border-radius: 5px;
           -moz-border-radius: 5px;
                border-radius: 5px;
        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
           -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
                box-shadow: 0 1px 2px rgba(0,0,0,.05);
      }
      .form-signin .form-signin-heading,
      .form-signin .checkbox {
        margin-bottom: 10px;
      }
      .form-signin input[type="text"],
      .form-signin input[type="password"] {
        font-size: 16px;
        height: auto;
        margin-bottom: 15px;
        padding: 7px 9px;
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
	
	
	</style>
  <link href="bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
  <link href="css/bootstrap-select.min.css" rel="stylesheet">
  <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
  <!--[if lt IE 9]>
      <script src="../../Third Party Source Code/bootstrap/js/html5shiv.js"></script>
    <![endif]-->
  <!-- Fav and touch icons -->
  <link rel="apple-touch-icon-precomposed" sizes="144x144" href="bootstrap/ico/apple-touch-icon-144-precomposed.png">
  <link rel="apple-touch-icon-precomposed" sizes="114x114" href="bootstrap/ico/apple-touch-icon-114-precomposed.png">
  <link rel="apple-touch-icon-precomposed" sizes="72x72" href="bootstrap/ico/apple-touch-icon-72-precomposed.png">
  <link rel="apple-touch-icon-precomposed" href="bootstrap/ico/apple-touch-icon-57-precomposed.png">
  <link rel="shortcut icon" href="bootstrap/ico/favicon.png">
  <script src="advert.js"></script>
  <script type="text/javascript" src="ScriptLibrary/jquery-latest.pack.js"></script>
  <script type="text/javascript" src="bootstrap/js/bootstrap.js"></script>
  <script type="text/javascript" src="js/bootstrap-select.js"></script>
  </head>

  <body>
<div id="myModalBox" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- Заголовок модального окна -->
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title">Предупреждение</h4>
      </div>
      <!-- Основное содержимое модального окна -->
      <div class="modal-body">
       
		 <div id="dfm2">
		  
		  
		  
		  </div>
		  
		
		  
      </div>
      <!-- Футер модального окна -->
      <div class="modal-footer">
        <button type="button" class="btn btn-info" data-dismiss="modal">Закрыть</button>
       
      </div>
    </div>
  </div>
</div>
    <div class="container">

      <form ACTION="<?php echo $loginFormAction; ?>" METHOD="POST" class="form-signin" name="log" id="log">
        <h2 class="form-signin-heading">Введите пароль</h2>
<?php if ($colname_fio<1){ ?>
     <div class="btn-group">
    <button id="btt" type="button" class="btn btn-success dropdown-toggle form-control"  data-toggle="dropdown">
        Выберите специальность   <span class="caret"></span>
    </button>
      
        
         <ul class="dropdown-menu" role="menu">
    
      <?php do { ?>
          <li><a href="?spec=<?php echo $row_spec['num']; ?>"><?php echo $row_spec['nazv']; ?></a></li>
        <?php } while ($row_spec =  /* fixed MMiC */ mysqli_fetch_assoc($spec)); ?>
        
    </ul>
</div>
       <hr>
<?php } ?>
	<input type="checkbox" class="checkbox" name="urlico" id="chec" value="1">
								<label for="chec">Юридическое лицо</label><br>
        <select name="user" class="selectpicker show-tick form-control" data-live-search="true" title="Выберите ФИО" id="sp">
         
         <?php    $row_fio =  /* fixed MMiC */ mysqli_fetch_assoc($fio);
do {  
?>
         <option value="<?php echo $row_fio['num']?>"><?php echo $row_fio['fio']?></option>
         <?php
} while ($row_fio =  /* fixed MMiC */ mysqli_fetch_assoc($fio));
  $rows =  /* fixed MMiC */ mysqli_num_rows($fio);
  if($rows > 0) {
       /* fixed MMiC */ mysqli_data_seek($fio, 0);
	  $row_fio =  /* fixed MMiC */ mysqli_fetch_assoc($fio);
  }
?>
       </select>
<hr>
         <input type="password" class="input-block-level" placeholder="Password" name="passw" id="passw">
       <label><input type="checkbox" class="password-checkbox"> Показать пароль</label>
<br>
        <button class="btn btn-large btn-primary" type="submit">Войти</button>
        	<button class="btn btn-large btn-danger" id="zb" type="button">Забыли пароль</button>
        <input name="spec" type="hidden" id="spec" value="<php? echo $_GET['spec'] ?>">
      </form>

    </div> <!-- /container -->
 <div align="center">
<div style="background-color: brown; width: 300px;color: aliceblue;display: none" id="ab">У вас стоит AD-block режущий рекламу  <br>
	  из за этого тесты блокируются и не отпарвляются на сайт. 
	советую временно октлючить его на этом сайте
	  </div> </div>
  </body>
  <script>
   if ( ! ( 'adBlock' in window ) ) {
 $('#ab').show();
}
$('body').on('click', '.password-checkbox', function(){
	if ($(this).is(':checked')){
		$('#passw').attr('type', 'text');
	} else {
		$('#passw').attr('type', 'password');
	}
}); 
$("#chec").click(function(p){1==$(this).prop("checked")?$.post("login.php",{ul:"1",spec:<?php echo $colname_fio;?>},function(p){$("#sp").empty(),$("#sp").append(p),$("#sp").selectpicker("refresh"),console.log(p)}):$.post("login.php",{ul:"2",spec:<?php echo $colname_fio;?>},function(p){$("#sp").empty(),$("#sp").append(p),$("#sp").selectpicker("refresh"),console.log(p)})}),$("#zb").click(function(){spec=$("#sp :selected").val(),$.post("vost_p.php",{userid:spec},function(p){$("#dfm2").html(p),$("#myModalBox").modal("show")})});		
	
  </script>
  
</html>
<?php
 /* fixed MMiC */ mysqli_free_result($fio);
?>
