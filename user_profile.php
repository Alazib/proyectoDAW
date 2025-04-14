<?php
session_start();
require("database.php");
$con = conectar();


// Verificar si se recibió un ID por GET
if (!isset($_GET['id']) || empty($_GET['id'])) {

    die("ID de usuario no proporcionado.");
}

// Obtener id del usuario a través de la URL
$id_user = $_GET['id'];
$id_user = mysqli_real_escape_string($con, $id_user); // Seguridad contra SQL Injection


// Obtener datos del usuario
$user = getUser($con, $id_user);


$subscription_date = $user['subscription_date'];

$datetime1 = new DateTime($subscription_date);
$datetime2 = new DateTime();
$interval = $datetime1->diff($datetime2);

$time_elapsed = $interval->y . " años, " . $interval->m . " meses y " . $interval->d . " días";

// Verificar si el usuario existe
if (!$user) {
    die("Usuario no encontrado.");
}

// Procesar el formulario si se envió
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $alias = trim($_POST['alias']);
    $user_name = trim($_POST['user_name']);
    $user_last_name_1 = trim($_POST['user_last_name_1']);
    $user_last_name_2 = trim($_POST['user_last_name_2']);
    $id_gender = trim($_POST['id_gender']);
    $id_country = trim($_POST['id_country']);
    $user_birth_date = trim($_POST['user_birth_date']);

    $email = trim($_POST['email']);
    $id_rol = trim($_POST['id_rol']);
    $password = trim($_POST['password']);

    // Validaciones básicas
    if (empty($user_name) || empty($user_last_name_1) || empty($alias) || empty($email) || empty($id_rol)) {
        echo "<p style='color: red;'>Todos los campos NO opcionales deben estar completos.</p>";
    } else {
        // Escapar datos para evitar SQL Injection
        $alias = mysqli_real_escape_string($con, $alias);
        $user_name = mysqli_real_escape_string($con, $user_name);
        $user_last_name_1 = mysqli_real_escape_string($con, $user_last_name_1);
        $user_last_name_2 = mysqli_real_escape_string($con, $user_last_name_2);
        $id_gender = mysqli_real_escape_string($con, $id_gender);
        $id_country = mysqli_real_escape_string($con, $id_country);
        $user_birth_date = mysqli_real_escape_string($con, $user_birth_date);

        $email = mysqli_real_escape_string($con, $email);
        $id_rol = mysqli_real_escape_string($con, $id_rol);
        $password = mysqli_real_escape_string($con, $password);


        // Iniciar consulta de actualización
        $sql_update = "UPDATE users SET alias='$alias', 
                                        user_name='$user_name', 
                                        user_last_name_1='$user_last_name_1', 
                                        user_last_name_2='$user_last_name_2',
                                        id_gender='$id_gender',
                                        id_country='$id_country',
                                        user_birth_date='$user_birth_date',
                                        email='$email',
                                        id_rol='$id_rol' 
                                        ";

        // Si el usuario ingresó una nueva contraseña, la encriptamos y la actualizamos
        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $sql_update .= ", password_hash='$hashed_password'";
        }

        // Completar la consulta con la condición WHERE
        $sql_update .= " WHERE id_user='$id_user'";

        if (update($con, $sql_update)) {
            // Redirigir de vuelta a la lista de usuarios
            header("Location: admin_dashboard.php");
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
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">Proyecto</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active" href="index.php">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link" href="libros.php">Libros</a></li>
                    <li class="nav-item"><a class="nav-link" href="autores.php">Autores</a></li>
                    <li class="nav-item"><a class="nav-link" href="ranking.php">Ranking</a></li>
                    <li class="nav-item"><a class="nav-link" href="wishlist.php">Wishlist</a></li>
                    <li class="nav-item">
                        <a class="nav-link" href="user_profile.php?id=<?php echo $id_user; ?>">
                            Perfil de <?php echo htmlspecialchars($user['alias']); ?>
                        </a>
                    </li>


                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="text-center"><?= htmlspecialchars($user['user_name']); ?> <?= htmlspecialchars($user['user_last_name_1']); ?></h2>

        <div class="mb-3 text-center">
            <p><strong>Suscrito desde:</strong> <?= $subscription_date ?> (hace <?= $time_elapsed ?>)</p>
        </div>

        <form method="post">

            <div class="mb-3" for="alias">
                <label class="form-label"><strong>Alias</strong></label>
                <input type="text" name="alias" class="form-control" value="<?= htmlspecialchars($user['alias']); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label" for='user_name'><strong>Nombre</strong></label>
                <input type="text" name="user_name" class="form-control" value=<?= htmlspecialchars($user['user_name']); ?> required>
            </div>

            <div class="mb-3" for="user_last_name_1">
                <label class="form-label"><strong>Primer apellido</strong></label>
                <input type="text" name="user_last_name_1" class="form-control" value="<?= htmlspecialchars($user['user_last_name_1']); ?>" required>
            </div>

            <div class="mb-3" for="user_last_name_2">
                <label class="form-label"><strong>Segundo apellido</strong> (opcional)</label>
                <input type="text" name="user_last_name_2" class="form-control" value="<?= htmlspecialchars($user['user_last_name_2']); ?>">
            </div>

            <div class="mb-3">
                <label class="form-label" for="id_gender"><strong>Género</strong> (opcional)</label>
                <select name="id_gender" class="form-control">
                    <?php
                    $genders = getAllGenders($con);

                    if ($user['id_gender']) {

                        $gender = getGender($con, $user['id_gender']);
                        echo '<option value="' . $user['id_gender'] . '">' . $gender['gender'] . '</option>';
                    } else {
                        echo '<option>Seleccione un género </option>';
                    }

                    while ($row = mysqli_fetch_assoc($genders)) {

                        if ($user['id_gender'] != $row['id_gender']) {

                            echo '<option value="' . $row['id_gender'] . '">' . $row['gender'] . '</option>';
                        }
                    };

                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label" for="id_country"><strong>País</strong> (opcional)</label>
                <select name="id_country" class="form-control">
                    <?php
                    $countries = getAllCountries($con);

                    if ($user['id_country']) {

                        $country = getCountry($con, $user['id_country']);
                        echo '<option value="' . $user['id_country'] . '">' . $country['country'] . '</option>';
                    } else {
                        echo '<option>Seleccione un país </option>';
                    }

                    while ($row = mysqli_fetch_assoc($countries)) {

                        if ($user['id_country'] != $row['id_country']) {

                            echo '<option value="' . $row['id_country'] . '">' . $row['country'] . '</option>';
                        }
                    };

                    ?>
                </select>
            </div>
            <div class="mb-3" for="user_birth_date">
                <label class="form-label"><strong>Fecha de nacimiento</strong> (opcional)</label>

                <?php
                if ($user["user_birth_date"]) {
                    echo '<input type="date" name="user_birth_date" class="form-control" value="' . $user["user_birth_date"] . '" max="' . date('Y-m-d') . '">';
                } else {
                    echo '<input type="date" name="user_birth_date" class="form-control" max="' . date('Y-m-d') . '">';
                }
                ?>
            </div>
            </br>
            </br>

            <div class="mb-3">
                <label class="form-label"><strong>Correo Electrónico</strong></label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label" for="id_rol"><strong>Tipo de usuario</strong></label>
                <select name="id_rol" class="form-control">
                    <?php

                    $rols = getAllRols($con);
                    $user_rol = getRol($con, $user['id_rol']);

                    echo '<option value="' . $user['id_rol'] . '">' . $user_rol['rol'] . '</option>';

                    while ($row = mysqli_fetch_assoc($rols)) {

                        if ($user['id_rol'] != $row['id_rol']) {

                            echo '<option value="' . $row['id_rol'] . '">' . $row['rol'] . '</option>';
                        }
                    };
                    ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label"><strong>Nueva Contraseña</strong> (opcional)</label>
                <input type="password" name="password" class="form-control" placeholder="Dejar en blanco para no cambiar">
            </div>

            <button type="submit" class="btn btn-success">Guardar Cambios</button>
            <a href="admin_dashboard.php" class="btn btn-secondary">Cancelar</a>

        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>