<?php
session_start();
require '../config/conexion.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$query = "
    SELECT i.id AS inscripcion_id, e.nombre AS evento, e.tipo, e.fecha_inicio, e.fecha_fin, 
           i.estado, i.comprobante_pago
    FROM inscripciones i
    JOIN eventos e ON e.id = i.evento_id
    WHERE i.usuario_id = ?
    ORDER BY e.fecha_inicio DESC";

$stmt = mysqli_prepare($conexion, $query);
mysqli_stmt_bind_param($stmt, "i", $usuario_id);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Mis Cursos Inscritos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="p-4">
    <div class="container">
        <h3>Mis Inscripciones</h3>
        <table class="table table-bordered table-hover mt-3">
            <thead class="table-dark">
                <tr>
                    <th>Evento</th>
                    <th>Tipo</th>
                    <th>Fechas</th>
                    <th>Estado</th>
                    <th>Comprobante</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($resultado)): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['evento']) ?></td>
                        <td><?= ucfirst($row['tipo']) ?></td>
                        <td><?= $row['fecha_inicio'] . " al " . $row['fecha_fin'] ?></td>
                        <td>
                            <span
                                class="badge bg-<?= $row['estado'] == 'Pagado' ? 'success' : ($row['estado'] == 'Error' ? 'danger' : 'warning') ?>">
                                <?= $row['estado'] ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($row['comprobante_pago']): ?>
                                <a href="../uploads/<?= $usuario_id ?>/evento_<?= $row['inscripcion_id'] ?>/<?= $row['comprobante_pago'] ?>"
                                    target="_blank">Ver comprobante</a>
                            <?php else: ?>
                                <span class="text-muted">No cargado</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (!$row['comprobante_pago'] || $row['estado'] == 'Error'): ?>
                                <form action="subir_comprobante.php" method="POST" enctype="multipart/form-data"
                                    class="d-flex flex-column">
                                    <input type="hidden" name="inscripcion_id" value="<?= $row['inscripcion_id'] ?>">
                                    <input type="file" name="comprobante" required class="form-control mb-1">
                                    <button class="btn btn-sm btn-primary">Subir</button>
                                </form>
                            <?php else: ?>
                                <span class="text-success">Subido</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>

</html>