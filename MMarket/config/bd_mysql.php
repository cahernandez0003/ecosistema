<?php
// bd_mysql.php

// Sesiones
if (!isset($_SESSION)) {
    session_start();
}

// Verifica la inactividad solo si no está en la página index.php
if (!strpos($_SERVER['REQUEST_URI'], 'index.php') && isset($_SESSION['last_activity'])) {
    $inactive_time = 1440 * 60; // 25 minutos en segundos
    $elapsed_time = time() - $_SESSION['last_activity'];

    if ($elapsed_time > $inactive_time) {
        // La sesión ha expirado, cierra la sesión y redirige al usuario al cierre de sesión
        session_unset();
        session_destroy();
        header('Location: ../pages/close.php?session_expired=true');
        exit;
    }
}

// Actualiza la variable de última actividad en cada página a la que el usuario tenga acceso
$_SESSION['last_activity'] = time();

// Resto del código de inicio de sesión y conexión a la base de datos
$host_mysql = 'localhost';
$user_mysql = 'consultasMM';
$pass_mysql = 'Sage2009+';
$nmdb_mysql = 'cuvinor22';

try {
    $con_mysql = new PDO("mysql:host=$host_mysql;dbname=$nmdb_mysql", $user_mysql, $pass_mysql);
    $con_mysql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Error de conexión a MySQL: " . $e->getMessage();
    die();
}

function loginmm($con_mysql, $username, $password) {
    try {
        $sql = "SELECT * FROM admins WHERE username = :username AND password = :password";
        $stm = $con_mysql->prepare($sql);
        $stm->bindparam(':username', $username);
        $stm->bindparam(':password', $password);
    
        $stm->execute();
        if ($stm->rowCount() > 0) {
            $urow = $stm->fetch(PDO::FETCH_ASSOC);
            $_SESSION['username'] = $urow['username'];
            // $_SESSION['ufoto'] = $urow['foto'];
            $_SESSION['urol'] = $urow['rol'];
            return true;
        } else {
            return false;
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

?>


