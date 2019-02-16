<?php

    include("../php/conexion.php");

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>PhoneShop - Estado de el pago</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <base href="<?php echo $direction . "Users/";?>">
    <link rel="stylesheet" href="../css/icon.css">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/main.css">
    <style>
        .alert-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 80vh;
        }
    </style>
</head>
<body>
    <?php
        include("../php/navbar.php");
    ?>

    <div class="container-fluid">
        <div class="row alert-container">
            <div class=" col-lg-4 py-4 alert-danger text-center">
                <h4 class="title d-block">El pedido no se a realizado con exito.</h4>
                <a href="<?php echo $direction;?>">
                    <button class="btn btn-danger font-weight-bold py-2 px-3 mt-3">
                        Seguir Comprando
                    </button>
                </a>
            </div>
        </div>
    </div>

    <?php
        include("../php/footer.php");
    ?>

    <script src="../js/jquery.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
</body>
</html>