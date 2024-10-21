<?php require 'config/app.php'; ?>
<?php include_once('config/bd_mysql.php'); ?>
<?php include_once('config/bd.php'); ?>
<?php include 'includes/header.inc'; ?>
<body class="home">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1 class="text-muted text-center"> <i class="fas fa-pallet"></i> ARTÍCULOS </h1>                
                <table style="vertical-align: middle;" id="articulosTable" class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th style="text-align: center"> Codigo_Art </th>
                            <th style="text-align: center"> Descripción</th>
                            <th style="text-align: center"> TipoIva</th>
                            <th style="text-align: center; display: none;"> CodigoBarras</th>
                            <th style="text-align: center; display: none;"> CodigoBarras2</th>
                            <th style="text-align: center"> SinIva</th>
                            <th style="text-align: center"> Importe</th>
                            <th style="text-align: center"> Stock </th>
                            <th style="text-align: center"> Imagen</th>
                            <!-- <th style="text-align: center"> codbar</th> -->
                            
                        </tr>
                    </thead>
                    <tbody style="">
                        <?php $lstar = listararticulos($con);?>
                        <?php foreach ($lstar as $arow): ?>
                            <tr>
                                <td> <?php echo $arow['CodigoArticulo']; ?></td>
                                <td> <?php echo $arow['DescripcionArticulo']; ?></td>
                                <td style="display: none;"> <?php echo $arow['CodigoAlternativo']; ?></td>
                                <td style="display: none;"> <?php echo $arow['CodigoAlternativo2']; ?></td>
                                <td>
                                    <?php
                                    $grupoIva = $arow['GrupoIva'];
                                    switch ($grupoIva) {
                                        case 1:
                                            echo '21%';
                                            break;
                                        case 2:
                                            echo '10%';
                                            break;
                                        case 4:
                                            echo '0%';
                                            break;
                                        case 5:
                                            echo '5%';
                                            break;
                                        case 6:
                                            echo '2%';
                                            break;
                                        case 7:
                                            echo '7.5%';
                                            break;
                                        default:
                                            echo 'No definido';
                                            break;
                                    }
                        ?>
                                </td>
                                <td> <?php echo number_format($arow['PrecioVentaOriginal'],2,'.'.''); ?> €</td>
                                <td> <?php echo $arow['PrecioVenta']; ?> €</td>
                                <td style=""><?php echo $arow['TotalUnidadSaldo']; ?></td>
                                <td>
                                    <img src="<?php echo $arow['ImagenURL']; ?>" alt="Imagen del artículo" style="width: 75px; height: 75px;">
                                </td>
                               <!--  <td>
                                    <svg class="barcode" data-code="<?php echo $arow['CodigoAlternativo']; ?>"></svg>
                                </td> -->
                            </tr>
                        <?php endforeach?>
                    </tbody>
                </table>
                </table>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#articulosTable').DataTable({
                "paging": true,
                "lengthMenu": [10, 20, 50, 100],
                "searching": true
            });
        });
    </script>
    
    <script>
        // Agrega un script para mostrar/ocultar el formulario de inicio de sesión
        document.getElementById('loginButton').addEventListener('click', function () {
            document.getElementById('loginForm').style.display = 'block';
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.barcode').forEach(function(element) {
                var code = element.getAttribute('data-code');
                JsBarcode(element, code, {
                    format: "CODE128", 
                    displayValue: false, 
                    width: 2,
                    height: 40
                });
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