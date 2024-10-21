<?php
require '../config/app.php';
require '../config/security_admin.php';
require '../config/bd_mysql.php';
require '../config/bd.php';
require '../includes/header.inc';

// Verificar si se ha seleccionado una familia
$codigoFamilia = isset($_GET['familia']) ? $_GET['familia'] : null;

// Llamar a la función exportart() para obtener la lista de artículos
$lstar = exportart($con, $codigoFamilia);

?>

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
            <!-- Selector de familia -->
            <form method="GET" action="exportart.php">
                <label for="familia">Selecciona una familia:</label>
                <select name="familia" id="familia">
                    <option value="">Seleccione una familia</option>
                    <option value="AL01">CHOCLITOS</option>
                    <option value="AR">Arroz en sacos</option>
                    <option value="BA">Bazar</option>
                    <option value="CA">CALDOS</option>
                    <option value="CH">Bombones & chocolates</option>
                    <option value="CN">Conservas en cubo/ lata</option>
                    <option value="CO">Cocina & repostería</option>
                    <option value="DU">Dulces & aperitivos</option>
                    <option value="EM">Embutidos</option>
                    <option value="ES">Especias en bote/bolsa</option>
                    <option value="FP">Fideos & pasta/spaguetti</option>
                    <option value="FS">Frutos secos en bolsa/saco</option>
                    <option value="GA">Galletas & polvorones</option>
                    <option value="HA">Harina</option>
                    <option value="LAT">LATINOS</option>
                    <option value="LE">Legumbres en paquete/bote</option>
                    <option value="NM">Especias en bote/bolsa Noraia Market</option>
                    <option value="PA">PALETS</option>
                    <option value="PB">Productos Bolivianos</option>
                    <option value="PC">Productos de cámara Noraia Market</option>
                    <option value="PL">Productos lacteos/camara</option>
                    <option value="PP">FRUTAS Y VERDURAS</option>
                    <option value="RE">RESIDUOS CARNICOS</option>
                    <option value="SA">Salsas aceites & vinagres</option>
                    <option value="TE">The en hojas / grano / polvo</option>
                    <option value="TR">TRANSPORTE</option>
                    <option value="VA">VARIOS</option>
                    <option value="ZR">Zumos y refrescos</option>
                </select>
                <button type="submit">Mostrar Artículos</button>
            </form>

            <br>
            <br>

            <!-- Tabla de artículos -->
            <table id="articulosTable" class="table table-striped">
                <!-- Encabezados de la tabla -->
                <thead>
                    <tr>
                        <th style="text-align: center;"> Codigo_Art </th>
                        <th style="text-align: center;"> Descripción</th>
                        <th style="text-align: center;"> TipoIva</th>
                        <th style=""> CodigoFamilia </th>
                        <th> Imagen</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
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
                        <td style=""> <?php echo $arow['CodigoFamilia']; ?></td>
                        <td>
                            <img src="../<?php echo $arow['ImagenURL']; ?>" alt="Imagen del artículo"
                                style="width: 150px; height: 150px;">
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="container">
                <div class="row">
                    <!-- Botón de exportación -->
                    <a href="pdf_content.php?familia=<?php echo $codigoFamilia; ?>" class="btn btn-primary"
                        target="_blank">Exportar a PDF</a>
                </div>
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

    <div style="display: none;"><?php
    // Incluir el pie de página de forma oculta
    include '../includes/footer.inc';
    ?></div>

</body>

</html>
