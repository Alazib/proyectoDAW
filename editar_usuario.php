<?php
session_start();
require("database.php");
$con = conectar();

// Verificar si se recibió un ID por GET
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID de usuario no proporcionado.");
}

$id = $_GET['id'];
$id = mysqli_real_escape_string($con, $id); // Seguridad contra SQL Injection

// Obtener datos del usuario
$sql = "SELECT * FROM usuario WHERE id = '$id'";
$result = mysqli_query($con, $sql);
$user = mysqli_fetch_assoc($result);

// Verificar si el usuario existe
if (!$user) {
    die("Usuario no encontrado.");
}

// Procesar el formulario si se envió
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST['nombre']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $tipo = trim($_POST['tipo']);
    $password = trim($_POST['password']);

    // Validaciones básicas
    if (empty($nombre) || empty($username) || empty($email)) {
        echo "<p style='color: red;'>Todos los campos obligatorios deben estar completos.</p>";
    } else {
        // Escapar datos para evitar SQL Injection
        $nombre = mysqli_real_escape_string($con, $nombre);
        $username = mysqli_real_escape_string($con, $username);
        $email = mysqli_real_escape_string($con, $email);
        $tipo = mysqli_real_escape_string($con, $tipo);

        // Iniciar consulta de actualización
        $sql_update = "UPDATE usuario SET nombre='$nombre', username='$username', email='$email', tipo='$tipo'";

        // Si el usuario ingresó una nueva contraseña, la encriptamos y la actualizamos
        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $sql_update .= ", pass='$hashed_password'";
        }

        // Completar la consulta con la condición WHERE
        $sql_update .= " WHERE id='$id'";

        if (mysqli_query($con, $sql_update)) {
            // Redirigir de vuelta a la lista de usuarios
            header("Location: admin_page.php");
            exit();
        } else {
            echo "<p style='color: red;'>Error al actualizar el usuario: " . mysqli_error($con) . "</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center">Editar Usuario</h2>

    <form method="post">
        <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($user['nombre']); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Usuario</label>
            <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($user['username']); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Correo Electrónico</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Tipo de Usuario</label>
            <select name="tipo" class="form-control">
                <option value="0" <?= ($user['tipo'] == 0) ? 'selected' : ''; ?>>Administrador</option>
                <option value="1" <?= ($user['tipo'] == 1) ? 'selected' : ''; ?>>Usuario</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Nueva Contraseña (opcional)</label>
            <input type="password" name="password" class="form-control" placeholder="Dejar en blanco para no cambiar">
        </div>
        <button type="submit" class="btn btn-success">Guardar Cambios</button>
        <a href="admin_page.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
