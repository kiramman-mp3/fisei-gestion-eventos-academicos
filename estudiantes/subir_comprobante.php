<?php
session_start();
require '../conexion.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['comprobante'])) {
    $inscripcion_id = intval($_POST['inscripcion_id']);
    $archivo = $_FILES['comprobante'];
    
    // Validar tipo de archivo
    $permitidos = ['image/jpeg', 'image/png', 'image/jpg'];
    if (!in_array($archivo['type'], $permitidos)) {
        echo "Error: Formato no permitido.";
        exit;
    }

    // Crear ruta
    $directorio = "../uploads/$usuario_id/evento_$inscripcion_id/";
    if (!is_dir($directorio)) {
        mkdir($directorio, 0777, true);
    }

    $nombre_archivo = basename($archivo['name']);
    $ruta_archivo = $directorio . $nombre_archivo;

    if (move_uploaded_file($archivo['tmp_name'], $ruta_archivo)) {
        // Guardar en BD
        $query = "UPDATE inscripciones 
                  SET comprobante_pago = ?, estado = 'Esperando aprobación del admin' 
                  WHERE id = ? AND usuario_id = ?";
        $stmt = mysqli_prepare($conexion, $query);
        mysqli_stmt_bind_param($stmt, "sii", $nombre_archivo, $inscripcion_id, $usuario_id);
        mysqli_stmt_execute($stmt);

        header("Location: mis_cursos.php?mensaje=ok");
    } else {
        echo "Error al subir el archivo.";
    }
} else {
    echo "Acceso no permitido.";
}
