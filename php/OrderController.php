<?php

    if(!class_exists("ShoppingCart")) {
        include("ShoppingCartController.php"); 
    }

    if(!class_exists("Products")) {
        include("ProductController.php");
    }

    /**
     * @property PDO $pdo
     * @property ShoppingCart $cart
     * @property Products $products
     * @property int $userId
     * @property int $orderId
     */

    class Orders {
        private $pdo;
        private $cart;
        private $products;
        public $userId = 0;
        public $orderId = 0;

        public function __construct() {
            $this -> pdo = $GLOBALS["pdo"];
            $this -> cart = new ShoppingCart();
            $this -> products = new Products();
        }

        /**
         * @param int $userId
         * 
         * @return boolean
         */

        public function saveOrder($userId) {
            $insert_order = $this -> pdo -> prepare("INSERT INTO orders (Id, Subtotal, Shipping, UserId) VALUES (NULL, ?, ?, ?)");
            $products = $this -> cart -> getProducts();
            $subtotal = 0;
            $shipping = 0;

            foreach($products as $product) {
                $subtotal += $product["Price"] * $product["Quantity"];
            }

            if($insert_order -> execute([$subtotal, $shipping, $userId])) {
                foreach($products as $product) {
                    if($product["State"]) {
                        $orderId = $this -> pdo -> lastInsertId();
                        $this -> saveOrderItem($orderId, $product);
                    }
                }
                return true;
            }
        }

        /**
         * @param int $orderId
         * 
         * @return boolean
         */

        public function saveOrderItem($orderId, $product) {
            $insert_order_item = $this -> pdo -> prepare("INSERT INTO orderitems (Id, Quantity, Price, ProductId, OrderId) VALUES (NULL, ?, ?, ?, ?)");
            $this -> products -> id = $product["Id"];
            $available = $this -> products -> getAvailable();

            if(!$this -> products -> editAvailable($available - $product["Quantity"])) {
                return false;
            }

            if($insert_order_item -> execute([$product["Quantity"], $product["Price"], $product["Id"], $orderId])) {
                return true;
            }
        }

        public function getRecentOrders() {
            $orders = $this -> pdo -> prepare("SELECT * FROM orders WHERE UserId = ? ORDER BY Id DESC");

            if($orders -> execute([$this -> userId])) {
                return $orders;
            }
        }

        /**
         * @return array
         */

        public function getOrderProducts() {
            $order_items = $this -> pdo -> prepare("SELECT Quantity, Price, ProductId FROM orderitems WHERE OrderId = ?");

            if($order_items -> execute([$this -> orderId])) {
                $items = array();
                foreach($order_items as $item) {
                    $this -> products -> id = $item["ProductId"];
                    $product = $this -> products -> getProductById();
                    $product = $product -> fetch();
                    $product["Price"] = $item["Price"];
                    $product["Quantity"] = $item["Quantity"];
                    $items[] = $product;
                }
                return $items;
            }
        }
    }

?>