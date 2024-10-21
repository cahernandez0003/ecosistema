<?php

// CONECTAR A BASE DE DATOS

try {
    $con = new PDO("sqlsrv:server=$hostSqlServer;Database=$nmdbSqlServer", $userSqlServer, $passSqlServer);

    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo "Conexión a la base de datos exitosa";
} catch (PDOException $e) {
    echo $e->getMessage();
}


// LISTAR LOS ARTÍCULOS

function listararticulos($con)
{
    try {
        $sql = "SELECT a.CodigoArticulo, a.DescripcionArticulo, a.CodigoAlternativo, a.CodigoAlternativo2, a.GrupoIva, a.PrecioVenta,a.PrecioCompra,MIN(b.FechaCaducidad) AS FechaCaducidadMinima,SUM(b.UnidadSaldo) AS TotalUnidadSaldo FROM  Articulos a LEFT JOIN Vis_Stock b ON b.CodigoArticulo = a.CodigoArticulo AND b.CodigoEmpresa = a.CodigoEmpresa WHERE a.CodigoEmpresa = 1 AND a.DescripcionArticulo NOT LIKE '%baja%' AND a.DescripcionArticulo NOT LIKE '%ARTICU%' AND a.DescripcionArticulo NOT LIKE '%PALET%' and b.Ejercicio=2024 GROUP BY a.CodigoArticulo, a.DescripcionArticulo, a.CodigoAlternativo, a.CodigoAlternativo2, a.GrupoIva, a.PrecioCompra, a.PrecioVenta;";

        $stm = $con->prepare($sql);
        $stm->execute();

        $resultados = $stm->fetchAll();

        // Formatear PrecioVenta y ajustar según GrupoIva
        foreach ($resultados as &$fila) {
            // Convertir la cadena formateada a un número flotante
            $precioVentaNumerico = floatval(str_replace(',', '.', str_replace(' €', '', $fila['PrecioVenta'])));
            $fila['PrecioVentaOriginal'] = $precioVentaNumerico;

            switch ($fila['GrupoIva']) {
                case 1:
                    $fila['PrecioVenta'] = number_format($precioVentaNumerico * 1.21, 2, ',', '');
                    break;
                case 5:
                    $fila['PrecioVenta'] = number_format($precioVentaNumerico * 1.05, 2, ',', '');
                    break;
                case 2:
                    $fila['PrecioVenta'] = number_format($precioVentaNumerico * 1.10, 2, ',', '');
                    break;
                case 4:
                    $fila['PrecioVenta'] = number_format($precioVentaNumerico, 2, ',', '');
                    break;
                // No hacer nada si GrupoIva es 4
                default:
                    break;
            }

            // URL de la imagen
            $codigoArticulo = $fila['CodigoArticulo'];
            $fila['ImagenURL'] = "public/imgs/img_art/{$codigoArticulo}.jpg"; // Ajusta la ruta según tu estructura

            // Formatear 'TotalUnidadSaldo' a tres decimales
            $fila['TotalUnidadSaldo'] = number_format($fila['TotalUnidadSaldo'], 3, '.', '');
        }

        return $resultados;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}



// mostrar a admins

// function listararticulosadmins($con)
// {
//     try {
//         $sql = "SELECT a.CodigoArticulo, a.DescripcionArticulo, a.CodigoAlternativo, a.CodigoAlternativo2, a.GrupoIva, a.PrecioVenta,a.PrecioCompra,MIN(b.FechaCaducidad) AS FechaCaducidadMinima,SUM(b.UnidadSaldo) AS TotalUnidadSaldo FROM  Articulos a LEFT JOIN Vis_Stock b ON b.CodigoArticulo = a.CodigoArticulo AND b.CodigoEmpresa = a.CodigoEmpresa WHERE a.CodigoEmpresa = 1 AND a.DescripcionArticulo NOT LIKE '%ARTICU%' AND a.DescripcionArticulo NOT LIKE '%PALET%' GROUP BY a.CodigoArticulo, a.DescripcionArticulo, a.CodigoAlternativo, a.CodigoAlternativo2, a.GrupoIva, a.PrecioCompra, a.PrecioVenta HAVING SUM(b.UnidadSaldo) > 0;";

//         $stm = $con->prepare($sql);
//         $stm->execute();

//         $resultados = $stm->fetchAll();

//         // Formatear PrecioVenta y ajustar según GrupoIva
//         foreach ($resultados as &$fila) {
//             // Convertir la cadena formateada a un número flotante
//             $precioVentaNumerico = floatval(str_replace(',', '.', str_replace(' €', '', $fila['PrecioVenta'])));

//             switch ($fila['GrupoIva']) {
//                 case 1:
//                     $fila['PrecioVenta'] = number_format($precioVentaNumerico * 1.21, 2, ',', '');
//                     break;
//                 case 5:
//                     $fila['PrecioVenta'] = number_format($precioVentaNumerico * 1.05, 2, ',', '');
//                     break;
//                 case 2:
//                     $fila['PrecioVenta'] = number_format($precioVentaNumerico * 1.10, 2, ',', '');
//                     break;
//                 case 4:
//                     $fila['PrecioVenta'] = number_format($precioVentaNumerico, 2, ',', '');
//                     break;
//                 // No hacer nada si GrupoIva es 4
//                 default:
//                     break;
//             }

//             // URL de la imagen
//             $codigoArticulo = $fila['CodigoArticulo'];
//             $fila['ImagenURL'] = "public/imgs/img_art/{$codigoArticulo}.jpg"; // Ajusta la ruta según tu estructura

//             // Formatear 'TotalUnidadSaldo' a tres decimales
//             $fila['TotalUnidadSaldo'] = number_format($fila['TotalUnidadSaldo'], 3, '.', '');
//         }

//         return $resultados;
//     } catch (PDOException $e) {
//         echo $e->getMessage();
//     }
// }


function listararticulosadmins($con)
{
    try {
        $sql = "SELECT
    a.CodigoArticulo,
    a.DescripcionArticulo,
    a.CodigoAlternativo,
    a.CodigoAlternativo2,
    a.GrupoIva,
    a.PrecioVenta,
    a.PrecioCompra,
    MIN(b.FechaCaducidad) AS FechaCaducidadMinima,
    COALESCE(SUM(b.UnidadSaldo), 0) AS TotalUnidadSaldo
FROM
    Articulos a
LEFT JOIN
    Vis_Stock b ON b.CodigoArticulo = a.CodigoArticulo AND b.CodigoEmpresa = a.CodigoEmpresa AND b.Ejercicio = 2024
WHERE
    a.CodigoEmpresa = 1
    AND a.DescripcionArticulo NOT LIKE '%ARTICU%'
    AND a.DescripcionArticulo NOT LIKE '%PALET%'
GROUP BY
    a.CodigoArticulo,
    a.DescripcionArticulo,
    a.CodigoAlternativo,
    a.CodigoAlternativo2,
    a.GrupoIva,
    a.PrecioCompra,
    a.PrecioVenta;
";

        $stm = $con->prepare($sql);
        $stm->execute();

        $resultados = $stm->fetchAll();

        // Formatear PrecioVenta y ajustar según GrupoIva
        foreach ($resultados as &$fila) {
            // Convertir la cadena formateada a un número flotante
            $precioVentaNumerico = floatval(str_replace(',', '.', str_replace(' €', '', $fila['PrecioVenta'])));

            // Agregar el campo con el valor original de PrecioVenta
            $fila['PrecioVentaOriginal'] = $precioVentaNumerico;

            switch ($fila['GrupoIva']) {
                case 1:
                    $fila['PrecioVenta'] = number_format($precioVentaNumerico * 1.21, 2, ',', '');
                    break;
                case 5:
                    $fila['PrecioVenta'] = number_format($precioVentaNumerico * 1.05, 2, ',', '');
                    break;
                case 2:
                    $fila['PrecioVenta'] = number_format($precioVentaNumerico * 1.10, 2, ',', '');
                    break;
                case 4:
                    $fila['PrecioVenta'] = number_format($precioVentaNumerico, 2, ',', '');
                    break;
                    break;
                default:
                    // No hace nada para otros valores de GrupoIva
                    break;
            }

            // URL de la imagen
            $codigoArticulo = $fila['CodigoArticulo'];
            $fila['ImagenURL'] = "public/imgs/img_art/{$codigoArticulo}.jpg"; // Ajusta la ruta según tu estructura

            // Formatear 'TotalUnidadSaldo' a tres decimales
            $fila['TotalUnidadSaldo'] = number_format($fila['TotalUnidadSaldo'], 3, '.', '');
        }

        return $resultados;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}


// mostrar deuda clientes
function listarclientes($con) {
    try {
        $sql = "WITH CTE AS (
    SELECT
        a.CodigoCliente, 
        a.RazonSocial, 
        a.CifDni, 
        c.FechaEmision, 
        c.COMENTARIO, 
        c.SerieDocumento, 
        c.NumeroDocumento,
        r.Ruta_,
        x.MunicipioEnvios,
        c.ImporteEfecto,
        SUM(c.ImporteEfecto) OVER (PARTITION BY a.CodigoCliente) as DeudaTotal,
        ROW_NUMBER() OVER (PARTITION BY c.SerieDocumento, c.NumeroDocumento ORDER BY c.FechaEmision) as RowNum
    FROM 
        clientes a 
    LEFT JOIN 
        Vis_TES_EfectosCobroNoBorrados c 
    ON 
        a.CodigoCliente = c.CodigoClienteProveedor
    LEFT JOIN 
        [MMARKET].[dbo].[Rutas_] r 
    ON 
        r.CodigoRuta_ = a.CodigoRuta_
    LEFT JOIN 
        [MMARKET].[dbo].[CabeceraAlbaranCliente] x 
    ON 
        CONCAT(x.SerieFactura, x.NumeroFactura) = CONCAT(c.SerieDocumento, c.NumeroDocumento)
    WHERE 
        a.CodigoCliente LIKE '430%' 
        AND ISNUMERIC(c.ImporteEfecto) != '' 
)
SELECT *
FROM CTE
WHERE RowNum = 1
ORDER BY ImporteEfecto DESC;";
        $stm = $con->prepare($sql);
        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        throw new Exception("Error al obtener la lista de clientes: " . $e->getMessage());
    } finally {
        $stm = null; // Liberar recursos
    }
}

function ocultarCifDni($cifDni) {
    // Verifica si es una búsqueda y devuelve el valor sin cambios
    if (isset($_GET['search']) && !empty($_GET['search'])) {
        return $cifDni;
    }

    // Aplica la ocultación
    return substr($cifDni, 0, 2) . str_repeat('*', strlen($cifDni) - 3) . substr($cifDni, -1);
}

function obtenerTotalDeuda($con) {
    try {
        $sql = "SELECT SUM(c.ImporteEfecto) FROM Vis_TES_EfectosCobroNoBorrados c WHERE ISNUMERIC(c.ImporteEfecto) != ''";
        $stm = $con->prepare($sql);
        $stm->execute();
        $totalDeuda = $stm->fetchColumn();
        return $totalDeuda;
    } catch (PDOException $e) {
        throw new Exception("Error al obtener el total general de la deuda: " . $e->getMessage());
    }
}

function list_albaranes($con) {
    try {
        $sql = "SELECT * FROM CabeceraAlbaranCliente WHERE CodigoEmpresa = 1 ORDER BY FechaAlbaran ASC";
        $stm = $con->prepare($sql);
        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        throw new Exception("Error al obtener los albaranes: " . $e->getMessage());
    }
}

function list_albcom($con) {
    try {
        $sql = "SELECT * FROM CabeceraAlbaranProveedor WHERE CodigoEmpresa = 1 ORDER BY FechaAlbaran ASC";
        $stm = $con->prepare($sql);
        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        throw new Exception("Error al obtener los albaranes: " . $e->getMessage());
    }
}


function list_pedicom($con) {
    try {
        $sql = "SELECT * FROM CabeceraPedidoProveedor WHERE CodigoEmpresa = 1 ORDER BY FechaPedido ASC";
        $stm = $con->prepare($sql);
        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        throw new Exception("Error al obtener los pedidos: " . $e->getMessage());
    }
}

// function obtenerLineasAlbaranCliente($Seriealbaran, $NumeroAlbaran) {
//     global $con; // Asegúrate de tener la conexión disponible

//     $query = "SELECT fechaalbaran, seriealbaran, NumeroAlbaran, codigoarticulo, descripcionarticulo, Partida, Unidades, Importecoste, PrecioCoste, BaseImponible, margenbeneficio, PorMargenBeneficio, [%Descuento]
//              FROM MMARKET.dbo.LineasAlbaranCliente
//              WHERE CodigoEmpresa = 1 AND SerieAlbaran = :SerieAlbaran AND NumeroAlbaran = :NumeroAlbaran
//              ORDER BY fechaalbaran DESC";

//     $stmt = $con->prepare($query);
//     $stmt->bindParam(':SerieAlbaran', $SerieAlbaran);
//     $stmt->bindParam(':NumeroAlbaran', $NumeroAlbaran);
//     $stmt->execute();

//     return $stmt->fetchAll(PDO::FETCH_ASSOC);
// }

function obtenerLineasAlbaranCliente($con, $SerieAlbaran, $NumeroAlbaran) {
    try {
        $sql = "SELECT * FROM LineasAlbaranCliente WHERE CodigoEmpresa = 1 AND SerieAlbaran = :SerieAlbaran AND NumeroAlbaran = :NumeroAlbaran";
        $stm = $con->prepare($sql);
        $stm->bindparam(":SerieAlbaran", $SerieAlbaran);
        $stm->bindparam(":NumeroAlbaran", $NumeroAlbaran);
        $stm->execute();

        return $stm->fetchAll();
    } catch (PDOException $e) {
        echo $e->getMessage();
    } 
}


function obtenerLineasAlbaranProveedor($con, $SerieAlbaran, $NumeroAlbaran) {
    try {
        $sql = "SELECT * FROM LineasAlbaranProveedor WHERE CodigoEmpresa = 1 AND SerieAlbaran = :SerieAlbaran AND NumeroAlbaran = :NumeroAlbaran";
        $stm = $con->prepare($sql);
        $stm->bindparam(":SerieAlbaran", $SerieAlbaran);
        $stm->bindparam(":NumeroAlbaran", $NumeroAlbaran);
        $stm->execute();

        return $stm->fetchAll();
    } catch (PDOException $e) {
        echo $e->getMessage();
    } 
}


function obtenerLineasPedidoProveedor($con, $SeriePedido, $NumeroPedido) {
    try {
        $sql = "SELECT * FROM LineasPedidoProveedor WHERE CodigoEmpresa = 1 AND SeriePedido = :SeriePedido AND NumeroPedido = :NumeroPedido";
        $stm = $con->prepare($sql);
        $stm->bindparam(":SeriePedido", $SeriePedido);
        $stm->bindparam(":NumeroPedido", $NumeroPedido);
        $stm->execute();

        return $stm->fetchAll();
    } catch (PDOException $e) {
        echo $e->getMessage();
    } 
}


function obtenerInformacionCliente($con, $SerieAlbaran, $NumeroAlbaran) {
    try {
        $sql = "SELECT SerieAlbaran, NumeroAlbaran, CodigoCliente, RazonSocial, ImporteLiquido, FechaAlbaran FROM CabeceraAlbaranCliente WHERE SerieAlbaran = :SerieAlbaran AND NumeroAlbaran = :NumeroAlbaran";
        $stm = $con->prepare($sql);
        $stm->bindParam(":SerieAlbaran", $SerieAlbaran);
        $stm->bindParam(":NumeroAlbaran", $NumeroAlbaran);
        $stm->execute();

        return $stm->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}


function obtenerInformacionProveedor($con, $SerieAlbaran, $NumeroAlbaran) {
    try {
        $sql = "SELECT SerieAlbaran, NumeroAlbaran, CodigoProveedor, RazonSocial, ImporteLiquido, FechaAlbaran FROM CabeceraAlbaranProveedor WHERE SerieAlbaran = :SerieAlbaran AND NumeroAlbaran = :NumeroAlbaran";
        $stm = $con->prepare($sql);
        $stm->bindParam(":SerieAlbaran", $SerieAlbaran);
        $stm->bindParam(":NumeroAlbaran", $NumeroAlbaran);
        $stm->execute();

        return $stm->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function obtenerInformacionProveedorPed($con, $SeriePedido, $NumeroPedido) {
    try {
        $sql = "SELECT SeriePedido, NumeroPedido, CodigoProveedor, RazonSocial, ImporteLiquido, FechaPedido FROM CabeceraPedidoProveedor WHERE SeriePedido = :SeriePedido AND NumeroPedido = :NumeroPedido";
        $stm = $con->prepare($sql);
        $stm->bindParam(":SeriePedido", $SeriePedido);
        $stm->bindParam(":NumeroPedido", $NumeroPedido);
        $stm->execute();

        return $stm->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}