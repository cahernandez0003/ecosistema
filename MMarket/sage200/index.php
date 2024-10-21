<?php require '../config/app.php'; ?>
<?php require '../config/security_admin.php'; ?>
<?php require '../config/bd_mysql.php'; ?>
<?php require '../config/bd.php'; ?>
<?php require '../includes/header.inc'; ?>


<div class="container">
		<br><br>
	<div class="row">
		<div class="col-md-10 offset-md-1 text-center">
			<h1 class="text-muted"> INFORMACIÓN MIMOUN MARKET SL </h1>
			<h3>SAGE 200</h3>
			<hr>
			<div class="btn-group-vertical">
				<a href="../sage200/cartera.php" class="btn btn-outline-success btn-lg text-left"> 
					<i class="fas fa-money-bill-alt"></i>
				 	DEUDA CLIENTES <i class="fas fa-comments-dollar"></i>
				</a>

				<a href="../sage200/albaranes1.php" class="btn btn-outline-success btn-lg text-left"> 
					<i class="fas fa-file-invoice"></i>
				 	ALBARANES DE VENTA 
				</a>
				<a href="../sage200/rutas.php" class="btn btn-outline-success btn-lg text-left"> 
				 	<i class="fas fa-truck"></i>
				 	RUTAS 
				</a>
				<a href="../sage200/articles.php" class="btn btn-outline-success btn-lg text-left"> 
				 	<i class="fas fa-boxes"></i>
				 	ARTICULOS 
				</a>
				<a href="../sage200/albcom.php" class="btn btn-outline-success btn-lg text-left"> 
				 	<i class="fas fa-store"></i>
				 	COMPRAS 
				</a>
				<a href="../sage200/pedicom.php" class="btn btn-outline-success btn-lg text-left"> 
				 	<i class="fas fa-shopping-cart"></i>
				 	PEDICOM 
				</a>
				<a href="../sage200/abonos.php" class="btn btn-outline-success btn-lg text-left"> 
				 	<i class="fas fa-exchange-alt"></i>
				 	DEVOLUCIONES 
				</a>
				<a href="<?php echo $url_site.'pages/close.php'; ?>" class="btn btn-outline-danger btn-lg text-left">
          			<i class="fa fa-times"></i> 
          			Cerrar Sesión
          		</a>
			</div>
			<!-- <div class="card-group">
				<div class="card">
				    <a href="../sage200/abonos.php" class="card-body text-left">
				    <div class="text-center">
				        <h5>DEVOLUCIONES</h5>
				    </div>
				        <img class="card-img-top img-fluid" src="../public/imgs/devolucion.png" alt="Card image cap">
				    </a>
				</div>
				<div class="card">
				    <a href="../sage200/rutas.php" class="card-body text-left">
				    <div class="text-center">
				        <h5>GESTIÓN DE RUTAS</h5>
				    </div>
				        <img class="card-img-top img-fluid" src="../public/imgs/rutas.png" alt="Card image cap">
				    </a>
				</div>
				<div class="card">
				    <a href="../sage200/cartera.php" class="card-body text-left">
				    <div class="text-center">
				        <h5>GESTIÓN DE CARTERA</h5>
				    </div>
				        <img class="card-img-top img-fluid" src="../public/imgs/cartera.png" alt="Card image cap">
				    </a>
				</div>

			</div>
			<div class="card-group">
				<div class="card">
				    <a href="../sage200/albaranes1.php" class="card-body text-left">
				    <div class="text-center">
				        <h5>ALBARANES DE VENTA</h5>
				    </div>
				        <img class="card-img-top img-fluid" src="../public/imgs/albaranes.png" alt="Card image cap">
				    </a>
				</div>
			   
			    <div class="card">
				    <a href="../sage200/articles.php" class="card-body text-left">
				    <div class="text-center">
				        <h5>GESTIÓN DE ARTÍCULOS</h5>
				    </div>
				        <img class="card-img-top img-fluid" src="../public/imgs/articulos.png" alt="Card image cap">
				    </a>
				</div>
				<div class="card">
				    <a href="../sage200/albcom.php" class="card-body text-left">
				    <div class="text-center">
				        <h5>ALBARANES DE COMPRA</h5>
				    </div>
				        <img class="card-img-top img-fluid" src="../public/imgs/albaranes.png" alt="Card image cap">
				    </a>
				</div>
				
				<div class="card">
				    <a href="<?php echo $url_site.'pages/close.php'; ?>" class="card-body text-left">
				    <div class="text-center">
				        <h5>CERRAR SESIÓN</h5>
				    </div>
				        <img class="card-img-top img-fluid" src="../public/imgs/salir.png" alt="Card image cap">
				    </a>
				</div>
			</div> -->
		</div>
	</div>
</div>
<?php $con = null; ?>
<?php include '../includes/footer.inc'; ?>