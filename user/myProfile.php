<?php

    session_start();
    include("../php/UserController.php");
    include("../php/OrderController.php");

    $users = new Users();
    $orders = new Orders();

    if(!$users -> checkSession()) {
        header("Location: $direction");
    }

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>PhoneShop - Mi Perfil</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <base href="<?php echo $direction . "Users/";?>">
    <link rel="stylesheet" href="../css/icon.css">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/main.css">
</head>
<body>
    <?php
        include("../php/navbar.php");
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
    
    <!-- <h1>Mi Perfil</h1> -->
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-11 menu-linear collapse-sm">
                <span class="menu-item active" id="menu-config" for="config">Configuracion</span>
                <span class="menu-item" id="menu-myOrders" for="myOrders">Mis pedidos</span>
            </div>
        </div>
        <div class="row justify-content-center mt-3" id="panel-container">
            <div class="col-12 col-md-11 configuration-container active" id="config">
                <span class="title"> Nombre de usuario:</span>
                <button class="pencil-button" onclick="ChangeStateForm('form-username')">
                    <span class="fa fa-pencil"></span>
                </button>
                <form action="#" method="post" id="form-username" class="mb-4 mt-4 form-group">
                    <div class="input-group">
                        <input type="text" class="ml-0" id="username" maxlength="20" value="<?php echo $users -> data["Username"];?>" required>
                        <label for="username">Nombre de usuario</label>
                    </div>
                    <button type="submit" class="ml-2 bg-success">Guardar</button>
                    <button type="button" class="bg-danger" onclick="ChangeStateForm('form-username')">Cancelar</button>
                </form>
                <p id="username-text"><?php echo $users -> data["Username"];?></p>
                
                <hr>

                <span class="title"> Correo electronico:</span>
                <button class="pencil-button" onclick="ChangeStateForm('form-email')">
                    <span class="fa fa-pencil"></span>
                </button>
                <form action="#" method="post" id="form-email" class="mb-4 mt-4 form-group">
                    <div class="input-group">
                        <input type="text" class="ml-0" id="email" maxlength="100" value="<?php echo $users -> data["Email"];?>" required>
                        <label for="email">Correo electronico</label>
                    </div>
                    <button type="submit" class="ml-2 bg-success">Guardar</button>
                    <button type="button" class="bg-danger" onclick="ChangeStateForm('form-email')">Cancelar</button>
                </form>
                <p id="email-text"><?php echo $users -> data["Email"];?></p>

                <hr>

                <span class="title"> Contraseña:</span>
                <button class="pencil-button" onclick="ChangeStateForm('form-password')">
                    <span class="fa fa-pencil"></span>
                </button>
                <form action="#" method="post" id="form-password" class="mb-4 mt-4 form-group">
                    <div class="input-group">
                        <input type="password" class="ml-0" id="password" maxlength="50" required>
                        <label for="password">Coloca la nueva contraseña</label>
                    </div>

                    <div class="input-group">
                        <input type="password" class="ml-0" id="repeat-password" maxlength="50" required>
                        <label for="repeat-password">Repite la nueva contraseña</label>
                    </div>

                    <div class="input-group">
                        <input type="password" class="ml-0" id="old-password" maxlength="50" required>
                        <label for="old-password">Coloca la contraseña actual</label>
                    </div>
                    <button type="submit" class="ml-2 bg-success">Guardar</button>
                    <button type="button" class="bg-danger" onclick="ChangeStateForm('form-password')">Cancelar</button>
                </form>
                <p><?php for($i = 0;$i < 10;$i++) {echo "&bull;";}?></p>
            </div>
            <div class="col-12 col-md-11 configuration-container" id="myOrders">
                <?php

                    $orders -> userId = $users -> data["Id"];
                    $allOrders = $orders -> getRecentOrders();

                    foreach($allOrders as $order) {
                        $orders -> orderId = $order["Id"];
                        $products = $orders -> getOrderProducts();

                        echo "
                        <div class='order-item' id='order-".$order["Id"]."'>
                            <span class='detail'>Id de el pedido: ".$order["Id"]."</span>
                            <span class='detail'>Total: $".$order["Subtotal"]."MXN</span>
                            <span class='view-details' onclick='showDetails(\"order-".$order["Id"]."\")'>Ver detalles</span>
                            <span class='fa fa-angle-up icon up'></span>
                            <span class='fa fa-angle-down icon down'></span>
                            <div class='details'>
                        ";

                        foreach($products as $product) {
                            echo "<div class='detail-item'>
                                    <a href='../Products/".$product["Name"]."-".$product["Id"]."/'>
                                        <img src='../images/products/".$product["Image"]."'>
                                    </a>
                                    <span>Precio: $".$product["Price"]."MXN</span>
                                    <span>Canidad: ".$product["Quantity"]."</span>
                                </div>";
                        }
                        echo "</div>
                        </div>
                        <hr class='order-separator'>
                        ";

                    }
                
                ?>
            </div>
        </div>
    </div>

    <?php
        include("../php/footer.php");
        echo "<script>var usernameOriginal = '".$users -> data["Username"]."';
        var emailOriginal = '".$users -> data["Email"]."';</script>";
    ?>

    <script src="../js/jquery.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script>
        const loader = $("#loader");
        const alertSuccess = $("#alert-success");
        const msgSuccess = $("#msg-success");
        const alertError = $("#alert-error");
        const msgError = $("#msg-error");
    	const regex = /^([a-zA-Z0-9\.-]+)@([a-zA-Z0-9-]+)\.([a-z]{2,6})/;

        $("#form-username").submit(e => {
            e.preventDefault();

            const username = $("#username").val();

            alertSuccess.removeClass("active");
            alertError.removeClass("active");

            if(username.length < 4 || username.length > 20) {
                msgError.html("El nombre de usuario debe tener 4 o mas caracteres.");
                alertError.addClass("active");
                return;
            }else if(username == usernameOriginal) {
                msgError.html("No se a realizado ningún cambio.");
                alertError.addClass("active");
                return;
            }

            loader.addClass("active");

            $.ajax({
                url: "../php/userSystem.php",
                method: "post",
                data: `update_username&username=${username}`,
                success: data => {
                    data = JSON.parse(data);

                    if(data.state) {
                        usernameOriginal = username;
                        $("#username-text").html(username);
                        ChangeStateForm("form-username");

                        $("#msg-success").html(data.msg);
                        $("#alert-success").addClass("active");
                    }else{
                        $("#msg-error").html(data.msg);
                        $("#alert-error").addClass("active");
                    }

                    loader.removeClass("active");
                },
            });
        });

        $("#form-email").submit(e => {
            e.preventDefault();

            const email = $("#email").val();

            alertSuccess.removeClass("active");
            alertError.removeClass("active");

            if(!regex.test(email)) {
                msgError.html("El correo electronico no es valido.");
                alertError.addClass("active");
                return;
            }else if(email == emailOriginal) {
                msgError.html("No se a realizado ningún cambio.");
                alertError.addClass("active");
                return;
            }

            loader.addClass("active");

            $.ajax({
                url: "../php/userSystem.php",
                method: "post",
                data: `update_email&email=${email}`,
                success: data => {
                    data = JSON.parse(data);

                    if(data.state) {
                        emailOriginal = email;
                        $("#email-text").html(email);
                        ChangeStateForm("form-email");

                        $("#msg-success").html(data.msg);
                        $("#alert-success").addClass("active");
                    }else{
                        $("#msg-error").html(data.msg);
                        $("#alert-error").addClass("active");
                    }

                    loader.removeClass("active");
                },
            });
        });

        $("#form-password").submit(e => {
            e.preventDefault();

            const password = $("#password").val();
            const repeatPassword = $("#repeat-password").val();
            const oldPassword = $("#old-password").val();

            alertSuccess.removeClass("active");
            alertError.removeClass("active");

            if(password.length < 6 || password.length > 50) {
                msgError.html("La contraseña debe tener 6 o mas caracteres.");
                alertError.addClass("active");
                return;
            }else if(password != repeatPassword) {
                msgError.html("La contraseñas no coinciden.");
                alertError.addClass("active");
                return;
            }else if(oldPassword.length < 6 || oldPassword.length > 50) {
                msgError.html("La contraseña actual debe tener 6 o mas caracteres.");
                alertError.addClass("active");
                return;
            }

            loader.addClass("active");

            $.ajax({
                url: "../php/userSystem.php",
                method: "post",
                data: `update_password&password=${password}&oldPassword=${oldPassword}`,
                success: data => {
                    data = JSON.parse(data);

                    if(data.state) {
                        ChangeStateForm("form-password");
                        $("#form-password").trigger("reset");

                        $("#msg-success").html(data.msg);
                        $("#alert-success").addClass("active");
                    }else{
                        $("#msg-error").html(data.msg);
                        $("#alert-error").addClass("active");
                    }

                    loader.removeClass("active");
                },
            });
        });

        function ChangeStateForm(id) {
            if($("#" + id).hasClass("active")) {
                $("#" + id).removeClass("active");
            }else{
                $("#" + id).addClass("active");
            }
        }

        function showDetails(id) {
            if($("#" + id).hasClass("details-active")) {
                $("#" + id).removeClass("details-active");
            }else{
                $("#" + id).addClass("details-active");
            }
        }

        $("#close-success").click(() => {
            $("#alert-success").removeClass("active");
        });

        $("#close-error").click(() => {
            $("#alert-error").removeClass("active");
        });

        $(".menu-linear .menu-item").click(e => {
            $(".menu-linear .menu-item").removeClass("active");

            e.target.classList.add("active");
            $("#panel-container").children().removeClass("active");
            $("#"+e.target.attributes.for.value).addClass("active");
        });
    </script>
    <?php
    
        if(isset($_GET["myOrders"])) {
            echo "<script>document.getElementById('menu-myOrders').click();</script>";
        }

    ?>
</body>
</html>