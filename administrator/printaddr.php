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

$MM_restrictGoTo = "index.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
$_POST['user']=(int)$_POST['user'];
$sql="SELECT 
  `tm_addr_otprav`.`num`,
  `tm_addr_otprav`.`inn`,
  `tm_addr_otprav`.`oblast`,
  `tm_addr_otprav`.`rayon`,
  `tm_addr_otprav`.`gorod`,
  `tm_addr_otprav`.`dom`,
  `tm_addr_otprav`.`kv`,
  `tm_addr_otprav`.`Fam`,
  `tm_addr_otprav`.`Name`,
  `tm_addr_otprav`.`Otch`,
  `tm_addr_otprav`.`ind`,
  `tm_addr_otprav`.`comment`,
  `tm_addr_otprav`.`ulica`
FROM
  `tm_addr_otprav`
WHERE
  `tm_addr_otprav`.`inn` = ".$_POST['user'];
$otprav =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_otprav =  /* fixed MMiC */ mysqli_fetch_assoc($otprav);
$totalrow_otprav =  /* fixed MMiC */ mysqli_num_rows($otprav);
$sql="SELECT 
  `tm_user`.`fio`,
  `tm_user`.`num`
FROM
  `tm_user`
WHERE
  `tm_user`.`num` = ".$_POST['user'];

$urlico =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_urlico =  /* fixed MMiC */ mysqli_fetch_assoc($urlico);
?>
 <table width="99%" border="1"  id="taddr"  >

  <tbody>
	   <tr >
      <td colspan="2"><?php echo $row_urlico['fio'];?></td>
    
      
    </tr>
    <tr >
      <td>Фамилия</td>
      <td><?php echo $row_otprav['Fam'];?></td>
      
    </tr>
    <tr>
      <td>Имя</td>
      <td><?php echo $row_otprav['Name'];?></td>
      
    </tr>
    <tr>
      <td>Отчество</td>
      <td><?php echo $row_otprav['Otch'];?></td>
      
    </tr>
	   <tr>
      <td>Адрес</td>
      <td> <?php echo $row_otprav['ind'];?> <?php echo $row_otprav['oblast'];?>, <?php echo $row_otprav['rayon'];?>, <?php echo $row_otprav['gorod'];?>, <?php echo $row_otprav['ulica'];?>, дом:<?php echo $row_otprav['dom'];?>, кв:<?php echo $row_otprav['kv'];?></td>
      
    
    <tr>
      <td>Комментарий</td>
      <td><?php echo $row_otprav['comment'];?></td>
      
    </tr>
	  
	 
  </tbody>
</table>