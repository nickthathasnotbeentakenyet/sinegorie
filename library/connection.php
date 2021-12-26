<?php
/*
* Proxy connection to the sinegorie database
*/

function sinegorieConnect()
{
    $server = 'localhost';
    $dbname = 'sinegorie';
    $username = 'iClient';
    $password = 'bE_-@Ztm]hB!2Kac';
    $dsn = "mysql:host=$server;dbname=$dbname";
    $options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
    try {
        $link = new PDO($dsn, $username, $password, $options);
        // * This is for test only purposes *
        // if(is_object($link)){
        //     echo 'it works!';
        // }
        return $link;
    } catch (PDOException $e) {
        // * This is for test only purposes *
        // echo "it did work ;(" . $e->getMessage();
        header('Location: /sinegorie/view/500.php');
        exit;
    }
}
// * This is for test only purposes *
// sinegorieConnect();
