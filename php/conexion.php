<?php

    $pdo = new PDO("mysql:host=localhost;dbname=ecommerce", "root", "");
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // $direction = "http://localhost/Ecommerce/";
    $direction = "http://" . $_SERVER["HTTP_HOST"] . "/Ecommerce/";
    //$cookie_direction = "localhost";
    $cookie_direction = $_SERVER["HTTP_HOST"];

?>