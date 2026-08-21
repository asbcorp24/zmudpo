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
$user=$_SESSION['MM_Username1'];
$grp22=$_SESSION['MM_UserGroup'];
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
if ((isset($_POST["chat"])) && ($_POST["chat"] == "3")) {
$sql="SELECT 
  COUNT(`tm_chat_kurator`.`k_pr`) AS `cupr`,
  `tm_prepod`.`num`,
  `tm_prepod`.`fio`
FROM
  `tm_chat_kurator`
  INNER JOIN `tm_prepod` ON (`tm_chat_kurator`.`kurator` = `tm_prepod`.`num`)
WHERE
  `tm_chat_kurator`.`user` = $user
GROUP BY
  `tm_prepod`.`num`";
$ch =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_ch =  /* fixed MMiC */ mysqli_fetch_assoc($ch);
$totalRows_ch =  /* fixed MMiC */ mysqli_num_rows($ch);
	
	do{
	if ($row_ch['cupr']>0)	echo '<a href="#" data-id="'.$row_ch['num'].'" class="gus" data-fio="'.$row_ch['fio'].'"><span class="badge badge-warning pull-right" style="background-color: #b48dec; font-size: 9px">'.$row_ch['fio'].'['.$row_ch['cupr'].']</span></a>';
		
		
		   } while ($row_ch =  /* fixed MMiC */ mysqli_fetch_assoc($ch)); 
	
	exit();

}
if ((isset($_POST["chat"])) && ($_POST["chat"] == "2")) {
	$users=intval($_POST["user"]);
	$obn=(int)22;
//var_dump($_POST);$
$sql="UPDATE `tm_chat_kurator` SET `k_pr`=NULL WHERE `kurator` = ".$users.';';
$ch =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
//	echo($sql);
$sql="SELECT * FROM (SELECT 
  `tm_chat_kurator`.`num`,
  `tm_chat_kurator`.`user`,
  `tm_chat_kurator`.`kurator`,
  `tm_chat_kurator`.`razdel`,
  `tm_chat_kurator`.`txt`,
  `tm_chat_kurator`.`dat`,
  `tm_chat_kurator`.`k_pr`,
  `tm_chat_kurator`.`u_pr`,`tm_chat_kurator`.`ku`
FROM
  `tm_chat_kurator`
WHERE
  `tm_chat_kurator`.`kurator` = $users AND 
  `tm_chat_kurator`.`user` = $user and razdel=". $_SESSION['rzd']." order by num desc limit 50) AS t1 
ORDER BY num;";		
	
	$ch =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_ch =  /* fixed MMiC */ mysqli_fetch_assoc($ch);
$totalRows_ch =  /* fixed MMiC */ mysqli_num_rows($ch);
	
	do{
		 if ($row_ch['ku']==2)  echo '<li class="left clearfix"><span class="chat-img pull-left">
                            <img src="http://placehold.it/50/55C1E7/fff&text=U" alt="User Avatar" class="img-circle" />
                        </span>
                            <div class="chat-body clearfix">
                                <div class="header">
                                  <small class="pull-right text-muted">
                                        <span class="glyphicon glyphicon-time "pull-left"></span> '. $row_ch['dat'] .'</small>
                                </div>
                                <p class="text-left">
                                   '. $row_ch['txt'] .'
                                </p>
                            </div>
                        </li>';
		
		
		
		 if ($row_ch['ku']==1) echo '<li class="right clearfix"><span class="chat-img pull-right">
                            <img src="http://placehold.it/50/FA6F57/fff&text=ME" alt="User Avatar" class="img-circle" />
                        </span>
                            <div class="chat-body clearfix">
                                <div class="header">
                                    <small class="pull-left text-muted"><span class="glyphicon glyphicon-time"></span>'. $row_ch['dat'] .'</small>
                                  
                                </div><br>
                                <div class="text-right">
                                    '. $row_ch['txt'] .'
                                </div>
                            </div>
                        </li>';
		
		   } while ($row_ch =  /* fixed MMiC */ mysqli_fetch_assoc($ch)); 
	
	exit();
	//$.post('kurator_forum.php', {'chat':'1', 'user' :defuser,'txt':txt},
}
if ((isset($_POST["chat"])) && ($_POST["chat"] == "1")) {
	$users=intval($_POST["user"]);
	$txt=$_POST["txt"];
$sql="INSERT INTO `tm_chat_kurator` (`num`, `user`, `kurator`, `razdel`, `txt`, `dat`, `ku`,`k_pr`, `u_pr`) VALUES (NULL,".$user.",$users, ". $_SESSION['rzd'].", '$txt', '".date("Y-m-d H:i:s")."',2,NULL, 1)";
//echo $sql;
	DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$sql="SELECT * FROM (SELECT 
  `tm_chat_kurator`.`num`,
  `tm_chat_kurator`.`user`,
  `tm_chat_kurator`.`kurator`,
  `tm_chat_kurator`.`razdel`,
  `tm_chat_kurator`.`txt`,
  `tm_chat_kurator`.`dat`,
  `tm_chat_kurator`.`k_pr`,
  `tm_chat_kurator`.`u_pr`,`tm_chat_kurator`.`ku`
FROM
  `tm_chat_kurator`
WHERE
  `tm_chat_kurator`.`kurator` = $users AND 
  `tm_chat_kurator`.`user` = $user and razdel=". $_SESSION['rzd']." order by num desc limit 50) AS t1 
