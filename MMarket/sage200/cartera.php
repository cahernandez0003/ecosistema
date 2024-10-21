<?php require '../config/app.php'; ?>
<?php require '../config/security_admin.php'; ?>
<?php require '../config/bd_mysql.php'; ?>
<?php require '../config/bd.php'; ?>
<?php require '../includes/header.inc'; ?>

<body class="home">
<nav class="navbar navbar-expand-lg" style="background-color: hsl(220,100%,90%); position: fixed; z-index: 1; height: 65px; width: 100%;"><img src="../public/imgs/favicon.png" style="width: 55px; height: 55px; padding: 0px; margin: 20px;">


        <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
            <li class="nav-item active"><a class="btn btn-default" href="articles.php">ARTÍCULOS</a>&nbsp; |</li>
            <li class="nav-item active"><a class="btn btn-default" href="albcom.php">ALBCOM</a>&nbsp; |</li>
            <li class="nav-item active"><a class="btn btn-default" href="pedicom.php">PEDICOM</a>&nbsp; |</li>
            <li class="nav-item active"><a class="btn btn-default" href="rutas.php">RUTAS</a>&nbsp; |</li>
            <li class="nav-item active"><a class="btn btn-default" href="albaranes1.php">ALBAVEN</a>&nbsp; |</li>
            <li class="nav-item active"><a class="btn btn-default" href="index.php">VOLVER</a>&nbsp; |</li>
            <li class="nav-item active"><a class="btn btn-default" href="<?php echo $url_site.'pages/close.php';?>"><i class="fa fa-times"></i> 
                    Cerrar Sesión
                </a>&nbsp; |</li>
            
        </ul>
    </nav>
    <br><br><br>
    <div class="container">
        <div style="margin-top: 15px; margin-bottom: 10px; width: 100%; font-size: x-large;" class="btn btn-danger btn-lg text-left" id="totalDeuda">
            IMPORTE DE LA DEUDA GENERAL DE LOS CLIENTES: <strong style="font-size: xx-large;"><?php echo number_format(obtenerTotalDeuda($con), 2, '.', ''); ?>€</strong>
        </div>

        <div class="row">

           
            <div class="col-md-10 offset-md-1">
                <h1 class="text-muted text-center"> <i class="fas fa-book-dead"></i> LISTADO DE DEUDORES <i class="fas fa-comments-dollar"></i> </h1>
                <table style="vertical-align: middle;" id="clientesTable" class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Serie</th>
                            <th>Número</th>
                            <th>Código</th>
                            <th style="display:none;">Cif/DNI</th>
                            <th>Nombre</th>
                            <th>Ruta</th>
                            <th>Municipio Envío</th>
                            <th>Deuda</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $lstar = listardeuda($con); ?>
                        <?php foreach ($lstar as $arow): ?>
                            <tr>
                                <td style="white-space: nowrap;"><?php echo date('Y-m-d', strtotime($arow['FechaFactura'])); ?></td>
                                <td><?php echo $arow['SerieFactura']; ?></td>
                                <td><?php echo $arow['Factura']; ?></td>
                                <td><?php echo $arow['CodigoClienteProveedor']; ?></td>
                                <td style="display:none;"><?php echo $arow['CifDni']; ?></td>
                                <td><?php echo $arow['RazonSocial']; ?></td>
                                <td><?php echo $arow['Ruta_']; ?></td>
                                <td><?php echo $arow['MunicipioEnvios']; ?></td>
                                <td style="text-align: right"><?php echo number_format($arow['ImporteEfecto'], 2, '.', ''); ?>€</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

                <script>
                    // Inicializa el DataTable
                    $(document).ready(function () {
                        $('#clientesTable').DataTable();
                    });

                    // Muestra la alerta con el importe total al hacer clic en una fila
                    $('#clientesTable tbody').on('click', 'tr', function () {
                        var data = $('#clientesTable').DataTable().row(this).data();
                        var codigoCliente = data[3]; // Ajusta el índice según la posición del código del cliente en la tabla

                        // Realiza la solicitud AJAX para obtener el importe total
                        $.post('obtener_importe_total.php', { codigoCliente: codigoCliente }, function (data) {
                            // Formatea el importe a dos decimales
                            var importeFormateado = parseFloat(data).toFixed(2);

                            // Añade la razón social al mensaje
                            var razonSocial = data[6]; // Ajusta el índice según la posición de la razón social en la tabla

                            Swal.fire({
                                title: importeFormateado+'€',
                                text: 'ES LA DEUDA TOTAL PARA EL CLIENTE: ' + codigoCliente,
                                icon: 'warning',
                            });
                        });
                    });

                    // ...

                    // Manejar el evento input en el campo de búsqueda
                    $('#searchField').on('input', function () {
                        var term = $(this).val();

                        // Realizar solicitud AJAX para obtener el total de la deuda
                        $.post('obtener_total_deuda_busqueda.php', { term: term }, function (data) {
                            // Actualizar el elemento HTML que muestra el total de la deuda
                            $('#totalDeuda').html('Total General: ' + data + '€');
                        });
                    });

                    // ...

                </script>
</body>
<?php $con = null;?>
<div style="display:none;">
    <?php include '../includes/footer.inc';?>
</div>
