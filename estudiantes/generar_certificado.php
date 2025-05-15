<?php
require_once("../conexion.php");
require_once("../libs/fpdf/fpdf.php");

session_start();
$usuario_id = $_SESSION['usuario_id'];
$inscripcion_id = $_GET['id'];

// Validar condiciones
$consulta = $conn->query("SELECT i.*, e.nombre_evento, u.nombre_completo FROM inscripciones i 
JOIN eventos e ON e.id = i.evento_id 
JOIN usuarios u ON u.id = i.usuario_id 
WHERE i.id = $inscripcion_id AND i.usuario_id = $usuario_id 
AND i.estado = 'Pagado' AND i.nota >= 8 AND i.asistencia >= 80");

if ($consulta->num_rows === 0) {
    die("No cumple con los requisitos para obtener el certificado.");
}

$datos = $consulta->fetch_assoc();

// Generar certificado
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,10,'CERTIFICADO DE PARTICIPACIÓN',0,1,'C');

$pdf->SetFont('Arial','',12);
$pdf->Ln(10);
$pdf->MultiCell(0,10,"Se certifica que el estudiante: {$datos['nombre_completo']}\n
Ha participado en el evento: {$datos['nombre_evento']}\n
Con una nota de {$datos['nota']} y una asistencia del {$datos['asistencia']}%\n
Fecha: del {$datos['fecha_inicio']} al {$datos['fecha_fin']}", 0, 'L');

$pdf->Output();
