<?php

/*
 * Messeges Model
 */ 



function sendMessage($senderId, $senderName, $receiverId, $messageText){
    $db = sinegorieConnect();
    $sql = 'INSERT INTO messages (senderId, senderName, receiverId, messageText)
        VALUES (:senderId, :senderName, :receiverId, :messageText)';
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':senderId', $senderId, PDO::PARAM_INT);
    $stmt->bindValue(':senderName', $senderName, PDO::PARAM_STR);
    $stmt->bindValue(':receiverId', $receiverId, PDO::PARAM_INT);
    $stmt->bindValue(':messageText', $messageText, PDO::PARAM_STR);
    $stmt->execute();
    $rowsChanged = $stmt->rowCount();
    $stmt->closeCursor();
    return $rowsChanged;
   }

function getIncomingMessages($clientEmail){
    $db = sinegorieConnect();
    $sql = 'SELECT messageId, messages.senderId, messages.senderName, messages.receiverId, messages.messageText, messages.messageDate, messages.isread 
    FROM messages 
    JOIN clients ON messages.receiverId = clients.clientId 
    WHERE clients.clientEmail = :clientEmail ORDER BY messages.messageDate DESC';
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':clientEmail', $clientEmail, PDO::PARAM_STR);
    $stmt->execute();
    $messagesData = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $messagesData;
   }

   function countIncomingMessages($clientEmail){
    $db = sinegorieConnect();
    $sql = 'SELECT count(messageId) 
    FROM messages 
    JOIN clients ON messages.receiverId = clients.clientId 
    WHERE clients.clientEmail = :clientEmail';
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':clientEmail', $clientEmail, PDO::PARAM_STR);
    $stmt->execute();
    $messageCounter = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $messageCounter;
   }

   function deleteMessage($messageId){
    $db = sinegorieConnect();
    $sql = 'DELETE FROM messages WHERE messageId = :messageId';
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':messageId', $messageId, PDO::PARAM_INT);
    $stmt->execute();
    $rowsChanged = $stmt->rowCount();
    $stmt->closeCursor();
    return $rowsChanged;
   }
