<?php header('Content-type: image/jpeg');
$file=$_GET['img'];
$image = new Imagick($file);

$image->blurImage(1,1);
echo $image;

?>