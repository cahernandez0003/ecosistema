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
        $sql = "SELECT a.CodigoArticulo, a.DescripcionArticulo, a.CodigoAlternativo, a.CodigoAlternativo2, a.GrupoIva, a.PrecioVenta,a.PrecioCompra,MIN(b.FechaCaducidad) AS FechaCaducidadMinima,SUM(b.UnidadSaldo) AS TotalUnidadSaldo FROM  Articulos a LEFT JOIN Vis_Stock b ON b.CodigoArticulo = a.CodigoArticulo AND b.CodigoEmpresa = a.CodigoEmpresa WHERE a.CodigoEmpresa = 1 and b.CodigoAlmacen=1 AND a.DescripcionArticulo NOT LIKE '%baja%' AND a.DescripcionArticulo NOT LIKE '%ARTICU%' AND a.DescripcionArticulo NOT LIKE '%PALET%' and b.Ejercicio=2024 GROUP BY a.CodigoArticulo, a.DescripcionArticulo, a.CodigoAlternativo, a.CodigoAlternativo2, a.GrupoIva, a.PrecioCompra, a.PrecioVenta;";

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
                case 6:
                    $fila['PrecioVenta'] = number_format($precioVentaNumerico * 1.02, 2, ',', '');
                    break;
                case 7:
                    $fila['PrecioVenta'] = number_format($precioVentaNumerico * 1.075, 2, ',', '');
                    break;
                // No hacer nada si GrupoIva es 4
                default:
                    break;
            }

            // URL de la imagen
            $codigoArticulo = $fila['CodigoArticulo'];
            $fila['ImagenURL'] = "public/imgs/img_art/{$codigoArticulo}/{$codigoArticulo}.jpg"; // Ajusta la ruta según tu estructura

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
//             $fila['ImagenURL'] = "public/imgs/img_art/{$codigoArticulo}/{$codigoArticulo}.jpg"; // Ajusta la ruta según tu estructura

//             // Formatear 'TotalUnidadSaldo' a tres decimales
//             $fila['TotalUnidadSaldo'] = number_format($fila['TotalUnidadSaldo'], 3, '.', '');
//         }

//         return $resultados;
//     } catch (PDOException $e) {
//         echo $e->getMessage();
//     }
// }


// bd.php

function listararticulosadmins($con, $codigoFamilia = null)
{
    try {
        $sql="SELECT   b.CodigoAlmacen,     
            a.CodigoArticulo,
            a.DescripcionArticulo,
            a.CodigoAlternativo,
            a.CodigoAlternativo2,
            a.GrupoIva,
            a.PrecioVenta,
            a.PrecioCompra,
            a.CodigoFamilia,
            cast(b.FechaCaducidad as date) as FechaCaducidad,
            b.Partida,
            b.UnidadSaldo
        FROM
            Articulos a
        LEFT JOIN
            Vis_Stock b ON b.CodigoArticulo = a.CodigoArticulo AND b.CodigoEmpresa = a.CodigoEmpresa AND b.Ejercicio = 2024
        WHERE
            a.CodigoEmpresa = 1
            AND a.DescripcionArticulo NOT LIKE '%ARTICU%'
            AND a.DescripcionArticulo NOT LIKE '%PALET%'
            AND b.UnidadSaldo >0
        GROUP BY
        b.CodigoAlmacen,
            a.CodigoArticulo,
            a.CodigoFamilia,
            a.DescripcionArticulo,
            a.CodigoAlternativo,
            a.CodigoAlternativo2,
            a.GrupoIva,
            a.PrecioCompra,
            a.PrecioVenta,
            b.FechaCaducidad,
            b.Partida,
            b.unidadsaldo";
        // $sql = "
        //SELECT
        //     a.CodigoArticulo,
        //     a.DescripcionArticulo,
        //     a.CodigoAlternativo,
        //     a.CodigoAlternativo2,
        //     a.GrupoIva,
        //     a.PrecioVenta,
        //     a.PrecioCompra,
        //     a.CodigoFamilia,
        //     MIN(b.FechaCaducidad) AS FechaCaducidadMinima,
        //     COALESCE(SUM(b.UnidadSaldo), 0) AS TotalUnidadSaldo
        // FROM
        //     Articulos a
        // LEFT JOIN
        //     Vis_Stock b ON b.CodigoArticulo = a.CodigoArticulo AND b.CodigoEmpresa = a.CodigoEmpresa AND b.Ejercicio = 2024
        // WHERE
        //     a.CodigoEmpresa = 1
        //     AND a.DescripcionArticulo NOT LIKE '%ARTICU%'
        //     AND a.DescripcionArticulo NOT LIKE '%PALET%'
        //     " . ($codigoFamilia ? "AND a.CodigoFamilia = :codigoFamilia" : "") . "
        // GROUP BY
        //     a.CodigoArticulo,
        //     a.CodigoFamilia,
        //     a.DescripcionArticulo,
        //     a.CodigoAlternativo,
        //     a.CodigoAlternativo2,
        //     a.GrupoIva,
        //     a.PrecioCompra,
        //     a.PrecioVenta
        // ";

        $stm = $con->prepare($sql);

        if ($codigoFamilia) {
            $stm->bindParam(":codigoFamilia", $codigoFamilia);
        }

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
                case 6:
                    $fila['PrecioVenta'] = number_format($precioVentaNumerico * 1.02, 2, ',', '');
                    break;
                case 7:
                    $fila['PrecioVenta'] = number_format($precioVentaNumerico * 1.075, 2, ',', '');
                    break;
                // No hacer nada si GrupoIva es 4
                default:
                    break;
            }

             // URL de la imagen
             $codigoArticulo = $fila['CodigoArticulo'];
             $fila['ImagenURL'] = "public/imgs/img_art/{$codigoArticulo}/{$codigoArticulo}.jpg"; 


// Ajusta la ruta según tu estructura

//             Formatear 'TotalUnidadSaldo' a tres decimales
             $fila['UnidadSaldo'] = number_format($fila['UnidadSaldo'], 3, '.', '');
         }

         return $resultados;
     } catch (PDOException $e) {
         echo $e->getMessage();
     }
 }





