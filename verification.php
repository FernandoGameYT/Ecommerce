<?php

    include("php/userController.php");

    if(!isset($_GET["activationCode"])) {
        header("Location: $direction");
    }

    $users = new Users();

    $status = (array) json_decode($users -> activateUser($_GET["activationCode"]));

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>PhoneShop - Verificacion de cuenta</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <base href="<?php echo $direction;?>">
    <link rel="stylesheet" href="css/icon.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/main.css">
    <style>
        .center {
            display: flex;
            height: 50vh;
            justify-content: center;
            align-items: center;
        }
        div.status {
            width: 100%;
            max-width: 500px;
            text-align: center;
            font-weight: bold;
        }
        div.status a {
            text-decoration: none;
        }
    </style>
</head>
<body>
    <?php
        include("php/navbar.php");

        if($status["state"]) {
            $class = "success";
            $msg = "Iniciar Sesion";
            $url = "login/";
        }else{
            $class = "danger";
            $msg = "Registrarse";
            $url = "login?r";
        }

        echo "
        <div class='center'>
            <div class='alert-$class p-4 status'>
                ".$status["msg"]."<br>
                <a href='$url' class='text-$class'>$msg</a>
            </div>
        </div>
        ";
    ?>

    <?php
        include("php/footer.php");
    ?>

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>