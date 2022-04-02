<?php

// *** poem image uploads model ***

function storeImages($imgPath, $poemId, $imgName, $imgPrimary)
{
    $db = sinegorieConnect();
    $sql = 'INSERT INTO images (poemId, imgPath, imgName, imgPrimary) VALUES (:poemId, :imgPath, :imgName, :imgPrimary)';
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':poemId', $poemId, PDO::PARAM_INT);
    $stmt->bindValue(':imgPath', $imgPath, PDO::PARAM_STR);
    $stmt->bindValue(':imgName', $imgName, PDO::PARAM_STR);
    $stmt->bindValue(':imgPrimary', $imgPrimary, PDO::PARAM_INT);
    $stmt->execute();
    // Make and store the thumbnail image information
    $imgPath = makeThumbnailName($imgPath);
    $imgName = makeThumbnailName($imgName);
    $stmt->bindValue(':poemId', $poemId, PDO::PARAM_INT);
    $stmt->bindValue(':imgPath', $imgPath, PDO::PARAM_STR);
    $stmt->bindValue(':imgName', $imgName, PDO::PARAM_STR);
    $stmt->bindValue(':imgPrimary', $imgPrimary, PDO::PARAM_INT);
    $stmt->execute();
    $rowsChanged = $stmt->rowCount();
    $stmt->closeCursor();
    return $rowsChanged;
}

// Get Image Information from images table
function getImages()
{
    $db = sinegorieConnect();
    $sql = 'SELECT imgId, imgPath, imgName, imgDate, poetry.poemId, poemName FROM images JOIN poetry ON images.poemId = poetry.poemId';
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $imageArray = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $imageArray;
}

// Delete image information from the images table
function deleteImage($imgId)
{
    $db = sinegorieConnect();
    $sql = 'DELETE FROM images WHERE imgId = :imgId';
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':imgId', $imgId, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->rowCount();
    $stmt->closeCursor();
    return $result;
}

// Check for an existing image
function checkExistingImage($imgName){
    $db = sinegorieConnect();
    $sql = "SELECT imgName FROM images WHERE imgName = :name";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':name', $imgName, PDO::PARAM_STR);
    $stmt->execute();
    $imageMatch = $stmt->fetch();
    $stmt->closeCursor();
    return $imageMatch;
   }

//    Obtain thumbnail image information
function getThumbnailImage($poemId){
    $db = sinegorieConnect();
    $sql = 'SELECT imgPath, imgName FROM images JOIN poetry ON poetry.poemId = images.poemId WHERE ( imgPrimary = 0 AND imgPath LIKE "%-tn%") AND poetry.poemId = :poemId';
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':poemId', $poemId, PDO::PARAM_STR);
    $stmt->execute();
    $thumbnailPath = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $thumbnailPath;
}