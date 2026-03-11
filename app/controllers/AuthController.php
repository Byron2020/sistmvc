<?php
require_once 'app/models/User.php';

class AuthController
{
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            header("Location: index.php");
            exit;
        }

        $usuario = trim($_POST['usuario']);
        $password = $_POST['password'];

        $user = new User();
        $data = $user->login($usuario, $password);

        if ($data) {
            session_regenerate_id(true);

            $_SESSION['user_id'] = $data['id_usuario'];
            $_SESSION['user_name'] = $data['nomb_usuario'];
            $_SESSION['rol'] = $data['tipo_usuario'];

            header("Location: index.php?page=reportes");
            exit;
        } else {
            header("Location: index.php?error=1");
            exit;
        }
    }

    public function logout()
    {
        $_SESSION = [];
        session_unset();
        session_destroy();

        header("Location: index.php");
        exit;
    }
}