<?php
    include("ProductController.php");

    if(isset($_POST["phones"])) {
        $products = new Products();
        $brand = filter_var($_POST["brand"], FILTER_SANITIZE_STRING);
        $offset = filter_var($_POST["offset"], FILTER_SANITIZE_NUMBER_INT);

        $products -> brand = $brand;

        $result = $products -> getProductsRecentOfBrand($offset * 20);
        echo json_encode($result -> fetchAll());
    }else if(isset($_POST["allTypes"])) {
        $products = new Products();
        $type = filter_var($_POST["type"], FILTER_SANITIZE_STRING);
        $offset = filter_var($_POST["offset"], FILTER_SANITIZE_NUMBER_INT);

        $products -> type = $type;

        $result = $products -> getRecentProducts($offset * 20);
        echo json_encode($result -> fetchAll());
    }else if(isset($_POST["searchPhones"])) {
        $products = new Products();
        $search = filter_var($_POST["search"], FILTER_SANITIZE_STRING);
        $offset = filter_var($_POST["offset"], FILTER_SANITIZE_NUMBER_INT);

        $products -> search = $search;

        $result = $products -> getProductsBySearch($offset * 20);
        echo json_encode($result -> fetchAll());
    }else{
        header("Location: $direction");
    }

?>