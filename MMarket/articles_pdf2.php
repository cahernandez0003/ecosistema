<?php
ini_set('memory_limit', '1024M');
ob_start();
require 'config/app.php';
include_once 'config/bd_mysql.php';
include_once 'config/bd.php';
include 'includes/header.inc';
include 'includes/navbar_pdf.inc';
require('fpdf186/fpdf.php');

// Comprobar si se han enviado subfamilias seleccionadas
$familias = isset($_GET['familias']) ? $_GET['familias'] : [];

if (empty($familias)) {
?>
    <div class="container">
        <br>
        <h1 class="text-center"><i class="fas fa-book"></i>&nbsp;Generar Catálogo de Artículos</h1>
        <form method="GET" action="articles_pdf.php" target="_blank">
            <label for="familia">Seleccionar Subfamilias:</label>
            <select name="familias[]" id="familia" multiple size="5">
                <option value="">Todas</option>
                <?php
                // Consulta para obtener todas las subfamilias
                $queryFamilia = "SELECT DISTINCT a.CodigoSubfamilia, b.Descripcion 
                                 FROM Articulos a 
                                 LEFT JOIN Familias b ON b.CodigoSubfamilia = a.CodigoSubfamilia
                                 ORDER BY b.Descripcion ASC";
                $stmtFamilia = $con->prepare($queryFamilia);
                $stmtFamilia->execute();
                $resultFamilia = $stmtFamilia->fetchAll(PDO::FETCH_ASSOC);

                // Llenar el select con las subfamilias
                if ($resultFamilia) {
                    foreach ($resultFamilia as $rowFamilia) {
                        echo '<option value="' . $rowFamilia['CodigoSubfamilia'] . '">' . $rowFamilia['Descripcion'] . '</option>';
                    }
                } else {
                    echo '<option value="">No hay subfamilias disponibles</option>';
                }
                ?>
            </select>
            <button type="submit" class="btn btn-primary">Generar PDF</button>
        </form>
    </div>
<?php
} else {
    // Si se seleccionan subfamilias
    if (in_array('', $familias)) {
        // Si selecciona "Todas", obtener todos los artículos
        $query = "SELECT CodigoArticulo, DescripcionArticulo FROM Articulos WHERE ObsoletoLc = 0 AND TipoArticulo = 'M'";
        $stmt = $con->prepare($query);
        $stmt->execute();
    } else {
        // Si seleccionan subfamilias específicas, filtrar por las seleccionadas
        $placeholders = implode(',', array_fill(0, count($familias), '?'));
        $query = "SELECT CodigoArticulo, DescripcionArticulo 
                  FROM Articulos 
                  WHERE CodigoSubfamilia IN ($placeholders) AND ObsoletoLc = 0 AND TipoArticulo = 'M'";
        $stmt = $con->prepare($query);
        $stmt->execute($familias);
    }

    $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Obtener el nombre de la primera subfamilia seleccionada para el título
    $familia = $familias[0] ?? '';  // En caso de seleccionar "Todas", podemos manejar de forma especial.
    $queryFamiliaNombre = "SELECT Descripcion FROM Familias WHERE CodigoSubfamilia = :familia";
    $stmtFamiliaNombre = $con->prepare($queryFamiliaNombre);
    $stmtFamiliaNombre->bindParam(':familia', $familia);
    $stmtFamiliaNombre->execute();
    $familia_nombre = $stmtFamiliaNombre->fetchColumn() ?? 'Todas';

    class CatalogPDF extends FPDF
    {
        function Header()
        {
            global $familia_nombre;
            // Agregar el logo de Mimoun Market SL en la esquina superior izquierda
            $this->Image('public/imgs/favicon.png', 10, 6, 30);
            $this->SetFont('Arial', 'B', 12);
            $this->Cell(0, 20, 'CATALOGO FAMILIA ' . strtoupper($familia_nombre) . ' MIMOUN MARKET SL', 0, 1, 'C');
            $this->Ln(10); // Mover hacia abajo para dar espacio al logo
        }

        function Footer()
        {
            $this->SetY(-15);
            $this->SetFont('Arial', 'I', 8);
            $this->Cell(0, 10, 'Pagina ' . $this->PageNo(), 0, 0, 'C');
        }

        function AddArticles($articles)
        {
            $this->SetFont('Arial', '', 10);
            $x = 10;
            $y = 40; // Empezamos después del logo
            $counter = 0;

            foreach ($articles as $article) {
                $codigoArticulo = $article['CodigoArticulo'];
                $imagePath = "public/imgs/img_art/{$codigoArticulo}/{$codigoArticulo}.jpg";

                if (file_exists($imagePath) && mime_content_type($imagePath) === 'image/jpeg') {
                    $this->Image($imagePath, $x, $y, 40, 40);
                } else {
                    $this->Rect($x, $y, 40, 40);
                    $this->SetXY($x, $y + 45);
                    $this->Cell(40, 5, "Imagen no disponible", 0, 0, 'C');
                }

                $this->SetXY($x, $y + 45);
                $this->Cell(40, 5, $article['CodigoArticulo'], 0, 0, 'C');
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
                    $y = 40; // Reiniciar el y después de agregar una nueva página
                }
            }
        }
    }

    if (!empty($articles)) {
        $pdf = new CatalogPDF();
        $pdf->AddPage();
        $pdf->AddArticles($articles);

        ob_end_clean();
        $pdf->Output('I', 'catalogo_' . $familia_nombre . '.pdf');
    } else {
        echo "<div class='alert alert-warning text-center'>No hay artículos para la(s) subfamilia(s) seleccionada(s).</div>";
    }
}
?>