function listacatalogo($con)
{
    try {
        $sql="SELECT   b.CodigoAlmacen,     
            a.CodigoArticulo,
            a.DescripcionArticulo,
            a.GrupoIva,
            a.CodigoFamilia
        FROM
            Articulos a
        LEFT JOIN
            Vis_Stock b ON b.CodigoArticulo = a.CodigoArticulo AND b.CodigoEmpresa = a.CodigoEmpresa AND b.Ejercicio = 2024
        WHERE
            a.CodigoEmpresa = 1
            AND b.CodigoAlmacen=1
            AND a.DescripcionArticulo NOT LIKE '%ARTICU%'
            AND a.DescripcionArticulo NOT LIKE '%PALET%'
            AND b.UnidadSaldo >0
        GROUP BY
        b.CodigoAlmacen,
            a.CodigoArticulo,
            a.CodigoFamilia,
            a.DescripcionArticulo,
            a.GrupoIva";
     

        $stm = $con->prepare($sql);

                $stm->execute();

        $resultados = $stm->fetchAll();

       // Formatear PrecioVenta y ajustar según GrupoIva
         foreach ($resultados as &$fila) {
          

             // URL de la imagen
             $codigoArticulo = $fila['CodigoArticulo'];
             $fila['ImagenURL'] = "public/imgs/img_art/{$codigoArticulo}/{$codigoArticulo}.jpg"; 


         }

         return $resultados;
     } catch (PDOException $e) {
         echo $e->getMessage();
     }
 }









// Obtener la lista de artículos por familia
function listararticulosadminsPorFamilia($con, $codigoFamilia) {
    try {
        // Tu consulta para obtener artículos de una familia específica
        // Puedes adaptarla según la estructura de tu base de datos
        $sql = "SELECT * FROM Articulos WHERE CodigoFamilia = :codigoFamilia";
        $stm = $con->prepare($sql);
        $stm->bindParam(":codigoFamilia", $codigoFamilia);
        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        throw new Exception("Error al obtener la lista de artículos por familia: " . $e->getMessage());
    }
}


function obtenerFamilias($con) {
    try {
        $sql = "SELECT CodigoFamilia, 
                       CASE 
                           WHEN CodigoFamilia = 'CN' THEN 'CONSERVAS EN LATA'
                           WHEN CodigoFamilia = 'LE' THEN 'LEGUMBRES'
                           ELSE MIN(Descripcion)
                       END AS Descripcion
                FROM Familias
                GROUP BY CodigoFamilia
                ORDER BY Descripcion ASC";

        $stm = $con->prepare($sql);
        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        throw new Exception("Error al obtener la lista de familias: " . $e->getMessage());
    }
}



