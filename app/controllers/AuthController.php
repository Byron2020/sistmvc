<?php
include ('app/models/User.php');

class AuthController {

    public function login() {

        if($_SERVER['REQUEST_METHOD']!='POST'){
            header("Location: index.php");
            exit;
        }
        $usuario= trim($_POST['usuario']);
        $password= $_POST['password'];

        $user = new User();


        $data = $user->login($usuario,$password);

        if ($data) {
            // SEGURIDAD CLAVE (EVITA SESSION FIXATION)
            session_regenerate_id(true);

            $_SESSION['user_id'] = $data['id_usuario'];
            $_SESSION['user_name'] = $data['nomb_usuario'];
            $_SESSION['rol'] = $data['tipo_usuario'];

            header("Location: index.php");
        } else {
            header("Location: index.php?error=1");
        }
    }

    public function logout() {
        session_destroy();
        header("Location: index.php");
    }
}
