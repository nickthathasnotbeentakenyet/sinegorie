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
function regPoem($classificationId, $poemText, $poemImage, $poemName, $poemDate)
{
    $db = sinegorieConnect();
    $sql = 'INSERT INTO poetry (classificationId, poemText, poemImage, poemName, poemDate)
           VALUES (:classificationId, :poemText, :poemImage, :poemName, :poemDate)';
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':classificationId', $classificationId, PDO::PARAM_INT);
    $stmt->bindValue(':poemText', $poemText, PDO::PARAM_STR);
    $stmt->bindValue(':poemImage', $poemImage, PDO::PARAM_STR);
    $stmt->bindValue(':poemName', $poemName, PDO::PARAM_STR);
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
    $sql = 'SELECT * FROM poetry WHERE classificationId IN (SELECT classificationId FROM classification WHERE classificationName = :classificationName)';
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
    $sql = 'SELECT * FROM poetry WHERE poetry.poemId = :poemId';
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
function updatePoem($classificationId, $poemName, $poemText, $poemDate, $poemImage, $poemId)
{
    $db = sinegorieConnect();
    $sql = 'UPDATE poetry SET classificationId = :classificationId, poemName = :poemName, 
    poemText = :poemText, poemDate = :poemDate, poemImage = :poemImage WHERE poemId = :poemId';
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':classificationId', $classificationId, PDO::PARAM_STR);
    $stmt->bindValue(':poemName', $poemName, PDO::PARAM_STR);
    $stmt->bindValue(':poemText', $poemText, PDO::PARAM_STR);
    $stmt->bindValue(':poemDate', $poemDate, PDO::PARAM_STR);
    $stmt->bindValue(':poemImage', $poemImage, PDO::PARAM_STR);
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

// Get information for all vehicles
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