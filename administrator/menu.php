<?php require_once('Connections/testmed.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
//    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

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

$madm_madm = "0";
if (isset($_SESSION['MM_UserGroup'])) {
  $madm_madm = $_SESSION['MM_UserGroup'];
}

$query_madm = sprintf("SELECT    `tm_menu`.`name`,   `tm_menu_adm`.`num`,   `tm_menu_adm`.`inn`,   `tm_menu`.`path` FROM   `tm_menu_adm`   INNER JOIN `tm_menu` ON (`tm_menu_adm`.`menu` = `tm_menu`.`num`) WHERE   `tm_menu_adm`.`inn` =%s", GetSQLValueString($madm_madm, "int"));

$madm = DB::Query($query_madm, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));

$row_madm = mysqli_fetch_assoc($madm);
$totalRows_madm = mysqli_num_rows($madm);
?><ul class="nav">

                 <?php do { ?>
              <li><a href="<?php echo $row_madm['path']; ?>"><?php echo $row_madm['name']; ?></a></li>

            <?php } while ($row_madm = mysqli_fetch_assoc($madm)); ?>
             <li><a href="index.php?doLogout=true">выход</a>
</ul>

    <?php
mysqli_free_result($madm);
?>
