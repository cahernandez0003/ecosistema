<?php
include 'bd.php';

function fetchResults($query) {
    global $connection;
    $stmt = $connection->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$results = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['button1'])) {
        $query = "SELECT cast(FECHAALBARAN as date) as FechaAlbaran, SerieAlbaran, NumeroAlbaran, Orden, CodigoArticulo, DescripcionArticulo, [%Iva], CodigoIva, CodigoTransaccion, NumeroFactura 
                  FROM LineasAlbaranProveedor 
                  WHERE [%Iva]=0 AND EjercicioAlbaran=2024 AND CodigoTransaccion=1 and NumeroFactura='' and CodigoIva=2 and cast(FechaAlbaran as date)>'2024-07-01'
                  ORDER BY CodigoTransaccion asc";
        $results = fetchResults($query);
    } elseif (isset($_POST['button2'])) {
        $query = "SELECT cast(FECHAALBARAN as date) as FechaAlbaran, SerieAlbaran, NumeroAlbaran, Orden, CodigoArticulo, DescripcionArticulo, PrecioCoste, ImporteLiquido, Partida, UNIDADES, NumeroFactura 
                  FROM lineasalbarancliente
                  WHERE CodigoEmpresa=1 AND EjercicioAlbaran=2024 AND NumeroFactura='' AND ImporteCoste=0 AND cast(FechaAlbaran as date)>'2024-07-01'
                  ORDER BY FechaAlbaran desc";
        $results = fetchResults($query);
    } elseif (isset($_POST['button3'])) {
        $query = "SELECT cast(FECHAALBARAN as date) as FechaAlbaran, SerieAlbaran, NumeroAlbaran, Orden, CodigoArticulo, DescripcionArticulo, [%Descuento], Precio, PrecioCoste, ImporteLiquido, NumeroFactura 
                  FROM lineasalbarancliente
                  WHERE CodigoEmpresa=1 AND EjercicioAlbaran=2024 AND NumeroFactura='' AND ImporteLiquido=0 AND cast(FechaAlbaran as date)>'2024-07-01'
                  ORDER BY FechaAlbaran desc";
        $results = fetchResults($query);
    } elseif (isset($_POST['button4'])) {
        $query = "SELECT cast(FECHAALBARAN as date) as FechaAlbaran, SerieAlbaran, NumeroAlbaran, Orden, CodigoArticulo, DescripcionArticulo, PrecioCoste, ImporteLiquido, Partida, FechaCaduca 
                  FROM lineasalbarancliente
                  WHERE CodigoEmpresa=1 AND EjercicioAlbaran=2024 AND NumeroFactura='' AND 
                  (Partida IS NULL OR FechaCaduca IS NULL) AND 
                  CodigoArticulo NOT LIKE 'BA%' AND cast(FechaAlbaran as date)>'2024-07-01'
                  ORDER BY FechaAlbaran desc";
        $results = fetchResults($query);
    } elseif (isset($_POST['button5'])) {
        $query = "SELECT CAST(a.FECHAALBARAN AS DATE) AS Fecha, CONCAT(a.SerieAlbaran, a.NumeroAlbaran) AS ALBVEN, a.CodigoCliente AS Cod_Cliente, a.RazonSocial AS Nombre_Cliente, b.CifEuropeo, CONCAT(FORMAT(a.ImporteLiquido, 'N2'), ' €') AS Importe, b.MascaraFactura_, CASE a.CodigoTransportistaEnvios         WHEN 1 THEN 'AYADI' WHEN 2 THEN 'YOUSSEF' WHEN 3 THEN 'SAMIR' WHEN 4 THEN 'LOGIN' WHEN 5 THEN 'ALMACÉN' WHEN 6 THEN 'MIMOUN' WHEN 7 THEN 'OTROS' ELSE 'SIN ASIGNAR' END AS transportista, b.EmailEnvioEFactura, b.telefono FROM CabeceraAlbaranCliente a LEFT JOIN clientes b ON b.CodigoCliente = a.CodigoCliente AND b.CodigoEmpresa = a.CodigoEmpresa WHERE a.NumeroFactura = ''";
        $results = fetchResults($query);
    } elseif (isset($_POST['button6'])) {
        $query = "SELECT cast(l.FechaAlbaran as date) as fecha, l.SerieAlbaran as serie, l.NumeroAlbaran as numero, c.codigocliente as cliente, c.razonsocial as nombre, c.CifEuropeo, l.CodigoArticulo, l.DescripcionArticulo, CASE c.CodigoTransportistaEnvios WHEN 1 THEN 'AYADI' WHEN 2 THEN 'YOUSSEF' WHEN 3 THEN 'SAMIR' WHEN 4 THEN 'LOGIN' WHEN 5 THEN 'ALMACÉN' WHEN 6 THEN 'MIMOUN' WHEN 7 THEN 'OTROS' ELSE 'SIN ASIGNAR' END AS transportista, l.codigoarancelario as cod_aran_alb, a.CodigoArancelario as cod_aran_art FROM [MMARKET].[dbo].[LineasAlbaranCliente] l LEFT JOIN Articulos a ON a.CodigoArticulo = l.CodigoArticulo AND a.CodigoEmpresa=l.CodigoEmpresa LEFT JOIN CabeceraAlbaranCliente c ON CONCAT(c.seriealbaran, c.NumeroAlbaran) = CONCAT(l.seriealbaran, l.numeroalbaran) AND c.CodigoEmpresa=l.CodigoEmpresa WHERE l.NumeroFactura='' AND cast(l.fechaalbaran as date) > '2024-07-01' AND l.CodigoArancelario='' ORDER BY l.FechaAlbaran desc";
        $results = fetchResults($query);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>PRE-FACTURAR</title>
    <link rel="shortcut icon" href="public/imgs/favicon.ico">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

   
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        
.button, .button-strong, .button-arance {
    padding: 8px 16px; 
    font-size: 14px;   
    text-align: center;
    display: inline-block;
    border-radius: 5px; 
    box-shadow: 0 4px #999; 
    transition: all 0.3s ease; 
}


.button {
    background-color: #4CAF50;
    color: white;
    border: none;
}

.button:hover {
    background-color: #45a049;
    box-shadow: 0 6px #666;
}

.button:active {
    background-color: #3e8e41;
    box-shadow: 0 2px #666;
    transform: translateY(2px);
}


.button-strong {
    background-color: #e60000;
    color: white;
    border: none;
}

.button-strong:hover {
    background-color: #cc0000;
    box-shadow: 0 6px #666;
}

.button-strong:active {
    background-color: #b30000;
    box-shadow: 0 2px #666;
    transform: translateY(2px);
}


.button-arance {
    background-color: #FFA500; 
    color: white;
    border: none;
}

.button-arance:hover {
    background-color: #e68900; 
    box-shadow: 0 6px #666;
}

.button-arance:active {
    background-color: #cc5800; 
    box-shadow: 0 2px #666;
    transform: translateY(2px);
}

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        caption {
            caption-side: top;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .highlight-red {
            background-color: #ffcccc;
        }
        .highlight-orange {
            background-color: #ffecb3;
        }
        .highlight-pink {
            background-color: #f8d7da;
        }
        .highlight-green {
            background-color: #ccffcc;
        }
    </style>
</head>
<body>
    <form method="POST">
        <button type="submit" name="button1" class="button">T.T_37</button>
        <button type="submit" name="button2" class="button">VEN_PC_0€</button>
        <button type="submit" name="button3" class="button">VEN_IMP_LIQ_0€</button>
        <button type="submit" name="button4" class="button">SIN_PARTIDA</button>
        <button type="submit" name="button5" class="button-strong">MASCARA_FACTURA</button>
        <button type="submit" name="button6" class="button-arance">COD_ARANCE</button>
    </form>
    <br>
<hr>
    <?php if (!empty($results)): ?>
        <table id="resultTable">
            <caption>RESULTADOS</caption>
            <thead>
                <tr>
                    <?php foreach (array_keys($results[0]) as $header): ?>
                        <th><?php echo htmlspecialchars($header); ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results as $row): ?>
                    <tr>
                        <?php foreach ($row as $column => $value): ?>
                            <?php 
                            $class = '';
                            if (isset($_POST['button6'])) {
                                if ($column == 'cod_aran_alb' && empty($value)) {
                                    $class = 'highlight-red';
                                } elseif ($column == 'cod_aran_art' && empty($value)) {
                                    $class = 'highlight-red';
                                }
                            } elseif (isset($_POST['button5'])) {
                                if ($column == 'EmailEnvioEFactura' && empty($value)) {
                                    $class = 'highlight-red';
                                } elseif ($column == 'Telefono' && empty($value)) {
                                    $class = 'highlight-orange';
                                } elseif ($column == 'MascaraFactura_' && empty($value)) {
                                    $class = 'highlight-pink';
                                }
                            }
                            ?>
                            <td class="<?php echo $class; ?>"><?php echo htmlspecialchars($value); ?></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <!-- Inicio de los DataTables -->
    <script>
        $(document).ready(function() {
            $('#resultTable').DataTable({
                "paging": true,
                "searching": true,
                "ordering": true,
                "info": true
            });
        });
    </script>
</body>
</html>
