<?php

    if(!isset($pdo)) {
        include("conexion.php");
    }

    /**
     * @property PDO $pdo
     * @property int $limit
     * @property string $brand
     * @property string $type
     * @property int $id
     * @property string $search
     */

    class Products {
        private $pdo;
        
        public $limit = 20;
        public $brand = "";
        public $type = "phones";
        public $id = 0;
        public $search = "";

        public function __construct() {
            $this -> pdo = $GLOBALS["pdo"];
        }

        /**
         * @param int $offset
         */

        public function getProductsRecentOfBrand($offset = 0) {
            $products = $this -> pdo -> prepare("SELECT Id, Name, Image, Description, Price, Available FROM products WHERE Available > 0 AND Type = ? AND Brand = ? ORDER BY Id DESC LIMIT ? OFFSET ?");

            if($products -> execute([$this -> type, $this -> brand, $this -> limit, $offset])) {
                return $products;
            }
        }

        /**
         * @param int $offset
         */

        public function getRecentProducts($offset = 0) {
            $products = $this -> pdo -> prepare("SELECT Id, Name, Image, Description, Price, Available, Brand FROM products WHERE Available > 0 AND Type = ? ORDER BY Id DESC LIMIT ? OFFSET ?");

            if($products -> execute([$this -> type, $this -> limit, $offset])) {
                return $products;
            }
        }

        /**
         * @param int $offset
         */

        public function getAllRecentProducts($offset = 0) {
            $products = $this -> pdo -> prepare("SELECT * FROM products ORDER BY Id DESC LIMIT ? OFFSET ?");

            if($products -> execute([$this -> limit, $offset])) {
                return $products;
            }
        }

        public function getProductById() {
            $products = $this -> pdo -> prepare("SELECT * FROM products WHERE Id = ?");

            if($products -> execute([$this -> id])) {
                return $products;
            }
        }

        /**
         * @param int $offset
         */

        public function getProductsBySearch($offset = 0) {
            $products = $this -> pdo -> prepare("SELECT * FROM products WHERE Name LIKE ? LIMIT ? OFFSET ?");

            if($products -> execute(["%".$this -> search."%", $this -> limit, $offset])) {
                return $products;
            }
        }

        /**
         * @return int
         */

        public function getAvailable() {
            $get_avaliable = $this -> pdo -> prepare("SELECT Available FROM products WHERE Id = ?");

            if($get_avaliable -> execute([$this -> id])) {
                return $get_avaliable -> fetch()["Available"];
            }else{
                return 0;
            }
        }

        /**
         * @param int $avaliable
         * 
         * @return boolean
         */

        public function editAvailable($available) {
            $edit_product = $this -> pdo -> prepare("UPDATE products SET Available = ? WHERE Id = ?");

            if($edit_product -> execute([$available, $this -> id])) {
                if($edit_product -> rowCount() > 0) {
                    return true;
                }else{
                    return false;
                }
            }
        }

        /**
         * @param string $name
         * @param string $image
         * @param string $description
         * @param int $price
         * @param int $available
         * @param string $type
         * @param string $brand
         * 
         * @return boolean
         */

        public function addProduct($name, $image, $description, $price, $available, $type, $brand) {
            $add_product = $this -> pdo -> prepare("INSERT INTO products (Id, Name, Image, Description, Price, Available, Type, Brand) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?)");

            if($add_product -> execute([$name, $image, $description, $price, $available, $type, $brand])) {
                return true;
            }
        }

        /**
         * @param string $name
         * @param string $image
         * @param string $description
         * @param int $price
         * @param int $available
         * @param string $type
         * @param string $brand
         * 
         * @return boolean
         */

        public function editProduct($id, $name, $image, $description, $price, $available, $type, $brand) {
            $edit_product = $this -> pdo -> prepare("UPDATE products SET Name = ?, Image = ?, Description = ?, Price = ?, Available = ?, Type = ?, Brand = ? WHERE Id = ?");

            if($edit_product -> execute([$name, $image, $description, $price, $available, $type, $brand, $id])) {
                return true;
            }
        }

        /**
         * @param int $id
         * 
         * @return boolean
         */

        public function deleteProduct($id) {
            $delete_product = $this -> pdo -> prepare("DELETE FROM products WHERE Id = ?");

            if($delete_product -> execute([$id])) {
                return true;
            }
        } 
    }

?>