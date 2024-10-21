<?php
// security_admin.php

// Sesiones
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verifica si la sesión está activa
if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    // La sesión no está activa, puedes mostrar un mensaje o realizar alguna acción adicional
    echo '<script>alert("La sesión ha finalizado. Por favor, inicia sesión de nuevo.");</script>';
    // También podrías redirigir al inicio u otra página si es necesario
    echo '<script>window.location.replace("../");</script>';
    exit();
}

// Verifica si el usuario tiene el rol de administrador
if (!isset($_SESSION['urol']) || $_SESSION['urol'] !== 'Admin') {
    // El usuario no es un administrador, puedes mostrar un mensaje o realizar alguna acción adicional
    echo '<script>alert("Acceso denegado. Este contenido solo está disponible para administradores.");</script>';
    // También podrías redirigir al inicio u otra página si es necesario
    echo '<script>window.location.replace("../");</script>';
    exit();
}
?>
