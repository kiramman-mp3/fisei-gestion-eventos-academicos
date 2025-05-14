<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}
?>
<form method="POST" action="procesarEvento.php">
    <input type="text" name="nombre" placeholder="Nombre del evento" required><br>
    <select name="tipo">
        <option value="curso">Curso</option>
        <option value="evento">Evento</option>
    </select><br>
    <input type="date" name="fecha_inicio" required><br>
    <input type="date" name="fecha_fin" required><br>
    <input type="number" name="cupos" placeholder="Número de cupos" required><br>
    <textarea name="requisitos_texto" placeholder="Requisitos generales..."></textarea><br>
    <select name="pago">
        <option value="gratis">Gratis</option>
        <option value="pagado">Pagado</option>
    </select><br>
    <button type="submit">Crear Evento</button>
</form>
