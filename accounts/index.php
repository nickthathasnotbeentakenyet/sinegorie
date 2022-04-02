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
require_once '../model/poetry-model.php';
require_once '../model/events-model.php';
require_once '../model/messeges-model.php';


$classifications = getClassifications();
$navList = getNavigationBar($classifications);

$action = filter_input(INPUT_POST, 'action');
if ($action == NULL) {
  $action = filter_input(INPUT_GET, 'action');
}

switch ($action) {
  case 'register-procedure':
    $clientImage = trim(filter_input(INPUT_POST,'clientImage',FILTER_SANITIZE_STRING));
    $clientLogin = trim(filter_input(INPUT_POST, 'clientLogin', FILTER_SANITIZE_STRING));
    $clientEmail = trim(filter_input(INPUT_POST, 'clientEmail', FILTER_SANITIZE_EMAIL));
    $clientPassword = trim(filter_input(INPUT_POST, 'clientPassword', FILTER_SANITIZE_STRING));
    $clientPassword2 = trim(filter_input(INPUT_POST, 'clientPassword2', FILTER_SANITIZE_STRING));
    $clientEmail = checkEmail($clientEmail);
    $checkPassword = checkPassword($clientPassword);
    $existingEmail = checkExistingEmail($clientEmail);

    if ($existingEmail) {
      $message = '<p class="notice">Данный email уже зарегистрирован. Хотите войти в аккаунт?</p>';
      include '../view/login.php';
      exit;
    }

    if (empty($clientLogin) || empty($clientEmail) || empty($checkPassword || empty($clientPassword2))) {
      $message = '<p id="msgEmptyField">Пожалуйста, заполните все поля</p>';
      include '../view/register.php';
      exit;
    }
    if($clientPassword !== $clientPassword2){
      $message = '<p class="failure">Пароли не совпадают. Пожалуйста проверьте.</p>';
      include '../view/register.php';
      exit;
    }

    $hashedPassword = password_hash($clientPassword, PASSWORD_DEFAULT);
    $regOutcome = regClient($clientLogin, $clientEmail, $hashedPassword, $clientImage);

    if ($regOutcome === 1) {
      setcookie('firstname', $clientFirstname, strtotime('+1 year'), '/');
      $_SESSION['message'] = "<p class='success'>Спасибо за регистрацию, <span class='warning'>$clientLogin</span>.<br> Используйте ваш email и пароль, чтобы войти в аккаунт.</p>";
      header('Location: /sinegorie/accounts/?action=login-view');
      $_SESSION['fullname'] = "$clientLogin";
      exit;
    } else {
      $message = "<p class='failure'>К сожалению регистрация не удалась. Пожалуйста, повторите.</p>";
      include '../view/register.php';
      exit;
    }
    break;
    // Login procedure
  case 'login-procedure':
    $clientEmail = trim(filter_input(INPUT_POST, 'clientEmail', FILTER_SANITIZE_EMAIL));
    $clientPassword = trim(filter_input(INPUT_POST, 'clientPassword', FILTER_SANITIZE_STRING));
    $clientEmail = checkEmail($clientEmail);
    $existingEmail = checkExistingEmail($clientEmail);
    $checkPassword = checkPassword($clientPassword);
    if (empty($clientEmail) || empty($checkPassword)) {
      $message = '<p class="failure">Пожалуйста, заполните все поля.</p>';
      include '../view/login.php';
      exit;
    }
    if (!$existingEmail){
      $message = '<p class="failure">Проверьте правильность введенного адреса электронной почты и повторите попытку..</p>';
      include '../view/login.php';
      exit;
    }
    $clientData = getClient($clientEmail);
    $hashCheck = password_verify($clientPassword, $clientData['clientPassword']);
    if (!$hashCheck) {
      $message = '<p class="failure">Проверьте пароль и повторите попытку.</p>';
      include '../view/login.php';
      exit;
    }
    $_SESSION['loggedin'] = TRUE;
    array_pop($clientData);
    $_SESSION['clientData'] = $clientData;
    $clientId = $_SESSION['clientData']['clientId'];
    $clientStatus = $_SESSION['clientData']['clientLevel'];
    switch ($clientStatus) {
      case 1:
        $clientStatus = "<span class='failure'>Заблокирован</span>";
        break;
      case 2:
        $clientStatus= "Читатель";
        break;
      case 3:
        $clientStatus = "Модератор";
        break;
      case 4:
        $clientStatus = "Администратор";
        break;
    }
    $clientEmail = $_SESSION['clientData']['clientEmail'];
    $messageCounter = countIncomingMessages($clientEmail);
    $messageCounter = implode(',', $messageCounter);
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
  case 'updateAvatar':
    $clientId = filter_input(INPUT_POST, 'clientId', FILTER_SANITIZE_NUMBER_INT);
    $clientData = getClientId($clientId);
    $oldImage = $clientData['clientImage'];
    if (!empty($_FILES['fupload']['name']))
    {
      $fupload = $_FILES['fupload']['name'];
      $fupload = trim($fupload);
      if ($fupload == '' or empty($fupload)) { unset($fupload);}
    }
    if (!isset($fupload) or empty($fupload) or $fupload == '') {$avatar    = "/sinegorie/images/clientAvatars/no-avatar.jpg";} 
    else { $path_to_90_directory    = '../images/clientAvatars/';         
      if (preg_match('/[.](JPG)|(jpg)|(gif)|(GIF)|(png)|(PNG)$/', $_FILES['fupload']['name']))
      {
        $filename =    $_FILES['fupload']['name'];
        $source =    $_FILES['fupload']['tmp_name'];
        $target =    $path_to_90_directory . $filename;
        move_uploaded_file($source,    $target);         
        if (preg_match('/[.](GIF)|(gif)$/',    $filename)) {
          $im    = imagecreatefromgif($path_to_90_directory . $filename); 
        }
        if (preg_match('/[.](PNG)|(png)$/',    $filename)) {
          $im =    imagecreatefrompng($path_to_90_directory . $filename); 
        }
        if (preg_match('/[.](JPG)|(jpg)|(jpeg)|(JPEG)$/',    $filename)) {
          $im =    imagecreatefromjpeg($path_to_90_directory . $filename); 
        }          
        $w    = 60;         
        $w_src    = imagesx($im);
        $h_src    = imagesy($im);
        $dest = imagecreatetruecolor($w, $w);
        if ($w_src > $h_src)
          imagecopyresampled(
            $dest,
            $im,
            0,
            0,
            round((max($w_src, $h_src) - min($w_src, $h_src)) / 2),
            0,
            $w,
            $w,
            min($w_src, $h_src),
            min($w_src, $h_src)
          );
        if ($w_src < $h_src)
          imagecopyresampled(
            $dest,
            $im,
            0,
            0,
            0,
            0,
            $w,
            $w,
            min($w_src, $h_src),
            min($w_src, $h_src)
          ); 
        if ($w_src == $h_src)
          imagecopyresampled($dest,    $im, 0, 0, 0, 0, $w, $w, $w_src, $w_src);
        $date = time();
        imagejpeg($dest,    $path_to_90_directory . $date . ".jpg"); 
        $avatar    = $path_to_90_directory . $date . ".jpg";
        $delfull    = $path_to_90_directory . $filename;
        unlink($delfull);
      } else {
        // exit("Аватара должна быть в формате <strong>JPG,GIF или PNG</strong>");
        $message = '<p class="failure">Аватара должна быть в формате <strong>JPG,GIF или PNG</strong></p>';
        include '../view/admin.php';
        exit;
      }
    }
    $clientImage = $avatar;
    $updateResult = updateAvatar($clientImage, $clientId);
    if ($updateResult) {
      if(!empty($oldImage )){
        if (!$avatar =="/sinegorie/images/clientAvatars/no-avatar.jpg"){
        unlink($oldImage);
        }
      }
      $clientData = getClientId($clientId);
      $_SESSION['clientData'] = $clientData;
      $message = "<p class=\"success\">Аватара успешно обновлена!</p>";
      $_SESSION['message'] = $message;
      include '../view/admin.php';
      exit;
    } else {
      $message = '<p class="failure">Ошибка. Аватара не обновлена</p>';
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
    $clientEmail = $_SESSION['clientData']['clientEmail'];
    $messageCounter = countIncomingMessages($clientEmail);
    $messageCounter = implode(',', $messageCounter);
    $clientStatus = $_SESSION['clientData']['clientLevel'];
    $login = $_SESSION['clientData']['clientLogin'];
    $friends = checkExistingFriend($clientId);
    $position = array_search($login, $friends);
    // Remove from array
    unset($friends[$position]);
    $displayFriends = displayFriends($friends);
    switch ($clientStatus) {
      case 1:
        $clientStatus = "<span class='failure'>Заблокирован</span>";
        break;
      case 2:
        $clientStatus= "Читатель";
        break;
      case 3:
        $clientStatus = "Модератор";
        break;
      case 4:
        $clientStatus = "Администратор";
        break;
    }
    include '../view/admin.php';
    unset($_SESSION['message']);
    break;
  case 'userpage':
    $clientId = isset($_SESSION['clientData']['clientId']);
    $userId = trim(filter_input(INPUT_GET, 'userId', FILTER_SANITIZE_NUMBER_INT));
    $isFriend = isFriend($clientId,$userId);
    $requestedFriend = isHalfFriend($clientId,$userId);
    $userInfo = getUser($userId);
    $userDisplay = displayUser($userInfo);
    if(!$isFriend){
      $friendMessage = "<a href='/sinegorie/accounts/?action=addFriend&friendId=$userId'>Добавить в друзья</a>";
    }
    if($requestedFriend){
      $friendMessage = "<p style='color:gold'>Запрос на добавление в друзья отправлен</p>";
    }
    if ($clientId == $userId){
      $friendMessage = "<p style='color:magenta'>Так выглядит ваша страничка для других пользователей</p>";
    }
    else {
      $friendMessage = "<p class='success'>Вы друзья!</p>";
    }
    include '../view/userpage.php';
    break; 
  case 'addFriend':
    $friendId = trim(filter_input(INPUT_GET, 'friendId', FILTER_SANITIZE_NUMBER_INT));
    $userId =  $_SESSION['clientData']['clientId'];
    $registerFriend = addFriend($userId, $friendId);
    if ($registerFriend === 1) {
      header("Location: {$_SERVER['HTTP_REFERER']}");
    }
    break; 
  case 'confirmFriend':
    $clientId = $_SESSION['clientData']['clientId'];
    $friendId = $_SESSION['clientData']['clientId'];
    $userId = trim(filter_input(INPUT_GET, 'friendId', FILTER_SANITIZE_NUMBER_INT));
    $confirmRequest = confirmRequest($userId, $friendId);
    if ($confirmRequest) {
      $message = "<p class='success'>Поздравляю! Вы добавили друга!</p>";
      $clientEmail = $_SESSION['clientData']['clientEmail'];
      $getIncoming = getIncomingMessages($clientEmail);
      $displayIncoming = displayIncomingMessages($getIncoming);
      $isFriend = checkFriendRequests($clientId);
      $dFReq = displayFriendRequest($isFriend);
      include '../view/messenger.php';
    }
    else {
      $message = "<p class='failure'>Ой! Что-то пошло не так...</p>";
      $clientEmail = $_SESSION['clientData']['clientEmail'];
      $getIncoming = getIncomingMessages($clientEmail);
      $displayIncoming = displayIncomingMessages($getIncoming);
      $isFriend = checkFriendRequests($clientId);
      $dFReq = displayFriendRequest($isFriend);
      include '../view/messenger.php';
    }
    break;
  case 'denyFriend':
    $userId =  $_SESSION['clientData']['clientId'];
    $friendId = trim(filter_input(INPUT_GET, 'friendId', FILTER_SANITIZE_NUMBER_INT));
    $confirmRequest = deleteRequest($userId, $friendId);
    if ($confirmRequest) {
      $message = "<p class='success'>Вы <span class='failure'>отменили</span> запрос на добавление в друзья</p>";
      $clientEmail = $_SESSION['clientData']['clientEmail'];
      $getIncoming = getIncomingMessages($clientEmail);
      $displayIncoming = displayIncomingMessages($getIncoming);
      $clientId = $_SESSION['clientData']['clientId'];
      $isFriend = checkFriendRequests($clientId);
      $dFReq = displayFriendRequest($isFriend);
      include '../view/messenger.php';
    }
    break;      
  case 'clientPrivilege-view':
    $users = getAllUsers();
    $usersDisplay = buildUsersSelector($users);
    include '../view/client-privileges.php';
    break;
  case 'setClientPrivilege':
    $clientId = trim(filter_input(INPUT_POST, 'userId', FILTER_SANITIZE_NUMBER_INT));
    $clientLevel = trim(filter_input(INPUT_POST, 'clientLevel', FILTER_SANITIZE_NUMBER_INT));
    $updateResult =  setPrivilege($clientId, $clientLevel);
    if($clientLevel === '1' and $updateResult){
      $message = "<p class='success'>Пользователь заблокирован!</p>";
      $_SESSION['message'] = $message;
      include '../view/admin.php';
      exit;
    }
    if ($updateResult) {
      $message = "<p class='success'>Новые привилегии были успешно установлены</p>";
      $_SESSION['message'] = $message;
      include '../view/admin.php';
      exit;
    } else {
      $message = '<p class="failure">Ошибка. Привилегии НЕ установлены.</p>';
      include '../view/admin.php';
      exit;
    }
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
