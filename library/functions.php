<?php
function getNavigationBar($classifications)
{
   $navList = '<ul class="navigation responsive">';
   $navList .= '<li id="mobileMenu"><a href="#" onclick="toggleMenu()">&#9776; Карта сайта</a></li>';
   $navList .= "<li><a href='/sinegorie/' title='Посетить главную страницу Синегорье'>Главная</a></li>";
   foreach ($classifications as $classification) {
      $navList .= "<li><a href='/sinegorie/poetry/?action=classification&classificationName=" . urlencode($classification['classificationName']) . "' title='Перейти к $classification[classificationName]'>$classification[classificationName]</a></li>";
   }
   $navList .= '</ul>';
   return $navList;
}
// Check if an email is valid
function checkEmail($clientEmail)
{
   $valEmail = filter_var($clientEmail, FILTER_VALIDATE_EMAIL);
   return $valEmail;
}

// Check the password for a minimum of 8 characters,
function checkPassword($clientPassword)
{
   $pattern = '/^(?=.*[[:digit:]])(?=.*[[:punct:]\s])(?=.*[A-Z])(?=.*[a-z])(?:.{8,})$/';
   return preg_match($pattern, $clientPassword);
}

// Check if classification name is less than 30 characters in length
function checkClassification($classificationName)
{
   $pattern = '/[а-яё]+/';
   return preg_match($pattern, $classificationName);
}

// Build the classifications select list 
function buildClassificationList($classifications)
{
   $classificationList = '<select name="classificationId" id="classificationList">';
   $classificationList .= "<option>Выберите жанр</option>";
   foreach ($classifications as $classification) {
      $classificationList .= "<option value='$classification[classificationId]'>$classification[classificationName]</option>";
   }
   $classificationList .= '</select>';
   return $classificationList;
}

// display poems based on classification.
function buildPoemsDisplay($poems)
{
   $dv = '<ul class="inv-display">';
   foreach ($poems as $poem) {
      $dv .= '<li>';
      $dv .= "<a href='/sinegorie/poetry/?action=poem-view&poemId=" . urlencode($poem['poemId']) . "'><img src='$poem[imgPath]'></a>";
      $dv .= "<h2>$poem[poemName]</h2>";
      $dv .= '</li>';
   }
   $dv .= '</ul>';
   return $dv;
}

//  build a display of a specific poem
function buildPoemSpecInfo($poemInfo)
{
   $dv = "<section class='poemPage'>";
   foreach ($poemInfo as $poem) {
      $dv .= "<h1>$poem[poemName]</h1>";
      $dv .= "<div  class='poemContainer'>";
      $dv .= "<div><img src='$poem[imgPath]' alt='Изображение $poem[poemName]'></div>";
      $dv .= "<div><p class='poemBody'>$poem[poemText]</p><br><br>";
      $dv .= "<p><span class='authorName'>$poem[poemAuthor]</span>  $poem[poemDate]</p><br></div>";
      $dv .= "</div>";
   }
   $dv .= '</section>';
   return $dv;
}
//  build a display of thumbnail pictures for a  specific poem
function buildPoemSpecInfoThumbnailImages($thumbnailImages)
{
   $dv = "<div class='thb'>";
   foreach ($thumbnailImages as $thumbs) {
      $dv .= "<img src='$thumbs[imgPath]' alt='Изображение $thumbs[imgName]>";
   }
   $dv .= '</div>';
   return $dv;
}
// ***************** reviews functions *************

