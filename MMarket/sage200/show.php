<?php
require '../config/app.php';
require '../config/security_admin.php';
require '../config/bd_mysql.php';
require '../config/bd.php';
require '../includes/header.inc';
?>


<body class="home">

	<style>
		td{
			text-align: right;
		}
		th{
			text-align: center;
		}
		.pie{
			display: none;
		}
	</style>
	<br>
	<div class="container">
		<div class="row">
            <nav aria-label="breadcrumb">
        		<ol class="breadcrumb">
        			<li class="breadcrumb-item"><a href="albaranes1.php" class="text-purple">Módulo Albaranes de Venta</a></li>
        			<li class="breadcrumb-item active"><i class="fas fa-eye"> </i>Detalle de Albarán</li>
        		</ol>
        	</nav>
	   </div>
	</div>
<?php
$SerieAlbaran = isset($_GET['SerieAlbaran']) ? $_GET['SerieAlbaran'] : '';
$NumeroAlbaran = isset($_GET['NumeroAlbaran']) ? $_GET['NumeroAlbaran'] : '';

// Obtener la información del cliente y el importe total
$clienteInfo = obtenerInformacionCliente($con, $SerieAlbaran, $NumeroAlbaran);
?>

<div class="container">
    <div class="row" style="text-align:center;">
        <h4>DETALLE DE ESTE ALBARÁN:</h4>
        <br>
    </div>
    <div class="row col-md-12">
       <div class="col-md-3">
            <!-- <strong>FECHA ALBARÁN:</strong> <?php echo date('Y-m-d', strtotime($clienteInfo['FechaAlbaran'])); ?> -->
            <strong>FECHA ALBARÁN:</strong> <?php echo $clienteInfo['FechaCreacion']; ?>

        </div>
        <div class="col-md-2">
            <strong>NÚMERO ALBARÁN:</strong> <?php echo $clienteInfo['SerieAlbaran']; ?><?php echo $clienteInfo['NumeroAlbaran']; ?>
        </div>
        <div class="col-md-2">
            <strong>CÓDIGO CLIENTE:</strong> <?php echo $clienteInfo['CodigoCliente']; ?>
        </div>
        <div class="col-md-3">
            <strong>RAZÓN SOCIAL:</strong> <?php echo $clienteInfo['RazonSocial']; ?>
        </div>
        <div class="col-md-2">
            <strong>IMPORTE:</strong> <?php echo number_format($clienteInfo['ImporteLiquido'], 2, '.', ''); ?>€
        </div>
    </div>
</div>
<hr>
 <div class="container">
        <div class="row">
            <div class="col-md-12 offset-md-0">
                <?php
                $SerieAlbaran = isset($_GET['SerieAlbaran']) ? $_GET['SerieAlbaran'] : '';
                $NumeroAlbaran = isset($_GET['NumeroAlbaran']) ? $_GET['NumeroAlbaran'] : '';

                // Realizar la consulta a la tabla lineasalbarancliente
                $lineas = obtenerLineasAlbaranCliente($con, $SerieAlbaran, $NumeroAlbaran);
                ?>
                <table style="vertical-align: middle;" id="albvenTable" class="table table-striped table-hover">
                    <thead>
                    <tr>
                        
                        <th>Código Artículo</th>
                        <th>Descripción de Artículo</th>
                        <th>Partida</th>
                        <th>Unidades</th>
                        <th>Importe Coste</th>
                        <th>Precio Coste</th>
                        <th>Base Imponible</th>
                        <th>€ Beneficio</th>
                        <th>% Beneficio</th>
                        <th>Descuento</th>
                       
                    </tr>
                </thead>
                    <tbody>
                        <?php foreach ($lineas as $arow): ?>
                            <tr>
                            <td><?php echo $arow['CodigoArticulo']; ?></td>
                            <td><?php echo $arow['DescripcionArticulo']; ?></td>
                            <td><?php echo $arow['Partida']; ?></td>
                            <td><?php echo number_format($arow['Unidades'], 3, '.', ''); ?></td>
                            <td><?php echo number_format($arow['ImporteCoste'], 2, '.', ''); ?>€</td>
                            <td><?php echo number_format($arow['PrecioCoste'], 2, '.', ''); ?>€</td>
                            <td><?php echo number_format($arow['BaseImponible'], 2, '.', ''); ?>€</td>
                            <td><?php echo number_format($arow['MargenBeneficio'], 2, '.', ''); ?>€</td>
                            <td><?php echo number_format($arow['PorMargenBeneficio'], 2, '.', ''); ?>%</td>
                            <td><?php echo number_format($arow['%Descuento'], 2, '.', ''); ?>%</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

   <div class="pie"> <?php require '../includes/footer.inc'; ?></div>