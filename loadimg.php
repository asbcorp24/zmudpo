<?php
header('Content-type: image/jpeg');
function getExtension1($filename) {
    return end(explode(".", $filename));
  }
$file=$_GET['file'];
if(getExtension1($file)=='jpeg ') header('Content-type: image/jpeg');
if(getExtension1($file)=='webp ') header('Content-type: image/webp');
$current = file_get_contents($file);
echo $current;
?>