// Build reviews for a specific poem
function buildReviewsDisplay($reviews)
{
   $r = '<div class="reviewsInfo">';
   foreach ($reviews as $review) {
      $name = ($review['clientLogin']);
      $ava = ($review['clientImage']);
      $date = date($review['reviewDate']);
      $arr = [
         'января',
         'февраля',
         'марта',
         'апреля',
         'мая',
         'июня',
         'июля',
         'августа',
         'сентября',
         'октября',
         'ноября',
         'декабря'
       ];
      $month = date('n')-1;
      $humanDate = date('d', strtotime($date)).' '. $arr[$month].' '.date('Y', strtotime($date));
      $r .= "<section class='reviewFlex'>";
      $r .= "<div><img src=\"$ava\"></div>";
      $r .= "<div>";
      $r .= "<p><span class='reviewRev'>Рецензия от </span><span class='reviewsScreenName'><a href='/sinegorie/accounts/?action=userpage&userId=$review[clientId]'>$name</span></a><span class='reviewsDate mhide'> $humanDate <a class='revmanLink' href='/sinegorie/reviews/?action=deleteModerView&reviewId=$review[reviewId]&poemId=$review[poemId]'>[редактировать]</a></span></p>";
      $r .= "<p class='dhide'><span class='reviewsDate'> $humanDate </span></p>";
      $r .= "<p class='revTxt'>$review[reviewText]</p>";
      $r .= "<br>";
      $r .= "</div>";
      $r .= "</section>";
   }
   $r .= '</div>';
   return $r;
}

// Build reviews for a specific user account
function buildAccountReviewsDisplay($accountReviews)
{
   $r = '<div>';
   foreach ($accountReviews as $review) {
      $date = date($review['reviewDate']);
      $arr = [
         'января',
         'февраля',
         'марта',
         'апреля',
         'мая',
         'июня',
         'июля',
         'августа',
         'сентября',
         'октября',
         'ноября',
         'декабря'
       ];
      $month = date('n')-1;
      $humanDate = date('d', strtotime($date)).' '. $arr[$month].' '.date('Y', strtotime($date));
      $r .= "<ul>";
      $r .= "<li class='gridview'><a class='adminVLink' href='/sinegorie/poetry?action=poem-view&poemId=$review[poemId]'>\"$review[poemName]\"</a><span class='reviewedDate'>[Опубликовано $humanDate]</span>
      <a class='reviewUpdLink' href='/sinegorie/reviews?action=updateView&reviewId=$review[reviewId]' title='Нажмите для изменения'>Изменить</a>
      <a class='reviewDelLink' href='/sinegorie/reviews?action=deleteView&reviewId=$review[reviewId]' title='Нажмите для удаления'>Удалить</a></li>";
      $r .= "</ul>";
      $r .= "<br>";
   }
   $r .= '</div>';
   return $r;
}


// build specific review 
function buildSpecReviewDisplay($specReview)
{
   foreach ($specReview as $review) {
      $s = "$review[reviewText]";
   }
   return $s;
}

/* * ********************************
*  Functions for working with images
* ********************************* */

// Adds "-tn" designation to file name
function makeThumbnailName($image)
{
   $i = strrpos($image, '.');
   $image_name = substr($image, 0, $i);
   $ext = substr($image, $i);
   $image = $image_name . '-tn' . $ext;
   return $image;
}

// Build images display for image management view
function buildImageDisplay($imageArray)
{
   $id = '<ul id="image-display">';
   foreach ($imageArray as $image) {
      $id .= '<li>';
      $id .= "<img src='$image[imgPath]' title='Изображение к произведению $image[poemName] ' alt='Изображение к произведению $image[poemName] '>";
      $id .= "<p><a href='/sinegorie/uploads?action=delete&imgId=$image[imgId]&filename=$image[imgName]' title='Удалить изображение'>Удалить $image[imgName]</a></p>";
      $id .= '</li>';
   }
   $id .= '</ul>';
   return $id;
}

// Build the poems select list
function buildPoemsSelect($poems)
{
   $prodList = '<select class="imageUploadSelector" name="poemId" id="poemId">';
   $prodList .= "<option>Выберите произведение</option>";
   foreach ($poems as $poem) {
      $prodList .= "<option value='$poem[poemId]'>$poem[poemName]</option>";
   }
   $prodList .= '</select>';
   return $prodList;
}


