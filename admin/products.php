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
        include("../php/ProductController.php");

        // Menu
        $products_status = "active";
        include("menu.php");

        $brands = new Brands();
        $all_brands = $brands -> getAllBrands() -> fetchAll();

        $products = new Products();

        if(isset($_GET["search"])) {
            $search = $_GET["search"];
            $products -> search = $search;
            
            $all_products = $products -> getProductsBySearch();
        }else{
            $search = "";
            $all_products = $products -> getAllRecentProducts();
        }
    ?>

    <div class="container-fluid mt-5">
        <div class="row mb-3">
            <div class="col-12 col-sm-6 col-lg-8 mt-4">
                <span class="title h3">Administrador de productos</span>
            </div>
            <div class="col-12 col-sm-6 col-lg-4">
                <form class="mx-2 d-inline" action="<?php echo $direction;?>Admins/products" method="get">
                    <div class="input-group">
                        <input type="search" name="search" class="form-control mr-sm-2" value="<?php echo $search;?>" placeholder="Buscar un producto">
                        <button class="btn btn-primary" type="submit">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <?php
            
            foreach($all_products as $product) {
                if($product["Type"] == "phones") {
                    $type = "Celular";
                }else{
                    $type = "Componente";
                }
                echo '
                <div class="row mb-5 align-items-center">
                    <div class="col-12 col-sm-6 col-md-4 col-xl-2 text-center">
                        <h4 class="title">Id</h4>
                        <span class="h5">'.$product["Id"].'</span>
                    </div>
                    <div class="col-12 col-sm-6 col-md-4 col-xl-2 text-center">
                        <h4 class="title">Nombre</h4>
                        <span class="h5">'.$product["Name"].'</span>
                    </div>
                    <div class="col-12 col-sm-6 col-md-4 col-xl-2 text-center">
                        <h4 class="title">Imagen</h4>
                        <span class="h5">'.$product["Image"].'</span>
                    </div>
                    <div class="col-12 col-sm-6 col-md-4 col-xl-2 text-center">
                        <h4 class="title">Descripcion</h4>
                        <span class="h5">'.$product["Description"].'</span>
                    </div>
                    <div class="mt-4 col-12 col-sm-6 col-md-4 col-xl-2 mt-xl-0 text-center">
                        <h4 class="title">Precio</h4>
                        <span class="h5">'.$product["Price"].'</span>
                    </div>
                    <div class="mt-4 col-12 col-sm-6 col-md-4 col-xl-2 mt-xl-0 text-center">
                        <h4 class="title">Disponibles</h4>
                        <span class="h5">'.$product["Available"].'</span>
                    </div>
                    <div class="mt-4 col-12 col-sm-6 col-md-4 col-xl-2 mt-xl-0 text-center">
                        <h4 class="title">Tipo</h4>
                        <span class="h5">'.$type.'</span>
                    </div>
                    <div class="mt-4 col-12 col-sm-6 col-md-4 col-xl-2 mt-xl-0 text-center">
                        <h4 class="title">Marca</h4>
                        <span class="h5">'.$product["Brand"].'</span>
                    </div>
                    <div class="col-12 col-sm-6 col-md-4 col-xl-2 text-sm-right">
                        <button class="btn btn-danger mr-2" onclick="deleteProduct('.$product["Id"].',\''.$product["Name"].'\',\''.$type.'\')">
                            <span class="fa fa-times"></span>
                        </button>
                        <button class="btn btn-primary" onclick="showEditProduct('.$product["Id"].',\''.$product["Name"].'\',\''.$product["Image"].'\',\''.$product["Description"].'\','.$product["Price"].','.$product["Available"].',\''.$product["Type"].'\',\''.$product["Brand"].'\')">
                            <span class="fa fa-pencil"></span>
                        </button>
                    </div>
                </div>
                ';
            }

        ?>
        <div class="row justify-content-center">
            <div class="col-12 col-md-9 col-lg-7 col-xl-12">
                <button class="btn btn-primary button-add py-3" id="add-product-show">
                    <span class="fa fa-plus"></span>
                    Agregar producto
                </button>
            </div>
        </div>
    </div>

    <!-- //****// -->

    <div class="form-screen-container"  id="add-product">
        <form action="../php/panelSystem.php" class="form-group" method="post" id="add-product-form">
            <button type="button" class="close" id="add-product-hide">
                <span class="fa fa-times"></span>
            </button>
            <h2>
                Agregar un producto
            </h2>
            <input type="hidden" name="addProduct">
            <div class="input-group">
                <input type="text" name="name" id="add-product-name" maxlength="100" required>
                <label for="add-product-name">Nombre</label>
            </div>
            <div class="input-group">
                <input type="text" name="image" id="add-product-image" maxlength="100" required>
                <label for="add-product-image">Imagen</label>
            </div>
            <div class="input-group">
                <input type="text" name="description" id="add-product-description" maxlength="1000" required>
                <label for="add-product-description">Descripcion</label>
            </div>
            <div class="input-group">
                <input type="number" name="price" id="add-product-price" min="1" max="1000000" required>
                <label for="add-product-price">Precio</label>
            </div>
            <div class="input-group">
                <input type="number" name="available" id="add-product-available" min="1" max="1000000" required>
                <label for="add-product-available">Disponibles</label>
            </div>
            <div class="input-group">
                <select name="type" id="add-product-type" class="w-100 p-3">
                    <option value="phones">Celular</option>
                    <option value="components">Componente</option>
                </select>
            </div>
            <div class="input-group">
                <select name="brand" id="add-product-brand" class="w-100 p-3">
                    <?php
                    
                        foreach($all_brands as $brand) {
                            echo '<option value="'.$brand["Name"].'">'.$brand["Name"].'</option>';
                        }
                    
                    ?>
                </select>
            </div>

            <button class="submit" type="submit">Agregar Producto</button>
        </form>
    </div>

    <div class="form-screen-container"  id="edit-product">
            <form action="../php/panelSystem.php" class="form-group" method="post" id="edit-product-form">
                <button type="button" class="close" id="edit-product-hide">
                    <span class="fa fa-times"></span>
                </button>
                <h2>
                    Editar un producto
                </h2>

                <input type="hidden" name="editProduct">
                <input type="hidden" name="id" id="edit-product-id">
                <div class="input-group">
                    <input type="text" name="name" id="edit-product-name" maxlength="100" required>
                    <label for="edit-product-name">Nombre</label>
                </div>
                <div class="input-group">
                    <input type="text" name="image" id="edit-product-image" maxlength="100" required>
                    <label for="edit-product-image">Imagen</label>
                </div>
                <div class="input-group">
                    <input type="text" name="description" id="edit-product-description" maxlength="1000" required>
                    <label for="edit-product-description">Descripcion</label>
                </div>
                <div class="input-group">
                    <input type="number" name="price" id="edit-product-price" min="1" max="1000000" required>
                    <label for="edit-product-price">Precio</label>
                </div>
                <div class="input-group">
                    <input type="number" name="available" id="edit-product-available" min="0" max="1000000" required>
                    <label for="edit-product-available">Disponibles</label>
                </div>
                <div class="input-group">
                    <select name="type" id="edit-product-type" class="w-100 p-3">
                        <option value="phones">Celular</option>
                        <option value="components">Componente</option>
                    </select>
                </div>
                <div class="input-group">
                    <select name="brand" id="edit-product-brand" class="w-100 p-3">
                        <?php
                        
                            foreach($all_brands as $brand) {
                                echo '<option value="'.$brand["Name"].'">'.$brand["Name"].'</option>';
                            }
                        
                        ?>
                    </select>
                </div>
    
                <button class="submit" type="submit">Editar Producto</button>
            </form>
        </div>

    <?php
        include("../php/footer.php");
    ?>

    <script src="../js/jquery.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script>
        //show add product form
        $("#add-product-show").click(() => {
            $("#add-product").addClass("active");
        });

        //hide add product form
        $("#add-product-hide").click(() => {
            $("#add-product").removeClass("active");
        });

        //show edit product form
        function showEditProduct(id, name, image, description, price, available, type, brand) {
            $("#edit-product").addClass("active");
            console.log($("#edit-product-description"));
            $("#edit-product-id").val(id);
            $("#edit-product-name").val(name);
            $("#edit-product-image").val(image);
            $("#edit-product-description").val(description);
            $("#edit-product-price").val(price);
            $("#edit-product-available").val(available);
            $("#edit-product-type").val(type);
            $("#edit-product-brand").val(brand);
        }

        //hide edit product form
        $("#edit-product-hide").click(() => {
            $("#edit-product").removeClass("active");
        });

        //delete product
        function deleteProduct(id, name, type) {
            if(confirm(`¿Desea eliminar el ${type} ${name}?`)) {
                $.ajax({
                    url: "../php/panelSystem.php",
                    method: "post",
                    data: "deleteProduct&id=" + id,
                    success: () => {
                        location.reload();
                    },
                });
            }
        }
    </script>
</body>
</html>