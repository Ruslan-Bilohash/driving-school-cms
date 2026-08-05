<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/auth.php';

function fail(string $msg): void {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $msg], JSON_UNESCAPED_UNICODE);
    exit;
}

require_login();

if (!csrf_check($_POST['csrf'] ?? null)) {
    fail('Сесія застаріла, оновіть сторінку.');
}

if (empty($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    fail('Файл не завантажено.');
}

$file = $_FILES['image'];

if ($file['size'] > 8 * 1024 * 1024) {
    fail('Файл завеликий (макс. 8 МБ).');
}

$info = @getimagesize($file['tmp_name']);
if ($info === false) {
    fail('Це не зображення.');
}

$mime = $info['mime'];
$allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
if (!in_array($mime, $allowed, true)) {
    fail('Підтримуються лише JPG, PNG, GIF, WebP.');
}

switch ($mime) {
    case 'image/jpeg': $src = @imagecreatefromjpeg($file['tmp_name']); break;
    case 'image/png': $src = @imagecreatefrompng($file['tmp_name']); break;
    case 'image/gif': $src = @imagecreatefromgif($file['tmp_name']); break;
    case 'image/webp': $src = @imagecreatefromwebp($file['tmp_name']); break;
    default: $src = false;
}

if (!$src) {
    fail('Не вдалося обробити зображення.');
}

$width = imagesx($src);
$height = imagesy($src);
$maxDim = 1600;

if ($width > $maxDim || $height > $maxDim) {
    $ratio = min($maxDim / $width, $maxDim / $height);
    $newW = (int) round($width * $ratio);
    $newH = (int) round($height * $ratio);
    $resized = imagecreatetruecolor($newW, $newH);
    imagealphablending($resized, false);
    imagesavealpha($resized, true);
    imagecopyresampled($resized, $src, 0, 0, 0, 0, $newW, $newH, $width, $height);
    imagedestroy($src);
    $src = $resized;
}

$uploadDir = __DIR__ . '/../assets/uploads';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$name = date('Ymd') . '-' . bin2hex(random_bytes(6)) . '.webp';
$path = $uploadDir . '/' . $name;

if (!imagewebp($src, $path, 82)) {
    imagedestroy($src);
    fail('Не вдалося зберегти зображення.');
}
imagedestroy($src);

echo json_encode(['success' => true, 'url' => BASE_PATH . '/assets/uploads/' . $name], JSON_UNESCAPED_UNICODE);