// Handles the file upload process and returns the path
// The file path is stored into the database
function uploadFile($name)
{
   // Gets the paths, full and local directory
   global $image_dir, $image_dir_path;
   if (isset($_FILES[$name])) {
      // Gets the actual file name
      $filename = $_FILES[$name]['name'];
      if (empty($filename)) {
         return;
      }
      // Get the file from the temp folder on the server
      $source = $_FILES[$name]['tmp_name'];
      // Sets the new path - images folder in this directory
      $target = $image_dir_path . '/' . $filename;
      // Moves the file to the target folder
      move_uploaded_file($source, $target);
      // Send file for further processing
      processImage($image_dir_path, $filename);
      // Sets the path for the image for Database storage
      $filepath = $image_dir . '/' . $filename;
      // Returns the path where the file is stored
      return $filepath;
   }
}

// Processes images by getting paths and 
// creating smaller versions of the image
function processImage($dir, $filename)
{
   // Set up the variables
   $dir = $dir . '/';

   // Set up the image path
   $image_path = $dir . $filename;

   // Set up the thumbnail image path
   $image_path_tn = $dir . makeThumbnailName($filename);

   // Create a thumbnail image that's a maximum of 200 pixels square
   resizeImage($image_path, $image_path_tn, 200, 200);

   // Resize original to a maximum of 500 pixels square
   resizeImage($image_path, $image_path, 500, 500);
}

// Checks and Resizes image
function resizeImage($old_image_path, $new_image_path, $max_width, $max_height)
{

   // Get image type
   $image_info = getimagesize($old_image_path);
   $image_type = $image_info[2];

   // Set up the function names
   switch ($image_type) {
      case IMAGETYPE_JPEG:
         $image_from_file = 'imagecreatefromjpeg';
         $image_to_file = 'imagejpeg';
         break;
      case IMAGETYPE_GIF:
         $image_from_file = 'imagecreatefromgif';
         $image_to_file = 'imagegif';
         break;
      case IMAGETYPE_PNG:
         $image_from_file = 'imagecreatefrompng';
         $image_to_file = 'imagepng';
         break;
      default:
         return;
   } // ends the swith

   // Get the old image and its height and width
   $old_image = $image_from_file($old_image_path);
   $old_width = imagesx($old_image);
   $old_height = imagesy($old_image);

   // Calculate height and width ratios
   $width_ratio = $old_width / $max_width;
   $height_ratio = $old_height / $max_height;

   // If image is larger than specified ratio, create the new image
   if ($width_ratio > 1 || $height_ratio > 1) {

      // Calculate height and width for the new image
      $ratio = max($width_ratio, $height_ratio);
      $new_height = round($old_height / $ratio);
      $new_width = round($old_width / $ratio);

      // Create the new image
      $new_image = imagecreatetruecolor($new_width, $new_height);

      // Set transparency according to image type
      if ($image_type == IMAGETYPE_GIF) {
         $alpha = imagecolorallocatealpha($new_image, 0, 0, 0, 127);
         imagecolortransparent($new_image, $alpha);
      }

      if ($image_type == IMAGETYPE_PNG || $image_type == IMAGETYPE_GIF) {
         imagealphablending($new_image, false);
         imagesavealpha($new_image, true);
      }

      // Copy old image to new image - this resizes the image
      $new_x = 0;
      $new_y = 0;
      $old_x = 0;
      $old_y = 0;
      imagecopyresampled($new_image, $old_image, $new_x, $new_y, $old_x, $old_y, $new_width, $new_height, $old_width, $old_height);

      // Write the new image to a new file
      $image_to_file($new_image, $new_image_path);
      // Free any memory associated with the new image
      imagedestroy($new_image);
   } else {
      // Write the old image to a new file
      $image_to_file($old_image, $new_image_path);
   }
   // Free any memory associated with the old image
   imagedestroy($old_image);
} // ends resizeImage function



// *************************************** 

