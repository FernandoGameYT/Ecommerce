<?php

    session_start();
    require "../php/bootstrap.php";

    use PayPal\Api\Amount;
    use PayPal\Api\Details;
    use PayPal\Api\Item;
    use PayPal\Api\ItemList;
    use PayPal\Api\Payer;
    use PayPal\Api\Payment;
    use PayPal\Api\RedirectUrls;
    use PayPal\Api\Transaction;

    include("../php/conexion.php");
    include("../php/ShoppingCartController.php");
    include("../php/UserController.php");

    if(!isset($_POST["purchase"])) {
        header("Location: $direction");
        exit;
    }

    $users = new Users();

    if(!$users -> checkSession()) {
        header("Location: $direction");
        exit;
    }

    $payer = new Payer();
    $payer ->  setPaymentMethod("paypal");

    $subtotal = 0;
    $shipping = 0;
    $cart = new ShoppingCart();
    $products = $cart -> getProducts();
    $items = array();
    $currency = "MXN";

    foreach($products as $product) {
        if($product["State"]) {
            $item = new Item();
            $item -> setName($product["Name"])
            -> setCurrency($currency)
            -> setQuantity($product["Quantity"])
            -> setPrice($product["Price"]);

            $subtotal += $product["Price"] * $product["Quantity"];
            $items[] = $item;
        }
    }

    $item_list = new ItemList();
    $item_list -> setItems($items);

    $details = new Details();
    $details -> setSubtotal($subtotal)
    -> setShipping($shipping);

    $total = $subtotal + $shipping;

    $amount = new Amount();
    $amount -> setCurrency($currency)
    -> setTotal($total)
    -> setDetails($details);

    $transaction = new Transaction();
    $transaction -> setAmount($amount)
    -> setItemList($item_list)
    -> setDescription("Pedido de prueba");

    $redirect_urls = new RedirectUrls();
    $redirect_urls -> setReturnUrl($direction."Users/complete")
    -> setCancelUrl($direction."Users/cancel");

    $payment = new Payment();
    $payment -> setIntent("sale")
    -> setPayer($payer)
    -> setRedirectUrls($redirect_urls)
    -> setTransactions(array($transaction));

    try {
        $payment->create($apiContext);

        $_SESSION["paypal_payment_id"] = $payment -> getId();
        $_SESSION["UserId"] = $users -> data["Id"];
    
        header("Location: " . $payment->getApprovalLink());
    }
    catch (\PayPal\Exception\PayPalConnectionException $ex) {
        echo $ex->getData();
    }

?>