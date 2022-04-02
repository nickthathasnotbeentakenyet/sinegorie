<?php

/*
 * Events Model
 */ 

 //  Register a new event
function newEvent($eventName, $eventText, $eventDate, $eventTime){
    $db = sinegorieConnect();
    $sql = 'INSERT INTO events (eventName, eventText, eventDate, eventTime)
        VALUES (:eventName, :eventText, :eventDate, :eventTime)';
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':eventName', $eventName, PDO::PARAM_STR);
    $stmt->bindValue(':eventText', $eventText, PDO::PARAM_STR);
    $stmt->bindValue(':eventDate', $eventDate, PDO::PARAM_STR);
    $stmt->bindValue(':eventTime', $eventTime, PDO::PARAM_STR);
    $stmt->execute();
    $rowsChanged = $stmt->rowCount();
    $stmt->closeCursor();
    return $rowsChanged;
   }

      // Get all events
function getEvents(){
    $db = sinegorieConnect();
    $sql = 'SELECT * FROM events';
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $eventData = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $eventData;
   }

   function deleteEvent($eventId){
    $db = sinegorieConnect();
    $sql = 'DELETE FROM events WHERE eventId = :eventId';
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':eventId', $eventId, PDO::PARAM_INT);
    $stmt->execute();
    $rowsChanged = $stmt->rowCount();
    $stmt->closeCursor();
    return $rowsChanged;
}