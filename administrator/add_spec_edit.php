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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE tm_spec SET nazv=%s, dat=%s, img=%s, actiiv=%s WHERE num=%s",
                       GetSQLValueString($_POST['nazv'], "text"),
                       GetSQLValueString($_POST['dat'], "date"),
                       GetSQLValueString($_POST['img'], "text"),
                       GetSQLValueString(isset($_POST['actiiv']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['num'], "int"));

   /* fixed MMiC */ mysqli_select_db(DB::$link, $database_testmed);
  $Result1 =  /* fixed MMiC */ DB::Query($updateSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));

  $updateGoTo = "add_spec.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_spec_edit = "-1";
if (isset($_GET['num'])) {
  $colname_spec_edit = $_GET['num'];
}
 /* fixed MMiC */ mysqli_select_db(DB::$link, $database_testmed);
$query_spec_edit = sprintf("SELECT * FROM tm_spec WHERE num = %s", GetSQLValueString($colname_spec_edit, "int"));
$spec_edit =  /* fixed MMiC */ DB::Query($query_spec_edit, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_spec_edit =  /* fixed MMiC */ mysqli_fetch_assoc($spec_edit);
$totalRows_spec_edit =  /* fixed MMiC */ mysqli_num_rows($spec_edit);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Документ без названия</title>
</head>

<body>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Num:</td>
      <td><?php echo $row_spec_edit['num']; ?></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Nazv:</td>
      <td><input type="text" name="nazv" value="<?php echo htmlentities($row_spec_edit['nazv'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Dat:</td>
      <td><input type="date" name="dat" value="<?php echo htmlentities($row_spec_edit['dat'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Img:</td>
      <td><input type="text" name="img" value="<?php echo htmlentities($row_spec_edit['img'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Actiiv:</td>
      <td><input type="checkbox" name="actiiv" value=""  <?php if (!(strcmp(htmlentities($row_spec_edit['actiiv'], ENT_COMPAT, 'utf-8'),""))) {echo "checked=\"checked\"";} ?> /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Обновить запись" /></td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="num" value="<?php echo $row_spec_edit['num']; ?>" />
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
 /* fixed MMiC */ mysqli_free_result($spec_edit);
?>