// mostrar deuda clientes
function listardeuda($con) {
    try {
        $sql = "SELECT a.CodigoEmpresa,
a.Ejercicio,
a.Prevision,
a.StatusBorrado,
a.FechaFactura,
a.SerieFactura,
a.Factura,
a.CodigoClienteProveedor,
n.RazonSocial,
a.ImporteEfecto,
r.Ruta_,
b.MunicipioEnvios
FROM CarteraEfectos a 
LEFT JOIN 
Clientes n on n.CodigoCliente=a.CodigoClienteProveedor and n.CodigoEmpresa=a.CodigoEmpresa
LEFT JOIN 
Rutas_ r on r.CodigoRuta_=n.CodigoRuta_ and r.CodigoEmpresa=a.CodigoEmpresa
LEFT OUTER JOIN
Vis_MM_MinMunEnvFra b ON a.CodigoEmpresa = b.CodigoEmpresa AND a.Ejercicio = b.EjercicioFactura AND 
a.SerieFactura = b.SerieFactura AND a.Factura = b.NumeroFactura
WHERE
a.Prevision = 'C' AND a.StatusBorrado = 0 AND a.CodigoEmpresa = 1 AND a.CodigoClienteProveedor like '4300%'";
        $stm = $con->prepare($sql);
        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        throw new Exception("Error al obtener la lista de clientes: " . $e->getMessage());
    } finally {
        $stm = null; // Liberar recursos
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
        [MMARKET].[dbo].[resumencliente] x 
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

function list_abonos($con) {
    try {
        $sql = "SELECT 
    CAST(a.FechaFactura AS DATE) AS FechaFactura, 
    a.SerieFactura, 
    a.Factura, 
    a.CodigoClienteProveedor, 
    c.RazonSocial, 
    a.ImporteEfecto,
    a.FechaCobroEfecto_,
    CASE 
        WHEN a.StatusBorrado = 0 THEN 'SIN CRUZAR'
        WHEN a.StatusBorrado = -1 THEN 'CRUZADO'
    END AS Estado
FROM 
    CarteraEfectos a 
LEFT JOIN 
    clientes c ON c.CodigoEmpresa = a.CodigoEmpresa AND c.CodigoCliente = a.CodigoClienteProveedor
WHERE 
    a.Ejercicio = 2024 
    AND a.CodigoEmpresa = 1 
    AND a.ImporteEfecto < 0
    AND CodigoClienteProveedor LIKE '430%'
ORDER BY 
    a.FechaFactura ASC";
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
        $sql = "SELECT * FROM LineasAlbaranCliente WHERE CodigoEmpresa = 1 AND SerieAlbaran = :SerieAlbaran AND NumeroAlbaran = :NumeroAlbaran order by CodigoArticulo ASC";
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


// function obtenerLineasPedidoProveedor($con, $SeriePedido, $NumeroPedido) {
//     try {
//         $sql = "SELECT 
//     lp.CodigoArticulo, 
//     lp.DescripcionArticulo, 
//     lp.UnidadesPedidas,
//     lp.Precio,
//     lp.[%Iva],
//     s.UnidadSaldo
// FROM 
//     LineasPedidoProveedor lp
// LEFT JOIN 
//     Vis_Stock s ON s.codigoempresa = lp.CodigoEmpresa AND s.CodigoArticulo = lp.CodigoArticulo
// WHERE 
//     lp.CodigoEmpresa = 1 AND SeriePedido = :SeriePedido AND NumeroPedido = :NumeroPedido";
//         $stm = $con->prepare($sql);
//         $stm->bindparam(":SeriePedido", $SeriePedido);
//         $stm->bindparam(":NumeroPedido", $NumeroPedido);
//         $stm->execute();

//         return $stm->fetchAll();
//     } catch (PDOException $e) {
//         echo $e->getMessage();
//     } 
// }

// function obtenerLineasPedidoProveedor($con, $SeriePedido, $NumeroPedido) {
//     try {
//         $sql = "SELECT 
//                     lp.CodigoArticulo, 
//                     lp.DescripcionArticulo, 
//                     lp.UnidadesPedidas,
//                     lp.Precio,
//                     lp.[%Iva],
//                     s.UnidadSaldo
//                 FROM 
//                     LineasPedidoProveedor lp
//                 LEFT JOIN 
//                     Vis_Stock s ON s.codigoempresa = lp.CodigoEmpresa AND s.CodigoArticulo = lp.CodigoArticulo
//                 WHERE 
//                     lp.CodigoEmpresa = 1 
//                     AND lp.UnidadesPedidas>0
//                     AND lp.SeriePedido = :SeriePedido 
//                     AND lp.NumeroPedido = :NumeroPedido";
//         $stm = $con->prepare($sql);
//         $stm->bindparam(":SeriePedido", $SeriePedido);
//         $stm->bindparam(":NumeroPedido", $NumeroPedido);
//         $stm->execute();

//         return $stm->fetchAll();
//     } catch (PDOException $e) {
//         echo $e->getMessage();
//     } 
// }

function obtenerLineasPedidoProveedor($con, $SeriePedido, $NumeroPedido) {
    try {
        $sql = "SELECT lp.CodigoArticulo, lp.DescripcionArticulo, lp.UnidadesPedidas, lp.Precio, lp.[%Iva],     COALESCE(SUM(s.UnidadSaldo), 0) AS TotalUnidadSaldo FROM LineasPedidoProveedor lp LEFT JOIN     Vis_Stock s ON s.codigoempresa = lp.CodigoEmpresa AND s.CodigoArticulo = lp.CodigoArticulo AND s.CodigoAlmacen = lp.CodigoAlmacen WHERE lp.CodigoEmpresa = 1 AND s.Ejercicio=2024 and s.CodigoEmpresa=1 and s.CodigoEmpresa=1 --AND lp.UnidadesPedidas > 0 
        AND lp.SeriePedido = :SeriePedido AND lp.NumeroPedido = :NumeroPedido GROUP BY lp.CodigoArticulo, lp.DescripcionArticulo, lp.UnidadesPedidas, lp.Precio,     lp.[%Iva]";
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
        $sql = "SELECT SerieAlbaran, NumeroAlbaran, CodigoCliente, RazonSocial, ImporteLiquido, FechaAlbaran, FechaCreacion FROM CabeceraAlbaranCliente WHERE SerieAlbaran = :SerieAlbaran AND NumeroAlbaran = :NumeroAlbaran";
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

function exportart($con, $codigoFamilia = null)
{
    try {
        $sql = "SELECT
                    a.CodigoArticulo,
                    a.DescripcionArticulo,
                    a.GrupoIva,
                    a.CodigoFamilia,
                    COALESCE(d.Descripcion, 'Sin descripción disponible') AS DescripcionFamilia
                FROM
                    Articulos a
                LEFT JOIN
                    Vis_Stock b ON b.CodigoArticulo = a.CodigoArticulo 
                                 AND b.CodigoEmpresa = a.CodigoEmpresa 
                                 AND b.Ejercicio = 2024
                LEFT JOIN
                    (SELECT CodigoFamilia, MIN(Descripcion) AS Descripcion FROM Familias GROUP BY CodigoFamilia) d
                    ON d.CodigoFamilia = a.CodigoFamilia
                WHERE
                    a.CodigoEmpresa = 1";
        
        if ($codigoFamilia) {
            $sql .= " AND a.CodigoFamilia = :codigoFamilia";
        }

        $stm = $con->prepare($sql);

        if ($codigoFamilia) {
            $stm->bindParam(":codigoFamilia", $codigoFamilia);
        }

        $stm->execute();

        $resultados = $stm->fetchAll();

        foreach ($resultados as &$fila) {
            $codigoArticulo = $fila['CodigoArticulo'];
            $fila['ImagenURL'] = "public/imgs/img_art/{$codigoArticulo}/{$codigoArticulo}.jpg";
        }

        return $resultados;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}


function exportart2($con, $codigoFamilia = null)
{
    try {
        $sql = "WITH DescripcionesOrdenadas AS (
            SELECT
                CodigoEmpresa,
                CodigoFamilia,
                Descripcion,
                ROW_NUMBER() OVER (PARTITION BY CodigoFamilia ORDER BY Descripcion) AS Orden
            FROM
                Familias)
            SELECT
                a.CodigoArticulo,
                a.DescripcionArticulo,
                a.GrupoIva,
                a.CodigoFamilia,
                COALESCE(d.Descripcion, 'Sin descripción disponible') AS DescripcionFamilia
            FROM
                Articulos a
            LEFT JOIN
                Vis_Stock b ON b.CodigoArticulo = a.CodigoArticulo 
                             AND b.CodigoEmpresa = a.CodigoEmpresa 
                             AND b.Ejercicio = 2024
            LEFT JOIN
                DescripcionesOrdenadas d ON d.CodigoEmpresa = a.CodigoEmpresa 
                                          AND d.CodigoFamilia = a.CodigoFamilia 
                                          AND d.Orden = 1
            WHERE
                a.CodigoEmpresa = 1";

        $stm = $con->prepare($sql);

        if ($codigoFamilia) {
            $stm->bindParam(":codigoFamilia", $codigoFamilia);
        }

        $stm->execute();

        $resultados = $stm->fetchAll();

        // URL de la imagen
        foreach ($resultados as &$fila) {
            $codigoArticulo = $fila['CodigoArticulo'];
            $fila['ImagenURL'] = "public/imgs/img_art/{$codigoArticulo}/{$codigoArticulo}.jpg";
        }

        return $resultados;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}



