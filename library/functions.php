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
   $dv = '<ul>';
   foreach ($poems as $poem) {
      $dv .= '<li>';
      $dv .= "<a href='/sinegorie/poetry/?action=poem-view&poemId=" . urlencode($poem['poemId']) . "'>  ";
      $dv .= "<h2>$poem[poemName]</h2>";
      $dv .= '</li>';
   }
   $dv .= '</ul>';
   return $dv;
}

//  build a display of a specific poem
function buildPoemSpecInfo($poemInfo)
{
   $dv = "<div>";
   foreach ($poemInfo as $poem) {
      $dv .= "<h1>$poem[poemName]</h1>";
      $dv .= "<p id='poemBody'>$poem[poemText]</p><br>";
      $dv .= "<p>$poem[poemDate]</p>";
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
      $r .= "<div>";
      $r .= "<p><span class='reviewRev'>Рецензия от </span><span class='reviewsScreenName'>$name</span><span class='reviewsDate'> $humanDate</span></p>";
      $r .= "<p class='revTxt'>$review[reviewText]</p>";
      $r .= "<br>";
      $r .= "</div>";
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