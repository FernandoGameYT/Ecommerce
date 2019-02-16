<?php

    session_start();
    include("../php/UserController.php");

    $users = new Users();

    $users -> closeSession();

    header("Location: $direction");

?>