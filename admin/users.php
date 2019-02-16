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
        $brand_status = "active";
        include("menu.php");

        $brands = new Brands();

        if(isset($_GET["search"])) {
            $search = $_GET["search"];
            $all_brands = $brands -> getBrandsBySearch($search);
        }else{
            $search = "";
            $all_brands = $users;
        }
    ?>

    <div class="container-fluid mt-5">
        <div class="row mb-3">
            <div class="col-12 col-sm-6 col-md-6 col-lg-6 offset-xl-3 col-xl-3 mt-4">
                <span class="title h3">Administrador de marcas</span>
            </div>
            <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3">
                <form class="mx-2 d-inline" action="<?php echo $direction;?>Admins/brands" method="get">
                    <div class="input-group">
                        <input type="search" name="search" class="form-control mr-sm-2" value="<?php echo $search;?>" placeholder="Buscar una marca">
                        <button class="btn btn-primary" type="submit">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <?php
            
            foreach($all_brands as $brand) {
                echo '
                <div class="row mb-5 align-items-center justify-content-center">
                    <div class="col-3 col-sm-4 col-md-3 col-lg-2 col-xl-2 text-center">
                        <h4 class="title">Id</h4>
                        <span class="h5">'.$brand["Id"].'</span>
                    </div>
                    <div class="col-6 col-sm-5 col-md-4 col-lg-3 col-xl-2 text-center">
                        <h4 class="title">Nombre</h4>
                        <span class="h5">'.$brand["Name"].'</span>
                    </div>
                    <div class="col-3 col-sm-3 col-md-2 col-lg-2 col-xl-2 text-sm-right">
                        <button class="btn btn-danger mr-2" onclick="deleteBrand('.$brand["Id"].',\''.$brand["Name"].'\')">
                            <span class="fa fa-times"></span>
                        </button>
                        <button class="btn btn-primary" onclick="showEditBrand('.$brand["Id"].',\''.$brand["Name"].'\')">
                            <span class="fa fa-pencil"></span>
                        </button>
                    </div>
                </div>
                ';
            }

        ?>
        <div class="row justify-content-center">
            <div class="col-12 col-md-9 col-lg-7 col-xl-6">
                <button class="btn btn-primary button-add" id="add-brand-show">
                    <span class="fa fa-plus"></span>
                    Agregar marca
                </button>
            </div>
        </div>
    </div>

    <div class="form-screen-container"  id="add-brand">
        <form action="../php/panelSystem.php" class="form-group" method="post" id="add-brand-form">
            <button type="button" class="close" id="add-brand-hide">
                <span class="fa fa-times"></span>
            </button>
            <h2>
                Agregar una marca
            </h2>
            <input type="hidden" name="addBrand">
            <div class="input-group">
                <input type="text" name="name" id="add-brand-name" maxlength="50" required>
                <label for="add-brand-name">Nombre</label>
            </div>

            <button class="submit" type="submit">Agregar Marca</button>
        </form>
    </div>

    <div class="form-screen-container"  id="edit-brand">
            <form action="../php/panelSystem.php" class="form-group" method="post" id="edit-brand-form">
                <button type="button" class="close" id="edit-brand-hide">
                    <span class="fa fa-times"></span>
                </button>
                <h2>
                    Editar una marca
                </h2>

                <input type="hidden" name="editBrand">
                <input type="hidden" name="id" id="edit-brand-id">

                <div class="input-group">
                    <input type="text" name="name" id="edit-brand-name" maxlength="50" required>
                    <label for="edit-brand-name">Nombre</label>
                </div>
    
                <button class="submit" type="submit">Editar Marca</button>
            </form>
        </div>

    <?php
        include("../php/footer.php");
    ?>

    <script src="../js/jquery.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script>
        //show add brand form
        $("#add-brand-show").click(() => {
            $("#add-brand").addClass("active");
        });

        //hide add brand form
        $("#add-brand-hide").click(() => {
            $("#add-brand").removeClass("active");
        });

        //show edit brand form
        function showEditBrand(id, name) {
            $("#edit-brand").addClass("active");
            $("#edit-brand-name").val(name);
            $("#edit-brand-id").val(id);
        }

        //hide edit brand form
        $("#edit-brand-hide").click(() => {
            $("#edit-brand").removeClass("active");
        });

        //delete brand
        function deleteBrand(id, name) {
            if(confirm(`¿Desea eliminar la marca ${name}?`)) {
                $.ajax({
                    url: "../php/panelSystem.php",
                    method: "post",
                    data: "deleteBrand&id=" + id,
                    success: () => {
                        location.reload();
                    },
                });
            }
        }
    </script>
</body>
</html>