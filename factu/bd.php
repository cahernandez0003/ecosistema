<?php
// Variables (Rutas Relativas/Absolutas)
$url_site   = 'http://192.168.1.5:8080/factu/';
$url_public = $url_site.'public/';
$url_css    = $url_public.'css/';
$url_js     = $url_public.'js/';
$url_imgs   = $url_public.'imgs/';

// Configuración BD SQL Server
$hostSqlServer = 'SERVIDOR\SAGE200';
$userSqlServer = 'consultasMM';
$passSqlServer = 'Sage2009+';
$nmdbSqlServer = 'MMARKET';

// Conexión a la base de datos
try {
    $connection = new PDO("sqlsrv:server=$hostSqlServer;Database=$nmdbSqlServer", $userSqlServer, $passSqlServer);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error connecting to SQL Server: " . $e->getMessage());
}
?>