function buildLastReviewDisplay($lastReview) {
   $r = '<div>';
   foreach ($lastReview as $lr) {
      $date = date($lr['reviewDate']);
      $arr = [
         'января',
         'февраля',
         'марта',
         'апреля',
         'мая',
         'июня',
         'июля',
         'августа',
         'сентября',
         'октября',
         'ноября',
         'декабря'
       ];
      $month = date('n')-1;
      $humanDate = date('d', strtotime($date)).' '. $arr[$month].' '.date('Y', strtotime($date));
      $r .= "<ul class='lastreview'>";
      $r .= "<li>От <span class='reviewsScreenName'><a href='/sinegorie/accounts/?action=userpage&userId=$lr[clientId]'> $lr[clientLogin]</span></a> <span class='reviewdate'>$humanDate</span></li>";
      $r .= "<li>На произведение: <a href='/sinegorie/poetry?action=poem-view&poemId=$lr[poemId]'>\"$lr[poemName]\"</a>";
      $r .= "<li class='reviewText'>$lr[reviewText]</li>";
      $r .= "</ul>";
   }
   $r .= '</div>';
   return $r;
}

function buildClassificationSelector($classifications){
   $classList = '<select class="imageUploadSelector" name="classificationId" id="classificationId">';
   $classList .= "<option>Выберите жанр</option>";
   foreach ($classifications as $cls) {
      $classList .= "<option value='$cls[classificationId]'>$cls[classificationName]</option>";
   }
   $classList .= '</select>';
   return $classList;
}

function displayFoundPoem($findPoem){
   $r = '<div>';
   foreach ($findPoem as $poem) {
      $r .= "<ul class='fpoemList'>";
      $r .= "<li  ><a class='adminVLink' href='/sinegorie/poetry?action=poem-view&poemId=$poem[poemId]'>\"$poem[poemName]\"</a>";
      $r .= "<li><a class='reviewUpdLink' href='/sinegorie/poetry?action=updateEvent&poemId=$poem[poemId]' title='Нажмите для изменения'>Изменить</a>";
      $r .= "<li><a class='reviewUpdLink' href='/sinegorie/poetry?action=deleteEvent&poemId=$poem[poemId]' title='Нажмите для удаления'>Удалить</a>";
      $r .= "</ul>";
      $r .= "<br>";
   }
   $r .= '</div>';
   return $r;
}

function displayEvents($events){
   $r = '<div>';
   foreach ($events as $event) {
      $time = substr( $event['eventTime'], 0, 5 );
      $r .= "<h3>$event[eventName]</h3>";
      $r .= "<p>Описание:<br> $event[eventText]</p>";
      $r .= "<p>Дата: $event[eventDate]</p>";
      $r .= "<p>Время: $time</p>";
   }
   $r .= '</div>';
   return $r;
}

function displayEventsToDelete($events){
   $r = '<div>';
   foreach ($events as $event) {
      $date = date($event['eventDate']);
      $arr = [
         'января',
         'февраля',
         'марта',
         'апреля',
         'мая',
         'июня',
         'июля',
         'августа',
         'сентября',
         'октября',
         'ноября',
         'декабря'
       ];
      $month = date('n')-1;
      $humanDate = date('d', strtotime($date)).' '. $arr[$month].' '.date('Y', strtotime($date));
      $r .= "<ul>";
      $r .= "<li class='gridview'><p><span>$event[eventName]</span> назначенное на <span>$humanDate</span><a class='reviewUpdLink' href='/sinegorie/events?action=deleteEvent&eventId=$event[eventId]' title='Нажмите для удаления'>Удалить</a></p>";
      $r .= "</ul>";
   }
   $r .= '</div>';
   return $r;
}

// Display users list to work with privileges

function buildUsersSelector($users){
   $usersList = '<select class="userSelector" name="userId" id="userId">';
   $usersList .= "<option>Выберите пользователя</option>";
   foreach ($users as $user) {
      switch ($user['clientLevel']) {
         case 1:
            $userLevel = '[Заблокированный]';
            break;
         case 2:
            $userLevel = '[Читатель]';
            break;
         case 3:
            $userLevel = '[Модератор]';
            break;
         case 4:
            $userLevel = '[Администратор]';
            break;
         default:
         return;
      }
      $usersList .= "<option value='$user[clientId]'>$userLevel $user[clientLogin]</option>";
   }
   $usersList .= '</select>';
   return $usersList;
}


