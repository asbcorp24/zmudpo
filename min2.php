<?php
require_once('Connections/testmed.php');
include('classSimpleImage.php');

if (!isset($_SESSION)) {
    session_start();
}

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

function getExtension1($filename) {
    return strtolower(pathinfo($filename, PATHINFO_EXTENSION));
}

// ИЗМЕНЕНО: теперь 10 дней вместо 29
$sql = "SELECT 
    `tm_nmo_user_file`.`num`,
    `tm_nmo_user_file`.`path`,  
    `tm_nmo_user_file`.`dat` as da,
    (TO_DAYS(CURDATE()) - TO_DAYS(`tm_nmo_user_file`.`dat`)) AS dat
    FROM `tm_nmo_user_file`
    INNER JOIN `tm_nmo_razd_media` ON (`tm_nmo_user_file`.`inn` = `tm_nmo_razd_media`.`id`)
    WHERE `tm_nmo_user_file`.`opt` = 1 
    AND `tm_nmo_user_file`.`tip` = 2 
    AND (TO_DAYS(CURDATE()) - TO_DAYS(`tm_nmo_user_file`.`dat`)) >= 10  -- ИЗМЕНЕНО: 10 дней
    AND `tm_nmo_razd_media`.`gal` <> 1  
    ORDER BY dat DESC";

echo $sql . "<br>\n";

$result2 = DB::Query($sql, $testmed) or die(mysqli_error(DB::$link));
$total = mysqli_num_rows($result2);

echo "total: " . $total . "<br>\n";

if ($total < 1) exit;

// Создаем монтажное изображение (3200x3200)
$montage_image = imagecreatetruecolor(3200, 3200);
$white = imagecolorallocate($montage_image, 255, 255, 255);
imagefill($montage_image, 0, 0, $white);

$x_index = 0;
$y_index = 0;
$processed_count = 0;
$max_images = 100; // Максимум 100 изображений на один монтаж

// Собираем все подходящие изображения
$images_to_process = [];
while ($row2 = mysqli_fetch_assoc($result2)) {
    if ($row2['dat'] >= 10) { // ИЗМЕНЕНО: проверка на 10 дней
        if (file_exists('./usrimg/' . $row2['path'])) {
            $images_to_process[] = $row2;
        }
    }
}

echo "Найдено изображений для обработки: " . count($images_to_process) . "<br>\n";

// Обрабатываем изображения
foreach ($images_to_process as $row2) {
    if ($processed_count >= $max_images) {
        // Если достигнут лимит, сохраняем текущий монтаж и начинаем новый
        if ($processed_count > 0) {
            $montage_filename = 'montage_' . date('Y-m-d_H-i-s') . '_' . uniqid() . '.webp';
            imagewebp($montage_image, './usrimg/' . $montage_filename, 40);
            echo '<a href="./usrimg/' . $montage_filename . '">Скачать монтаж (часть ' . ceil($processed_count/$max_images) . ')</a><br>';
            
            // Создаем новый монтаж
            imagedestroy($montage_image);
            $montage_image = imagecreatetruecolor(3200, 3200);
            $white = imagecolorallocate($montage_image, 255, 255, 255);
            imagefill($montage_image, 0, 0, $white);
            $x_index = 0;
            $y_index = 0;
            $processed_count = 0;
        }
    }
    
    $filename = './usrimg/' . $row2['path'];
    $ext = getExtension1($filename);
    
    echo "Обработка: " . $filename . "<br>";
    
    // Загружаем изображение
    if ($ext == "webp") {
        $current_image = imagecreatefromwebp($filename);
    } elseif ($ext == "jpg" || $ext == "jpeg") {
        $current_image = imagecreatefromjpeg($filename);
    } elseif ($ext == "png") {
        $current_image = imagecreatefrompng($filename);
    } else {
        echo "Неподдерживаемый формат: " . $ext . "<br>";
        continue;
    }
    
    if (!$current_image) {
        echo "Ошибка загрузки изображения<br>";
        continue;
    }
    
    // Изменяем размер до 320x320
    $resized = imagecreatetruecolor(320, 320);
    imagecopyresampled($resized, $current_image, 0, 0, 0, 0, 320, 320, imagesx($current_image), imagesy($current_image));
    
    // Копируем на монтажное полотно
    imagecopy($montage_image, $resized, $x_index * 320, $y_index * 320, 0, 0, 320, 320);
    
    imagedestroy($current_image);
    imagedestroy($resized);
    
    // Генерируем новое уникальное имя
    $new_fname = uniqid() . '.webp';
    
    // Обновляем БД
    $update_sql = "UPDATE tm_nmo_user_file SET opt=2, path='$new_fname' WHERE num=" . $row2['num'];
    DB::Query($update_sql, $testmed) or die(mysqli_error(DB::$link));
    echo "Обновлено: " . $update_sql . "<br>";
    
    // Удаляем исходный файл
    if (unlink($filename)) {
        echo "Удален: " . $filename . "<br>";
    } else {
        echo "Ошибка удаления: " . $filename . "<br>";
    }
    
    $x_index++;
    $processed_count++;
    
    if ($x_index >= 10) {
        $x_index = 0;
        $y_index++;
    }
    
    echo "Прогресс: " . $processed_count . " из " . count($images_to_process) . "<br><br>";
}

// Сохраняем последний монтаж
if ($processed_count > 0) {
    $montage_filename = 'montage_' . date('Y-m-d_H-i-s') . '_' . uniqid() . '.webp';
    imagewebp($montage_image, './usrimg/' . $montage_filename, 40);
    echo '<a href="./usrimg/' . $montage_filename . '">Скачать финальный монтаж</a><br>';
}

imagedestroy($montage_image);
echo "<br><h3>Готово! Обработано всего: " . count($images_to_process) . " изображений</h3>";
?>