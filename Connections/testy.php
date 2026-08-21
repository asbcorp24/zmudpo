<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
 /* fixed MMiC */ DB::deprecated('mysql_pconnect');//$hostname_testy = "Localhost";
$database_testy = "testmed";
$username_testy = "root";
$password_testy = "";
$testy = mysql_pconnect($hostname_testy, $username_testy, $password_testy) or trigger_error( /* fixed MMiC */ mysqli_error(DB::$link),E_USER_ERROR); 
?>