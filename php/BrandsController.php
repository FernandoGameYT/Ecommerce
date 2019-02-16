<?php

    if(!isset($pdo)) {
        include("conexion.php");
    }

    /**
     * @property PDO $pdo
     */

    class Brands {
        private $pdo;

        public function __construct() {
            $this -> pdo = $GLOBALS["pdo"];
        }

        /**
         * @param string $name
         * 
         * @return boolean
         */

        public function addBrand($name) {
            $insert_brand = $this -> pdo -> prepare("INSERT INTO brands (Id, Name) VALUES (NULL, ?)");

            if($insert_brand -> execute([$name])) {
                return true;
            }
        }

        /**
         * @param int $id
         * 
         * @return boolean
         */

        public function deleteBrand($id) {
            $delete_brand = $this -> pdo -> prepare("DELETE FROM brands WHERE Id = ?");

            if($delete_brand -> execute([$id])) {
                return true;
            }
        }

        /**
         * @param int $id
         * @param string $name
         * 
         * @return boolean
         */

        public function editBrand($id, $name) {
            $edit_brand = $this -> pdo -> prepare("UPDATE brands SET Name = ? WHERE Id = ?");

            if($edit_brand -> execute([$name, $id])) {
                return true;
            }
        }

        public function getAllBrands() {
            $brands = $this -> pdo -> prepare("SELECT * FROM brands");

            if($brands -> execute()) {
                return $brands;
            }
        }

        /**
         * @param string $search
         */

        public function getBrandsBySearch($search) {
            $brands = $this -> pdo -> prepare("SELECT * FROM brands WHERE Name LIKE ?");

            if($brands -> execute(["%".$search."%"])) {
                return $brands;
            }
        }
    }

?>