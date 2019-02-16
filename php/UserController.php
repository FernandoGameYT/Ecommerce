<?php

    if(!isset($pdo)) {
        include("conexion.php");
    }

    /**
     * @property PDO $pdo
     * @property array $data
     */

    class Users {
        private $pdo;

        public $data = array();

        public function __construct() {
            $this -> pdo = $GLOBALS["pdo"];
        }

        /**
         * @param int $limit
         * @param int $offset
         */

        public function getAllUsers($limit = 20, $offset = 0) {
            $get_users = $this -> pdo -> prepare("SELECT * FROM users ORDER BY Id DESC LIMIT ? OFFSET ?");

            if($get_users -> execute([$limit, $offset])) {
                return $get_users;
            }
        }

        /**
         * @param string $username
         * @param string $email
         * @param string $password
         * 
         * @return array
         */

        public function addUser($username, $email, $password) {
            $direction = $GLOBALS["direction"];
            if(strlen($username) < 4 || strlen($username) > 20) {
                $msg = array(
                    "state" => false,
                    "msg" => "El nombre de usuario debe tener 4 o mas caracteres."
                );
                return json_encode($msg);
            }else if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $msg = array(
                    "state" => false,
                    "msg" => "El correo electronico no es valido."
                );
                return json_encode($msg);
            }else if(strlen($password) < 6 || strlen($password) > 50) {
                $msg = array(
                    "state" => false,
                    "msg" => "La contraseña debe tener 6 o mas caracteres."
                );
                return json_encode($msg);
            }

            $check_user = $this -> pdo -> prepare("SELECT Username, Email FROM users WHERE Username = ? OR Email = ?");

            if($check_user -> execute([$username, $email])) {
                $user = $check_user -> fetch();

                if($username == $user["Username"]) {
                    $msg = array(
                        "state" => false,
                        "msg" => "El nombre de usuario ya existe."
                    );
                    return json_encode($msg);
                }else if($email == $user["Email"]){
                    $msg = array(
                        "state" => false,
                        "msg" => "El correo electronico ya existe."
                    );
                    return json_encode($msg);
                }
            }

            $new_user = $this -> pdo -> prepare("INSERT INTO users (Id, Username, Email, Password, ActivationCode) VALUES (NULL, ?, ?, ?, ?)");
            $activationCode = md5($email.time());
            $password = password_hash($password, PASSWORD_ARGON2I);

            include("mails/register.php");

            $mail = mail($email, "Verificacion de correo electronico - (PhoneShop)", $registerMail, $headers);

            if(!$mail) {
                $msg = array(
                    "state" => false,
                    "msg" => "No hemos podido verificar su correo eletronico."
                );
                return json_encode($msg);
            }

            if($new_user -> execute([$username, $email, $password, $activationCode])) {
                $msg = array(
                    "state" => true,
                    "msg" => "Registro correcto, para continuar hemos mandado un correo electronico para verificar que eres el dueño del correo electronico."
                );
                return json_encode($msg);
            }
        }

        /**
         * @param string $activationCode
         * 
         * @return array
         */

        public function activateUser($activationCode) {
            $check_activation = $this -> pdo -> prepare("SELECT Activation FROM users WHERE ActivationCode = ?");

            if($check_activation -> execute([$activationCode])) {
                if($check_activation -> rowCount() > 0) {
                    $user_activation_status = $check_activation -> fetch()["Activation"];

                    if($user_activation_status > 0) {
                        $msg = array(
                            "state" => false,
                            "msg" => "La cuenta ya a sido activada."
                        );
                        return json_encode($msg);
                    }

                    $update_activation = $this -> pdo -> prepare("UPDATE users SET Activation = 1 WHERE ActivationCode = ?");

                    if($update_activation -> execute([$activationCode])) {
                        $msg = array(
                            "state" => true,
                            "msg" => "Tu cuenta a sido activada. Ya puedes iniciar sesion."
                        );
                        return json_encode($msg);
                    }
                }else{
                    $msg = array(
                        "state" => false,
                        "msg" => "No se a encontrado la cuenta que desea activar."
                    );
                    return json_encode($msg);
                }
            }
        }

        /**
         * @param string $username
         * @param string $password
         * 
         * @return array
         */

        public function hasUser($username, $password) {
            $cookie_direction = $GLOBALS["cookie_direction"];
            $user = $this -> pdo -> prepare("SELECT Password, Activation FROM users WHERE Username = ?");

            if($user -> execute([$username])) {
                if($user -> rowCount() > 0) {
                    $user = $user -> fetch();

                    if($user["Activation"] < 1) {
                        $msg = array(
                            "state" => false,
                            "msg" => "La cuenta no a sido activada."
                        );
                        return json_encode($msg);
                    }

                    if(password_verify($password, $user["Password"])) {
                        $sessionId = md5($username . time());

                        $set_sessionId = $this -> pdo -> prepare("UPDATE users SET SessionId = ? WHERE Username = ?");

                        if($set_sessionId -> execute([$sessionId, $username])) {
                            $_SESSION["SessionId"] = $sessionId;
                            setcookie("SessionId", $sessionId, time() + 3600 * 24 * 30, "/", $cookie_direction);

                            $msg = array(
                                "state" => true,
                                "msg" => "Inicio de sesion correcto."
                            );
                            return json_encode($msg);
                        }
                    }else{
                        $msg = array(
                            "state" => false,
                            "msg" => "La contraseña es incorrecta."
                        );
                        return json_encode($msg);
                    }
                }else{
                    $msg = array(
                        "state" => false,
                        "msg" => "El nombre de usuario no existe."
                    );
                    return json_encode($msg);
                }
            }
        }

        /**
         * @return boolean
         */

        public function checkSession() {
            if(!isset($_SESSION["SessionId"])) {
               if(isset($_COOKIE["SessionId"])) {
                   $_SESSION["SessionId"] = $_COOKIE["SessionId"];
               }else{
                   return false;
               }
            }

            $check_session = $this -> pdo -> prepare("SELECT * FROM users WHERE SessionId = ?");

            if($check_session -> execute([$_SESSION["SessionId"]])) {
                if($check_session -> rowCount() > 0) {
                    $this -> data = $check_session -> fetch();

                    return true;
                }else{
                    return false;
                }
            }
        }

        public function closeSession() {
            setcookie("SessionId", "", time() - 1, "/", $cookie_direction);

            if(isset($_SESSION["SessionId"])) {
                unset($_SESSION["SessionId"]);
            }
        }

        /**
         * @param string $username
         * 
         * @return array
         */

        public function updateUsername($username) {
            if(!isset($this -> data["Username"])) {
                $this -> checkSession();
            }

            if(strlen($username) < 4 || strlen($username) > 20) {
                $msg = array(
                    "state" => false,
                    "msg" => "El nombre de usuario debe tener 4 o mas caracteres."
                );
                return json_encode($msg);
            }else if($username == $this -> data["Username"]) {
                $msg = array(
                    "state" => false,
                    "msg" => "No se a realizado ningún cambio."
                );
                return json_encode($msg);
            }else{
                $check_username = $this -> pdo -> prepare("SELECT Id FROM users WHERE Username = ?");

                if($check_username -> execute([$username])) {
                    if($check_username -> rowCount() > 0) {
                        $msg = array(
                            "state" => false,
                            "msg" => "El nombre de usuario ya existe."
                        );
                        return json_encode($msg);
                    }
                }

                $update_username = $this -> pdo -> prepare("UPDATE users SET Username = ? WHERE SessionId = ?");

                if($update_username -> execute([$username, $_SESSION["SessionId"]])) {
                    $msg = array(
                        "state" => true,
                        "msg" => "Se a cambiado el nombre de usuario."
                    );
                    return json_encode($msg);
                }
            }
        }

        /**
         * @param string $email
         * 
         * @return array
         */

        public function updateEmail($email) {
            if(!isset($this -> data["Email"])) {
                $this -> checkSession();
            }

            if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $msg = array(
                    "state" => false,
                    "msg" => "El correo electronico no es valido."
                );
                return json_encode($msg);
            }else if($email == $this -> data["Email"]) {
                $msg = array(
                    "state" => false,
                    "msg" => "No se a realizado ningún cambio."
                );
                return json_encode($msg);
            }else{
                $check_email = $this -> pdo -> prepare("SELECT Id FROM users WHERE Email = ?");

                if($check_email -> execute([$email])) {
                    if($check_email -> rowCount() > 0) {
                        $msg = array(
                            "state" => false,
                            "msg" => "El email ya existe."
                        );
                        return json_encode($msg);
                    }
                }

                $update_email = $this -> pdo -> prepare("UPDATE users SET Email = ? WHERE SessionId = ?");

                if($update_email -> execute([$email, $_SESSION["SessionId"]])) {
                    $msg = array(
                        "state" => true,
                        "msg" => "Se a cambiado el correo electronico."
                    );
                    return json_encode($msg);
                }
            }
        }

        /**
         * @param string $password
         * @param string $oldPassword
         * 
         * @return array
         */

        public function updatePassword($password, $oldPassword) {
            if(!isset($this -> data["Password"])) {
                $this -> checkSession();
            }

            if(strlen($password) < 6 || strlen($password) > 50) {
                $msg = array(
                    "state" => false,
                    "msg" => "La nueva contraseña debe tener 6 o mas caracteres."
                );
                return json_encode($msg);
            }else if(!password_verify($oldPassword, $this -> data["Password"])) {
                $msg = array(
                    "state" => false,
                    "msg" => "La contraseña actual es incorrecta."
                );
                return json_encode($msg);
            }else{
                $password = password_hash($password, PASSWORD_ARGON2I);
                $update_password = $this -> pdo -> prepare("UPDATE users SET Password = ? WHERE SessionId = ?");

                if($update_password -> execute([$password, $_SESSION["SessionId"]])) {
                    $msg = array(
                        "state" => true,
                        "msg" => "Se a cambiado la contraseña."
                    );
                    return json_encode($msg);
                }
            }
        }
    }

?>