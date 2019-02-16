<?php
    if(!isset($direction)) {
        include("conexion.php");
    }
?>
<footer class="page-footer mt-5">
    <div class="container">
        <div class="row text-center text-md-left">
            <div class="col-md-4 col-lg-4 col-xl-4 mx auto mb-4">
                <h5 class="font-weight-bold">PhoneShop</h5>
                <hr class="mb-4 mt-0 d-inline-block mx-auto separator">
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consectetur, expedita.</p>
            </div>

            <div class="col-md-4 col-lg-4 col-xl-4 mx auto mb-4">
                <h5 class="font-weight-bold">Productos</h5>
                <hr class="mb-4 mt-0 d-inline-block mx-auto separator">
                <p>
                    <a href="<?php echo $direction;?>Products/Phones/">Celulares</a>
                </p>
                <p>
                    <a href="<?php echo $direction;?>Products/Components/">Componentes</a>
                </p>
            </div>

            <div class="col-md-4 col-lg-4 col-xl-4 mx auto mb-4">
                <h5 class="font-weight-bold">Redes Sociales</h5>
                <hr class="mb-4 mt-0 d-inline-block mx-auto separator">
                <p class="social-container">
                    <a href="#">
                        <i class="fa fa-facebook mr-4"></i>
                    </a>

                    <a href="#">
                        <i class="fa fa-twitter mr-4"></i>
                    </a>
                    
                    <a href="#">
                        <i class="fa fa-instagram mr-4"></i>
                    </a>

                    <a href="#">
                        <i class="fa fa-linkedin mr-4"></i>
                    </a>
                </p>
            </div>
        </div>
    </div>

    <div class="footer-copyright text-center py-3">© 2019 Copyright:
        <a href="#">PhoneShop.com</a>
    </div>

</footer>