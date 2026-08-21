<?php
//открывает сессию
if (!isset($_SESSION)) {
  session_start();
}
function generate_password($number)
  {
    $arr = array('1','2','3','4','5','6',
                 '7','8','9','0');
    // Генерируем пароль
    $pass = "";
    for($i = 0; $i < $number; $i++)
    {
      // Вычисляем случайный индекс массива
      $index = rand(0, count($arr) - 1);
      $pass .= $arr[$index];
    }
    return $pass;
  }

//присваивает PHP переменной captchastring строку символов
$captchastring = generate_password(8);
//получает первые 6 символов после их перемешивания с помощью функции str_shuffle
//$captchastring = substr(str_shuffle($captchastring), 0, 6);
//инициализация переменной сессии с помощью сгенерированной подстроки captchastring,
//содержащей 6 символов
$_SESSION["code"] = $captchastring;

//Генерирует CAPTCHA

//создает новое изображение из файла background.png 
$image = imagecreatetruecolor(110,50);
 $bgc = imagecolorallocate($image, 255, 255, 255);
 //  $bgc  = imagecolorallocate($image);

     imagefilledrectangle($image, 0, 0, 150, 50, $bgc);

//$image = imagecreatefrompng('bcap.png');
//устанавливает цвет (R-200, G-240, B-240) изображению, хранящемуся в $image
$colour = imagecolorallocate($image, 0, 0, 0);
//присваивает переменной font название шрифта
$font = './10217.ttf';
//устанавливает случайное число между -10 и 10 градусов для поворота текста 
$rotate =0;// rand(-10, 10);
//рисует текст на изображении шрифтом TrueType (1 параметр - изображение ($image), 
//2 - размер шрифта (18), 3 - угол поворота текста ($rotate), 
//4, 5 - начальные координаты x и y для текста (18,30), 6 - индекс цвета ($colour),
//7 - путь к файлу шрифта ($font), 8 - текст ($captchastring) 
imagettftext($image,23, $rotate,5,30, $colour, $font, $captchastring);
//будет передавать изображение в формате png
header('Content-type: image/png');
//выводит изображение
imagepng($image);
?>

