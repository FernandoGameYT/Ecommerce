<?php

    session_start();
    include("php/conexion.php");

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>PhoneShop - Iniciar Sesion</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <base href="<?php echo $direction;?>">
    <link rel="stylesheet" href="css/icon.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
    <?php
    
        if(isset($_GET["r"])) {
            $login = "";
            $register = "active";
        }else{
            $login = "active";
            $register = "";
        }
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

    <form action="#" method="post" class="form-center form-group <?php echo $login;?> mt-5" id="login">
        <h2>
            <span class="fa fa-lock"></span>
            Iniciar Sesion
        </h2>
        <div class="input-group">
            <input type="username" id="l-username" maxlength="20" required>
            <label for="l-username">Nombre de usuario</label>
            <span class="fa fa-user"></span>
        </div>

        <div class="input-group">
            <input type="password" id="l-password" maxlength="50" required>
            <label for="l-password">Contraseña</label>
            <span class="fa fa-lock"></span>
        </div>

        <button type="submit">Iniciar Sesion</button>

        <p class="mt-3">
            <a href="javascript:void(0)" onclick="changeForm()">
                ¿No tienes una cuenta? Registrate.
            </a>
        </p>
    </form>

    <form action="#" method="post" class="form-center form-group <?php echo $register;?> mt-5" id="register">
        <h2>
            <span class="fa fa-lock"></span>
            Registrate
        </h2>
        <div class="input-group">
            <input type="username" id="username" maxlength="20" required>
            <label for="username">Nombre de usuario</label>
            <span class="fa fa-user"></span>
        </div>

        <div class="input-group">
            <input type="text" id="email" maxlength="100" required>
            <label for="email">Correo electronico</label>
            <span class="fa fa-envelope"></span>
        </div>

        <div class="input-group">
            <input type="password" id="password" maxlength="50" required>
            <label for="password">Contraseña</label>
            <span class="fa fa-lock"></span>
        </div>

        <div class="input-group">
            <input type="password" id="repeat-password" maxlength="50" required>
            <label for="repeat-password">Repite la contraseña</label>
            <span class="fa fa-lock"></span>
        </div>

        <p>
            <input type="checkbox" required> 
            <a href="#">Aceptar terminos y condiciones</a>
        </p>

        <button type="submit">Registrarse</button>

        <p class="mt-3">
            <a href="javascript:void(0)" onclick="changeForm()">
                ¿Ya tienes una cuenta? Iniciar Sesion.
            </a>
        </p>
    </form>

    <?php
        include("php/footer.php");
    ?>

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script>
        const loader = $("#loader");
        const alertSuccess = $("#alert-success");
        const msgSuccess = $("#msg-success");
        const alertError = $("#alert-error");
        const msgError = $("#msg-error");
    	const regex = /^([a-zA-Z0-9\.-]+)@([a-zA-Z0-9-]+)\.([a-z]{2,6})/;

        $("form#register").submit(e => {
            e.preventDefault();
            // Inputs
            const username = $("#username").val();
            const email = $("#email").val();
            const password = $("#password").val();
            const repeatPassword = $("#repeat-password").val();
            
            alertSuccess.removeClass("active");
            alertError.removeClass("active");

            if(username.length < 4 || username.length > 20) {
                msgError.html("El nombre de usuario debe tener 4 o mas caracteres.");
                alertError.addClass("active");
                return;
            }else if(!regex.test(email)) {
                msgError.html("El correo electronico no es valido.");
                alertError.addClass("active");
                return;
            }else if(password.length < 6 || password.length > 50) {
                msgError.html("La contraseña debe tener 6 o mas caracteres.");
                alertError.addClass("active");
                return;
            }else if(password != repeatPassword) {
                msgError.html("La contraseñas no coinciden.");
                alertError.addClass("active");
                return;
            }

            loader.addClass("active");
            const data = `register&username=${username}&email=${email}&password=${password}`;

            $.ajax({
                url: "php/userSystem.php",
                method: "post",
                data: data,
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
            });
        });

        $("form#login").submit(e => {
            e.preventDefault();

            const username = $("#l-username").val();
            const password = $("#l-password").val();

            alertSuccess.removeClass("active");
            alertError.removeClass("active");

            if(username.length < 4 || username.length > 20) {
                msgError.html("El nombre de usuario debe tener 4 o mas caracteres.");
                alertError.addClass("active");
                return;
            }else if(password.length < 6 || password.length > 50) {
                msgError.html("La contraseña debe tener 6 o mas caracteres.");
                alertError.addClass("active");
                return;
            }

            loader.addClass("active");
            const data = `login&username=${username}&password=${password}`;

            $.ajax({
                url: "php/userSystem.php",
                method: "post",
                data: data,
                success: data => {
                    data = JSON.parse(data);

                    if(data.state) {
                        $("#msg-success").html(data.msg);
                        $("#alert-success").addClass("active");

                        setTimeout(() => {
                            location.href = "./";
                        }, 1000);
                    }else{
                        $("#msg-error").html(data.msg);
                        $("#alert-error").addClass("active");
                    }

                    loader.removeClass("active");
                },
            });
        });

        $("#close-success").click(() => {
            $("#alert-success").removeClass("active");
        });

        $("#close-error").click(() => {
            $("#alert-error").removeClass("active");
        });

        function changeForm() {
            if($("form#login").hasClass("active")) {
                $("form#login").removeClass("active");
                $("form#register").addClass("active");
            }else{
                $("form#login").addClass("active");
                $("form#register").removeClass("active");
            }
        }
    </script>
</body>
</html>