function displayIncomingMessages($getIncoming){
   $incomingMessagesList = "<ul class='msgItem'>";
   foreach ($getIncoming as $message){
      $date = date($message['messageDate']);
      $time = date('H:i', strtotime($date));
      $arr = [
         'января',
         'февраля',
         'марта',
         'апреля',
         'мая',
         'июня',
         'июля',
         'августа',
         'сентября',
         'октября',
         'ноября',
         'декабря'
       ];
      $month = date('n')-1;
      $humanDate = date('d', strtotime($date)).' '. $arr[$month].' '.date('Y', strtotime($date));
      $incomingMessagesList .= "<li>Сообщение от <a class='msgName' href='/sinegorie/accounts/?action=userpage&userId=$message[senderId]'>$message[senderName]</a> <span class='msgDate'>$humanDate в $time</span> <a class='msgDel' href='/sinegorie/messages/?action=deleteMessage&messageId=$message[messageId]'>удалить</a>
      <p class='msgText'>$message[messageText]</p></li><br>";
   }
$incomingMessagesList .= "</ul>";
return $incomingMessagesList;
}

function displayUser($userInfo){
   $uf = "<ul>";
   foreach ($userInfo as $user){
      switch ($user['clientLevel']) {
         case 1:
            $userLevel = 'Заблокированный';
            break;
         case 2:
            $userLevel = 'Читатель';
            break;
         case 3:
            $userLevel = 'Модератор';
            break;
         case 4:
            $userLevel = 'Администратор';
            break;
         default:
         return;
      }  
   }
   $clientId = $user['clientId'];
   $accountReviews = getAccountReviews($clientId);
   $uf .= "<li>Логин: $user[clientLogin]</li>";
   $uf .= "<li>Аватара:<br><img src='$user[clientImage]'></li>";
   $uf .= "<li>Привилегии: $userLevel</li>";
   $uf .= "<li>Рецензии:</li>";
   $uf .= buildUserReviewsUserPage($accountReviews);
   $uf .= "</ul>";
return $uf;
}

function buildUserReviewsUserPage($accountReviews)
{
   $r = '<div>';
   foreach ($accountReviews as $review) {
      $date = date($review['reviewDate']);
      $arr = [
         'января',
         'февраля',
         'марта',
         'апреля',
         'мая',
         'июня',
         'июля',
         'августа',
         'сентября',
         'октября',
         'ноября',
         'декабря'
       ];
      $month = date('n')-1;
      $humanDate = date('d', strtotime($date)).' '. $arr[$month].' '.date('Y', strtotime($date));
      $r .= "<ul>";
      $r .= "<li> $review[reviewText]</li>";
      $r .= "<li>На произведение: <a href='/sinegorie/poetry?action=poem-view&poemId=$review[poemId]'>\"$review[poemName]\"</a><span class='reviewedDate'> [Опубликовано $humanDate]</span>";
      $r .= "</ul>";
      $r .= "<br>";
   }
   $r .= '</div>';
   return $r;
}

function displayFriendRequest($isFriend){
   $fr = "<ul class='msgItem'>";
   foreach ($isFriend as $friend) {
   $fr .= "<li>Пользователь <span class='authorName'>$friend[clientLogin]</span> желает стать вашим другом.<br>";
   $fr .= "Принять предложение? <a class='yes' href='/sinegorie/accounts/?action=confirmFriend&friendId=$friend[clientId]'>Да</a>  <a class='no' href='/sinegorie/accounts/?action=denyFriend&friendId=$friend[clientId]'>Нет</a></li><br>";
}
   $fr .= "</ul>";
   return $fr;
}

function displayFriends($friends){
   $fr = "<h3>Друзья: </h3>";
   $fr .= "<ol class='frlist'>";
   foreach ($friends as $friend) {
   $fr .= "<li>$friend[clientLogin]</li>";
   }
   $fr .= "</ol>";
   return $fr;
}
