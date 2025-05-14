<?php
include('conexion.php');
session_start();

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
    $error = "Credenciales incorrectas";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Login - FISEI Eventos</title>
</head>
<body>
  <h2>Iniciar sesión</h2>

  <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

  <form method="POST">
    <label>Correo:</label>
    <input type="email" name="correo" required><br>

    <label>Contraseña:</label>
    <input type="password" name="password" required><br>

    <button type="submit">Ingresar</button>
  </form>

  <p>¿No tienes cuenta? <a href="registro.php">Regístrate</a></p>
</body>
</html>
