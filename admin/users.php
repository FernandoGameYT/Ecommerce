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
    <title>PhoneShop - Administrar Productos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <base href="<?php echo $direction . "Admins/";?>">
    <link rel="stylesheet" href="../css/icon.css">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/main.css">
</head>
<body>
    <?php
        include("../php/navbar.php");

        // Menu
        $user_status = "active";
        include("menu.php");

        if(isset($_GET["search"])) {
            $search = $_GET["search"];
            $all_users = $users -> getUsersBySearch($search);
        }else{
            $search = "";
            $all_users = $users -> getAllUsers($users -> data["Permits"] - 1);
        }

        if(isset($_GET["msg"])) {
            echo '
            <div class="modal fade" id="msg" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Estado de la accion</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            '.$_GET["msg"].'
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>';
        }
    ?>

    <div class="container-fluid mt-5">
        <div class="row mb-3">
            <div class="col-12 col-sm-6 col-md-6 col-lg-6 offset-xl-1 col-xl-5 mt-4">
                <span class="title h3">Administrador de usuarios</span>
            </div>
            <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-5">
                <form class="mx-2 d-inline" action="<?php echo $direction;?>Admins/users/" method="get">
                    <div class="input-group">
                        <input type="search" name="search" class="form-control mr-sm-2" value="<?php echo $search;?>" placeholder="Buscar un usuario">
                        <button class="btn btn-primary" type="submit">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <?php
            
            foreach($all_users as $user) {
                if($user["Permits"] == 3) {
                    $permits = "Desarrollador";
                }
                elseif($user["Permits"] == 2) {
                    $permits = "Super Administrador";
                }elseif($user["Permits"] == 1) {
                    $permits = "Administrador";
                }else{
                    $permits = "Ningún Permiso";
                }
                echo '
                <div class="row mb-5 align-items-center justify-content-center">
                    <div class="col-3 col-sm-4 col-md-3 col-lg-2 col-xl-2 text-center">
                        <h4 class="title">Id</h4>
                        <span class="h5">'.$user["Id"].'</span>
                    </div>
                    <div class="col-6 col-sm-5 col-md-4 col-lg-3 col-xl-2 text-center">
                        <h4 class="title">Username</h4>
                        <span class="h5">'.$user["Username"].'</span>
                    </div>
                    <div class="col-6 col-sm-5 col-md-4 col-lg-3 col-xl-2 text-center">
                        <h4 class="title">Email</h4>
                        <span class="h5">'.$user["Email"].'</span>
                    </div>
                    <div class="col-6 col-sm-5 col-md-4 col-lg-3 col-xl-2 text-center">
                        <h4 class="title">Permisos</h4>
                        <span class="h5">'.$permits.'</span>
                    </div>
                    <div class="col-3 col-sm-3 col-md-2 col-lg-2 col-xl-2 text-sm-right">
                        <button class="btn btn-danger mr-2" onclick="deleteUser('.$user["Id"].',\''.$user["Username"].'\')">
                            <span class="fa fa-times"></span>
                        </button>
                        <button class="btn btn-primary" onclick="showEditUser('.$user["Id"].',\''.$user["Username"].'\',\''.$user["Email"].'\','.$user["Permits"].')">
                            <span class="fa fa-pencil"></span>
                        </button>
                    </div>
                </div>
                ';
            }

        ?>
        <div class="row justify-content-center">
            <div class="col-12 col-md-9 col-lg-7 col-xl-10">
                <button class="btn btn-primary button-add p-3" id="add-user-show">
                    <span class="fa fa-plus"></span>
                    Agregar usuario
                </button>
            </div>
        </div>
    </div>

    <div class="form-screen-container"  id="add-user">
        <form action="../php/panelSystem.php" class="form-group" method="post" id="add-user-form">
            <button type="button" class="close" id="add-user-hide">
                <span class="fa fa-times"></span>
            </button>
            <h2>
                Agregar un usuario
            </h2>
            <input type="hidden" name="addUser">
            <div class="input-group">
                <input type="text" name="username" id="add-user-username" minlength="4" maxlength="20" required>
                <label for="add-user-username">Username</label>
            </div>
            <div class="input-group">
                <input type="email" name="email" id="add-user-email" maxlength="100" required>
                <label for="add-user-email">Correo electronico</label>
            </div>
            <div class="input-group">
                <input type="password" name="password" id="add-user-password" minlength="6" maxlength="200" required>
                <label for="add-user-password">Contraseña</label>
            </div>
            <div class="input-group">
                <input type="password" name="repeat-password" id="add-user-repeat-password" minlength="6" maxlength="200" required>
                <label for="add-user-repeat-password">Repite la contraseña</label>
            </div>
            <div class="input-group">
                <select name="permits" id="add-user-permits" class="w-100 p-3">
                <option value="0">Ningún Permiso</option>
                    <?php
                    
                        if($users -> data["Permits"] >= 2) {
                            echo "<option value='1'>Administrador</option>";
                        }

                        if($users -> data["Permits"] == 3) {
                            echo "<option value='2'>Super Administrador</option>";
                        }
                    
                    ?>
                </select>
            </div>

            <button class="submit" type="submit">Agregar usuario</button>
        </form>
    </div>

    <div class="form-screen-container"  id="edit-user">
            <form action="../php/panelSystem.php" class="form-group" method="post" id="edit-user-form">
                <button type="button" class="close" id="edit-user-hide">
                    <span class="fa fa-times"></span>
                </button>
                <h2>
                    Editar un usuario
                </h2>

                <input type="hidden" name="editUser">
                <input type="hidden" name="id" id="edit-user-id">

                <span class="text-info">
                    <b>Nota:</b> si dejas los campos de las contraseñas vacios no se le aplicaran cambios.
                </span>

                <div class="input-group">
                    <input type="text" name="username" id="edit-user-username" maxlength="20" required>
                    <label for="edit-user-username">Username</label>
                </div>
                <div class="input-group">
                    <input type="email" name="email" id="edit-user-email" maxlength="100" required>
                    <label for="edit-user-email">Correo electronico</label>
                </div>
                <div class="input-group">
                    <input type="password" name="password" id="edit-user-password" maxlength="200">
                    <label for="edit-user-password">Nueva contraseña</label>
                </div>
                <div class="input-group">
                    <input type="password" name="repeat-password" id="edit-user-repeat-password" maxlength="200">
                    <label for="edit-user-repeat-password">Repite la contraseña</label>
                </div>
                <div class="input-group">
                    <select name="permits" id="edit-user-permits" class="w-100 p-3">
                    <option value="0">Ningún Permiso</option>
                        <?php
                        
                            if($users -> data["Permits"] >= 2) {
                                echo "<option value='1'>Administrador</option>";
                            }

                            if($users -> data["Permits"] == 3) {
                                echo "<option value='2'>Super Administrador</option>";
                            }
                        
                        ?>
                    </select>
                </div>
    
                <button class="submit" type="submit">Editar Usuario</button>
            </form>
        </div>

    <?php
        include("../php/footer.php");
    ?>

    <script src="../js/jquery.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script>
        $("#msg").modal({
            show: true
        });
        //show add brand form
        $("#add-user-show").click(() => {
            $("#add-user").addClass("active");
        });

        //hide add user form
        $("#add-user-hide").click(() => {
            $("#add-user").removeClass("active");
        });

        //show edit brand form
        function showEditUser(id, username, email, permits) {
            $("#edit-user").addClass("active");
            $("#edit-user-id").val(id);
            $("#edit-user-username").val(username);
            $("#edit-user-email").val(email);
            $("#edit-user-permits").val(permits);
        }

        //hide edit brand form
        $("#edit-user-hide").click(() => {
            $("#edit-user").removeClass("active");
        });

        //delete brand
        function deleteUser(id, username) {
            if(confirm(`¿Desea eliminar el usuario ${username}?`)) {
                $.ajax({
                    url: "../php/panelSystem.php",
                    method: "post",
                    data: "deleteUser&id=" + id,
                    success: data => {
                        location.href = "users?msg=" + data;
                    },
                });
            }
        }
    </script>
</body>
</html>