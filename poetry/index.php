<?php
/* 
* --------------------------------
* This is the Poetry Controller
* -------------------------------
*/

// Create or access a Session
session_start();

require_once '../library/connection.php';
require_once '../library/functions.php';
require_once '../model/main-model.php';
require_once '../model/accounts-model.php';
require_once '../model/poetry-model.php';
require_once '../model/reviews-model.php';

$classifications = getClassifications();
$navList = getNavigationBar($classifications);

$action = filter_input(INPUT_POST, 'action');
if ($action == NULL) {
    $action = filter_input(INPUT_GET, 'action');
}

switch ($action) {
    case 'addClassification-view':
        include '../view/add-classification.php';
        break;
    case 'addClassification':
        $classificationName = trim(filter_input(INPUT_POST, 'classificationName', FILTER_SANITIZE_STRING));
        $checkClassification = checkClassification($classificationName);
        if (empty($checkClassification)) {
            $message = '<p id="emptyAddClass">Пожалуйста, заполните поле ввода</p>';
            include '../view/add-classification.php';
            break;
        } else {
            header('Location: /sinegorie/poetry/index.php');
        }
        $regOutClass = regClassification($classificationName);

    case 'addPoem-view':
        include '../view/add-poem.php';
        break;

    case 'addPoem':
        $classificationId = trim(filter_input(INPUT_POST, 'classificationId', FILTER_SANITIZE_NUMBER_INT));
        $poemText = (filter_input(INPUT_POST, 'poemText', FILTER_SANITIZE_STRING));
        $poemText = '<p>' . str_replace("\n\n", '</p><p>', $poemText) . '</p>';
        $poemText = str_replace("\n", '<br>', $poemText);

        $poemImage = trim(filter_input(INPUT_POST, 'poemImage', FILTER_SANITIZE_STRING));
        $poemName = trim(filter_input(INPUT_POST, 'poemName', FILTER_SANITIZE_STRING));
        $poemDate = trim(filter_input(INPUT_POST, 'poemDate', FILTER_SANITIZE_STRING));

        if (empty($classificationId) || empty($poemText) || empty($poemName)) {
            $message = '<p id="msgEmptyField">Пожалуйста, заполните все поля.</p>';
            include '../view/add-poem.php';
            break;
        } else {
            $regOutPoem = regPoem($classificationId, $poemText, $poemImage, $poemName, $poemDate);
            $message = '<p id="msgSuccess">Произведение успешно добавлено!</p>';
            include '../view/add-poem.php';
            break;
        }
    case 'poetry-management':
        include '../view/poetry-management.php';
        exit;
    case 'classification':
        $classificationName = filter_input(INPUT_GET, 'classificationName', FILTER_SANITIZE_STRING);
        $poems = getPoemsByClassification($classificationName);
        if (!count($poems)) {
            $message = "<p class='notice'>К сожалению, в разделе $classificationName пока нет произведений.</p>";
        } else {
            $poemsDisplay = buildPoemsDisplay($poems);
        }
        include '../view/classification.php';
        break;
    case 'poem-view':
        $poemId = filter_input(INPUT_GET, 'poemId', FILTER_SANITIZE_NUMBER_INT);
        $poemInfo = getSpecificPoemInfo($poemId);
        $reviews = getAllReviews($poemId);
        if (!count($reviews)) {
            $msgNoReviews = '<p id="noreviews">Данное произведение не содержит рецензий.<br> Хотите быть первым?</p>';
        } else {
            $displayReviews = buildReviewsDisplay($reviews);
        }
        if (!count($poemInfo)) {
            $message = "<p class='notice'>К сожалению ничего не найдено.</p>";
        } else {
            $poemDisplay = buildPoemSpecInfo($poemInfo);
        }
        include '../view/poem.php';
        break;
    case 'getPoemsList':
        $classificationId = filter_input(INPUT_GET, 'classificationId', FILTER_SANITIZE_NUMBER_INT);
        $poemsArray = getAllPoemsByClassification($classificationId);
        echo json_encode($poemsArray);
        break;
    case 'updateEvent':
        $poemId = filter_input(INPUT_GET, 'poemId', FILTER_VALIDATE_INT);
        $poemInfo = getPoemInfo($poemId);
        if (count($poemInfo) < 1) {
            $message = 'К сожалению, информация не найдена.';
        }
        include '../view/poem-update.php';
        exit;
        break;
    case 'deleteEvent':
        $poemId = filter_input(INPUT_GET, 'poemId', FILTER_VALIDATE_INT);
        $poemInfo = getPoemInfo($poemId);
        if (count($poemInfo) < 1) {
            $message = 'К сожалению, информация не найдена.';
        }
        include '../view/poem-delete.php';
        exit;
        break;
    case 'updatePoem':
        $classificationId = trim(filter_input(INPUT_POST, 'classificationId', FILTER_SANITIZE_NUMBER_INT));
        $poemText = trim(filter_input(INPUT_POST, 'poemText', FILTER_SANITIZE_STRING));
        $poemDate = trim(filter_input(INPUT_POST, 'poemDate', FILTER_SANITIZE_STRING));
        $poemImage = trim(filter_input(INPUT_POST, 'poemImage', FILTER_SANITIZE_STRING));
        $poemName = trim(filter_input(INPUT_POST, 'poemName', FILTER_SANITIZE_STRING));
        $poemId = filter_input(INPUT_POST, 'poemId', FILTER_SANITIZE_NUMBER_INT);
        if (empty($classificationId) || empty($poemText) || empty($poemName)) {
            $message = '<p id="emptyAddClass">Пожалуйста, заполните *обязательные поля.</p>';
            include '../view/poem-update.php';
            break;
        }

        $updateResult = updatePoem($classificationId, $poemName, $poemText, $poemDate, $poemImage, $poemId);
        if ($updateResult) {
            $message = "<p id=\"msgSuccess\">Произведение $poemName было успешно обновлено.</p>";
            $_SESSION['message'] = $message;
            header('location: /sinegorie/poetry/');
            exit;
        } else {
            $message = '<p id="msgFailure">Ошибка. Произведение не было обновлено.</p>';
            include '../view/poem-update.php';
            exit;
        }
        break;

    case 'deletePoem':
        $poemText = trim(filter_input(INPUT_POST, 'poemText', FILTER_SANITIZE_STRING));
        $poemDate = trim(filter_input(INPUT_POST, 'poemDate', FILTER_SANITIZE_STRING));
        $poemImage = trim(filter_input(INPUT_POST, 'poemImage', FILTER_SANITIZE_STRING));
        $poemName = trim(filter_input(INPUT_POST, 'poemName', FILTER_SANITIZE_STRING));
        $poemId = filter_input(INPUT_POST, 'poemId', FILTER_SANITIZE_NUMBER_INT);
        $deleteResult = deletePoem($poemId);
        if ($deleteResult) {
            $message = "<p id=\"msgSuccess\">Произведение $poemName было успешно удалено.</p>";
            $_SESSION['message'] = $message;
            header('location: /sinegorie/poetry/');
            exit;
        } else {
            $message = "<p id='emptyFailure'>Ошибка: Произведение $poemName не было удалено.</p>";
            $_SESSION['message'] = $message;
            header('location: /sinegorie/poetry/');
            exit;
        }
        break;

    default:
        $classificationList = buildClassificationList($classifications);
        include '../view/poetry-management.php';
        break;
}
