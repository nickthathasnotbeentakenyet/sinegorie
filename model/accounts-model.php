<?php

/*
 * Accounts Model
 */ 


//  Register a new client
function regClient($clientLogin, $clientEmail, $clientPassword, $clientImage){
    $db = sinegorieConnect();
    $sql = 'INSERT INTO clients (clientLogin, clientEmail, clientPassword, clientImage)
        VALUES (:clientLogin, :clientEmail, :clientPassword, :clientImage)';
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':clientLogin', $clientLogin, PDO::PARAM_STR);
    $stmt->bindValue(':clientEmail', $clientEmail, PDO::PARAM_STR);
    $stmt->bindValue(':clientPassword', $clientPassword, PDO::PARAM_STR);
    $stmt->bindValue(':clientImage', $clientImage, PDO::PARAM_STR);
    $stmt->execute();
    $rowsChanged = $stmt->rowCount();
    $stmt->closeCursor();
    return $rowsChanged;
   }

// Check for an existing email address
function checkExistingEmail($clientEmail) {
    $db =  sinegorieConnect();
    $sql = 'SELECT clientEmail FROM clients WHERE clientEmail = :email';
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':email', $clientEmail, PDO::PARAM_STR);
    $stmt->execute();
    $matchEmail = $stmt->fetch(PDO::FETCH_NUM);
    $stmt->closeCursor();
    if(empty($matchEmail)){
     return 0;
    } else {
     return 1;
    }
   }

   // Get client data based on an email address
function getClient($clientEmail){
    $db = sinegorieConnect();
    $sql = 'SELECT clientId, clientLogin, clientEmail, clientLevel, clientImage, clientPassword FROM clients WHERE clientEmail = :clientEmail';
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':clientEmail', $clientEmail, PDO::PARAM_STR);
    $stmt->execute();
    $clientData = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $clientData;
   }

//  Update client
function updateClient($clientLogin, $clientEmail, $clientId){
    $db = sinegorieConnect();
    $sql = 'UPDATE clients SET clientLogin = :clientLogin, clientEmail = :clientEmail WHERE clientId = :clientId';
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':clientLogin', $clientLogin, PDO::PARAM_STR);
    $stmt->bindValue(':clientEmail', $clientEmail, PDO::PARAM_STR);
    $stmt->bindValue(':clientId', $clientId, PDO::PARAM_INT);
    $stmt->execute();
    $rowsChanged = $stmt->rowCount();
    $stmt->closeCursor();
    return $rowsChanged;
   }

   //  Update password
function updatePassword($clientPassword, $clientId){
    $db = sinegorieConnect();
    $sql = 'UPDATE clients SET clientPassword = :clientPassword WHERE clientId = :clientId';
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':clientPassword', $clientPassword, PDO::PARAM_STR);
    $stmt->bindValue(':clientId', $clientId, PDO::PARAM_INT);
    $stmt->execute();
    $rowsChanged = $stmt->rowCount();
    $stmt->closeCursor();
    return $rowsChanged;
   }

   // Get client data based on an client ID
   function getClientId($clientId){
    $db = sinegorieConnect();
    $sql = 'SELECT clientId, clientLogin, clientEmail, clientImage, clientLevel, clientPassword FROM clients WHERE clientId = :clientId';
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':clientId', $clientId, PDO::PARAM_STR);
    $stmt->execute();
    $clientData = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $clientData;
   }

//    Get number of clients

function getClientsNumber(){
    $db = sinegorieConnect();
$sql = 'SELECT count(clientId) FROM clients';
$stmt = $db->prepare($sql);
$stmt->execute();
$accountsNumber = $stmt->fetch(PDO::FETCH_ASSOC);
$stmt->closeCursor();
return $accountsNumber;
}

// Update avatar 
function updateAvatar($clientImage, $clientId){
    $db = sinegorieConnect();
    $sql = 'UPDATE clients SET clientImage = :clientImage WHERE clientId = :clientId';
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':clientImage', $clientImage, PDO::PARAM_STR);
    $stmt->bindValue(':clientId', $clientId, PDO::PARAM_INT);
    $stmt->execute();
    $rowsChanged = $stmt->rowCount();
    $stmt->closeCursor();
    return $rowsChanged;
   }
