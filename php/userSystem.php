<?php

    session_start();
    include("userController.php");
    $users = new Users();

    if(isset($_POST["register"])) {
        $username = filter_var($_POST["username"], FILTER_SANITIZE_STRING);
        $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
        $password = filter_var($_POST["password"], FILTER_SANITIZE_STRING);

        echo $users -> addUser($username, $email, $password);
    }else if(isset($_POST["login"])) {
        $username = filter_var($_POST["username"], FILTER_SANITIZE_STRING);
        $password = filter_var($_POST["password"], FILTER_SANITIZE_STRING);

        echo $users -> hasUser($username, $password);
    }else if(isset($_POST["update_username"])) {
        $username = filter_var($_POST["username"], FILTER_SANITIZE_STRING);

        echo $users -> updateUsername($username);
    }else if(isset($_POST["update_email"])) {
        $email = filter_var($_POST["email"], FILTER_SANITIZE_STRING);

        echo $users -> updateEmail($email);
    }else if(isset($_POST["update_password"])) {
        $password = filter_var($_POST["password"], FILTER_SANITIZE_STRING);
        $oldPassword = filter_var($_POST["oldPassword"], FILTER_SANITIZE_STRING);

        echo $users -> updatePassword($password, $oldPassword);
    }

?>