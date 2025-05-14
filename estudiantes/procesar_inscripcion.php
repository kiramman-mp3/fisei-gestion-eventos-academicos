<?php
session_start();
require '../conexion.php';

// Validación de login
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit;
}

// Validar que se haya enviado el formulario
$usuario_id = $_SESSION['usuario_id'];
$evento_id = $_POST['evento_id'];
$estado = "En espera de orden de pago";

// Crear carpeta de subida si no existe
$dir_base = "../uploads/$usuario_id/evento_$evento_id";
if (!file_exists($dir_base)) {
    mkdir($dir_base, 0777, true);
}

// Guardar requisitos
$requisitos_guardados = true;
foreach ($_FILES['requisitos']['tmp_name'] as $key => $tmp_name) {
    $nombre_archivo = basename($_FILES['requisitos']['name'][$key]);
    $ruta_destino = "$dir_base/$nombre_archivo";
    if (!move_uploaded_file($tmp_name, $ruta_destino)) {
        $requisitos_guardados = false;
    }
}

// Guardar comprobante (si existe)
$comprobante_nombre = null;
if (!empty($_FILES['comprobante']['name'])) {
    $comprobante_nombre = basename($_FILES['comprobante']['name']);
    $ruta_comprobante = "$dir_base/$comprobante_nombre";
    if (move_uploaded_file($_FILES['comprobante']['tmp_name'], $ruta_comprobante)) {
        $estado = "Esperando aprobación del admin";
    } else {
        $estado = "Error en carga de comprobante";
    }
}

// Insertar inscripción
$query = "INSERT INTO inscripciones (usuario_id, evento_id, estado, comprobante_pago, fecha_inscripcion)
          VALUES (?, ?, ?, ?, NOW())";

$stmt = mysqli_prepare($conexion, $query);
mysqli_stmt_bind_param($stmt, "iiss", $usuario_id, $evento_id, $estado, $comprobante_nombre);
mysqli_stmt_execute($stmt);

if (mysqli_stmt_affected_rows($stmt) > 0 && $requisitos_guardados) {
    echo "<script>alert('Inscripción registrada correctamente.'); window.location.href='mis_cursos.php';</script>";
} else {
    echo "<script>alert('Error al registrar inscripción.'); window.history.back();</script>";
}
