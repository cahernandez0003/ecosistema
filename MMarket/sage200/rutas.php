<?php require '../config/app.php';?>
<?php require '../config/security_admin.php';?>
<?php require '../config/bd_mysql.php';?>
<?php require '../config/bd.php';?>
<?php require '../includes/header.inc';?>

<body class="home">
	<style>
		li{
			list-style-type: none;
			font-size: 16px;
		}
		img{
			width: 150px;
			height: 150px;
		}
		div{
			border:1px solid black;
		}
		.derecho{
			border: 1px solid black; 
			text-align: right;
		}
		h4{
			text-align: center;
		}
	</style>
	<nav class="navbar navbar-expand-lg" style="background-color: hsl(220,100%,90%); position: fixed; z-index: 1; height: 65px; width: 100%;"><img src="../public/imgs/favicon.png" style="width: 55px; height: 55px; padding: 0px; margin: 20px;">


        <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
            <li class="nav-item active"><a class="btn btn-default" href="cartera.php">CARTERA</a>&nbsp; |</li>
            <li class="nav-item active"><a class="btn btn-default" href="albcom.php">ALBCOM</a>&nbsp; |</li>
            <li class="nav-item active"><a class="btn btn-default" href="albaranes1.php">ALBAVEN</a>&nbsp; |</li>
            <li class="nav-item active"><a class="btn btn-default" href="articles.php">ARTÍCULOS</a>&nbsp; |</li>
            <li class="nav-item active"><a class="btn btn-default" href="index.php">VOLVER</a>&nbsp; |</li>
            <li class="nav-item active"><a class="btn btn-default" href="<?php echo $url_site.'pages/close.php';?>"><i class="fa fa-times"></i> 
                    Cerrar Sesión
                </a>&nbsp; |</li>
        </ul>
    </nav>
    <br><br><br>	
        	<h4>GESTIÓN DE RUTAS</h4>
    <div class="container">
       <div class="row">
       	<div class="col-md-2">
       		<img src="../public/imgs/favicon.png" alt=""></div>
       	<div class="col-md-5">
       		<li>MIMOUN MARKET SL</li>
       		<li>CIF: B95829370</li>
       		<li>Polígono Artunduaga 4, Pab-3</li>
       		<li>48970 Basauri</li>
       		<li>Bizkaia</li>
       		<li>681392805</li>
       		<li>info@mimounmarket.com</li>
       	</div>
       	<!-- <div class="col-md-2"></div> -->
       	<div class="col-md-5 derecho">
       		<li>LOGISTICA AYADI Y OTROS SC</li>
       		<li>CIF: X8242239M</li>
       		<li>Calle Fontuso P4 1B</li>
       		<li>600758080</li>
       		<li>48980 Santurtzi</li>
       		<li>Bizkaia</li>
       		<li>ayadim384@gmail.com</li>
       	</div>
       	<div class="">
       	<h4 style="margin-top: 10px;">ALBARÁN DE SALIDA MERCANCÍA</h4>
       	</div>
       </div> 
       
    </div>
</body>