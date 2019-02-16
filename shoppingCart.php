<?php

    session_start();
    include("php/conexion.php");

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>PhoneShop - Mi carrito</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <base href="<?php echo $direction;?>">
    <link rel="stylesheet" href="css/icon.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
    <?php
        include("php/navbar.php");
        include("php/ShoppingCartController.php");

        $car = new ShoppingCart();
        $products = $car -> getProducts();
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

    <main class="container-fluid">
        <?php
            $total = 0;
            foreach($products as $product) {
                if($product["State"]) {
                    $total += $product["Price"] * $product["Quantity"];
                    $state = "text-success";
                }else{
                    $product["State"] = 0;
                    $state = "text-danger";
                }
                echo '
                <div class="row shopping-cart-product justify-content-center mt-5" id="element'.$product["IdCart"].'">
                    <div class="col-6 col-sm-2 col-md-3 col-lg-2">
                        <a href="Products/'.$product["Name"].'-'.$product["Id"].'/">
                            <img src="images/products/'.$product["Image"].'" alt="Product">
                        </a>
                    </div>
        
                    <div class="col-6 col-sm-3 col-md-3 col-lg-2">
                        <span class="title">Cantidad:</span>
                        <span class="state">
                            <span class="fa fa-minus" onclick="editQuantity('.$product["Id"].', this.parentElement, -1, '.$product["State"].', '.$product["Price"].')"></span>
                            <span class="number" id="'.$product["IdCart"].'">'.$product["Quantity"].'</span>
                            <span class="fa fa-plus" onclick="editQuantity('.$product["Id"].', this.parentElement, 1, '.$product["State"].', '.$product["Price"].')"></span>
                        </span>
                    </div>
        
                    <div class="col-12 col-sm-5 col-md-6 col-lg-8">
                        <span class="title">Precio:</span>
                        <span class="text-success state h-25">$'.$product["Price"].'MXN</span>
                        <span class="title">Estado:</span>
                        <span class="'.$state.' state h-25">'.$product["Msg"].'</span>
                    </div>
                    <button class="btn btn-danger delete" onclick="deleteProduct('.$product["IdCart"].', \''.$product["IdCart"].'\', '.$product["State"].', '.$product["Price"].')">
                        <span class="fa fa-times"></span>
                    </button>
                </div>
                <hr>
                ';
            }
            if(count($products) > 0) {
                echo '
                <div class="row mt-5 justify-content-center">
                    <div class="col-12 col-sm-8 col-md-6 col-lg-4 purchase-container font-weight-bold border py-4">
                        <h4 class="mb-3">Precio total</h4>
                        <hr class="bg-dark">
                        <span class="d-block mb-1">
                            Precio: 
                            <span class="text-success float-right mr-3">$<span id="price">'.$total.'</span>MXN</span>
                        </span>
        
                        <span class="d-block mb-1">
                            Envio: 
                            <span class="text-success float-right mr-3">Gratis</span>
                        </span>
        
                        <span class="d-block">
                            Total: 
                            <span class="text-success float-right mr-3">$<span id="total">'.$total.'</span>MXN</span>
                        </span>
        
                        <form action="Users/purchase/" method="POST">
                            <button class="btn btn-success py-3 mt-3 font-weight-bold w-100" name="purchase" type="submit">
                                Confirmar pedido
                            </button>
                        </form>
                    </div>
                </div>';
            }else{
                echo "<div style='height: 50vh;' class='d-flex flex-wrap justify-content-center align-items-center'>
                    <h2 class='text-center font-weight-bold mt-5'>No hay productos en el carrito</h2>
                    <div class='w-100'></div>
                    <a href='".$direction."'>
                        <button class='btn btn-primary mb-5 px-5 py-3 font-weight-bold'>Ir a comprar</button>
                    </a>
                </div>";
            }
        ?>
    </main>

    <?php
        include("php/footer.php");
        echo "<script>var total = $total;</script>";
    ?>

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script>
        const loader = $("#loader");
        const msgSuccess = $("#msg-success");
        const alertSuccess = $("#alert-success");
        const msgError = $("#msg-error");
        const alertError = $("#alert-error");

        function editQuantity(id, container, sum, state, productPrice) {
            const numberElement = container.children.item(1);
            var quantity = parseInt(numberElement.innerHTML);
            
            if(sum < 0 && quantity + sum > 0 || state) {
                quantity += sum;
                alertSuccess.removeClass("active");
                alertError.removeClass("active");
                loader.addClass("active");
                $.ajax({
                    url: "php/editShoppingCart.php",
                    method: "post",
                    data: `editQuantity&id=${id}&quantity=${quantity}`,
                    success: data => {
                        data = JSON.parse(data);

                        if(data.state) {
                            msgSuccess.html(data.msg);
                            alertSuccess.addClass("active");
                            numberElement.innerHTML = quantity;
                            total += sum * productPrice;
                            $("#price").html(total);
                            $("#total").html(total);
                        }else{
                            msgError.html(data.msg);
                            alertError.addClass("active");
                        }

                        loader.removeClass("active");
                    },
                });
            }
        }

        function deleteProduct(id, numberId, state, productPrice) {
            const numberElement = document.getElementById(numberId);
            var quantity = parseInt(numberElement.innerHTML);

            alertSuccess.removeClass("active");
            alertError.removeClass("active");
            loader.addClass("active");
            $.ajax({
                url: "php/editShoppingCart.php",
                method: "post",
                data: `delete&id=${id}`,
                success: data => {
                    data = JSON.parse(data);

                    if(data.state) {
                        msgSuccess.html(data.msg);
                        alertSuccess.addClass("active");

                        $(`#element${id}`).remove();
                        
                        if(state) {
                            total -= quantity * productPrice;
                            $("#price").html(total);
                            $("#total").html(total);
                        }

                    }else{
                        msgError.html(data.msg);
                        alertError.addClass("active");
                    }

                    loader.removeClass("active");
                },
            });
        }

        $("#close-success").click(() => {
            $("#alert-success").removeClass("active");
        });

        $("#close-error").click(() => {
            $("#alert-error").removeClass("active");
        });
    </script>
</body>
</html>