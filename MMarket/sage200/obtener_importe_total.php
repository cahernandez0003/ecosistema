<?php
require '../config/app.php';
include '../config/bd.php';

// Verifica si se ha enviado el código de cliente por POST
if (isset($_POST['codigoCliente'])) {
    $codigoCliente = $_POST['codigoCliente'];

    try {
        // Realiza la consulta para obtener la suma total de importes para el cliente
        $sql = "SELECT SUM(ImporteEfecto) as TotalImporte FROM Vis_TES_EfectosCobroNoBorrados WHERE CodigoClienteProveedor = :codigoCliente";
        $stm = $con->prepare($sql);
        $stm->bindParam(':codigoCliente', $codigoCliente);
        $stm->execute();

        // Obtiene el resultado de la consulta
        $resultado = $stm->fetch(PDO::FETCH_ASSOC);

        // Devuelve el importe total como respuesta
        echo $resultado['TotalImporte'];
    } catch (PDOException $e) {
        // Maneja cualquier error que pueda ocurrir durante la consulta
        echo "Error al obtener el importe total: " . $e->getMessage();
    } finally {
        // Cierra la conexión a la base de datos
        $con = null;
    }
} else {
    // Devuelve un mensaje de error si no se proporcionó el código de cliente
    echo "Error: No se proporcionó el código de cliente.";
}
?>
