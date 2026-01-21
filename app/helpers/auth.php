<?php
function auth_required() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: index.php");
        exit;
    }
}

function role_required($roles = []) {
    if (!in_array($_SESSION['rol'], $roles)) {
        header("Location: index.php");
        exit;
    }
}

?>