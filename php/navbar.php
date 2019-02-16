<?php

    include("BrandsController.php");
    
    if(!class_exists("Users")) {
        include("UserController.php");
    }

    $brand = new Brands();
    $users = new Users();

    $session_status = $users -> checkSession();
?>

<nav class="navbar navbar-expand-xl navbar-light bg-light">

    <a href="<?php echo $direction;?>" class="navbar-brand navbar-title">
        <b>PhoneShop</b>
    </a>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-collapse">
        <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="navbar-collapse collapse" id="navbar-collapse">
        <ul class="navbar-nav mr-auto mt-2 mt-lg-0" style="font-size: 16px;font-weight: bold;">
            <li class="nav-item">
                <a class="nav-link" href="<?php echo $direction;?>">Inicio</a>
            </li>

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Marcas
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <?php
                        $allBrands = $brand -> getAllBrands();

                        foreach($allBrands as $brand) {
                            echo '<a class="dropdown-item" href="'.$direction.'Brands/'.$brand["Name"].'/">
                                '.$brand["Name"].'
                            </a>';
                        }
                    
                    ?>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="<?php echo $direction;?>Products/Components/">Componentes</a>
            </li>

            <?php

                if(!$session_status) {
                    echo '
                    <li class="nav-item">
                        <a class="nav-link" href="'.$direction.'login/">Iniciar Sesion</a>
                    </li>
                    ';
                }

            ?>

            <li class="nav-item">
                <a class="nav-link" href="<?php echo $direction;?>shoppingCart/">Mi carrito</a>
            </li>

            <?php
            
                if($session_status) {
                    echo '
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Mi perfil
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="'.$direction.'Users/myProfile/">
                                Configuracion
                            </a>
                            <a class="dropdown-item" href="'.$direction.'Users/myProfile?myOrders">
                                Mis pedidos
                            </a>
                        </div>
                    </li>
                    ';

                    if($users -> data["Permits"] > 0) {
                        echo '
                        <li class="nav-item">
                            <a class="nav-link" href="'.$direction.'Admins/">Panel de administrador</a>
                        </li>
                        ';
                    }

                    echo '
                    <li class="nav-item">
                        <a class="nav-link" href="'.$direction.'Users/closeSession/">Cerrar sesion</a>
                    </li>
                    ';
                }

            ?>
        </ul>

        <form class="mx-2 my-auto d-inline" action="<?php echo $direction;?>Products" method="get">
            <div class="input-group">
                <input type="search" name="search" class="form-control mr-sm-2" placeholder="Buscar un celular" required>
                <button class="btn btn-primary" type="submit">
                    <i class="fa fa-search"></i>
                </button>
            </div>
        </form>
            
    </div>
</nav>