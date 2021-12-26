<?php

/*
 * Accounts Model
 */ 


//  Register a new client
function regClient($clientLogin, $clientEmail, $clientPassword){
    $db = sinegorieConnect();
    $sql = 'INSERT INTO clients (clientLogin, clientEmail, clientPassword)
        VALUES (:clientLogin, :clientEmail, :clientPassword)';
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':clientLogin', $clientLogin, PDO::PARAM_STR);
    $stmt->bindValue(':clientEmail', $clientEmail, PDO::PARAM_STR);
    $stmt->bindValue(':clientPassword', $clientPassword, PDO::PARAM_STR);
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
    $sql = 'SELECT clientId, clientLogin, clientEmail, clientLevel, clientPassword FROM clients WHERE clientEmail = :clientEmail';
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
    $sql = 'SELECT clientId, clientLogin, clientEmail, clientLevel, clientPassword FROM clients WHERE clientId = :clientId';
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