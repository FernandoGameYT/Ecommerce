<?php
    include("php/ProductController.php");
    include("php/drawProducts.php");

    $products = new Products();

    if(isset($_GET["type"])) {

        $products -> type = $_GET["type"];
        $typeName = $_GET["type"];

    }else if(isset($_GET["id"])) {

        $products -> id = $_GET["id"];
        $typeName = $_GET["name"];

    }else if(isset($_GET["search"])) {

        $products -> search = $_GET["search"];
        $typeName = $_GET["search"] . " - Buscar en PhoneShop";

    }else{
        header("Location: $direction");
    }

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>PhoneShop - <?php echo $typeName;?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <base href="<?php echo $direction;?>">
    <link rel="stylesheet" href="css/icon.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
    <?php
        include("php/navbar.php");
    ?>

    <div id="loader"></div>

    <div class="alert alert-success" id="alert-success">
        <span id="msg-success" class="msg">Success</span>

        <button type="button" class="close" id="close-success">
            <span class="fa fa-times"></span>
        </button>
    </div>

    <div class="alert alert-danger" id="alert-error">
        <span id="msg-error" class="msg">Error</span>

        <button type="button" class="close" id="close-error">
            <span class="fa fa-times"></span>
        </button>
    </div>
    <main class="container-fluid mt-5">
        <section class="row justify-content-center">
            <?php

                if(isset($_GET["type"]) || isset($_GET["search"])) {
                    if(isset($_GET["type"])) {
                        $allProducts = $products -> getRecentProducts();
                    }else{
                        $allProducts = $products -> getProductsBySearch();
                    }

                    echo "
                    <div class='col-12 card-container' id='product-container'>
                    ";

                    //Draw products function
                    drawProducts($allProducts);

                    echo "
                    </div>
                    <div class='offset-panel mt-5'>
                        <span class='fa fa-angle-left offset-icon' id='offset-left'></span>
                        <span class='offset-number' id='offset-number'>0</span>
                        <span class='fa fa-angle-right offset-icon' id='offset-right'></span>
                    </div>
                    ";
                }else if(isset($_GET["id"])) {
                    $product = $products -> getProductById();
                    $product = $product -> fetchAll()[0];

                    echo "
                    <div class='col-12 col-sm-6 col-md-5 col-lg-3 mb-5 text-center'>
                        <img src='images/products/".$product["Image"]."' alt='".$product["Name"]."' class='product-image'>
                    </div>
                    <div class='col-12 col-sm-6 col-md-7 col-lg-9 text-center product-description'>
                        <h2 class='name'>".$product["Name"]."</h2>
                        <p class='description'>".$product["Description"]."</p>
                        <p class='price'>$<span id='price'>".$product["Price"]."</span> MXN</p>
                        <button class='btn btn-primary mt-1' id='add-cart'>
                            Añadir al carrito
                            <span class='fa fa-shopping-cart'></span>
                        </button>
                    <div class='count-container mt-3'>
                    ";

                    if($product["Available"] > 0) {
                        echo "<p class='text-success font-weight-bold'>Quedan ".$product["Available"]." restantes</p>";
                    }else{
                        echo "<p class='text-danger font-weight-bold'>No hay elementos de este producto</p>";
                    }

                    echo "
                            <span class='title'>Cantidad:</span>
                            <span class='fa fa-minus' id='minus'></span>
                            <span class='number' id='number-count'>1</span>
                            <span class='fa fa-plus' id='plus'></span>
                        </div>
                    </div>
                    ";
                }
            ?>
        </section>
    </main>

    <?php
        include("php/footer.php");

        if(isset($_GET["type"])) {
            echo "<script>const type = 'productType';const typeName = '$typeName';</script>";
        }else if(isset($_GET["id"])){
            echo "<script>const id = ".$_GET["id"].";var price = ".$product["Price"].";const maxQuantity = ".$product["Available"].";</script>";
        }else{
            echo "<script>const type = 'search';const search = '".$_GET["search"]."';</script>";
        }
    ?>

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/drawProducts.js"></script>
    <script>
        const offsetNumber = $("#offset-number");
        const productContainer = $("#product-container");
        const loader = $("#loader");
        const priceNumber = $("#price");
        const countNumber = $("#number-count");
        var maxOffset = 99999;

        var offset = 0;
        var count = 1;

        $("#offset-left").click(() => {
            if(offset > 0) {
                offset--;
                offsetNumber.html(offset);
                loader.addClass("active");
                
                var data = "";

                if(type == "productType") {
                    data = `allTypes&type=${typeName}&offset=${offset}`;
                }else{
                    data = `searchPhones&search=${search}&offset=${offset}`;
                }

                $.ajax({
                    url: "php/offsetSystem.php",
                    method: "post",
                    data: data,
                    success: data => {
                        data = JSON.parse(data);
                        drawProducts(data, productContainer);
                        loader.removeClass("active");
                    },
                });
            }
        });

        $("#offset-right").click(() => {
            if(offset < maxOffset) {
                offset++;
                offsetNumber.html(offset);
                loader.addClass("active");
                
                var data = "";

                if(type == "productType") {
                    data = `allTypes&type=${typeName}&offset=${offset}`;
                }else{
                    data = `searchPhones&search=${search}&offset=${offset}`;
                }

                $.ajax({
                    url: "php/offsetSystem.php",
                    method: "post",
                    data: data,
                    success: data => {
                        data = JSON.parse(data);
                        drawProducts(data, productContainer);
                        loader.removeClass("active");

                        if(data.length < 20) {
                            maxOffset = offset;
                        }
                    },
                });
            }
        });

        $("#minus").click(() => {
            if(count > 1) {
                count--;
                priceNumber.html(price * count);
                countNumber.html(count);
            }
        });

        $("#plus").click(() => {
            if(count < maxQuantity) {
                count++;
                priceNumber.html(price * count);
                countNumber.html(count);
            }
        });

        $("#add-cart").click(() => {
            loader.addClass("active");
            $("#alert-success").removeClass("active");
            $("#alert-error").removeClass("active");
            $.ajax({
                url: "php/editShoppingCart.php",
                method: "post",
                data: `add&id=${id}&quantity=${count}`,
                success: data => {
                    data = JSON.parse(data);

                    if(data.state) {
                        $("#msg-success").html(data.msg);
                        $("#alert-success").addClass("active");
                    }else{
                        $("#msg-error").html(data.msg);
                        $("#alert-error").addClass("active");
                    }

                    loader.removeClass("active");
                },
            })
        });

        $("#close-success").click(() => {
            $("#alert-success").removeClass("active");
        });

        $("#close-error").click(() => {
            $("#alert-error").removeClass("active");
        });
    </script>
</body>
</html>