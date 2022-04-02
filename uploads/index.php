<?php

// *** Image uploads controller ***
session_start();

require_once '../library/connection.php';
require_once '../library/functions.php';
require_once '../model/main-model.php';
require_once '../model/poetry-model.php';
require_once '../model/uploads-model.php';

$classifications = getClassifications();
$navList = getNavigationBar($classifications);
$image_dir = '/sinegorie/images/poems';
$image_dir_path = $_SERVER['DOCUMENT_ROOT'] . $image_dir;

$action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);
if ($action == NULL) {
    $action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
}

switch ($action) {
    case 'upload':
        $poemId = filter_input(INPUT_POST, 'poemId', FILTER_VALIDATE_INT);
        $imgPrimary = filter_input(INPUT_POST, 'imgPrimary', FILTER_VALIDATE_INT);
        $imgName = $_FILES['file1']['name'];
        $imageCheck = checkExistingImage($imgName);
        if ($imageCheck) {
            $message = '<p class="failure">Ошибка. Изображение с таким именем уже занято.</p>';
        } elseif (empty($poemId) || empty($imgName)) {
            $message = '<p class="failure">Необходимо выбрать произведение и соответствующее ему изображение.</p>';
        } else {
            $imgPath = uploadFile('file1');
            $result = storeImages($imgPath, $poemId, $imgName, $imgPrimary);
            if ($result) {
                $message = '<p class="success">Изображение успешно загружено.</p>';
            } else {
                $message = '<p class="failure">К сожалению, не удалось загрузить изображение.</p>';
            }
        }
        $_SESSION['message'] = $message;
        header('location: .');
        break;
    case 'delete':
        $filename = filter_input(INPUT_GET, 'filename', FILTER_SANITIZE_STRING);
        $imgId = filter_input(INPUT_GET, 'imgId', FILTER_VALIDATE_INT);
        $target = $image_dir_path . '/' . $filename;
        if (file_exists($target)) {
            $result = unlink($target);
        }
        if ($result) {
            $remove = deleteImage($imgId);
        }
        if ($remove) {
            $message = "<p class='success'>$filename был успешно удален.</p>";
        } else {
            $message = "<p class='failure'>Ошибка. $filename НЕ удален.</p>";
        }
        $_SESSION['message'] = $message;
        header('location: .');
        break;
    default:
        $imageArray = getImages();
        if (count($imageArray)) {
            $imageDisplay = buildImageDisplay($imageArray);
        } else {
            $imageDisplay = '<p class="failure">К сожалению, изображения не найдены.</p>';
        }
        $poems = getPoems();
        $prodSelect = buildPoemsSelect($poems);
        include '../view/image-admin.php';
        exit;
        break;
}
