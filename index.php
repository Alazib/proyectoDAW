<?php
session_start();
require("database.php");
$con = conectar();

$error_message = ""; // Variable para almacenar el mensaje de error

if (isset($_POST['login'])) {
    $alias = trim($_POST['alias']);
    $password = trim($_POST['password']);

    if (empty($alias) || empty($password)) {
        $error_message = "Por favor, complete todos los campos.";
    } else {
        $usuario = login($con, $alias, $password);

        if (empty($usuario)) {
            $error_message = "Las credenciales introducidas no son correctas.";
        } else {
            $_SESSION['logged_user'] = $usuario['id_user'];
            $_SESSION['logged_user_name'] = $usuario['user_name'];
            $_SESSION['logged_user_type'] = $usuario['id_rol'];

            if ($usuario['id_rol'] == 1) {
                header("Location: admin_dashboard.php");;
                exit();
            } else {
                header("Location: user_page.php");
                exit();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
 <link rel="stylesheet" href="styles/index.css" type="text/css">

</head>
<body>

<div class="login-container">
    <h3 class="text-center">Iniciar Sesión</h3>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <div class="mb-3">
            <label for="alias" class="form-label">Alias</label>
            <input type="text" name="alias" class="form-control" id="alias" placeholder="Ingrese su alias">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" name="password" class="form-control" id="password" placeholder="Ingrese su contraseña">
        </div>

        <?php if ($error_message != ""): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <div class="btn-group-custom">
            <button type="submit" name="login" class="btn btn-primary w-50">Iniciar Sesión</button>
            <a href="register_form.php" class="btn btn-secondary w-50">Registrarse</a>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
