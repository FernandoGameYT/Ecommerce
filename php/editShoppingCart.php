<?php

    session_start();
    include("conexion.php");
    include("ShoppingCartController.php");
    $cart = new ShoppingCart();

    if(isset($_POST["add"])) {
        $id = filter_var($_POST["id"], FILTER_SANITIZE_NUMBER_INT);
        $quantity = filter_var($_POST["quantity"], FILTER_SANITIZE_NUMBER_INT);

        echo $cart -> addProduct($id, $quantity);
    }else if(isset($_POST["delete"])) {
        $id = filter_var($_POST["id"], FILTER_SANITIZE_NUMBER_INT);

        echo $cart -> deleteProduct($id);
    }else if(isset($_POST["editQuantity"])) {
        $id = filter_var($_POST["id"], FILTER_SANITIZE_NUMBER_INT);
        $quantity = filter_var($_POST["quantity"], FILTER_SANITIZE_NUMBER_INT);

        echo $cart -> editProduct($id, $quantity);
    }else{
        header("Location: $direction");
    }

?>