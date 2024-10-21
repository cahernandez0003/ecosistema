<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DASH_MIMOUNMARKET</title>
    <link rel="shortcut icon" href="public/imgs/favicon.ico">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 10px;
            background-color: #f4f4f4;
        }

        h1, h2, h3, h4 {
            text-align: center;
            color: #333;
        }

        .historico-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
            background-color: #fff;
            box-shadow: 0 2px 3px rgba(0, 0, 0, 0.1);
            font-size: 0.8em;
        }

        th, td {
            padding: 8px;
            text-align: right;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        td:first-child, th:first-child {
            text-align: left;
        }

        tfoot th {
            background-color: #333;
            color: white;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }

        .comparison-table {
            width: 40%;
            margin: 10px;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 2px 3px rgba(0, 0, 0, 0.1);
            font-size: 0.8em;
            text-align: center;
            display: inline-block;
            vertical-align: top;
        }

        .comparison-table th, .comparison-table td {
            padding: 5px;
            border: 1px solid #ddd;
        }

        .comparison-table th {
            background-color: #ADD8E6;
            color: black;
        }

        .comparison-table td {
            background-color: #F9F9F9;
            font-weight: bold;
        }

        select, input[type="text"] {
            padding: 5px;
            font-size: 0.9em;
        }

        input[type="submit"] {
            padding: 5px 10px;
            font-size: 0.9em;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .filters {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 10px 0;
            font-size: 0.9em;
        }

        .filters label {
            margin-right: 10px;
        }

        .filters input[type="text"],
        .filters select {
            margin-right: 10px;
            min-width: 150px;
        }

        .filters select[multiple] {
            height: auto;
        }

        .filters input[type="submit"] {
            margin-left: 10px;
        }

        .deuda-total {
            font-size: 1.2em;
            font-weight: bold;
            color: #D32F2F;
            text-align: left;
            margin: 10px 10px;
            display: inline-block;
            vertical-align: top;
        }

        .cliente-reporte {
            width: 100%;
            margin: 20px auto;
            font-size: 0.8em;
        }

        .cliente-reporte h3,
        .cliente-reporte h4 {
            font-size: 1em;
            text-align: left;
            margin-left: 10px;
        }

        .cliente-reporte table {
            width: 80%;
            margin-left: 10px;
            border-collapse: collapse;
        }

        .cliente-reporte th, .cliente-reporte td {
            padding: 8px;
            border: 1px solid #ddd;
        }
    </style>
    <script>
        function actualizarReporte() {
            var ejercicio1 = document.getElementById('ejercicio1').value;
            var ejercicio2 = document.getElementById('ejercicio2').value;
            var mes = document.getElementById('mes').value;

            if (ejercicio1 && ejercicio2 && mes) {
                var totalEjercicio1 = parseFloat(document.getElementById('total_' + ejercicio1 + '_' + mes).innerText.replace(' €', '').replace('.', '').replace(',', '.'));
                var totalEjercicio2 = parseFloat(document.getElementById('total_' + ejercicio2 + '_' + mes).innerText.replace(' €', '').replace('.', '').replace(',', '.'));

                var diferencia = totalEjercicio2 - totalEjercicio1;
                var resultado = diferencia > 0 ? "EJERCICIO ANTERIOR SUPERADO EN:" : "EJERCICIO ANTERIOR NO SUPERADO POR:";
                var diferenciaAbs = Math.abs(diferencia).toFixed(2);

                document.getElementById('total_mes_1').innerText = number_format(totalEjercicio1, 2, ',', '.') + ' €';
                document.getElementById('total_mes_2').innerText = number_format(totalEjercicio2, 2, ',', '.') + ' €';
                document.getElementById('resultado').innerText = resultado;
                document.getElementById('diferencia').innerText = diferenciaAbs.replace('.', ',') + ' €';
            }
        }

        function number_format(number, decimals, dec_point, thousands_sep) {
            number = (number + '').replace(',', '').replace(' ', '');
            var n = !isFinite(+number) ? 0 : +number,
                prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                sep = (typeof thousands_sep === 'undefined') ? '.' : thousands_sep,
                dec = (typeof dec_point === 'undefined') ? ',' : dec_point,
                s = '',
                toFixedFix = function (n, prec) {
                    var k = Math.pow(10, prec);
                    return '' + Math.round(n * k) / k;
                };
            s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
            if (s[0].length > 3) {
                s[0] = s[0].replace(/\B(?=(\d{3})+(?!\d))/g, sep);
            }
            if ((s[1] || '').length < prec) {
                s[1] = s[1] || '';
                s[1] += new Array(prec - s[1].length + 1).join('0');
            }
            return s.join(dec);
        }

        function mesEnEspanol(mes) {
            const meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
            return meses[mes - 1];
        }
    </script>
</head>
<body>

    <h1>Histórico de Ventas</h1>

    <?php
    // Función para convertir el número de mes a nombre en español
    function mesEnEspanol($mes) {
        $meses = [
            1 => "Enero", 2 => "Febrero", 3 => "Marzo",
            4 => "Abril", 5 => "Mayo", 6 => "Junio",
            7 => "Julio", 8 => "Agosto", 9 => "Septiembre",
            10 => "Octubre", 11 => "Noviembre", 12 => "Diciembre"
        ];
        return $meses[$mes];
    }

    // Incluir la conexión a la base de datos
    include 'bd.php';

    // Conectar a la base de datos
    try {
        $conn = new PDO("sqlsrv:server=$hostSqlServer;Database=$nmdbSqlServer", $userSqlServer, $passSqlServer);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Consulta para obtener el total de ventas por ejercicio y mes
        $sql_ventas = "
            SELECT 
                a.Ejercicio, 
                MONTH(a.FechaFactura) as Mes, 
                SUM(a.ImporteEfecto) as TotalVentas 
            FROM CarteraEfectos a 
            LEFT JOIN Clientes n on n.CodigoCliente=a.CodigoClienteProveedor and n.CodigoEmpresa=a.CodigoEmpresa
            LEFT JOIN Rutas_ r on r.CodigoRuta_=n.CodigoRuta_ and r.CodigoEmpresa=a.CodigoEmpresa
            LEFT OUTER JOIN Vis_MM_MinMunEnvFra b ON a.CodigoEmpresa = b.CodigoEmpresa 
                AND a.Ejercicio = b.EjercicioFactura 
                AND a.SerieFactura = b.SerieFactura 
                AND a.Factura = b.NumeroFactura
            WHERE a.Prevision = 'C' 
                AND a.CodigoEmpresa = 1 
                AND a.CodigoClienteProveedor LIKE '4300%'
            GROUP BY a.Ejercicio, MONTH(a.FechaFactura)
            ORDER BY a.Ejercicio, MONTH(a.FechaFactura)
        ";

        // Ejecutar la consulta de ventas
        $stmt_ventas = $conn->query($sql_ventas);
        $resultados = $stmt_ventas->fetchAll(PDO::FETCH_ASSOC);

        // Inicializar arrays para los resultados de ventas
        $data = [];
        $totalesPorMes = array_fill(1, 12, 0); // Inicializar los totales por mes

        // Procesar los resultados de ventas si existen
        if ($resultados) {
            foreach ($resultados as $row) {
                $ejercicio = $row['Ejercicio'];
                $mes = $row['Mes'];
                $total = $row['TotalVentas'];
                
                // Inicializar el array para el año si no existe
                if (!isset($data[$ejercicio])) {
                    $data[$ejercicio] = array_fill(1, 12, 0); // 12 meses con valores iniciales en 0
                }
                
                // Asignar el total de ventas al mes correspondiente
                $data[$ejercicio][$mes] = $total;
                
                // Sumar el total al acumulado por mes
                $totalesPorMes[$mes] += $total;
            }
        }

        // Consulta para obtener el total de la deuda de los clientes
        $sql_deuda = "
            SELECT SUM(a.ImporteEfecto) as DeudaTotal
            FROM CarteraEfectos a 
            LEFT JOIN Clientes n on n.CodigoCliente=a.CodigoClienteProveedor and n.CodigoEmpresa=a.CodigoEmpresa
            LEFT JOIN Rutas_ r on r.CodigoRuta_=n.CodigoRuta_ and r.CodigoEmpresa=a.CodigoEmpresa
            LEFT OUTER JOIN Vis_MM_MinMunEnvFra b ON a.CodigoEmpresa = b.CodigoEmpresa 
                AND a.Ejercicio = b.EjercicioFactura 
                AND a.SerieFactura = b.SerieFactura 
                AND a.Factura = b.NumeroFactura
            WHERE a.Prevision = 'C' 
                AND a.StatusBorrado = 0 
                AND a.CodigoEmpresa = 1 
                AND a.CodigoClienteProveedor LIKE '4300%'
        ";

        // Ejecutar la consulta de deuda
        $stmt_deuda = $conn->query($sql_deuda);
        $resultadoDeuda = $stmt_deuda->fetch(PDO::FETCH_ASSOC);
        $deudaTotal = $resultadoDeuda['DeudaTotal'];

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    ?>

    <?php if (!empty($data)): ?>
    <table class="historico-table">
        <thead>
            <tr>
                <th>Ejercicio</th>
                <?php for ($i = 1; $i <= 12; $i++): ?>
                    <th><?php echo mesEnEspanol($i); ?></th>
                <?php endfor; ?>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data as $ejercicio => $meses): ?>
                <tr>
                    <td><?php echo $ejercicio; ?></td>
                    <?php 
                    $totalEjercicio = 0;
                    foreach ($meses as $mes => $total): 
                        $totalEjercicio += $total;
                    ?>
                        <td id="total_<?php echo $ejercicio; ?>_<?php echo $mes; ?>"><?php echo number_format($total, 2, ',', '.') . ' €'; ?></td>
                    <?php endforeach; ?>
                    <td><strong><?php echo number_format($totalEjercicio, 2, ',', '.') . ' €'; ?></strong></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <th>Total</th>
                <?php foreach ($totalesPorMes as $total): ?>
                    <th><?php echo number_format($total, 2, ',', '.') . ' €'; ?></th>
                <?php endforeach; ?>
                <th><?php echo number_format(array_sum($totalesPorMes), 2, ',', '.') . ' €'; ?></th>
            </tr>
        </tfoot>
    </table>
    <?php else: ?>
        <p>No se encontraron datos para mostrar en el reporte.</p>
    <?php endif; ?>

    <!-- Nueva tabla para mostrar la deuda total de los clientes -->
    <div class="deuda-total">
        <p>Deuda Total de los Clientes: <?php echo number_format($deudaTotal, 2, ',', '.') . ' €'; ?></p>
    </div>

    <!-- Tabla con selectores y resultados -->
    <table class="comparison-table">
        <tr>
            <th>EJERCICIO</th>
            <th>
                <select id="mes" onchange="actualizarReporte()" required>
                    <?php for ($i = 1; $i <= 12; $i++): ?>
                        <option value="<?php echo $i; ?>"><?php echo mesEnEspanol($i); ?></option>
                    <?php endfor; ?>
                </select>
            </th>
        </tr>
        <tr>
            <td>
                <select id="ejercicio1" onchange="actualizarReporte()" required>
                    <?php foreach (array_keys($data) as $ejercicio): ?>
                        <option value="<?php echo $ejercicio; ?>"><?php echo $ejercicio; ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
            <td id="total_mes_1">0 €</td>
        </tr>
        <tr>
            <td>
                <select id="ejercicio2" onchange="actualizarReporte()" required>
                    <?php foreach (array_keys($data) as $ejercicio): ?>
                        <option value="<?php echo $ejercicio; ?>"><?php echo $ejercicio; ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
            <td id="total_mes_2">0 €</td>
        </tr>
        <tr>
            <td id="resultado">EJERCICIO ANTERIOR SUPERADO EN:</td>
            <td id="diferencia">0,00 €</td>
        </tr>
    </table>

    <!-- Formulario para ingresar el código del cliente -->
    <h2>Consulta de Deuda de un Cliente Específico</h2>
    <div class="filters">
        <form method="post">
            <label for="codigoclientepro">Código Cliente Proveedor:</label>
            <input type="text" id="codigoclientepro" name="codigoclientepro" required>

            <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['codigoclientepro'])): ?>
                <?php
                // Capturar el código de cliente ingresado
                $codigoclientepro = $_POST['codigoclientepro'];

                // Consulta para obtener los municipios del cliente específico
                $sql_municipios = "
                    SELECT DISTINCT b.MunicipioEnvios
                    FROM CarteraEfectos a
                    LEFT OUTER JOIN Vis_MM_MinMunEnvFra b ON a.CodigoEmpresa = b.CodigoEmpresa 
                        AND a.Ejercicio = b.EjercicioFactura 
                        AND a.SerieFactura = b.SerieFactura 
                        AND a.Factura = b.NumeroFactura
                    WHERE a.CodigoEmpresa = 1 
                        AND a.CodigoClienteProveedor = :codigoclientepro
                ";

                $stmt_municipios = $conn->prepare($sql_municipios);
                $stmt_municipios->bindParam(':codigoclientepro', $codigoclientepro);
                $stmt_municipios->execute();
                $municipios = $stmt_municipios->fetchAll(PDO::FETCH_ASSOC);
                ?>
                
                <label for="municipioenvios">Municipio Envios:</label>
                <select id="municipioenvios" name="municipioenvios[]" multiple required>
                    <?php foreach ($municipios as $municipio): ?>
                        <option value="<?php echo $municipio['MunicipioEnvios']; ?>"><?php echo $municipio['MunicipioEnvios']; ?></option>
                    <?php endforeach; ?>
                </select>

                <label for="mes">Mes:</label>
                <select id="mes" name="mes[]" multiple required>
                    <?php for ($i = 1; $i <= 12; $i++): ?>
                        <option value="<?php echo $i; ?>"><?php echo mesEnEspanol($i); ?></option>
                    <?php endfor; ?>
                </select>

                <input type="submit" value="Filtrar">
            <?php endif; ?>
        </form>
    </div>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['codigoclientepro']) && isset($_POST['municipioenvios']) && isset($_POST['mes'])) {
        // Capturar el código de cliente, municipios y meses ingresados
        $codigoclientepro = $_POST['codigoclientepro'];
        $municipioenvios = $_POST['municipioenvios'];
        $meses = $_POST['mes'];

        // Convertir las selecciones múltiples en una cadena separada por comas
        $municipioenvios_str = implode("','", $municipioenvios);
        $meses_str = implode(',', $meses);

        // Consulta para obtener la deuda del cliente específico filtrada por municipios y meses
        $sql_cliente = "
            SELECT 
                n.RazonSocial,
                SUM(a.ImporteEfecto) as DeudaTotal,
                CAST(a.FechaFactura as date) as FechaFactura,
                a.SerieFactura,
                a.Factura,
                b.MunicipioEnvios,
                a.ImporteEfecto
            FROM CarteraEfectos a 
            LEFT JOIN Clientes n on n.CodigoCliente=a.CodigoClienteProveedor and n.CodigoEmpresa=a.CodigoEmpresa
            LEFT OUTER JOIN Vis_MM_MinMunEnvFra b ON a.CodigoEmpresa = b.CodigoEmpresa 
                AND a.Ejercicio = b.EjercicioFactura 
                AND a.SerieFactura = b.SerieFactura 
                AND a.Factura = b.NumeroFactura
            WHERE a.CodigoEmpresa = 1 
                AND a.CodigoClienteProveedor = :codigoclientepro
                AND b.MunicipioEnvios IN ('$municipioenvios_str')
                AND MONTH(a.FechaFactura) IN ($meses_str)
                AND a.StatusBorrado = 0
            GROUP BY n.RazonSocial, CAST(a.FechaFactura as date), a.SerieFactura, a.Factura, b.MunicipioEnvios, a.ImporteEfecto
            ORDER BY CAST(a.FechaFactura as date)
        ";

        // Preparar y ejecutar la consulta
        $stmt_cliente = $conn->prepare($sql_cliente);
        $stmt_cliente->bindParam(':codigoclientepro', $codigoclientepro);
        $stmt_cliente->execute();
        $resultadosCliente = $stmt_cliente->fetchAll(PDO::FETCH_ASSOC);

        if ($resultadosCliente) {
            $razonSocial = $resultadosCliente[0]['RazonSocial'];
            $deudaTotalCliente = array_sum(array_column($resultadosCliente, 'ImporteEfecto'));

            echo "<div class='cliente-reporte'>";
            echo "<h3>Cliente: $razonSocial</h3>";
            echo "<h4>Deuda Total en municipios seleccionados para meses seleccionados: " . number_format($deudaTotalCliente, 2, ',', '.') . " €</h4>";

            echo "<table>";
            echo "<thead><tr><th>Fecha Factura</th><th>Serie Factura</th><th>Factura</th><th>Municipio Envios</th><th>Importe</th></tr></thead>";
            echo "<tbody>";
            foreach ($resultadosCliente as $row) {
                echo "<tr>";
                echo "<td>" . $row['FechaFactura'] . "</td>";
                echo "<td>" . $row['SerieFactura'] . "</td>";
                echo "<td>" . $row['Factura'] . "</td>";
                echo "<td>" . $row['MunicipioEnvios'] . "</td>";
                echo "<td>" . number_format($row['ImporteEfecto'], 2, ',', '.') . " €</td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
            echo "</div>";
        } else {
            echo "<p>No se encontraron resultados para los criterios seleccionados.</p>";
        }
    }
    ?>

</body>
</html>
