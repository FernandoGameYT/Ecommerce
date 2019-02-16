<?php

    include("UserController.php");
    include("BrandsController.php");
    include("ProductController.php");

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

?>