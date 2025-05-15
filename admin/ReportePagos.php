<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}
require '../conexion.php';
$result = mysqli_query($conn, "SELECT e.nombre AS evento, est.nombre AS estudiante, p.monto, p.estado FROM pagos p JOIN eventos e ON p.id_evento = e.id JOIN estudiantes est ON p.id_estudiante = est.id");
echo "<h2>Reporte de Pagos</h2><table border='1'><tr><th>Evento</th><th>Estudiante</th><th>Monto</th><th>Estado</th></tr>";
while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr><td>{$row['evento']}</td><td>{$row['estudiante']}</td><td>\${$row['monto']}</td><td>{$row['estado']}</td></tr>";
}
echo "</table>";
?>