<?php
require '../config/app.php';
require '../config/security_admin.php';
require '../config/bd_mysql.php';
require '../config/bd.php'; 
require '../includes/header.inc';?>

<body class="home">

<style>
    td {
        text-align: center;
        vertical-align: middle;
    }
</style>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light"
    style="background-color: hsl(220,100%,90%); position: fixed; z-index: 1; height: 65px; width: 100%;">
    <img src="../public/imgs/favicon.png" style="width: 55px; height: 55px; padding: 0px; margin: 5px;">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
            <li class="nav-item active"><a class="btn btn-default" href="cartera.php">CARTERA</a>&nbsp; |</li>
            <li class="nav-item active"><a class="btn btn-default" href="albcom.php">ALBCOM</a>&nbsp; |</li>
            <li class="nav-item active"><a class="btn btn-default" href="pedicom.php">PEDICOM</a>&nbsp; |</li>
            <li class="nav-item active"><a class="btn btn-default" href="rutas.php">RUTAS</a>&nbsp; |</li>
            <li class="nav-item active"><a class="btn btn-default" href="albaranes1.php">ALBAVEN</a>&nbsp; |</li>
            <li class="nav-item active"><a class="btn btn-default" href="index.php">VOLVER</a>&nbsp; |</li>
            <li class="nav-item active"><a class="btn btn-default"
                    href="<?php echo $url_site.'pages/close.php'; ?>"><i class="fa fa-times"></i>
                    Cerrar Sesión
                </a>&nbsp; |</li>
        </ul>
    </div>
</nav>
<br>
<br>
<br>
<br>
<div class="container">
    <div class="row">
        <!-- Tabla de artículos -->
        <table id="articulosTable" class="table table-striped">
        <!-- Encabezados de la tabla -->
            <thead>
                <tr>
                    <th style="text-align: center;"> Codigo_Art </th>
                    <th style="text-align: center;"> Descripción</th>
                    <th style="text-align: center;"> TipoIva</th>
                    <th style="display: none;"> CodigoFamilia </th>
                    <th> Imagen</th>

                </tr>
            </thead>
            <tbody>
                <?php
                // Obtener la familia seleccionada (cámbialo según cómo obtienes este valor)
                $codigoFamiliaSeleccionada = isset($_GET['codigoFamilia']) ? $_GET['codigoFamilia'] : null;

                // Obtener la lista de artículos según la familia seleccionada
                $lstar = listacatalogo($con);

                foreach ($lstar as $arow):
                ?>
                    <tr>
                        <!-- Datos de la fila... -->
                        <td> <?php echo $arow['CodigoArticulo']; ?></td>
                        <td> <?php echo $arow['DescripcionArticulo']; ?></td>
                      
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
                                default:
                                    echo 'No definido';
                                    break;
                            }
                            ?>
                        </td>  
                        <td style="display: none;"> <?php echo $arow['CodigoFamilia']; ?></td>
                        <td>
                            <img src="../<?php echo $arow['ImagenURL']; ?>" alt="Imagen del artículo"
                                style="width: 150px; height: 150px;">
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        // Inicialización del DataTable
        $('#articulosTable').DataTable({
            "paging": true,
            "lengthMenu": [10, 20, 50, 100],
            "searching": true
        });
    });
</script>

<?php
// Cerrar la conexión a la base de datos
$con = null;

// Incluir el pie de página de forma oculta
?>
<div style="display:none;"> <?php include '../includes/footer.inc'; ?> </div>
</body>
</html>