<?php

// Incluir las configuraciones de base de datos u otros archivos necesarios
require '../config/app.php';
include '../config/bd.php';

// Obtener el término de búsqueda desde la solicitud POST
$term = $_POST['term'];

// Lógica para obtener el total de la deuda para el término de búsqueda
$totalDeudaBusqueda = obtenerTotalDeudaBusqueda($con, $term);

// Devolver el resultado como respuesta AJAX
echo $totalDeudaBusqueda;

// Función para obtener el total de la deuda para el término de búsqueda
function obtenerTotalDeudaBusqueda($con, $term) {
    try {
        // Lógica de consulta para obtener el total de la deuda para el término de búsqueda
        $sql = "SELECT SUM(c.ImporteEfecto) FROM Vis_TES_EfectosCobroNoBorrados c WHERE ISNUMERIC(c.ImporteEfecto) != '' AND (c.CodigoClienteProveedor LIKE :term OR c.RazonSocial LIKE :term)";
        $stm = $con->prepare($sql);
        $stm->bindValue(":term", "%$term%");
        $stm->execute();
        $totalDeuda = $stm->fetchColumn();

        return number_format($totalDeuda, 2, '.', ''); // Formatear el resultado a dos decimales
    } catch (PDOException $e) {
        throw new Exception("Error al obtener el total de la deuda para la búsqueda: " . $e->getMessage());
    }
}
?>
