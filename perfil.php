<?php
session_start();
include('conexion.php');

// Verificar sesión
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$user = $_SESSION['user'];
$correo = $user['correo'];

// Actualización de perfil
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $carrera = $_POST['carrera'] ?? $user['carrera'];

    $uploadDir = 'uploads/documentos/' . str_replace('@', '_', $correo) . '/';
    if (!file_exists($uploadDir)) mkdir($uploadDir, 0777, true);

    $documentos = ['cedula', 'papeleta', 'matricula'];
    $rutas = [];

    foreach ($documentos as $doc) {
        if (isset($_FILES[$doc]) && $_FILES[$doc]['error'] === 0) {
            $file = $_FILES[$doc];
            $destino = $uploadDir . $doc . '_' . basename($file['name']);
            if (move_uploaded_file($file['tmp_name'], $destino)) {
                $rutas[$doc] = $destino;
            }
        } else {
            $rutas[$doc] = $user[$doc . '_path']; // mantener el anterior
        }
    }

    $stmt = $conexion->prepare("UPDATE estudiantes SET carrera = ?, cedula_path = ?, papeleta_path = ?, matricula_path = ? WHERE correo = ?");
    $stmt->bind_param("sssss", $carrera, $rutas['cedula'], $rutas['papeleta'], $rutas['matricula'], $correo);
    $stmt->execute();

    // Actualizar sesión
    $query = $conexion->prepare("SELECT * FROM estudiantes WHERE correo = ?");
    $query->bind_param("s", $correo);
    $query->execute();
    $result = $query->get_result();
    $_SESSION['user'] = $result->fetch_assoc();

    header("Location: perfil.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Mi Perfil</title>
</head>
<body>
  <h2>Perfil de Estudiante</h2>
  <p><strong>Nombre:</strong> <?= htmlspecialchars($user['nombre']) ?></p>
  <p><strong>Correo:</strong> <?= htmlspecialchars($user['correo']) ?></p>

  <form method="POST" enctype="multipart/form-data">
    <?php if (strpos($correo, '@uta.edu.ec') !== false && empty($user['carrera'])): ?>
      <label>Seleccionar carrera:</label>
      <select name="carrera" required>
        <option value="">--Seleccione--</option>
        <option value="Software">Ingeniería en Software</option>
        <option value="Electrónica">Electrónica</option>
        <option value="Industrial">Industrial</option>
      </select><br>
    <?php else: ?>
      <p><strong>Carrera:</strong> <?= htmlspecialchars($user['carrera']) ?></p>
    <?php endif; ?>

    <label>Actualizar cédula:</label>
    <input type="file" name="cedula" accept="application/pdf,image/*"><br>
    <label>Actualizar papeleta:</label>
    <input type="file" name="papeleta" accept="application/pdf,image/*"><br>
    <label>Actualizar matrícula:</label>
    <input type="file" name="matricula" accept="application/pdf,image/*"><br>

    <button type="submit">Guardar cambios</button>
  </form>

  <p><a href="mis_cursos.php">Ver mis cursos</a></p>
  <p><a href="logout.php">Cerrar sesión</a></p>
</body>
</html>
