<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>PhoneShop - Home</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/icon.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
    <?php
        include("php/navbar.php");
        include("php/ProductController.php");
        include("php/drawProducts.php");

        $products = new Products();
    ?>

    <div id="carousel" class="carousel slide" data-ride="carousel">

        <ol class="carousel-indicators">
            <li data-target="#carousel" data-slide-to="0" class="active"></li>
        </ol>

        <div class="carousel-inner">
            <div class="carousel-item active">
                <div style="
                    background: url(images/iphoneX.jpg);
                    background-size: cover;
                    background-position: left;
                    width: 100%;
                    height: 80vh;
                "></div>
                <div class="carousel-caption">
                    <h3>¡Compra el nuevo IPhoneX a un precio irresistible!</h3>
                    <h5>¡Al comprar dos IphoneX te damos el tercero gratis!</h5>
                </div>
            </div>
        </div>
        <a class="carousel-control-prev" href="#carousel" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carousel" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>

    <main class="container-fluid mt-5">
        <section class="row">
            <div class="col-12">
                <h3 class="title">Celulares ultima generacion:</h3>
            </div>
        </section>
        <section class="row justify-content-center">
            <div class="col-12 card-container">
                <?php
                    $allPhones = $products -> getRecentProducts();

                    //Draw products function
                    drawProducts($allPhones);
                ?>
            </div>
        </section>
    </main>

    <?php
        include("php/footer.php");
    ?>

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>