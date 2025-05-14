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

    echo "Registro exitoso. <a href='login.php'>Iniciar sesión</a>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registro Estudiante</title>
  <link rel="stylesheet" href="css/styles.css">
</head>
<body>
  <h2>Registro de Estudiante</h2>
  <form method="POST" enctype="multipart/form-data" onsubmit="return validarCorreo()">
    <label>Nombre completo:</label>
    <input type="text" name="nombre" required><br>

    <label>Correo institucional (@uta.edu.ec):</label>
    <input type="email" name="correo" id="correo" required><br>

    <label>Contraseña:</label>
    <input type="password" name="password" required><br>

    <div id="carreraSelect" style="display:none;">
      <label>Carrera:</label>
      <select name="carrera" required>
        <option value="">--Seleccione--</option>
        <option value="Software">Ingeniería en Software</option>
        <option value="Electrónica">Electrónica</option>
        <option value="Industrial">Industrial</option>
        <!-- Agregar más carreras si es necesario -->
      </select><br>
    </div>

    <label>Subir cédula (PDF o imagen):</label>
    <input type="file" name="cedula" accept="application/pdf,image/*" required><br>

    <label>Subir papeleta de votación:</label>
    <input type="file" name="papeleta" accept="application/pdf,image/*" required><br>

    <label>Subir certificado de matrícula:</label>
    <input type="file" name="matricula" accept="application/pdf,image/*" required><br>

    <button type="submit">Registrarse</button>
  </form>

  <script>
    function validarCorreo() {
      const correo = document.getElementById('correo').value;
      const carreraDiv = document.getElementById('carreraSelect');
      if (correo.includes('@uta.edu.ec')) {
        carreraDiv.style.display = 'block';
        return true;
      } else {
        carreraDiv.style.display = 'none';
        return true;
      }
    }
    document.getElementById('correo').addEventListener('input', validarCorreo);
  </script>
</body>
</html>
