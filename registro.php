<?php
session_start();
require("database.php");
$con = conectar();

$errores = []; // Array para almacenar mensajes de error

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $first_surname = trim($_POST['first_surname']);
    $id_gender = trim($_POST['id_gender']);
    $alias = trim($_POST['alias']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validar si los campos están vacíos
    if (empty($name) || empty($first_surname) || empty($id_gender) ||empty($alias) || empty($email) || empty($password) || empty($confirm_password)) {
        $errores[] = 'Por favor, complete todos los campos.';
    } elseif ($password !== $confirm_password) {
        $errores[] = 'Las contraseñas no coinciden.';
    } else {
        // Prevenir inyecciones SQL usando mysqli_real_escape_string
        $name = mysqli_real_escape_string($con, $name);
        $first_surname = mysqli_real_escape_string($con, $first_surname);
        $id_gender = mysqli_real_escape_string($con, $id_gender);
        $alias = mysqli_real_escape_string($con, $alias);
        $email = mysqli_real_escape_string($con, $email);

        // Verificar si el nombre de usuario o el correo ya existen
        $sql_check = "SELECT * FROM users WHERE alias = '$alias' OR email = '$email'";
        $result = mysqli_query($con, $sql_check);

        if (mysqli_num_rows($result) > 0) {
            $errores[] = 'El nombre de usuario o el correo ya están registrados.';
        }
    }

    // Si no hay errores, insertar el usuario
    if (empty($errores)) {
        // Encriptar la contraseña
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Consulta SQL para insertar el usuario
        $timestamp = date("Y-m-d H:i:s");
        $sql = "INSERT INTO users (user_name, alias, user_last_name_1, id_gender,  email, password_hash, subscription_date, id_rol)
                 VALUES ('$name', '$alias','$first_surname', '$id_gender', '$email', '$hashed_password',   '$timestamp', '2')";

        // Ejecutar el insert
        if (mysqli_query($con, $sql)) {
            // Redirigir sin alert
            header("Location: registro_exitoso.php"); // Página de registro exitoso
            exit();
        } else {
            echo "<script>alert('Error al registrar usuario: " . mysqli_error($con) . "');</script>";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/registro.css" type="text/css">
</head>
<body>

<div class="register-container">
    <h3 class="text-center">Registro</h3>
    
    <form id="registerForm" action="registro.php" method="post">
        <div class="mb-3">
            <label for="name" class="form-label">Nombre</label>
            <input type="text" name="name" class="form-control" id="name" placeholder="Ingrese su nombre">
        </div>
        <div class="mb-3">
            <label for="first_surname" class="form-label">Primer Apellido</label>
            <input type="text" name="first_surname" class="form-control" id="first_surname" placeholder="Ingrese su primer apellido">
        </div>
        <div class="mb-3">
            <label class="form-label" for="id_gender">Género</label>
                <select name="id_gender" class="form-control">
                    <?php
                        $genders = getAllGenders($con);

                        while($row = mysqli_fetch_assoc($genders)){
                            echo '<option value="' . $row['id_gender'] . '">' . $row['gender'] . '</option>';
                        };


                    ?>
                </select>

        </div>
        <div class="mb-3">
            <label for="alias" class="form-label">Alias</label>
            <input type="text" name="alias" class="form-control" id="alias" placeholder="Ingrese un nombre de usuario">
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Correo Electrónico</label>
            <input type="email" name="email" class="form-control" id="email" placeholder="Ingrese su correo">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" name="password" class="form-control" id="password" placeholder="Ingrese su contraseña">
        </div>
        <div class="mb-3">
            <label for="confirm_password" class="form-label">Confirmar Contraseña</label>
            <input type="password" name="confirm_password" class="form-control" id="confirm_password" placeholder="Repita su contraseña">
        </div>
            <!-- Mostrar los errores, si los hay -->
            <?php if (!empty($errores)): ?>
                 <div class="alert alert-danger">
                     <ul>
                        <?php foreach ($errores as $error): ?>
                            <li><?php echo $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
        <button type="submit" class="btn btn-primary w-100">Registrarse</button>
        <div class="text-center mt-3">
            <a href="index.php">¿Ya tienes cuenta? Inicia sesión</a>
        </div>
    </form>
</div>

<script src="scripts/validacion.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
