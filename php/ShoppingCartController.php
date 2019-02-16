<?php
    if(!class_exists("Products")) {
        include("ProductController.php"); 
    }

    class ShoppingCart {
        private $products;

        public function __construct() {
            $this -> products = new Products();

            if(!isset($_SESSION["car"])) {
                if(isset($_COOKIE["ShoppingCart"])) {
                    $_SESSION["car"] = (array) json_decode($_COOKIE["ShoppingCart"]);
                }
            }
        }

        /**
         * @param int $id
         * 
         * @return array
         */

        public function deleteProduct($id) {
            $id = "product".$id;

            if(isset($_SESSION["car"])) {
                $car = $_SESSION["car"];

                if(isset($car[$id])) {
                    unset($car[$id]);

                    $_SESSION["car"] = $car;
                    $this -> setCartCookie($car);
                    
                    $msg = array(
                        "state" => true,
                        "msg" => "Se a eliminado correctamente."
                    );
                    return json_encode($msg);
                }else{
                    $msg = array(
                        "state" => false,
                        "msg" => "El producto no existe."
                    );
                    return json_encode($msg);
                }
            }else{
                $msg = array(
                    "state" => false,
                    "msg" => "El producto no existe."
                );
                return json_encode($msg);
            }
        }

        public function deleteCart() {
            $cookie_direction = $GLOBALS["cookie_direction"];
            unset($_SESSION["car"]);
            setcookie("ShoppingCart", "", time() + 3600 * 24 * 365, "/", $cookie_direction);
        }

        /**
         * @param int $id
         * @param int $quantity
         * 
         * @return array
         */

        public function addProduct($id, $quantity) {
            $this -> products -> id = $id;
            $product = $this -> products -> getProductById();

            if($product -> rowCount() > 0) {
                $product = $product -> fetch();
                $id = "product".$id;

                if(isset($_SESSION["car"])) {
                    $car = $_SESSION["car"];

                    if(!isset($car[$id])) {
                        $car[$id] = 0;
                    }
                }else{
                    $car = array();
                    $car[$id] = 0;
                }

                if($car[$id] + $quantity <= $product["Available"]) {
                    $car[$id] += $quantity;
                    $_SESSION["car"] = $car;
                    $this -> setCartCookie($car);

                    $msg = array(
                        "state" => true,
                        "msg" => "Se a agregado a el carrito de compras."
                    );
                    return json_encode($msg);
                }else{
                    $msg = array(
                        "state" => false,
                        "msg" => "No hay productos suficientes."
                    );
                    return json_encode($msg);
                }
            }else{
                $msg = array(
                    "state" => false,
                    "msg" => "A ocurrido un error en el servidor."
                );
                return json_encode($msg);
            }
        }

        /**
         * @param int $id
         * @param int $quantity
         * 
         * @return array
         */

        public function editProduct($id, $quantity) {
            if($quantity < 1) {
                $msg = array(
                    "state" => false,
                    "msg" => "La cantidad no puede ser 0."
                );
                return json_encode($msg);
            }
            $this -> products -> id = $id;
            $product = $this -> products -> getProductById();

            if($product -> rowCount() > 0) {
                $product = $product -> fetch();
                $id = "product".$id;

                if(isset($_SESSION["car"])) {
                    $car = $_SESSION["car"];

                    if(!isset($car[$id])) {
                        $msg = array(
                            "state" => false,
                            "msg" => "A ocurrido un error en el servidor."
                        );
                        return json_encode($msg);
                    }
                }else{
                    $msg = array(
                        "state" => false,
                        "msg" => "A ocurrido un error en el servidor."
                    );
                    return json_encode($msg);
                }

                if($quantity <= $product["Available"]) {
                    $car[$id] = $quantity;
                    $_SESSION["car"] = $car;
                    $this -> setCartCookie($car);

                    $msg = array(
                        "state" => true,
                        "msg" => "Se a editado el carrito de compras."
                    );
                    return json_encode($msg);
                }else{
                    $msg = array(
                        "state" => false,
                        "msg" => "No hay productos suficientes."
                    );
                    return json_encode($msg);
                }
            }else{
                $msg = array(
                    "state" => false,
                    "msg" => "A ocurrido un error en el servidor."
                );
                return json_encode($msg);
            }
        }

        /**
         * @return array
         */

        public function getProducts() {
            if(isset($_SESSION["car"])) {
                $result = array();

                foreach($_SESSION["car"] as $id => $quantity) {
                    $data = array();
                    $id = (int) explode("product", $id)[1];
                    $this -> products -> id = $id;
                    $product = $this -> products -> getProductById();

                    $data["Quantity"] = $quantity;
                    $data["IdCart"] = $id;

                    if($product -> rowCount() > 0) {
                        $product = $product -> fetch();
                        $data["Id"] = $product["Id"];
                        $data["Name"] = $product["Name"];
                        $data["Image"] = $product["Image"];
                        $data["Price"] = $product["Price"];
                        if($product["Available"] < $quantity) {
                            $data["State"] = false;
                            $data["Msg"] = "Este producto no tiene la cantidad solicitada.";
                        }else{
                            $data["State"] = true;
                            $data["Msg"] = "Disponible.";
                        }
                    }else{
                        $data["Id"] = 0;
                        $data["Name"] = "Unknown";
                        $data["Image"] = "not_found.jpg";
                        $data["Price"] = 0;
                        $data["State"] = false;
                        $data["Msg"] = "Este producto ya no existe.";
                    }
                    
                    $result[] = $data;
                }

                return $result;
            }else{
                return array();
            }
        }

        private function setCartCookie($array) {
            $cookie_direction = $GLOBALS["cookie_direction"];
            setcookie("ShoppingCart", json_encode($array), time() + 3600 * 24 * 365, "/", $cookie_direction);
        }
    }

?>