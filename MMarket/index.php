<?php require 'config/app.php';?>
<?php include 'config/bd.php';?>
<?php include 'config/bd_mysql.php';?>
<?php include 'includes/header.inc'; ?>
<?php include 'includes/navbar.inc'; ?>

<div class="container text-center">
	<div class="row">
		<div class="col-md-12">
			<hr>
			<br>
			<?php include 'pages/home.php'; ?>
		</div>
	</div>
</div>
<?php $con = null; ?>
<div style="display:none">
	<?php include 'includes/footer.inc'; ?>
</div>