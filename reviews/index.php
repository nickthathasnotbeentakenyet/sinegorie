<?php
/* 
* ---------------------------
* This is the reviews controller
* ---------------------------
*/
session_start();

require_once '../library/connection.php';
require_once '../library/functions.php';
require_once '../model/main-model.php';
require_once '../model/poetry-model.php';
require_once '../model/reviews-model.php';
require_once '../model/accounts-model.php';


$classifications = getClassifications();

$action = filter_input(INPUT_POST, 'action');
if ($action == NULL) {
  $action = filter_input(INPUT_GET, 'action');
}

switch ($action) {
  case 'newReview':
    $reviewText = trim(filter_input(INPUT_POST, 'reviewText', FILTER_SANITIZE_STRING));
    $poemId = trim(filter_input(INPUT_POST, 'poemId', FILTER_SANITIZE_NUMBER_INT));
    if (isset($_SESSION['clientData']['clientId'])) {
      $clientId = trim(filter_input(INPUT_POST, 'clientId', FILTER_SANITIZE_NUMBER_INT));
    }
    if (empty($reviewText)) {
      $message = '<p class="failure">Необходимо заполнить поле текста рецензии</p>';
      include '../view/poem.php';
      exit;
    } else {
      $outReview = createReview($reviewText, $poemId, $clientId);
      if ($outReview){
      $message = '<p class="success">Рецензия успешно опубликована!</p>';
      }
      $poemInfo = getSpecificPoemInfo($poemId);
      // $thumbnailImages = getThumbnailImage($invId);
      $reviews = getAllReviews($poemId);
      if (!count($poemInfo)) {
        $message = "<p class='notice'>К сожалению, ничего не найдено.</p>";
      } else {
        $poemDisplay = buildPoemSpecInfo($poemInfo);
        $displayReviews = buildReviewsDisplay($reviews);
      }
      // if (count($thumbnailImages)) {
      //   $thumbsDisplay = buildVehicleSpecInfoThumbnailImages($thumbnailImages);
      // }
      include '../view/poem.php';
      exit;
    }
    break;
  case 'updateReview':
    $reviewDatetime = new DateTime('NOW');
    $reviewDate = $reviewDatetime->format('Y-m-d H:i:s');
    $reviewText = filter_input(INPUT_POST, 'reviewText', FILTER_SANITIZE_STRING);
    $reviewId = filter_input(INPUT_POST, 'reviewId', FILTER_SANITIZE_NUMBER_INT);
        if (empty($reviewText)) {
          $message = '<p class="failure">Форма должна быть заполнена!</p>';
          include '../view/review-update.php';
          exit;
        }
    $upd = updateReview($reviewText, $reviewId, $reviewDate);
    if ($upd) {
        $message = "<p class='success'>Рецензия успешно обновлена!.</p>";
    } else {
        $message = "<p class='failure'>Рецензия не обновлена.</p>";
    }
    $_SESSION['message'] = $message;
    header('location: /sinegorie/accounts/index.php?action=admin');
    break;
  case 'deleteReview':
        $reviewId = filter_input(INPUT_POST, 'reviewId', FILTER_VALIDATE_INT);
        $remove = deleteReview($reviewId);
        if ($remove) {
            $message = "<p class='success'>Рецензия успешно удалена!.</p>";
        } else {
            $message = "<p class='failure'>Рецензия НЕ удалена.</p>";
        }
        $_SESSION['message'] = $message;
        header('location: /sinegorie/accounts/index.php?action=admin');
        break;
  case 'updateView':
    $reviewId = filter_input(INPUT_GET, 'reviewId', FILTER_VALIDATE_INT);
    $specReview = getSpecificReview($reviewId);
    $displaySpecReview=buildSpecReviewDisplay($specReview);
    include '../view/review-update.php';
    break;
  case 'deleteView':
    $reviewId = filter_input(INPUT_GET, 'reviewId', FILTER_VALIDATE_INT);
    $specReview = getSpecificReview($reviewId);
    $displaySpecReview=buildSpecReviewDisplay($specReview);
    include '../view/review-delete.php';
    break;  
    case 'deleteModerView':
      $poemId = filter_input(INPUT_GET, 'poemId', FILTER_VALIDATE_INT);
      $reviewId = filter_input(INPUT_GET, 'reviewId', FILTER_VALIDATE_INT);
      $specReview = getSpecificReview($reviewId);
      $displaySpecReview=buildSpecReviewDisplay($specReview);
      include '../view/deleteReviewModer-view.php';
      break;  
  default:
    if (!$_SESSION['loggedin']) {
      header('Location: /sinegorie/');
    }
    include '../view/admin.php';
}
