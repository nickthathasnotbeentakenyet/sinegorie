<?php

// Events controller

session_start();

require_once '../library/connection.php';
require_once '../library/functions.php';
require_once '../model/main-model.php';
require_once '../model/accounts-model.php';
require_once '../model/events-model.php';

$classifications = getClassifications();
$navList = getNavigationBar($classifications);

$action = filter_input(INPUT_POST, 'action');
if ($action == NULL) {
    $action = filter_input(INPUT_GET, 'action');
}

switch ($action) {
    case 'newEvent-view':
        include '../view/add-event.php';
        break;
    case 'newEvent':
        // $clientData = getClient($clientEmail);
        // $clientId = $_SESSION['clientData']['clientId'];
        $eventName = trim(filter_input(INPUT_POST, 'eventName', FILTER_SANITIZE_STRING));
        $eventText = trim(filter_input(INPUT_POST, 'eventText', FILTER_SANITIZE_STRING));
        $eventDate = trim(filter_input(INPUT_POST, 'eventDate', FILTER_SANITIZE_STRING));
        $eventTime = trim(filter_input(INPUT_POST, 'eventTime', FILTER_SANITIZE_STRING));
        if (empty($eventName) || empty($eventText)) {
            $message = '<p class="failure">Пожалуйста, заполните все поля.</p>';
            include '../view/add-event.php';
            exit;
          }else {
            $registerEvent = newEvent($eventName,$eventText,$eventDate,$eventTime);
            $message = '<p class="success">Событие успешно добавлено!</p>';
            include '../view/admin.php';
            exit;
        }  
        break;
    case 'deleteEvent-view':
        include '../view/event-delete.php';
        break;
    case 'deleteEvent':
        $eventId = trim(filter_input(INPUT_GET, 'eventId', FILTER_SANITIZE_NUMBER_INT));
        $remove = deleteEvent($eventId);
        if ($remove) {
            $message = "<p class='success'>Событие успешно удалено!.</p>";
        } else {
            $message = "<p class='failure'>Ошибка! Событие НЕ удалено.</p>";
        }
        $_SESSION['message'] = $message;
        header('location: /sinegorie/accounts/index.php?action=admin');
        break;
    default:
    $events = getEvents();
    if (count($events)<1){
        $noEvents = "<p class='failure'>У вас нет событий для отображения</p>";
    }
    $displayDelete = displayEventsToDelete($events);
    include '../view/event-management.php';
}