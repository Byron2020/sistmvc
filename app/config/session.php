<?php
ini_set('session.gc_maxlifetime', 14400); // 4 horas
session_set_cookie_params([
    'lifetime' => 14400,
    'path' => '/',
    'secure' => false,   // true solo si usas HTTPS
    'httponly' => true,
    'samesite' => 'Lax'
]);
session_start();
?>