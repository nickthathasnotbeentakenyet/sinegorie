<?php
/* 
* --------------------------------
* This is the Messeges Controller
* -------------------------------
*/

// Create or access a Session
session_start();

require_once '../library/connection.php';
require_once '../library/functions.php';
require_once '../model/main-model.php';
require_once '../model/accounts-model.php';
require_once '../model/messeges-model.php';


$classifications = getClassifications();
$navList = getNavigationBar($classifications);

$action = filter_input(INPUT_POST, 'action');
if ($action == NULL) {
  $action = filter_input(INPUT_GET, 'action');
}

switch ($action) {
    case 'messenger-view':
        $clientEmail = $_SESSION['clientData']['clientEmail'];
        $clientId = $_SESSION['clientData']['clientId'];
        $getIncoming = getIncomingMessages($clientEmail);
        $displayIncoming = displayIncomingMessages($getIncoming);
        $isFriend = checkFriendRequests($clientId);
        $dFReq = displayFriendRequest($isFriend);
        include '../view/messenger.php';
        break;
    case 'new-message':
        $clientEmail = $_SESSION['clientData']['clientEmail'];
        $clientId = $_SESSION['clientData']['clientId'];
        $senderId = trim(filter_input(INPUT_POST, 'senderId', FILTER_SANITIZE_NUMBER_INT));
        $senderName = $_SESSION['clientData']['clientLogin'];
        $receiverId = trim(filter_input(INPUT_POST,  'receiverId', FILTER_SANITIZE_NUMBER_INT));
        $messageText = (filter_input(INPUT_POST, 'messageText', FILTER_SANITIZE_STRING));
        if (empty($receiverId)) {
            $message = '<p class="failure">Пожалуйста, выберите получателя.</p>';
            include '../view/messenger.php';
            break;
        } 
        if(empty($messageText)){
            $message = '<p class="failure">Сообщение не может быть пустым.</p>';
            include '../view/messenger.php';
            break;
        }
        else {
            $regOutMessage = sendMessage($senderId, $senderName, $receiverId, $messageText);
            $message = '<p class="success">Сообщение успешно отправлено!</p>';
            unset($messageText);
            $getIncoming = getIncomingMessages($clientEmail);
            $displayIncoming = displayIncomingMessages($getIncoming);
            $isFriend = checkFriendRequests($clientId);
            $dFReq = displayFriendRequest($isFriend);
            include '../view/messenger.php';
            break;
        }
        break;
    case 'deleteMessage':
        $messageId = trim(filter_input(INPUT_GET, 'messageId', FILTER_SANITIZE_NUMBER_INT));
        $delMessage = deleteMessage($messageId);
        $clientId = $_SESSION['clientData']['clientId'];
        $clientEmail = $_SESSION['clientData']['clientEmail'];
        $isFriend = checkFriendRequests($clientId);
        $dFReq = displayFriendRequest($isFriend);
        $getIncoming = getIncomingMessages($clientEmail);
        $displayIncoming = displayIncomingMessages($getIncoming);
        if (!$delMessage){
            include '../view/messenger.php';
        }
        else{
            include '../view/messenger.php';
            unset($message);
        break;
        }  
    default:
        $clientEmail = $_SESSION['clientData']['clientEmail'];
        $clientId = $_SESSION['clientData']['clientId'];
        $getIncoming = getIncomingMessages($clientEmail);
        $displayIncoming = displayIncomingMessages($getIncoming);
        $isFriend = checkFriendRequests($clientId);
        $dFReq = displayFriendRequest($isFriend);
        if (!$getIncoming){
            $displayIncoming = '<p class="failure">У вас нет входящих сообщений</p>';
        }
        include '../view/messenger.php';
}