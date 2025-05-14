<?php
session_start();
include('conexion.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = $_POST['correo'];
    $password = $_POST['password'];

    $query = $conexion->prepare("SELECT * FROM estudiantes WHERE correo = ?");
    $query->bind_param("s", $correo);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;
            header("Location: perfil.php");
            exit;
        }
    }

    $error = "Correo o contraseña incorrectos";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Login - Eventos FISEI</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
  <div class="text-center mb-4">
    <h2 class="text-primary">Iniciar Sesión</h2>
    <p class="text-muted">Accede con tu correo institucional</p>
  </div>

  <?php if (isset($error)): ?>
    <div class="alert alert-danger text-center"><?= $error ?></div>
  <?php endif; ?>

  <form method="POST" class="card shadow p-4 mx-auto" style="max-width: 450px;">
    <div class="mb-3">
      <label class="form-label">Correo:</label>
      <input type="email" name="correo" class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Contraseña:</label>
      <input type="password" name="password" class="form-control" required>
    </div>

    <div class="d-grid">
      <button type="submit" class="btn btn-primary">Ingresar</button>
    </div>

    <div class="text-center mt-3">
      <a href="registro.php" class="text-decoration-none">¿No tienes cuenta? Regístrate aquí</a>
    </div>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
