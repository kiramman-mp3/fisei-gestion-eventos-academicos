<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}
?>
<h1>Bienvenido Administrador</h1>
<ul>
    <li><a href="crearEvento.php">Crear Curso/Evento</a></li>
    <li><a href="subirRequisitos.php">Subir o Editar Requisitos</a></li>
    <li><a href="notasAsistencia.php">Registrar Notas y Asistencia</a></li>
    <li><a href="validarPagos.php">Validar Pagos</a></li>
</ul>