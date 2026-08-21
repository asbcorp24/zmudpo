<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_testmed = "localhost";
$database_testmed = "cs83914_testmed";
$username_testmed = "cs83914_testmed";
$password_testmed = "6392524035";

$testmed= mysqli_connect($hostname_testmed, $username_testmed, $password_testmed, $database_testmed) 
	or die("Ошибка= " . mysqli_error($testmed));
	
	if (!function_exists('mysql_result2')) {
    function mysql_result2($result, $number, $field=0) {
        mysqli_data_seek($result, $number);
        $row = mysqli_fetch_array($result);
        return $row[$field];
    }
}
?>