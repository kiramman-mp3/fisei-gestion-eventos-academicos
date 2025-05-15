<?php
session_start();
require '../conexion.php';

// Validación de login
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit;
}

// Obtener eventos disponibles (por carrera o público)
$carrera_usuario = $_SESSION['carrera'];
$correo = $_SESSION['correo'];
$query = "SELECT * FROM eventos WHERE estado='abierto' AND (publico=1 OR carrera='$carrera_usuario')";
$result = mysqli_query($conexion, $query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inscripción a Eventos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="mb-4">Inscribirse a un Evento</h2>
    <form action="procesar_inscripcion.php" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="evento" class="form-label">Selecciona el evento</label>
            <select name="evento_id" class="form-select" required>
                <?php while ($evento = mysqli_fetch_assoc($result)) { ?>
                    <option value="<?= $evento['id'] ?>">
                        <?= $evento['nombre'] ?> (<?= $evento['tipo'] ?>)
                    </option>
                <?php } ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="archivos_requisitos" class="form-label">Sube los requisitos solicitados</label>
            <input type="file" class="form-control" name="requisitos[]" multiple required>
        </div>

        <div class="mb-3">
            <label for="comprobante" class="form-label">Comprobante de pago (solo si aplica)</label>
            <input type="file" class="form-control" name="comprobante">
        </div>

        <button type="submit" class="btn btn-primary">Enviar inscripción</button>
    </form>
</div>
</body>
</html>
