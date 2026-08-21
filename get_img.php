<?php header('Content-Type: image/jpeg');
 include('classSimpleImage.php');
$image = new SimpleImage();
 $image->load($_GET['img']);

if (isset($_GET['h'])){$image->resize($_GET['w'],$_GET['h']);}else $image->resizeToWidth($_GET['w']);
//// $image->save('image1.jpg');
 $image->output();
//readfile("image1.jpg");

?>