// working with privileges 
   function getAllUsers(){
    $db = sinegorieConnect();
    $sql = 'SELECT clientId, clientLogin, clientLevel, clientComment, clientImage FROM clients ORDER BY clientlevel DESC, clientLogin ASC';
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $accounts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $accounts;
   }


   function setPrivilege($clientId, $clientLevel){
    $db = sinegorieConnect();
    $sql = 'UPDATE clients SET clientLevel = :clientLevel WHERE clientId = :clientId';
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':clientLevel', $clientLevel, PDO::PARAM_INT);
    $stmt->bindValue(':clientId', $clientId, PDO::PARAM_INT);
    $stmt->execute();
    $rowsChanged = $stmt->rowCount();
    $stmt->closeCursor();
    return $rowsChanged;
   }

   function getUser($userId){
    $db = sinegorieConnect();
    $sql = 'SELECT clients.clientId, clientLogin, clientLevel, clientComment, clientImage, reviewText, reviewDate, poemId FROM clients JOIN reviews ON reviews.clientId = clients.clientId WHERE clients.clientId = :userId';
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $userInfo = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $userInfo;
   }

   
   function addFriend($userId, $friendId){
    $db = sinegorieConnect();
    $sql = 'INSERT INTO friends (userId, friendId, confirmed)
        VALUES (:userId, :friendId, 0)';
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
    $stmt->bindValue(':friendId', $friendId, PDO::PARAM_INT);
    $stmt->execute();
    $rowsChanged = $stmt->rowCount();
    $stmt->closeCursor();
    return $rowsChanged;
   }

   function checkFriendRequests($clientId){
        $db = sinegorieConnect();
        $sql = 'SELECT confirmed, clientLogin, clientId FROM friends JOIN clients ON clients.clientId = friends.userId WHERE friendId = :clientId AND confirmed = 0';
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':clientId', $clientId, PDO::PARAM_INT);
        $stmt->execute();
        $friendRequest = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $friendRequest;
       }

       function confirmRequest($userId, $friendId){
        $db = sinegorieConnect();
        $sql = 'UPDATE friends SET confirmed = 1 WHERE userId = :userId AND friendId = :friendId';
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':friendId', $friendId, PDO::PARAM_INT);
        $stmt->execute();
        $rowsChanged = $stmt->rowCount();
        $stmt->closeCursor();
        return $rowsChanged;
       }
     
       function deleteRequest($userId, $friendId){
        $db = sinegorieConnect();
        $sql = 'DELETE FROM friends WHERE userId = :friendId AND friendId = :userId';
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':friendId', $friendId, PDO::PARAM_INT);
        $stmt->execute();
        $rowsChanged = $stmt->rowCount();
        $stmt->closeCursor();
        return $rowsChanged;
       }

       function checkExistingFriend($clientId){
        $db = sinegorieConnect();
        $sql = 'SELECT clientLogin FROM friends JOIN clients ON clients.clientId = friends.userId WHERE (friendId = :clientId OR userId = :clientId )AND confirmed = 1';
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':clientId', $clientId, PDO::PARAM_INT);
        $stmt->execute();
        $friends = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $friends;
       }

       
       function isFriend($clientId,$userId){
        $db =  sinegorieConnect();
        $sql = 'SELECT friendId, userId FROM friends WHERE (friendId = :friendId OR userId = :friendId) AND (userId = :userId OR friendId = :userId) AND confirmed = 1';
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':friendId', $clientId, PDO::PARAM_INT);
        $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $match = $stmt->fetchAll(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if(empty($match)){
         return 0;
        } else {
         return 1;
        }
       }

       function isHalfFriend($clientId,$userId){
        $db =  sinegorieConnect();
        $sql = 'SELECT friendId, userId FROM friends WHERE (friendId = :friendId OR userId = :friendId) AND (userId = :userId OR friendId = :userId) AND confirmed = 0';
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':friendId', $clientId, PDO::PARAM_INT);
        $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $match = $stmt->fetchAll(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if(empty($match)){
         return 0;
        } else {
         return 1;
        }
       }


   function getFriendListToMsg($clientId){
    $db = sinegorieConnect();
    $sql = 'SELECT clientId, clientLogin, clientLevel FROM clients 
    JOIN friends ON (clients.clientId = friends.friendId OR clients.clientId = friends.userId ) 
    WHERE (userId = :searcher OR friendId = :searcher) AND clientId != :searcher ORDER BY clientlevel DESC, clientLogin ASC';
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':searcher', $clientId, PDO::PARAM_INT);
    $stmt->execute();
    $accounts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $accounts;
   }
