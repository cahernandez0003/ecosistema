<?php
if (isset($_SESSION['urol'])) {
    if ($_SESSION['urol'] == 'Admin') {
        header("Location: sage200/index.php");
        exit(); // Asegura que el script se detenga después de la redirección
    } else {
        $errorMessage = "Usuario o contraseña incorrectos/usuario no existe";
    }
} else {
    $errorMessage = "Usuario o contraseña incorrectos/usuario no existe";
}

if (isset($errorMessage)) {
    echo $errorMessage;
}
?>
