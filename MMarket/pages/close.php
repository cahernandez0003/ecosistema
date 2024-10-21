<?php
session_start();

unset($_SESSION['username']);
unset($_SESSION['urol']);
// unset($_SESSION['ufoto']);
unset($_SESSION['type']);
unset($_SESSION['message']);

session_destroy();

// Verifica si el usuario está en la carpeta sage200
$redirectPath = ($_SESSION['urol'] == 'Admin') ? '../sage200/index.php' : '../index.php';

echo "<script>window.location.replace('$redirectPath');</script>";
?>
