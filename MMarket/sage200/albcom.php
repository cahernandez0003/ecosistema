<?php require '../config/app.php'; ?>
<?php require '../config/security_admin.php'; ?>
<?php require '../config/bd_mysql.php'; ?>
<?php require '../config/bd.php'; ?>
<?php require '../includes/header.inc'; ?>



<body class="home">
<nav class="navbar navbar-expand-lg" style="background-color: hsl(220,100%,90%); position: fixed; z-index: 1; height: 65px; width: 100%;"><img src="../public/imgs/favicon.png" style="width: 55px; height: 55px; padding: 0px; margin: 20px;">


        <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
            <li class="nav-item active"><a class="btn btn-default" href="cartera.php">CARTERA</a>&nbsp; |</li>
            <li class="nav-item active"><a class="btn btn-default" href="albaranes1.php">ALBVEN</a>&nbsp; |</li>
            <li class="nav-item active"><a class="btn btn-default" href="pedicom.php">PEDICOM</a>&nbsp; |</li>
            <li class="nav-item active"><a class="btn btn-default" href="rutas.php">RUTAS</a>&nbsp; |</li>
            <li class="nav-item active"><a class="btn btn-default" href="articles.php">ARTÍCULOS</a>&nbsp; |</li>
            <li class="nav-item active"><a class="btn btn-default" href="index.php">VOLVER</a>&nbsp; |</li>
            <li class="nav-item active"><a class="btn btn-default" href="<?php echo $url_site.'pages/close.php';?>"><i class="fa fa-times"></i> 
                    Cerrar Sesión
                </a>&nbsp; |</li>
        </ul>
    </nav>
    <br>
    <div class="container">            
        <div class="row">
            
            <div class="col-md-12 offset-md-0">
                <h1 class="text-muted text-center"> ALBARANES DE COMPRA </h1>

                <!-- Agregamos los campos de búsqueda -->
                <div class="row mb-3">
                    <div class="col">
                        <input type="text" class="form-control" placeholder="Buscar por Razón Social" id="searchByRazonSocial">
                    </div>
                    <div class="col">
                        <input type="text" class="form-control" placeholder="Buscar por Número Albarán" id="searchByNumeroAlbaran">
                    </div>
                </div>

                <table style="vertical-align: middle;" id="albvenTable" class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Serie</th>
                            <th>Número</th>
                            <th>Código Proveedor</th>
                            <th>Razón Social</th>
                            <th>Factura</th>
                            <th>Importe Liquido</th>
                            <th>VER</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $lstar = list_albcom($con); ?>
                        <?php foreach ($lstar as $crow): ?>
                            <tr>
                                <td style="white-space: nowrap;"><?php echo date('Y-m-d', strtotime($crow['FechaAlbaran'])); ?></td>
                                <td><?php echo $crow['SerieAlbaran']; ?></td>
                                <td><?php echo $crow['NumeroAlbaran']; ?></td>
                                <td><?php echo $crow['CodigoProveedor']; ?></td>
                                <td><?php echo $crow['RazonSocial']; ?></td>
                                <td><?php echo $crow['NumeroFactura']; ?></td>
                                <td style="text-align: right"><?php echo number_format($crow['ImporteLiquido'],2,'.',''); ?>€</td>
                                <td><a href="showalbcom.php?SerieAlbaran=<?php echo $crow['SerieAlbaran']; ?>&NumeroAlbaran=<?php echo $crow['NumeroAlbaran']; ?>"><i class="fa fa-search"></i></a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
    $(document).ready(function () {
        // Inicializamos la tabla
        var table = $('#albvenTable').DataTable({
            "paging": true,
            "lengthMenu": [10, 20, 50, 100],
            "searching": true,
            "dom": '<"top"lf>rt<"bottom"ip>',
            "language": {
                "search": '<div class="input-group"><div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-search"></i></span></div></div>',
            }
        });

        // en esta fracción agregué dos eventos de búsqueda
        $('#searchByRazonSocial').on('keyup', function() {
            table.column(4).search(this.value).draw();
        });

        $('#searchByNumeroAlbaran').on('keyup', function() {
            table.column(2).search(this.value).draw();
        });
    });
    </script>

    <?php $con = null; ?>
    <hr>
    <hr>
</body>
    <div style="display: none;">    <?php include 'includes/footer.inc'; ?></div>
</html>
