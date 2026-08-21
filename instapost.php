<?php require_once('Connections/testmed.php'); ?>
<?php 
/**
 * @param string $aInitialImageFilePath - строка, представляющая путь к обрезаемому изображению
 * @param string $aNewImageFilePath - строка, представляющая путь куда нахо сохранить выходное обрезанное изображение
 * @param int $aNewImageWidth - ширина выходного обрезанного изображения
 * @param int $aNewImageHeight - высота выходного обрезанного изображения
 */
function cropImage($aInitialImageFilePath, $aNewImageFilePath) {
 

    // Массив с поддерживаемыми типами изображений
    $lAllowedExtensions = array(1 => "gif", 2 => "jpeg", 3 => "png"); 
    
    // Получаем размеры и тип изображения в виде числа
    list($lInitialImageWidth, $lInitialImageHeight, $lImageExtensionId) = getimagesize($aInitialImageFilePath); 
    
    if (!array_key_exists($lImageExtensionId, $lAllowedExtensions)) {
        return false;
    }
    $lImageExtension = $lAllowedExtensions[$lImageExtensionId];
    
    // Получаем название функции, соответствующую типу, для создания изображения
    $func = 'imagecreatefrom' . $lImageExtension; 
    // Создаём дескриптор исходного изображения
    $lInitialImageDescriptor = $func($aInitialImageFilePath);
$aNewImageWidth=$lInitialImageHeight;
$aNewImageHeight=$lInitialImageHeight;
    // Определяем отображаемую область
    $lCroppedImageWidth = 0;
    $lCroppedImageHeight = 0;
    $lInitialImageCroppingX = 0;
    $lInitialImageCroppingY = 0;
    if ($aNewImageWidth / $aNewImageHeight > $lInitialImageWidth / $lInitialImageHeight) {
        $lCroppedImageWidth = floor($lInitialImageWidth);
        $lCroppedImageHeight = floor($lInitialImageWidth * $aNewImageHeight / $aNewImageWidth);
        $lInitialImageCroppingY = floor(($lInitialImageHeight - $lCroppedImageHeight) / 2);
    } else {
        $lCroppedImageWidth = floor($lInitialImageHeight * $aNewImageWidth / $aNewImageHeight);
        $lCroppedImageHeight = floor($lInitialImageHeight);
        $lInitialImageCroppingX = floor(($lInitialImageWidth - $lCroppedImageWidth) / 2);
    }
    
    // Создаём дескриптор для выходного изображения
    $lNewImageDescriptor = imagecreatetruecolor($aNewImageWidth, $aNewImageHeight);
    imagecopyresampled($lNewImageDescriptor, $lInitialImageDescriptor, 0, 0, $lInitialImageCroppingX, $lInitialImageCroppingY, $aNewImageWidth, $aNewImageHeight, $lCroppedImageWidth, $lCroppedImageHeight);
    $func = 'image' . $lImageExtension;
    
    // сохраняем полученное изображение в указанный файл
    return $func($lNewImageDescriptor, $aNewImageFilePath);
}


ini_set("display_errors", 1);
$sql="SELECT 
  `tm_news`.`num`,
  `tm_news`.`nazv`,
  `tm_news`.`content`,
  `tm_news`.`inst`,
  `tm_news`.`img`
FROM
  `tm_news`
WHERE
  `tm_news`.`inst` IS NULL LIMIT 1";

 $Result1 = DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
  $md = mysqli_fetch_assoc($Result1);
$totalmd =  /* fixed MMiC */ mysqli_num_rows($Result1);

if ($totalmd>0){
$sql="update `tm_news` set inst=1 where num=".$md['num'];

//DB::Query($sql, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));

set_time_limit(0);
date_default_timezone_set('UTC');

require 'vendor/autoload.php';

/////// CONFIG ///////
$username = 'zmu_dpo';
$password = 'zmu_dpo1';
$debug = true;
$truncatedDebug = false;
//////////////////////
/////// MEDIA ////////



cropImage('newsimage/'.$md['img'],'newsimage/_'.$md['img']);
echo "<a href='".'newsimage/_'.$md['img']."'>afq</a>";
$photoFilename ='newsimage/_'.$md['img'];
$captionText ='zero';// $md['content'];

//////////////////////
\InstagramAPI\Instagram::$allowDangerousWebUsageAtMyOwnRisk = true;
$ig = new \InstagramAPI\Instagram($debug, $truncatedDebug);

try {
    $ig->login($username, $password);
} catch (\Exception $e) {
    echo 'Something went wrong: '.$e->getMessage()."\n";
    exit(0);
}
$userId = $ig->people->getUserIdForName('MyUsername');
echo $userId;
try {
    // The most basic upload command, if you're sure that your photo file is
    // valid on Instagram (that it fits all requirements), is the following:
  //   $ig->timeline->uploadPhoto($photoFilename, ['caption' => $captionText]);
    // However, if you want to guarantee that the file is valid (correct format,
    // width, height and aspect ratio), then you can run it through our
    // automatic photo processing class. It is pretty fast, and only does any
    // work when the input file is invalid, so you may want to always use it.
    // You have nothing to worry about, since the class uses temporary files if
    // the input needs processing, and it never overwrites your original file.
    //
    // Also note that it has lots of options, so read its class documentation!
    $photo = new \InstagramAPI\Media\Photo\InstagramPhoto($photoFilename);
    print_r($photo);
    $ig->timeline->uploadPhoto($photo->getFile(), ['caption' => $captionText]);
} catch (\Exception $e) {
    echo 'ошибка закачки файл: '.$e->getMessage()."\n";
}
}
?>