ORDER BY num;";			
	
	$ch =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_ch =  /* fixed MMiC */ mysqli_fetch_assoc($ch);
$totalRows_ch =  /* fixed MMiC */ mysqli_num_rows($ch);
	
	do{
		 if ($row_ch['ku']==2)  echo '<li class="left clearfix"><span class="chat-img pull-left">
                            <img src="http://placehold.it/50/55C1E7/fff&text=U" alt="User Avatar" class="img-circle" />
                        </span>
                            <div class="chat-body clearfix">
                                <div class="header">
                                    <strong class="primary-font">Jack Sparrow</strong> <small class="pull-right text-muted">
                                        <span class="glyphicon glyphicon-time "pull-left"></span> '. $row_ch['dat'] .'</small>
                                </div>
                                <p class="text-left">
                                   '. $row_ch['txt'] .'
                                </p>
                            </div>
                        </li>';
		
		
		
		 if ($row_ch['ku']==1) echo '<li class="right clearfix"><span class="chat-img pull-right">
                            <img src="http://placehold.it/50/FA6F57/fff&text=ME" alt="User Avatar" class="img-circle" />
                        </span>
                            <div class="chat-body clearfix">
                                <div class="header">
                                    <small class="pull-left text-muted"><span class="glyphicon glyphicon-time"></span>'. $row_ch['dat'] .'</small>
                            
                                </div><br>
                                <div class="text-right">
                                    '. $row_ch['txt'] .'
                                </div>
                            </div>
                        </li>';
		
		   } while ($row_ch =  /* fixed MMiC */ mysqli_fetch_assoc($ch)); 
	
	exit();
	//$.post('kurator_forum.php', {'chat':'1', 'user' :defuser,'txt':txt},
}

if ((isset($_POST["upd"])) && ($_POST["upd"] == "1")) {
$sql="update `tm_nmo_razd_media` set ".$_POST["name"]."='".$_POST["val"]."' where id=".$_POST["num"];	
DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
	echo $sql;
	
	exit(0);	
	
}



$rzd=-1;
if(isset($_GET['r']))$rzd=intval($_GET['r']);


$query_spec = "SELECT distinct
  `tm_prepod`.`num`,
  `tm_prepod`.`fio`
  
FROM
  `tm_nmo_razd`
  INNER JOIN `tm_prepod` ON (`tm_nmo_razd`.`prepod` = `tm_prepod`.`num`)
  INNER JOIN `tm_spec` ON (`tm_nmo_razd`.`spec` = `tm_spec`.`num`)
WHERE
  `tm_nmo_razd`.`spec` = $grp22 and `tm_nmo_razd`.`id`=$rzd
  union
  SELECT DISTINCT 
  `tm_prepod`.`num`,
  `tm_prepod`.`fio`
FROM
  `tm_nmo_razd`
  INNER JOIN `tm_spec` ON (`tm_nmo_razd`.`spec` = `tm_spec`.`num`)
  INNER JOIN `tm_nmo_razd_dop_prepod` ON (`tm_nmo_razd`.`id` = `tm_nmo_razd_dop_prepod`.`razdel`)
  INNER JOIN `tm_prepod` ON (`tm_nmo_razd_dop_prepod`.`prepod` = `tm_prepod`.`num`)
WHERE
  `tm_nmo_razd`.`spec` = $grp22 and `tm_nmo_razd`.`id`=$rzd
  
  ";
