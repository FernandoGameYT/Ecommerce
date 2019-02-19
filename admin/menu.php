<?php

    if(!isset($brand_status)) {
        $brand_status = "";
    }

    if(!isset($products_status)) {
        $products_status = "";
    }

    if(!isset($user_status)) {
        $user_status = "";
    }

    if(!isset($order_status)) {
        $order_status = "";
    }

?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-11 menu-linear collapse-sm">
            <a href="<?php echo $direction;?>Admins/brands/">
                <span class="menu-item <?php echo $brand_status;?>">Administrar Marcas</span>
            </a>
            <a href="<?php echo $direction;?>Admins/products/">
                <span class="menu-item <?php echo $products_status;?>">Administrar Productos</span>
            </a>
            <a href="<?php echo $direction;?>Admins/users/">
                <span class="menu-item <?php echo $user_status;?>">Administrar Usuarios</span>
            </a>
            <a href="<?php echo $direction;?>Admins/orders/">
                <span class="menu-item <?php echo $order_status;?>">Administrar Pedidos</span>
            </a>
        </div>
    </div>
</div>