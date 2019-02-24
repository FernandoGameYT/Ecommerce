<?php
    
    require dirname(__DIR__) . "/vendor/autoload.php";

    $fb = new Facebook\Facebook([
        'app_id' => '594236447661664',
        'app_secret' => '9b280c630f962ef70fb8696e48a96d45',
        'default_graph_version' => 'v2.2',
    ]);
    
?>