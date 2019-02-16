<?php

    session_start();
    require "../php/bootstrap.php";
    include("../php/conexion.php");
    include("../php/OrderController.php");
    use PayPal\Api\Payment;
    use PayPal\Api\ExecutePayment;
    use PayPal\Api\PaymentExecution;

    $paymentId = $_SESSION["paypal_payment_id"];
    $userId = $_SESSION["UserId"];
    unset($_SESSION["paypal_payment_id"]);
    unset($_SESSION["UserId"]);

    if(!isset($_GET["PayerID"])) {
        header("Location: $direction");
    }else if(!isset($_GET["token"])) {
        header("Location: $direction");
    }
    $payerId = $_GET["PayerID"];
    $token = $_GET["token"];

    $payment = Payment::get($paymentId, $apiContext);

    $execution = new PaymentExecution();
    $execution -> setPayerId($payerId);

    $result = $payment -> execute($execution, $apiContext);

    if($result -> getState() == "approved") {
        //It's working
        $orders = new Orders();
        $orders -> saveOrder($userId);
        $cart = new ShoppingCart();
        $cart -> deleteCart();
        $paymentState = true;
    }else{
        $paymentState = false;
    }

    if($paymentState) {
        $state = "success";
        $message = "El pago se realizo exitosamente.";
    }else{
        $state = "danger";
        $message = "Hubo un problema al realizar el pago.";
    }

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
            <div class=" col-lg-4 py-4 alert-<?php echo $state?> text-center">
                <h4 class="title d-block"><?php echo $message;?></h4>
                <a href="<?php echo $direction;?>Users/myProfile?myOrders">
                    <button class="btn btn-<?php echo $state?> font-weight-bold py-2 px-3 mt-3">
                        Ver el pedido
                    </button>
                </a>
                <a href="<?php echo $direction;?>">
                    <button class="btn btn-<?php echo $state?> font-weight-bold py-2 px-3 mt-3">
                        Volver a el inicio
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