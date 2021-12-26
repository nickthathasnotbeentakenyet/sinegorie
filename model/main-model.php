<?php 
// This is the Main Sinegorie Model
function getClassifications(){
    $db = sinegorieConnect(); 
    $sql = 'SELECT * FROM classification ORDER BY classificationName ASC';      
    $stmt = $db->prepare($sql);
    $stmt->execute(); 
    $classifications = $stmt->fetchAll(); 
    $stmt->closeCursor(); 
    return $classifications;
   }