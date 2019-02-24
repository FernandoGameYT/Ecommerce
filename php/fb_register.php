<?php

    session_start();
    include("fb_init.php");
    include("UserController.php");

    if(!isset($_SESSION["fb_access_token"])) {
        echo "fb_access_token_error";
        exit;
    }else if(!isset($_POST["username"])) {
        header("Location: $direction");
        exit;
    }

    try {
        $response = $fb->get("/me?fields=id,email", $_SESSION["fb_access_token"]);
    } catch(Facebook\Exceptions\FacebookResponseException $e) {
        echo "Graph error";
        exit;
    } catch(Facebook\Exceptions\FacebookSDKException $e) {
        echo "Facebook SDK error";
        exit;
    }
    
    $user = $response->getGraphUser();

    $users = new Users();

    if($users -> hasUsername($_POST["username"])) {
        echo "El nombre de usuario ya existe.";
        exit;
    }

    $userData = [
        "OAuthProvider" => "facebook",
        "OAuthId" => $user["id"],
        "Email" => $user["email"],
        "Username" => $_POST["username"]
    ];

    if($users -> checkUser($userData)) {
        echo true;
        exit;
    }else{
        echo "A ocurrido un error en el servidor.";
        exit;
    }

?>