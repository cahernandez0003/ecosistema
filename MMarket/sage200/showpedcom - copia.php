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
        			<li class="breadcrumb-item"><a href="pedicom.php" class="text-purple">Módulo Pedidos de Compra</a></li>
        			<li class="breadcrumb-item active"><i class="fas fa-eye"> </i>Detalle del Pedido</li>
        		</ol>
        	</nav>
	   </div>
	</div>
<?php
$SeriePedido = isset($_GET['SeriePedido']) ? $_GET['SeriePedido'] : '';
$NumeroPedido = isset($_GET['NumeroPedido']) ? $_GET['NumeroPedido'] : '';


// Obtener la información del proveedor y el importe total
$proveedorInfo = obtenerInformacionProveedorPed($con, $SeriePedido, $NumeroPedido);
?>

<div class="container">
    <div class="row" style="text-align:center;">
        <h4>DETALLE DE ESTE PEDIDO:</h4>
        <br>
    </div>
    <div class="row col-md-12">
       <div class="col-md-3">
            <strong>FECHA PEDIDO:</strong> <?php echo date('Y-m-d', strtotime($proveedorInfo['FechaPedido'])); ?>
        </div>
        <div class="col-md-2">
            <strong>NÚMERO PEDIDO:</strong> <?php echo $proveedorInfo['SeriePedido']; ?><?php echo $proveedorInfo['NumeroPedido']; ?>
        </div>
        <div class="col-md-2">
            <strong>CÓDIGO PROVEEDOR:</strong> <?php echo $proveedorInfo['CodigoProveedor']; ?>
        </div>
        <div class="col-md-3">
            <strong>RAZÓN SOCIAL:</strong> <?php echo $proveedorInfo['RazonSocial']; ?>
        </div>
        <div class="col-md-2">
            <strong>IMPORTE:</strong> <?php echo number_format($proveedorInfo['ImporteLiquido'], 2, '.', ''); ?>€
        </div>
    </div>
</div>
<hr>
 <div class="container">
        <div class="row">
            <div class="col-md-12 offset-md-0">
                <?php
                $SeriePedido = isset($_GET['SeriePedido']) ? $_GET['SeriePedido'] : '';
                $NumeroPedido = isset($_GET['NumeroPedido']) ? $_GET['NumeroPedido'] : '';

                // Realizar la consulta a la tabla lineasPedidoproveedor
                $lineas = obtenerLineasPedidoProveedor($con, $SeriePedido, $NumeroPedido);
                ?>
                <table style="vertical-align: middle;" id="albvenTable" class="table table-striped table-hover">
                    <thead>
                    <tr>
                        
                        <th>Código Artículo</th>
                        <th>Descripción de Artículo</th>
                        <th>Unidades Pedidas</th>
                        <th>€xUd</th>
                        <th>IVA</th>                   
                    </tr>
                </thead>
                    <tbody>
                        <?php foreach ($lineas as $prow): ?>
                            <tr>
                            <td><?php echo $prow['CodigoArticulo']; ?></td>
                            <td><?php echo $prow['DescripcionArticulo']; ?></td>
                            <td><?php echo number_format($prow['UnidadesPedidas'], 3, '.', ''); ?></td>
                            <td><?php echo number_format($prow['Precio'], 2, '.', ''); ?>€</td>
                            <td><?php echo number_format($prow['%Iva'], 0, '.', ''); ?>%</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

   <div class="pie"> <?php require '../includes/footer.inc'; ?></div>