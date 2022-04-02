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
require_once '../model/uploads-model.php';

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
        $poemText = '<pre>' . str_replace("\n\n", '</p><p>', $poemText) . '</pre>';
        $poemText = str_replace("\n", '<br>', $poemText);
        $poemName = trim(filter_input(INPUT_POST, 'poemName', FILTER_SANITIZE_STRING));
        $poemAuthor = trim(filter_input(INPUT_POST, 'poemAuthor', FILTER_SANITIZE_STRING));
        $poemDate = trim(filter_input(INPUT_POST, 'poemDate', FILTER_SANITIZE_STRING));

        if (empty($classificationId) || empty($poemText) || empty($poemName)) {
            $message = '<p class="failure">Пожалуйста, заполните все поля.</p>';
            include '../view/add-poem.php';
            break;
        } else {
            $regOutPoem = regPoem($classificationId, $poemText, $poemName, $poemAuthor, $poemDate);
            $message = '<p class="success">Произведение успешно добавлено!</p>';
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
    case 'updateClassification-view':
        $classifications = getGenres();
        $classificationSelector = buildClassificationSelector($classifications);
        include '../view/classification-update.php';
        break;
    case 'updateClassification':
        $classificationId =  filter_input(INPUT_POST, 'classificationId', FILTER_SANITIZE_NUMBER_INT);
        $classificationName =  filter_input(INPUT_POST, 'classificationName', FILTER_SANITIZE_STRING);
        $classifications = getGenres();
        $classificationSelector = buildClassificationSelector($classifications);
        if (empty($classificationName)) {
            $message = '<p id="emptyAddClass">Пожалуйста, заполните поле ввода.</p>';
            include '../view/classification-update.php';
            break;
        }
        $updateResult = updateClassification($classificationId, $classificationName);
        if ($updateResult) {
            $message = "<p id=\"msgSuccess\">Жанр $classificationName был успешно обновлен.</p>";
            $_SESSION['message'] = $message;
            header('location: /sinegorie/poetry/');
            exit;
        } else {
            $message = '<p id="msgFailure">Ошибка. Жанр не был обновлен.</p>';
            include '../view/classification-update.php';
            exit;
        }
        break;
    case 'deleteClassification-view':
        $classifications = getGenres();
        $classificationSelector = buildClassificationSelector($classifications);
        include '../view/classification-delete.php';
        break;
    case 'deleteClassification':
        $classificationId =  filter_input(INPUT_POST, 'classificationId', FILTER_SANITIZE_NUMBER_INT);
        $classifications = getGenres();
        $classificationSelector = buildClassificationSelector($classifications);
        if (empty($classificationId)) {
            $message = '<p class="failure">Пожалуйста, выберите жанр для удаления.</p>';
            include '../view/classification-delete.php';
            break;
        }
        $emptyCheck = emptyClassification($classificationId);
        if (count($emptyCheck)){
            $message = "<p class='failure'>Ошибка! Данный жанр содержит произведения. <br>
            Если вы желаете удалить данный жанр, сперва удалите/переместите все произведения, закрепеленные за жанром.
            <br><a href='/sinegorie/poetry?action=\"poetry-management\"'>Перейти к управлению произведениями</a></p>";
            include '../view/classification-delete.php';
            break;
        }
        $updateResult = deleteClassification($classificationId);
        if ($updateResult) {
            $message = "<p class='success'>Жанр был успешно удален.</p>";
            $_SESSION['message'] = $message;
            header('location: /sinegorie/poetry/');
            exit;
        } else {
            $message = '<p class="failure">Ошибка. Жанр не был удален.</p>';
            include '../view/classification-delete.php';
            exit;
        }
        break;    
    case 'poem-view':
        if(isset($_SESSION['message'])){
            $message =($_SESSION['message']);
        };
        $poemId = filter_input(INPUT_GET, 'poemId', FILTER_SANITIZE_NUMBER_INT);
        $poemName = filter_input(INPUT_GET, 'poemName', FILTER_SANITIZE_STRING);
        $thumbnailImages = getThumbnailImage($poemId);
        $poemInfo = getSpecificPoemInfo($poemId);
        $reviews = getAllReviews($poemId);
        $clientId = isset($_SESSION['clientData']['clientId']);
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
        if (count($thumbnailImages)){
            $thumbsDisplay= buildPoemSpecInfoThumbnailImages($thumbnailImages);
          }
        if (!isset($_SESSION['clientData']['clientId'])){
            include '../view/poem.php';
        }
        else {
            include '../view/poem.php';
        }
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
        $poemText = '<pre>' . str_replace("\n\n", '</p><p>', $poemText) . '</pre>';
        $poemText = str_replace("\n", '<br>', $poemText);
        $poemDate = trim(filter_input(INPUT_POST, 'poemDate', FILTER_SANITIZE_STRING));
        $poemName = trim(filter_input(INPUT_POST, 'poemName', FILTER_SANITIZE_STRING));
        $poemAuthor = trim(filter_input(INPUT_POST, 'poemAuthor', FILTER_SANITIZE_STRING));
        $poemId = filter_input(INPUT_POST, 'poemId', FILTER_SANITIZE_NUMBER_INT);
        if (empty($classificationId) || empty($poemText) || empty($poemName)) {
            $message = '<p id="emptyAddClass">Пожалуйста, заполните *обязательные поля.</p>';
            include '../view/poem-update.php';
            break;
        }

        $updateResult = updatePoem($classificationId, $poemName, $poemText, $poemDate, $poemAuthor, $poemId);
        if ($updateResult) {
            $message = "<p class=\"success\">Произведение $poemName было успешно обновлено.</p>";
            $_SESSION['message'] = $message;
            header('location: /sinegorie/poetry/');
            exit;
        } else {
            $message = '<p class="failure">Ошибка. Произведение не было обновлено.</p>';
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
    case 'findPoem':
        $poemQuery = trim(filter_input(INPUT_GET, 'poemName', FILTER_SANITIZE_STRING));
        $findPoem = findPoem($poemQuery);
        $displayResult = displayFoundPoem($findPoem) ;
        $classificationList = buildClassificationList($classifications);
        if (count($findPoem) < 1) {
            $message = "<p class='failure'>Ошибка: Произведение $poemName не найдено.<br> Измените параметры поиска или воспользуйтесь ручным поиском</p>";
        }
        include '../view/poetry-management.php';
        exit;
        break;
    default:
        $classificationList = buildClassificationList($classifications);
        include '../view/poetry-management.php';
        break;
}
