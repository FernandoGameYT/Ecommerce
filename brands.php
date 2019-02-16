<?php
    include("php/conexion.php");
    include("php/drawProducts.php");

    if(!isset($_GET["brand"])) {
        header("Location: $direction");
    }

    $brandName = $_GET["brand"];

    include("php/ProductController.php");

    $products = new Products();

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>PhoneShop - <?php echo $brandName;?></title>
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
    <div class="container-fluid mt-5">
        <section class="row justify-content-center">
            <div class="col-12 card-container" id="phone-container">
                <?php
                    $products -> brand = $brandName;
                    $allPhones = $products -> getProductsRecentOfBrand();

                    drawProducts($allPhones);
                ?>
            </div>
            <div class="offset-panel mt-5">
                <span class="fa fa-angle-left offset-icon" id="offset-left"></span>
                <span class="offset-number" id="offset-number">0</span>
                <span class="fa fa-angle-right offset-icon" id="offset-right"></span>
            </div>
        </section>
    </div>

    <?php
        include("php/footer.php");

        echo "<script>const brand = '$brandName';</script>";
    ?>

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/drawProducts.js"></script>
    <script>
        const offsetNumber = $("#offset-number");
        const phoneContainer = $("#phone-container");
        const loader = $("#loader");
        var maxOffset = 99999;

        var offset = 0;

        $("#offset-left").click(() => {
            if(offset > 0) {
                offset--;
                offsetNumber.html(offset);
                loader.css("display", "flex");

                $.ajax({
                    url: "php/offsetSystem.php",
                    method: "post",
                    data: `phones&brand=${brand}&offset=${offset}`,
                    success: data => {
                        data = JSON.parse(data);
                        drawProducts(data, phoneContainer);
                        loader.css("display", "none");
                    },
                });
            }
        });

        $("#offset-right").click(() => {
            if(offset < maxOffset) {
                offset++;
                offsetNumber.html(offset);
                loader.css("display", "flex");

                $.ajax({
                    url: "php/offsetSystem.php",
                    method: "post",
                    data: `phones&brand=${brand}&offset=${offset}`,
                    success: data => {
                        data = JSON.parse(data);
                        drawProducts(data, phoneContainer);
                        loader.css("display", "none");

                        if(data.length < 20) {
                            maxOffset = offset;
                        }
                    },
                });
            }
        });
    </script>
</body>
</html>