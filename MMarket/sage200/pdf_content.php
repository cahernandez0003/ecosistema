<?php
require_once('TCPDF-main/tcpdf.php');
require_once('exportart.php'); 

// Crear nuevo objeto TCPDF
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

// Establecer información del documento
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nombre de tu empresa');
$pdf->SetTitle('Lista de artículos');
$pdf->SetSubject('Lista de artículos por familia');
$pdf->SetKeywords('TCPDF, PDF, export, artículos, familia');

// Establecer márgenes
$pdf->SetMargins(10, 10, 10);

// Agregar nueva página
$pdf->AddPage();

if (isset($_GET['familia'])) {
    $codigoFamilia = $_GET['familia'];
} else {
    $codigoFamilia = null;
}

// Obtener artículos de la familia seleccionada
$articulos = exportart($con, $codigoFamilia);

// Iniciar contenido del PDF
$html = '<h1>Lista de Artículos</h1>';
$html .= '<table border="1">';
$html .= '<tr><th>Código</th><th>Descripción</th><th>Imagen</th><th>Grupo IVA</th></tr>';
foreach ($articulos as $articulo) {
    $html .= '<tr>';
    $html .= '<td>' . $articulo['CodigoArticulo'] . '</td>';
    $html .= '<td>' . $articulo['DescripcionArticulo'] . '</td>';
    $html .= '<td><img src="' . $articulo['ImagenURL'] . '" width="100"></td>';
    $html .= '<td>' . $articulo['GrupoIva'] . '</td>';
    $html .= '</tr>';
}
$html .= '</table>';

// Escribir HTML en el PDF
$pdf->writeHTML($html, true, false, true, false, '');

// Nombre del archivo temporal
$tempFileName = tempnam(sys_get_temp_dir(), 'pdf_');

// Guardar el PDF en el archivo temporal
$pdf->Output($tempFileName, 'F');

// Cerrar la conexión a la base de datos
$con = null;

// Redirigir al usuario para descargar el archivo
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="lista_articulos.pdf"');
readfile($tempFileName);

// Eliminar el archivo temporal
unlink($tempFileName);
