<?php
/* 
* ---------------------------
* This is the main controller
* ---------------------------
*/

session_start();
require_once 'library/connection.php';
require_once 'library/functions.php';
require_once 'model/main-model.php';
require_once 'model/accounts-model.php';
require_once 'model/reviews-model.php';
require_once 'model/events-model.php';
require_once 'model/poetry-model.php';

$classifications = getClassifications();
$navList = getNavigationBar($classifications);
$action = filter_input(INPUT_POST, 'action');
 if ($action == NULL){
  $action = filter_input(INPUT_GET, 'action');
 }

 switch ($action){
 case 'template':
    include 'view/template.php';
    break;
 default:
 $accountsNumber = getClientsNumber();
 $aN = implode('|', $accountsNumber); 
 $poemsNumber = poemsNumber();
 $pN = implode('|', $poemsNumber);
 $reviewsNumber = reviewsNumber();
 $rN = implode('|', $reviewsNumber);
 $lastReview = lastReview();
 $displayLastReview = buildLastReviewDisplay($lastReview);
 $events = getEvents();
 $displayEvents = displayEvents($events);
 include 'view/home.php'; 
}