<?php

    session_start();
    include("../php/conexion.php");
    include("../php/UserController.php");

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
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>PhoneShop - Administrar Pedidos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <base href="<?php echo $direction . "Admins/";?>">
    <link rel="stylesheet" href="../css/icon.css">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/main.css">
</head>
<body>
    <?php
        include("../php/navbar.php");
        include("../php/OrderController.php");

        // Menu
        $order_status = "active";
        include("menu.php");

        $orders = new Orders();

        if(isset($_GET["search"])) {
            $search = $_GET["search"];
            if(!empty($search)) {
                $all_orders = $orders -> getOrderById($search);
            }else{
                $all_orders = $orders -> getAllRecentOrders();
            }
        }else{
            $search = "";
            $all_orders = $orders -> getAllRecentOrders();
        }
    ?>

    <div class="container-fluid mt-5">
        <div class="row mb-3">
            <div class="col-12 col-sm-6 col-md-6 col-lg-6 offset-xl-1 col-xl-5 mt-4">
                <span class="title h3">Administrador de marcas</span>
            </div>
            <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-5">
                <form class="mx-2 d-inline" action="<?php echo $direction;?>Admins/orders" method="get">
                    <div class="input-group">
                        <input type="search" name="search" class="form-control mr-sm-2" value="<?php echo $search;?>" placeholder="Buscar un pedido por id">
                        <button class="btn btn-primary" type="submit">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <?php
            
            foreach($all_orders as $order) {
                echo '
                <div class="row mb-5 align-items-center justify-content-center">
                    <div class="col-3 col-sm-4 col-md-3 col-lg-2 col-xl-2 text-center">
                        <h4 class="title">Id</h4>
                        <span class="h5">'.$order["Id"].'</span>
                    </div>
                    <div class="col-6 col-sm-5 col-md-4 col-lg-3 col-xl-2 text-center">
                        <h4 class="title">Subtotal</h4>
                        <span class="h5">$'.$order["Subtotal"].'MXN</span>
                    </div>
                    <div class="col-6 col-sm-5 col-md-4 col-lg-3 col-xl-2 text-center">
                        <h4 class="title">Envio</h4>
                        <span class="h5">$'.$order["Shipping"].'MXN</span>
                    </div>
                    <div class="col-6 col-sm-5 col-md-4 col-lg-3 col-xl-2 text-center">
                        <h4 class="title">Fecha</h4>
                        <span class="h5">'.$order["Date"].'</span>
                    </div>
                    <div class="col-3 col-sm-3 col-md-2 col-lg-2 col-xl-2 text-sm-right">
                        <button class="btn btn-danger mr-2" onclick="deleteOrder('.$order["Id"].')">
                            <span class="fa fa-times"></span>
                        </button>
                        <button class="btn btn-primary" onclick="showEditOrder('.$order["Id"].','.$order["Subtotal"].','.$order["Shipping"].',\''.$order["Date"].'\')">
                            <span class="fa fa-pencil"></span>
                        </button>
                    </div>
                </div>
                ';
            }

        ?>
    </div>

    <div class="form-screen-container"  id="edit-order">
            <form action="../php/panelSystem.php" class="form-group" method="post" id="edit-order-form">
                <button type="button" class="close" id="edit-order-hide">
                    <span class="fa fa-times"></span>
                </button>
                <h2>
                    Editar pedido
                </h2>

                <input type="hidden" name="editOrder">
                <input type="hidden" name="id" id="edit-order-id">

                <div class="input-group">
                    <input type="number" name="subtotal" id="edit-order-subtotal" maxlength="50" required>
                    <label for="edit-order-subtotal">Subtotal</label>
                </div>
                <div class="input-group">
                    <input type="number" name="shipping" id="edit-order-shipping" maxlength="50" required>
                    <label for="edit-order-shipping">Envio</label>
                </div>
                <div class="input-group">
                    <input type="text" name="date" id="edit-order-date" maxlength="50" required>
                    <label for="edit-order-date">Fecha</label>
                </div>
    
                <button class="submit" type="submit">Editar Pedido</button>
            </form>
        </div>

    <?php
        include("../php/footer.php");
    ?>

    <script src="../js/jquery.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script>
        //show edit order form
        function showEditOrder(id, subtotal, shipping, date) {
            $("#edit-order").addClass("active");
            $("#edit-order-id").val(id);
            $("#edit-order-subtotal").val(subtotal);
            $("#edit-order-shipping").val(shipping);
            $("#edit-order-date").val(date);
        }

        //hide edit order form
        $("#edit-order-hide").click(() => {
            $("#edit-order").removeClass("active");
        });

        //delete brand
        function deleteOrder(id) {
            if(confirm(`¿Desea el pedido numero ${id}?`)) {
                $.ajax({
                    url: "../php/panelSystem.php",
                    method: "post",
                    data: "deleteOrder&id=" + id,
                    success: () => {
                        location.reload();
                    },
                });
            }
        }
    </script>
</body>
</html>