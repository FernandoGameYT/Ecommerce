<?php

    include("UserController.php");
    include("BrandsController.php");
    include("ProductController.php");
    include("OrderController.php");

    $users = new Users();

    if($users -> checkSession()) {
        if($users -> data["Permits"] == 0) {
            header("Location: $direction");
            exit;
        }
    }else{
        header("Location: $direction");
        exit;
    }

    $brands = new Brands();
    $products = new Products();
    $orders = new Orders();

    //Brands
    if(isset($_POST["addBrand"])) {
        $name = filter_var($_POST["name"], FILTER_SANITIZE_STRING);

        $brands -> addBrand($name);

        header("Location: ".$direction."Admins/brands/");
        exit;
    }else if(isset($_POST["editBrand"])) {
        $id = filter_var($_POST["id"], FILTER_SANITIZE_NUMBER_INT);
        $name = filter_var($_POST["name"], FILTER_SANITIZE_STRING);

        $brands -> editBrand($id, $name);

        header("Location: ".$direction."Admins/brands/");
        exit;
    }else if(isset($_POST["deleteBrand"])) {
        $id = filter_var($_POST["id"], FILTER_SANITIZE_NUMBER_INT);

        $brands -> deleteBrand($id);

        header("Location: ".$direction."Admins/brands/");
        exit;
    }
    
    //Products

    else if(isset($_POST["addProduct"])) {
        $name = filter_var($_POST["name"], FILTER_SANITIZE_STRING);
        $image = filter_var($_POST["image"], FILTER_SANITIZE_STRING);
        $description = filter_var($_POST["description"], FILTER_SANITIZE_STRING);
        $price = filter_var($_POST["price"], FILTER_SANITIZE_NUMBER_INT);
        $available = filter_var($_POST["available"], FILTER_SANITIZE_NUMBER_INT);
        $type = filter_var($_POST["type"], FILTER_SANITIZE_STRING);
        $brand = filter_var($_POST["brand"], FILTER_SANITIZE_STRING);

        $products -> addProduct($name, $image, $description, $price, $available, $type, $brand);

        header("Location: ".$direction."Admins/products/");
        exit;
    }else if(isset($_POST["editProduct"])) {
        $id = filter_var($_POST["id"], FILTER_SANITIZE_NUMBER_INT);
        $name = filter_var($_POST["name"], FILTER_SANITIZE_STRING);
        $image = filter_var($_POST["image"], FILTER_SANITIZE_STRING);
        $description = filter_var($_POST["description"], FILTER_SANITIZE_STRING);
        $price = filter_var($_POST["price"], FILTER_SANITIZE_NUMBER_INT);
        $available = filter_var($_POST["available"], FILTER_SANITIZE_NUMBER_INT);
        $type = filter_var($_POST["type"], FILTER_SANITIZE_STRING);
        $brand = filter_var($_POST["brand"], FILTER_SANITIZE_STRING);

        $products -> editProduct($id, $name, $image, $description, $price, $available, $type, $brand);

        header("Location: ".$direction."Admins/products/");
        exit;
    }
    else if(isset($_POST["deleteProduct"])) {
        $id = filter_var($_POST["id"], FILTER_SANITIZE_NUMBER_INT);

        $products -> deleteProduct($id);

        header("Location: ".$direction."Admins/products/");
        exit;
    }

    //Users

    else if(isset($_POST["addUser"])) {
        $username = filter_var($_POST["username"], FILTER_SANITIZE_STRING);
        $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
        $password = filter_var($_POST["password"], FILTER_SANITIZE_STRING);
        $permits = filter_var($_POST["permits"], FILTER_SANITIZE_NUMBER_INT);

        $msg = $users -> addUserByAdmin($username, $email, $password, $permits);
        header("Location: ".$direction."Admins/users?msg=$msg");
        exit;
    }else if(isset($_POST["editUser"])) {
        $id = filter_var($_POST["id"], FILTER_SANITIZE_NUMBER_INT);
        $username = filter_var($_POST["username"], FILTER_SANITIZE_STRING);
        $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
        $password = filter_var($_POST["password"], FILTER_SANITIZE_STRING);
        $permits = filter_var($_POST["permits"], FILTER_SANITIZE_NUMBER_INT);

        $msg = $users -> updateUser($id, $username, $email, $password, $permits);
        header("Location: ".$direction."Admins/users?msg=$msg");
        exit; 
    }else if(isset($_POST["deleteUser"])) {
        $id = filter_var($_POST["id"], FILTER_SANITIZE_NUMBER_INT);

        $msg = $users -> deleteUser($id);
        echo $msg;
        exit;
    }

    //Orders

    else if(isset($_POST["editOrder"])) {
        $id = filter_var($_POST["id"], FILTER_SANITIZE_NUMBER_INT);
        $subtotal = filter_var($_POST["subtotal"], FILTER_SANITIZE_NUMBER_FLOAT);
        $shipping = filter_var($_POST["shipping"], FILTER_SANITIZE_NUMBER_FLOAT);
        $date = filter_var($_POST["date"], FILTER_SANITIZE_STRING);

        $orders -> updateOrder($id, $subtotal, $shipping, $date);
        header("Location: ".$direction."Admins/orders/");
        exit; 
    }else if(isset($_POST["deleteOrder"])) {
        $id = filter_var($_POST["id"], FILTER_SANITIZE_NUMBER_INT);

        $orders -> deleteOrder($id);
        header("Location: ".$direction."Admins/orders/");
        exit; 
    }

?>