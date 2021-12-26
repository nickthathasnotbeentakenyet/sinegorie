<?php
/* 
* --------------------------------
* This is the Accounts Controller
* -------------------------------
*/

// Create or access a Session
session_start();

require_once '../library/connection.php';
require_once '../library/functions.php';
require_once '../model/main-model.php';
require_once '../model/accounts-model.php';
require_once '../model/reviews-model.php';


$classifications = getClassifications();
$navList = getNavigationBar($classifications);

$action = filter_input(INPUT_POST, 'action');
if ($action == NULL) {
  $action = filter_input(INPUT_GET, 'action');
}

switch ($action) {
  case 'register-procedure':
    $clientLogin = trim(filter_input(INPUT_POST, 'clientLogin', FILTER_SANITIZE_STRING));
    $clientEmail = trim(filter_input(INPUT_POST, 'clientEmail', FILTER_SANITIZE_EMAIL));
    $clientPassword = trim(filter_input(INPUT_POST, 'clientPassword', FILTER_SANITIZE_STRING));
    $clientEmail = checkEmail($clientEmail);
    $checkPassword = checkPassword($clientPassword);
    $existingEmail = checkExistingEmail($clientEmail);

    if ($existingEmail) {
      $message = '<p class="notice">Данный email уже зарегистрирован. Хотите войти в аккаунт?</p>';
      include '../view/login.php';
      exit;
    }

    if (empty($clientLogin) || empty($clientEmail) || empty($checkPassword)) {
      $message = '<p id="msgEmptyField">Пожалуйста, заполните все поля</p>';
      include '../view/register.php';
      exit;
    }

    $hashedPassword = password_hash($clientPassword, PASSWORD_DEFAULT);
    $regOutcome = regClient($clientLogin, $clientEmail, $hashedPassword);

    if ($regOutcome === 1) {
      setcookie('firstname', $clientFirstname, strtotime('+1 year'), '/');
      $_SESSION['message'] = "<p id=\"msgRegistered\">Спасибо за регистрацию, <span class=\"msgName\">$clientLogin</span>.<br> Используйте ваш email и пароль, чтобы войти в аккаунт.</p>";
      header('Location: /sinegorie/accounts/?action=login-view');
      $_SESSION['fullname'] = "$clientLogin";
      exit;
    } else {
      $message = "<p id=\"msgUnregistered\">К сожалению регистрация не удалась. Пожалуйста, повторите.</p>";
      include '../view/register.php';
      exit;
    }
    break;
    // Login procedure
  case 'login-procedure':
    $clientEmail = trim(filter_input(INPUT_POST, 'clientEmail', FILTER_SANITIZE_EMAIL));
    $clientPassword = trim(filter_input(INPUT_POST, 'clientPassword', FILTER_SANITIZE_STRING));
    $clientEmail = checkEmail($clientEmail);
    $checkPassword = checkPassword($clientPassword);
    if (empty($clientEmail) || empty($checkPassword)) {
      $message = '<p id="msgEmptyField">Пожалуйста, заполните все поля.</p>';
      include '../view/login.php';
      exit;
    }
    $clientData = getClient($clientEmail);
    $hashCheck = password_verify($clientPassword, $clientData['clientPassword']);
    if (!$hashCheck) {
      $message = '<p class="notice">Проверьте пароль и повторите попытку.</p>';
      include '../view/login.php';
      exit;
    }
    $_SESSION['loggedin'] = TRUE;
    array_pop($clientData);
    $_SESSION['clientData'] = $clientData;
    $clientId = $_SESSION['clientData']['clientId'];
    $accountReviews = getAccountReviews($clientId);
    $reviewsDisplay = buildAccountReviewsDisplay($accountReviews);
    include '../view/admin.php';
    exit;
    // update account information procedure
  case 'updateAccountInfo':
    $clientLogin = trim(filter_input(INPUT_POST, 'clientLogin', FILTER_SANITIZE_STRING));
    $clientEmail = trim(filter_input(INPUT_POST, 'clientEmail', FILTER_SANITIZE_EMAIL));
    $clientId = filter_input(INPUT_POST, 'clientId', FILTER_SANITIZE_NUMBER_INT);
    $clientEmail = checkEmail($clientEmail);
    $existingEmail = checkExistingEmail($clientEmail);
    if ($clientEmail != $_SESSION['clientData']['clientEmail']) {
      if ($existingEmail) {
        $message = '<p id="msgEmptyField">Данный email уже существует</p>';
        include '../view/client-update.php';
        exit;
      }
    }
    if (empty($clientLogin) || empty($clientEmail)) {
      $message = '<p id="msgEmptyField">Пожалуйста, заполните все поля.</p>';
      include '../view/client-update.php';
      break;
    }
    $updateResult = updateClient($clientLogin, $clientEmail, $clientId);
    if ($updateResult) {
      $clientData = getClientId($clientId);
      $_SESSION['clientData'] = $clientData;
      $message = "<p id=\"msgSuccess\">Аккаунт успешно обновлен.</p>";
      $_SESSION['message'] = $message;
      include '../view/admin.php';
      exit;
    } else {
      $message = '<p id="msgFailure">Ошибка. Аккаунт не обновлен.</p>';
      include '../view/admin.php';
      exit;
    }
    break;

  case 'updatePassword':
    $clientPassword = trim(filter_input(INPUT_POST, 'clientPassword', FILTER_SANITIZE_STRING));
    $clientId = filter_input(INPUT_POST, 'clientId', FILTER_SANITIZE_NUMBER_INT);
    $checkPassword = checkPassword($clientPassword);

    if (empty($checkPassword)) {
      $messageNoPasswd = '<p id="msgEmptyField">Введите пароль</p>';
      include '../view/client-update.php';
      exit;
    }

    $hashedPassword = password_hash($clientPassword, PASSWORD_DEFAULT);
    $updateResult = updatePassword($hashedPassword, $clientId);
    if ($updateResult) {
      $message = "<p id=\"msgSuccess\">Пароль успешно обновлен</p>";
      $_SESSION['message'] = $message;
      include '../view/admin.php';
      exit;
    } else {
      $message = '<p id="emptyFailure">Error. The password was not updated.</p>';
      include '../view/admin.php';
      exit;
    }
    break;
  case 'login-view':
    include '../view/login.php';
    break;
  case 'register-view':
    include '../view/register.php';
    break;
  case 'admin':
    $clientId = $_SESSION['clientData']['clientId'];
    $accountReviews = getAccountReviews($clientId);
    $reviewsDisplay = buildAccountReviewsDisplay($accountReviews);
    include '../view/admin.php';
    unset($_SESSION['message']);
    break;
  case 'logout':
    unset($_SESSION["clientData"]);
    session_destroy();
    header('Location: /sinegorie/');
    break;
  case 'accountUpdate-view':
    include '../view/client-update.php';
    break;
}
