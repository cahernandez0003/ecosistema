<?php require '../config/app.php'; ?>
<?php include '../config/bd.php'; ?>
<?php include '../config/bd_mysql.php'; ?>
<?php include '../config/security_admin.php'; ?>
<?php include '../includes/header.inc'; ?>

<div class="container">
	<div class="row">
		<div class="col-md-8 offset-md-2 text-center">
			<h1 class="text-muted"> INFORMACIÓN PARA ADMINS </h1>
			<hr>
			<div class="btn-group-vertical">
				<a href="../sage200/cartera.php" class="btn btn-outline-success btn-lg text-left"> 
					<i class="fa fa-users"></i>
				 	DEUDA CLIENTES 
				</a>

				<a href="../sage200/albaranes1.php" class="btn btn-outline-success btn-lg text-left"> 
					<i class="fa fa-users"></i>
				 	ALBARANES DE VENTA 
				</a>
				<a href="#" class="btn btn-outline-success btn-lg text-left"> 
				 	<i class="fa fa-book"></i>
				 	ALBARANES DE COMPRA 
				</a>
				<a href="../sage200/articles.php" class="btn btn-outline-success btn-lg text-left"> 
				 	<i class="fa fa-clipboard"></i>
				 	ARTICULOS 
				</a>
				<br>
          		<a href="<?php echo $url_site.'pages/close.php'; ?>" class="btn btn-outline-danger btn-lg text-left">
          			<i class="fa fa-times"></i> 
          			Cerrar Sesión
          		</a>
			</div>
		</div>
	</div>
</div>
<?php $con = null; ?>
<?php include '../includes/footer.inc'; ?>
