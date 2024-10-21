<?php require '../config/app.php'; ?>
<?php require '../config/security_admin.php'; ?>
<?php require '../config/bd_mysql.php'; ?>
<?php require '../config/bd.php'; ?>
<?php require '../includes/header.inc'; ?>


<body class="home">

    <div class="container">            
        <div class="row">
                <div>
                    <a class="btn btn-outline-success btn-lg text-left" href="articles.php">ARTÍCULOS</a>
                    <a class="btn btn-outline-success btn-lg text-left" href="cartera.php">DEUDA CLIENTES</a>
                    <a class="btn btn-outline-success btn-lg text-left" href="index.php">VOLVER</a>
                </div>
            <div class="col-md-12 offset-md-0">
                <h1 class="text-muted text-center"> ALBARANES DE VENTA </h1>
                <table style="vertical-align: middle;" id="albvenTable" class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Serie</th>
                            <th>Número</th>
                            <th>Código Cliente</th>
                            <th>Razón Social</th>
                            <th>Factura</th>
                            <th>Mun. Envío</th>
                            <th>Importe Liquido</th>
                            <th>CodigoRuta_</th>
                            <th>% Beneficio</th>
                            <th>Beneficio</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $lstar = list_albaranes($con); ?>
                        <?php foreach ($lstar as $arow): ?>
                            <tr>
                                
                                <td><?php echo $arow['FechaAlbaran']; ?></td>
                                <td><?php echo $arow['SerieAlbaran']; ?></td>
                                <td><?php echo $arow['NumeroAlbaran']; ?></td>
                                <td><?php echo $arow['CodigoCliente']; ?></td>
                                <td><?php echo $arow['RazonSocial']; ?></td>
                                <td><?php echo $arow['NumeroFactura']; ?></td>
                                <td><?php echo $arow['MunicipioEnvios']; ?></td>
                                <td style="text-align: right"><?php echo number_format($arow['ImporteLiquido'],2,'.',''); ?>€</td>
                                <td><?php echo $arow['CodigoRuta_']; ?></td>
                                <td><?php echo number_format($arow['PorMargenBeneficio'],2,'.',''); ?>%</td>
                                <td style="text-align: right"><?php echo number_format($arow['MargenBeneficio'],2,'.',''); ?>€</td>
                             
                                
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <script>
    $(document).ready(function () {
        $('#albvenTable').DataTable({
            "paging": true,
            "lengthMenu": [10, 20, 50, 100],
            "searching": true
        });
    });
    </script>



    <?php $con = null; ?>
    <hr>
    <hr>
</body>
<div style="display:none;">
    <?php include '../includes/footer.inc';?>
</div>
</html>