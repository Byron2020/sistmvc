<?php
$maxTime = 14400; // 4 horas

if (isset($_SESSION['LAST_ACTIVITY'])) {
    if (time() - $_SESSION['LAST_ACTIVITY'] > $maxTime) {
        session_unset();
        session_destroy();
        header("Location: index.php");
        exit;
    }
}

$_SESSION['LAST_ACTIVITY'] = time();
?>