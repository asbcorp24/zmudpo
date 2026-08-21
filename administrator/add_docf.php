<?php require_once('Connections/testmed.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

 // $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

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

$currentPage = $_SERVER["PHP_SELF"];
//   /* fixed MMiC */ mysqli_select_db(DB::$link, $database_testmed);
if ((isset($_GET['del'])) && ($_GET['del'] != "")) {
if (file_exists('../docs/'.$_GET['file'])) unlink('../docs/'.$_GET['file']);
  $deleteSQL = sprintf("DELETE FROM tm_docs WHERE num=%s",
                       GetSQLValueString($_GET['del'], "int"));

   $Result1 =  /* fixed MMiC */ DB::Query($deleteSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
	}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {

	$filenameimg =uniqid().'.pdf';
		
	move_uploaded_file($_FILES['path']['tmp_name'],'../docs/'.$filenameimg);
  $insertSQL = sprintf("INSERT INTO tm_docs (spec, `path`, dat, nazv,comm) VALUES (%s, %s, %s, %s,%s)",
                       GetSQLValueString($_POST['spec'], "int"),
                       GetSQLValueString($filenameimg, "text"),
                       GetSQLValueString($_POST['dat'], "date"),
                       GetSQLValueString($_POST['nazv'], "text"),
					  GetSQLValueString($_POST['comm'], "text"));

  $Result1 =  /* fixed MMiC */ DB::Query($insertSQL, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
	
 // $Result1 = mysql_query($insertSQL, $loc) or die(mysql_error());
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

