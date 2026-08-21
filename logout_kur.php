<?php {
  //to fully log out a visitor we need to clear the session varialbles
  $_SESSION['MM_Username2020'] = NULL;
  $_SESSION['MM_UserGroup2020'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  unset($_SESSION['MM_Username2020']);
  unset($_SESSION['MM_UserGroup2020']);
  unset($_SESSION['PrevUrl']);
	setcookie("uname", "", time()-3600*24*10);  	
  $logoutGoTo = "loginpr.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
?>