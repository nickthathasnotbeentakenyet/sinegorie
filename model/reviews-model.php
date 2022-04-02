<?php

//  Insert a new review
function createReview($reviewText, $poemId, $clientId)
{
    $db = sinegorieConnect();
    $sql = 'INSERT INTO reviews (reviewText, poemId, clientId) VALUES (:reviewText, :poemId, :clientId)';
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':reviewText', $reviewText, PDO::PARAM_STR);
    $stmt->bindValue(':poemId', $poemId, PDO::PARAM_INT);
    $stmt->bindValue(':clientId', $clientId, PDO::PARAM_INT);
    $stmt->execute();
    $rowsChanged = $stmt->rowCount();
    $stmt->closeCursor();
    return $rowsChanged;
}

//    get all reviews for a specific poem
function getAllReviews($poemId)
{
    $db = sinegorieConnect();
    $sql = 'SELECT reviews.poemId, reviews.reviewId, reviews.reviewText, reviews.reviewDate, clients.clientId, clients.clientLogin, clients.clientImage
            FROM reviews 
            JOIN poetry ON reviews.poemId = poetry.poemId 
            JOIN clients ON reviews.clientId = clients.clientId
                WHERE reviews.poemId = :poemId ORDER BY reviews.reviewDate DESC';
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':poemId', $poemId, PDO::PARAM_INT);
    $stmt->execute();
    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $reviews;
}

// get all reviews for a specific user account

function getAccountReviews($clientId)
{
    $db = sinegorieConnect();
    $sql = 'SELECT poetry.poemId, poetry.poemName, reviews.reviewText, reviews.reviewDate, reviews.reviewId 
            FROM reviews
            JOIN poetry ON reviews.poemId = poetry.poemId 
            WHERE clientId = :clientId';
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':clientId', $clientId, PDO::PARAM_INT);
    $stmt->execute();
    $accountReviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $accountReviews;
}

// get a specific review
function getSpecificReview($reviewId)
{
    $db = sinegorieConnect();
    $sql = 'SELECT * FROM reviews WHERE reviewId = :reviewId';
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':reviewId', $reviewId, PDO::PARAM_INT);
    $stmt->execute();
    $specReview = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $specReview;
}

// update a specific review
function updateReview($reviewText, $reviewId, $reviewDate)
{
    $db = sinegorieConnect();
    $sql = 'UPDATE reviews SET reviewText = :reviewText, reviewDate = :reviewDate
	 WHERE reviewId = :reviewId';
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':reviewText', $reviewText, PDO::PARAM_STR);
    $stmt->bindValue(':reviewId', $reviewId, PDO::PARAM_INT);
    $stmt->bindValue(':reviewDate', $reviewDate, PDO::PARAM_STR);
    $stmt->execute();
    $rowsChanged = $stmt->rowCount();
    $stmt->closeCursor();
    return $rowsChanged;
}

// delete a specific review
function deleteReview($reviewId)
{
    $db = sinegorieConnect();
    $sql = 'DELETE FROM reviews WHERE reviewId = :reviewId';
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':reviewId', $reviewId, PDO::PARAM_INT);
    $stmt->execute();
    $rowsChanged = $stmt->rowCount();
    $stmt->closeCursor();
    return $rowsChanged;
}

// get total number of reviews
function reviewsNumber(){
    $db = sinegorieConnect();
    $sql = 'SELECT count(reviewId) FROM reviews';
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $rows;
} 

// get last review
function lastReview(){
    $db = sinegorieConnect();
    $sql = 'SELECT reviewText, reviewDate, clients.clientId, clientLogin, reviews.poemId ,poemName  
    FROM reviews JOIN clients ON reviews.clientId = clients.clientId 
    JOIN poetry ON poetry.poemId = reviews.poemId ORDER BY reviews.reviewId DESC LIMIT 1 ';
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $rows;
} 


