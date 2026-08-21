<link href="https://fonts.googleapis.com/css?family=Forum" rel="stylesheet">
<style>
	nav {
 font-family: 'Forum', -apple-system, system-ui, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
}
</style>
<?php require_once('Connections/testmed.php'); ?>
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

$colname_us = "-1";
if (isset($_SESSION['MM_Username1'])) {
  $colname_us = $_SESSION['MM_Username1'];
}
 /* fixed MMiC */ mysqli_select_db(DB::$link, $database_testmed);
$query_us = sprintf("SELECT * FROM tm_user WHERE num = %s", GetSQLValueString($colname_us, "int"));

$us =  /* fixed MMiC */ DB::Query($query_us, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_us =  /* fixed MMiC */ mysqli_fetch_assoc($us);
$totalRows_us =  /* fixed MMiC */ mysqli_num_rows($us);
$sql="SELECT 
  `tm_spec`.`kr`
FROM
  `tm_spec`
  INNER JOIN `tm_user` ON (`tm_spec`.`num` = `tm_user`.`spec`)
WHERE
  `tm_user`.`num` = $colname_us";
$us2 =  /* fixed MMiC */ DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_us2 =  /* fixed MMiC */ mysqli_fetch_assoc($us2);
$totalRows_us2 =  /* fixed MMiC */ mysqli_num_rows($us2);
?>
<link href="./main.550dcf66.css" rel="stylesheet"></head>
 <nav class="navbar navbar-default active">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
          <a class="navbar-brand" href="index.php">
            <?php if ($totalRows_us == 0) { // Show if recordset empty ?>
        ЗМУ
<?php } else echo $row_us['fio'];  ?>
          <img src="bt.png" class="navbar-logo-img" alt="">
                 </a>
      </div>

      <div class="collapse navbar-collapse" id="navbar-collapse">
        <ul class="nav navbar-nav navbar-right">
       
                         <?php if ($totalRows_us != 0) { ?> <li><a href="login.php?spec=73">Выход</a> </li><?php }?>

        </ul>
      </div> 
    </div>
  </nav>
  
  
 
    
  
    <!-- Brand and toggle get grouped for better mobile display -->

 
 
</nav>

<?php
 /* fixed MMiC */ mysqli_free_result($us);
?>
