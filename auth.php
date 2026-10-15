<?php
require_once('database.php');

if (
    !isset($_SESSION['isLoggedIn']) ||
    $_SESSION['isLoggedIn'] !== true
) {
    header('Location: login_form.php');
    exit();
}
?>