//echo $query_spec;
$stud =  /* fixed MMiC */ DB::Query($query_spec, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_stud =  /* fixed MMiC */ mysqli_fetch_assoc($stud);
$totalRows_stud =  /* fixed MMiC */ mysqli_num_rows($stud);



$sql="SELECT 
  `tm_spec`.`nazv` AS `snazv`,
  `tm_nmo_razd`.`nazv` AS `rnazv`,
  `tm_nmo_razd`.`id`
FROM
  `tm_nmo_razd`
  INNER JOIN `tm_spec` ON (`tm_nmo_razd`.`spec` = `tm_spec`.`num`)
  INNER JOIN `tm_user` ON (`tm_spec`.`num` = `tm_user`.`spec`)
WHERE
  `tm_user`.`num` = $user";
$razd =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_razd =  /* fixed MMiC */ mysqli_fetch_assoc($razd);
$totalRows_razd =  /* fixed MMiC */ mysqli_num_rows($razd);	  

//echo $query_spec;
$rzd=0;
if (isset($_GET['r']))$rzd=intval($_GET['r']);
$_SESSION['rzd']=$rzd;

$sql="SELECT 
  `tm_nmo_razd`.`nazv`as rnazv,
  `tm_nmo_razd`.`id`
FROM
  `tm_nmo_razd`
 
WHERE
  `tm_nmo_razd`.`id` = $rzd";
$rzd1 =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$rzd1 =  /* fixed MMiC */ mysqli_fetch_assoc($rzd1);
 $rzd1=$rzd1['rnazv'];


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
		.chat
{
    list-style: none;
    margin: 0;
    padding: 0;
}

.chat li
{
    margin-bottom: 10px;
    padding-bottom: 5px;
    border-bottom: 1px dotted #B3A9A9;
}

.chat li.left .chat-body
{
    margin-left: 60px;
}

.chat li.right .chat-body
{
    margin-right: 10px;
}


.chat li .chat-body p
{
    margin: 0;
    color: #777777;
}

.panel .slidedown .glyphicon, .chat .glyphicon
{
    margin-right: 5px;
}

.panel-body-chat
{
  overflow-y: scroll;
 max-height: 400px;
}


</style>
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


	
	
	
 <div class="container">
  <div class="row text-center">
	
   <div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Форум</h3>
  </div>
  <div class="panel-body">
	    <div class="text-left">
	    <div class="btn-group">
    <button id="btt" type="button" class="btn btn-success btn-lg dropdown-toggle btn-block btn-xs" data-toggle="dropdown" style="min-width: 200px">
     <?php if($rzd1!='') echo $rzd1; else echo "Раздел"; ?>   <span class="caret"></span>
    </button>
      
        
         <ul class="dropdown-menu" role="menu">
    
               <?php do{?>
		 <li><a href="?r=<?php echo $row_razd['id']  ?>">[<?php echo $row_razd['snazv']  ?>] <?php echo $row_razd['rnazv']  ?></a></li>
		
		 		  <?php } while ($row_razd =  /* fixed MMiC */ mysqli_fetch_assoc($razd)); ?> 
                
    </ul>
		  </div></div>
	  <br>  
   <div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Обучающиеся</h3>
  </div>
  <div class="panel-body">
    <div class="pull-left">
	 <select id="fio">
		 <?php do{?>
		 
		 <option value="<?php echo $row_stud['num']  ?>"><?php echo $row_stud['fio']  ?></option>
		      <?php } while ($row_stud =  /* fixed MMiC */ mysqli_fetch_assoc($stud)); ?>
		</select>
		<button id="sel">Выбрать</button>
	  </div><div id="npr"></div>
  </div>
</div>
<!--тут сообщения -->  
	  <?php if ($rzd>0){ ?>
	   <div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title" id="uf">Сообщения </h3>
  </div>
  <div class="panel-body panel-body-chat">
                    <ul class="chat">
                     
                    </ul>
                </div>
</div>
	  
	  <!--тут оправляем -->  
	<div class="panel panel-default">
  <div class="panel-body"><div class="input-group">
        <input type="text" class="form-control" id="txt">
        <span class="input-group-btn">
          <button class="btn btn-info" type="button" id="go">Вперед!</button>
        </span>
      </div></div>
</div> 
	  <?php }?>
  </div>
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
		defuser=0;

		function ref(){
					$.post('user_forum.php', {'chat':'3', 'user' :defuser,'txt':''},
		function(data) {
			$('#npr').html(data);
	
		});	
			$.post('user_forum.php', {'chat':'2', 'user' :defuser,'txt':''},
		function(data) {
			$('.chat').html(data);
		$('.panel-body-chat').scrollTop($('.panel-body-chat').prop('scrollHeight'));
		});	
				
		}
		
		
		
		
		$('body').on('click', '.gus',function(e){
		e.preventDefault();
		defuser=$(this).data('id');
			ufio=$(this).data('fio');
		$('#fio option[value='+defuser+']').prop('selected', true);
			$('#uf').html(ufio);
			ref();
			
	});	
		$('#sel').on('click',function(){
		
		unum=$('#fio').val();
			defuser=unum;
			console.log(defuser);
			ufio=$('#fio option:selected').text();
			$('#uf').html(ufio);
			ref();
	});	
		$('#txt').on( "keypress", function(event) {
			
			if (event.which == 13 && !event.shiftKey) {
        event.preventDefault();
      $('#go').trigger('click');
    }
		});
		$('#fio').on( "change", function(event) {
			
			$('#sel').trigger('click');
		});
		
		$('#go').on('click',function(){
		
		txt=$('#txt').val();$('#txt').val('');
			console.log(defuser);
		$.post('user_forum.php', {'chat':'1', 'user' :defuser,'txt':txt},
		function(data) {
			
	ref();
		});	
			
	});		
		
	
		 setInterval(ref, 10000);
		
});

//	/(".hello").clone().appendTo(".container");
	
	</script>
</body>
</html>
<?php
 /* fixed MMiC */ mysqli_free_result($spec);
?>