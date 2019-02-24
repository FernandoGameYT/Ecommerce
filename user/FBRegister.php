<?php

    include("../php/conexion.php");

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>PhoneShop - Coloca tu nombre de usuario</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <base href="<?php echo $direction . "Users/";?>">
    <link rel="stylesheet" href="../css/icon.css">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/main.css">
    <style>
        .container-fluid {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
    </style>
</head>
<body>

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

    <div class="container-fluid">
        <div class="row">
            <form action="#" class="form-center form-group active" id="username-form" method="post">
                <h2>
                    <span class="fa fa-lock"></span>
                    Coloca tu nombre de usuario
                </h2>
                <div class="input-group">
                    <input type="username" id="username" minlength="4" maxlength="20" required>
                    <label for="username">Nombre de usuario</label>
                    <span class="fa fa-user"></span>
                </div>

                <button type="submit">Aceptar</button>
            </form>
        </div>
    </div>

    <script src="../js/jquery.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script>
        const loader = $("#loader");
    
        $("#username-form").submit(e => {
            e.preventDefault();
            loader.addClass("active");

            $.ajax({
                url: "../php/fb_register.php",
                method: "post",
                data: "username="+$("#username").val(),
                success: data => {
                    if(data == true) {
                        location.href = "../";
                    }else if(data == "fb_access_token_error") {
                        location.href = "../";
                    }else{
                        $("#msg-error").html(data);
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
    
    </script>
</body>
</html>