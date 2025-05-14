<?php
include('conexion.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $carrera = isset($_POST['carrera']) ? $_POST['carrera'] : null;

    $uploadDir = 'uploads/documentos/' . str_replace('@', '_', $correo) . '/';
    if (!file_exists($uploadDir)) mkdir($uploadDir, 0777, true);

    $documentos = ['cedula', 'papeleta', 'matricula'];
    $rutas = [];

    foreach ($documentos as $doc) {
        $file = $_FILES[$doc];
        $destino = $uploadDir . $doc . '_' . basename($file['name']);
        if (move_uploaded_file($file['tmp_name'], $destino)) {
            $rutas[$doc] = $destino;
        } else {
            echo "Error subiendo $doc";
            exit;
        }
    }

    $stmt = $conexion->prepare("INSERT INTO estudiantes (nombre, correo, password, carrera, cedula_path, papeleta_path, matricula_path) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $nombre, $correo, $password, $carrera, $rutas['cedula'], $rutas['papeleta'], $rutas['matricula']);
    $stmt->execute();

    echo "<div class='alert alert-success text-center'>Registro exitoso. <a href='login.php'>Iniciar sesión</a></div>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registro Estudiante</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
  <h2 class="mb-4 text-center text-primary">Registro de Estudiante</h2>

  <form method="POST" enctype="multipart/form-data" class="card p-4 shadow" onsubmit="return validarCorreo()">
    <div class="mb-3">
      <label class="form-label">Nombre completo:</label>
      <input type="text" name="nombre" class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Correo institucional (@uta.edu.ec):</label>
      <input type="email" name="correo" id="correo" class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Contraseña:</label>
      <input type="password" name="password" class="form-control" required>
    </div>

    <div class="mb-3" id="carreraSelect" style="display:none;">
      <label class="form-label">Carrera:</label>
      <select name="carrera" class="form-select">
        <option value="">--Seleccione--</option>
        <option value="Software">Ingeniería en Software</option>
        <option value="Electrónica">Electrónica</option>
        <option value="Industrial">Industrial</option>
      </select>
    </div>

    <div class="mb-3">
      <label class="form-label">Subir cédula (PDF o imagen):</label>
      <input type="file" name="cedula" class="form-control" accept="application/pdf,image/*" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Subir papeleta de votación:</label>
      <input type="file" name="papeleta" class="form-control" accept="application/pdf,image/*" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Subir certificado de matrícula:</label>
      <input type="file" name="matricula" class="form-control" accept="application/pdf,image/*" required>
    </div>

    <div class="d-grid">
      <button type="submit" class="btn btn-primary">Registrarse</button>
    </div>
  </form>
</div>

<script>
function validarCorreo() {
  const correo = document.getElementById('correo').value;
  const carreraDiv = document.getElementById('carreraSelect');
  if (correo.includes('@uta.edu.ec')) {
    carreraDiv.style.display = 'block';
  } else {
    carreraDiv.style.display = 'none';
  }
  return true;
}
document.getElementById('correo').addEventListener('input', validarCorreo);
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
