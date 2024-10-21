<?php
ini_set('memory_limit', '1024M'); // para ayudarme con la ram para cargar las imágenes sin problema
ob_start();
require 'config/app.php';
include_once 'config/bd_mysql.php'; // este include es para cuando sea necesario el login x usuario
include_once 'config/bd.php';
include 'includes/header.inc';
include 'includes/navbar_pdf.inc'; //este navbar solo es para poder volver al inicio sin alterar la vista
require('fpdf186/fpdf.php');

// compruebo el envío del parámetro pero cuando esté vacío muestra todo el catálogo
$familia = isset($_GET['familia']) ? $_GET['familia'] : '';

if (!$familia) {
?>
    <div class="container">
        <br>
        <h1 class="text-center"><i class="fas fa-book"></i>&nbsp;Generar Catálogo de Artículos</h1>
        <form method="GET" action="articles_pdf.php" target="_blank">
            <label for="familia">Seleccionar Familia:</label>
            <select name="familia" id="familia">
                <option value="all">Todo el catálogo</option>
                <?php
                // consulta para familias
                $queryFamilia = "SELECT DISTINCT CodigoFamilia, Descripcion 
                                 FROM Vis_Familias 
                                 WHERE CodigoEmpresa=1
                                 ORDER BY Descripcion ASC";
                $stmtFamilia = $con->prepare($queryFamilia);
                $stmtFamilia->execute();
                $resultFamilia = $stmtFamilia->fetchAll(PDO::FETCH_ASSOC);

                // para llenar el select con las familias
                foreach ($resultFamilia as $rowFamilia) {
                    echo '<option value="' . $rowFamilia['CodigoFamilia'] . '">' . $rowFamilia['Descripcion'] . '</option>';
                }
                ?>
            </select>
            <button type="submit" class="btn btn-primary">Generar PDF</button>
        </form>
    </div>
<?php
} else {
    // cuando la opción es "Todo el catálogo"
    if ($familia === 'all') {
        $tituloCatalogo = 'CATALOGO MIMOUN MARKET SL';
    } else {
        // para el nombre de la familia que se selecciona 
        $queryFamiliaNombre = "SELECT Descripcion FROM Vis_Familias WHERE CodigoEmpresa=1 AND CodigoFamilia = :familia";
        $stmtFamiliaNombre = $con->prepare($queryFamiliaNombre);
        $stmtFamiliaNombre->bindParam(':familia', $familia);
        $stmtFamiliaNombre->execute();
        $familia_nombre = $stmtFamiliaNombre->fetchColumn();
        $tituloCatalogo = ' FAMILIA ' . strtoupper($familia_nombre) . ' MIMOUN MARKET SL';
    }

    class CatalogPDF extends FPDF
{
    function AddCoverPage()
    {
        // Agregar una página nueva que será la portada
        $this->AddPage();
        // Insertar la imagen de portada ocupando toda la página
        $this->Image('public/imgs/PORTADA.png', 0, 0, $this->GetPageWidth(), $this->GetPageHeight());
    }

    function Header()
    {
        global $tituloCatalogo;
        if ($this->PageNo() == 1) {
            return; // No se agrega el encabezado en la primera página (carátula)
        }
        
        $this->Image('public/imgs/favicon.png', 10, 6, 30);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(0, 20, $tituloCatalogo, 0, 1, 'C');
        $this->Ln(10); // Mover hacia abajo para dar espacio al logo

        // Dibujar una línea horizontal (equivalente a <hr>)
        $this->Line(10, $this->GetY(), 200, $this->GetY()); // Línea horizontal desde x=10 hasta x=200
        $this->Ln(5); // Agregar espacio después de la línea
    }

    function Footer()
    {
        if ($this->PageNo() == 1) {
            return; // No pie de página en la carátula
        }

        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Pagina ' . $this->PageNo(), 0, 0, 'C');
    }

    function AddArticles($articles)
    {
        $this->SetFont('Arial', '', 10); // Fuente para el código del artículo
        $x = 10;
        $y = 40; // aquí hago un salto para comenzar después del logo, no modificar para que se mantenga la presentación
        $counter = 0;

        foreach ($articles as $article) {
            $codigoArticulo = $article['CodigoArticulo'];
            $imagePath = "public/imgs/img_art/{$codigoArticulo}/{$codigoArticulo}.jpg";

            if (file_exists($imagePath) && mime_content_type($imagePath) === 'image/jpeg') {
                $this->Image($imagePath, $x, $y, 40, 40);
            } else {
                $this->Rect($x, $y, 40, 40); // se dibuja rectángulo para la imagen no disponible
                $this->SetXY($x, $y + 45);
                $this->Cell(40, 5, "", 0, 0, 'C'); // Crear una celda vacía, sin texto cuando no hay imagen, no modificar o se remonta encima del artículo que hay debajo
            }

            $this->SetFont('Arial', '', 8); //cambio el tamaño de la letra y me sirve en descripcionarticulo
            $this->SetXY($x, $y + 45);
            $this->Cell(40, 5, $article['CodigoArticulo'], 0, 0, 'C'); 

            $this->SetFont('Arial', '', 8); 
            $this->SetXY($x, $y + 50);
            $this->MultiCell(40, 5, $article['DescripcionArticulo'], 0, 'C'); 

            $x += 50;

            if (++$counter % 4 == 0) {
                $x = 10;
                $y += 60;
            }

            if ($counter % 12 == 0) {
                $this->AddPage();
                $x = 10;
                $y = 40; // para el reinicio después de agregar una nueva página
            }

            // Restaurar la fuente a 10 puntos para los siguientes artículos
            $this->SetFont('Arial', '', 10);
        }
    }
}


    // cuando la selección es "Todo el catálogo", el sql que sigue es para seleccionar todos los artículos
    if ($familia === 'all') {
        $query = "SELECT DISTINCT CodigoArticulo, DescripcionArticulo
                  FROM Articulos 
                  WHERE CodigoEmpresa=1 AND ObsoletoLc=0 AND TipoArticulo='M'";
    } else {
        // esta es para mostrar todos los artículos filtrados por la familia seleccionada
        $query = "SELECT DISTINCT CodigoArticulo, DescripcionArticulo
                  FROM Articulos 
                  WHERE CodigoEmpresa=1 AND ObsoletoLc=0 AND TipoArticulo='M' AND CodigoFamilia = :familia";
    }

    $stmt = $con->prepare($query);

    if ($familia !== 'all') {
        $stmt->bindParam(':familia', $familia);
    }

    $stmt->execute();
    $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($articles)) {
        $pdf = new CatalogPDF();
        $pdf->AddCoverPage(); // para agregar la carátula
        $pdf->AddPage(); // para agregar la primera página de artículos
        $pdf->AddArticles($articles);

        ob_end_clean();
        $nombreArchivo = ($familia === 'all') ? 'catalogo_mimoun_market_sl.pdf' : 'catalogo_' . $familia_nombre . '.pdf';
        $pdf->Output('I', $nombreArchivo);
    } else {
        echo "<div class='alert alert-warning text-center'>No hay artículos para la familia seleccionada.</div>";
    }
}
?>
