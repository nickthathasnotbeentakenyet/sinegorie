<?php

/*
 * Poetry Model
 */

//  Register a new genre
function regClassification($classificationName)
{
    $db = sinegorieConnect();
    $sql = 'INSERT INTO classification (classificationName) VALUES (:classificationName)';
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':classificationName', $classificationName, PDO::PARAM_STR);
    $stmt->execute();
    $rowsChanged = $stmt->rowCount();
    $stmt->closeCursor();
    return $rowsChanged;
}

//  Register a new poem
function regPoem($classificationId, $poemText, $poemName, $poemAuthor, $poemDate)
{
    $db = sinegorieConnect();
    $sql = 'INSERT INTO poetry (classificationId, poemText, poemName, poemAuthor, poemDate)
           VALUES (:classificationId, :poemText, :poemName, :poemAuthor, :poemDate)';
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':classificationId', $classificationId, PDO::PARAM_INT);
    $stmt->bindValue(':poemText', $poemText, PDO::PARAM_STR);
    $stmt->bindValue(':poemName', $poemName, PDO::PARAM_STR);
    $stmt->bindValue(':poemAuthor', $poemAuthor, PDO::PARAM_STR);
    $stmt->bindValue(':poemDate', $poemDate, PDO::PARAM_STR);
    $stmt->execute();
    $rowsChanged = $stmt->rowCount();
    $stmt->closeCursor();
    return $rowsChanged;
}


// Get poems by classificationId 
function getAllPoemsByClassification($classificationId)
{
    $db = sinegorieConnect();
    $sql = ' SELECT * FROM poetry WHERE classificationId = :classificationId';
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':classificationId', $classificationId, PDO::PARAM_INT);
    $stmt->execute();
    $poems = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $poems;
}

// get a list of poems based on the classificationName
function getPoemsByClassification($classificationName)
{
    $db = sinegorieConnect();
    // $sql = 'SELECT * FROM poetry WHERE classificationId IN (SELECT classificationId FROM classification WHERE classificationName = :classificationName)';
    $sql = 'SELECT * FROM poetry JOIN images ON poetry.poemId = images.poemId WHERE (imgPrimary LIKE 1 AND imgName LIKE "%-tn.%") AND poetry.classificationId IN (SELECT classificationId FROM classification WHERE classificationName = :classificationName)' ;
    // $sql = 'SELECT imgPath, imgName FROM images JOIN poetry ON poetry.poemId = images.poemId WHERE ( imgPrimary = 0 AND imgPath LIKE "%-tn%") AND poetry.poemId = :poemId';
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':classificationName', $classificationName, PDO::PARAM_STR);
    $stmt->execute();
    $poems = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $poems;

}
// get all info about a specific poem
function getSpecificPoemInfo($poemId)
{
    $db = sinegorieConnect();
    $sql = 'SELECT * FROM poetry JOIN images ON poetry.poemId = images.poemId WHERE poetry.poemId = :poemId AND (imgPrimary LIKE 1 AND imgName NOT LIKE "%-tn.%")';
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':poemId', $poemId, PDO::PARAM_INT);
    $stmt->execute();
    $poemInfo = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $poemInfo;
}
// Get poem information by poemId
function getPoemInfo($poemId)
{
    $db = sinegorieConnect();
    $sql = 'SELECT * FROM poetry WHERE poemId = :poemId';
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':poemId', $poemId, PDO::PARAM_INT);
    $stmt->execute();
    $poemInfo = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $poemInfo;
}

//  Update a poem
function updatePoem($classificationId, $poemName, $poemText, $poemDate, $poemAuthor, $poemId)
{
    $db = sinegorieConnect();
    $sql = 'UPDATE poetry SET classificationId = :classificationId, poemName = :poemName, 
    poemText = :poemText, poemDate = :poemDate, poemAuthor = :poemAuthor WHERE poemId = :poemId';
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':classificationId', $classificationId, PDO::PARAM_STR);
    $stmt->bindValue(':poemName', $poemName, PDO::PARAM_STR);
    $stmt->bindValue(':poemText', $poemText, PDO::PARAM_STR);
    $stmt->bindValue(':poemDate', $poemDate, PDO::PARAM_STR);
    $stmt->bindValue(':poemAuthor', $poemAuthor, PDO::PARAM_STR);
    $stmt->bindValue(':poemId', $poemId, PDO::PARAM_INT);
    $stmt->execute();
    $rowsChanged = $stmt->rowCount();
    $stmt->closeCursor();
    return $rowsChanged;
}
//  Delete a poem
function deletePoem($poemId)
{
    $db = sinegorieConnect();
    $sql = 'DELETE FROM poetry WHERE poemId = :poemId';
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':poemId', $poemId, PDO::PARAM_INT);
    $stmt->execute();
    $rowsChanged = $stmt->rowCount();
    $stmt->closeCursor();
    return $rowsChanged;
}

function getPoems()
{
    $db = sinegorieConnect();
    $sql = 'SELECT poemId, poemName FROM poetry';
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $poemInfo = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $poemInfo;
}

function getGenres(){
    $db = sinegorieConnect();
    $sql = 'SELECT * FROM classification';
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $genres = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $genres;
}

function updateClassification($classificationId, $classificationName){
    $db = sinegorieConnect();
    $sql = 'UPDATE classification SET classificationName = :classificationName WHERE classificationId = :classificationId';
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':classificationId', $classificationId, PDO::PARAM_INT);
    $stmt->bindValue(':classificationName', $classificationName, PDO::PARAM_STR);
    $stmt->execute();
    $rowsChanged = $stmt->rowCount();
    $stmt->closeCursor();
    return $rowsChanged;
}

function findPoem($poemQuery){
    $req = "%$poemQuery%";
    $db = sinegorieConnect();
    $sql = "SELECT * FROM poetry WHERE poemName LIKE :req";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':req', $req, PDO::PARAM_STR);
    $stmt->execute();
    $fPoem = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $fPoem;
}
// check if classification is empty
function emptyClassification($classificationId){
    $db = sinegorieConnect();
    $sql = 'SELECT * FROM poetry JOIN classification ON classification.classificationId = poetry.classificationId WHERE classification.classificationId = :classificationId';
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':classificationId', $classificationId, PDO::PARAM_INT);
    $stmt->execute();
    $contents = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $contents;
}


function deleteClassification($classificationId){
    $db = sinegorieConnect();
    $sql = 'DELETE FROM classification WHERE classificationId = :classificationId';
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':classificationId', $classificationId, PDO::PARAM_INT);
    $stmt->execute();
    $rowsChanged = $stmt->rowCount();
    $stmt->closeCursor();
    return $rowsChanged;
}

// get total number of poems
function poemsNumber(){
    $db = sinegorieConnect();
    $sql = 'SELECT count(poemId) FROM poetry';
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $rows;
} 
