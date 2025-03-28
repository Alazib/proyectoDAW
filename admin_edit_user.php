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

    <div class="container mt-5">
        <h2 class="text-center">Editar usuario <?= htmlspecialchars($user['user_name']); ?> <?= htmlspecialchars($user['user_last_name_1']); ?></h2>

        <div class="mb-3 text-center">
            <p><strong>Suscrito desde:</strong> <?= $subscription_date ?> (hace <?= $time_elapsed ?>)</p>
        </div>

        <form method="post">

            <div class="mb-3" for="alias">
                <label class="form-label">Alias</label>
                <input type="text" name="alias" class="form-control" value="<?= htmlspecialchars($user['alias']); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label" for='user_name'>Nombre</label>
                <input type="text" name="user_name" class="form-control" value=<?= htmlspecialchars($user['user_name']); ?> required>
            </div>

            <div class="mb-3" for="user_last_name_1">
                <label class="form-label">Primer apellido</label>
                <input type="text" name="user_last_name_1" class="form-control" value="<?= htmlspecialchars($user['user_last_name_1']); ?>" required>
            </div>

            <div class="mb-3" for="user_last_name_2">
                <label class="form-label">Segundo apellido (opcional)</label>
                <input type="text" name="user_last_name_2" class="form-control" value="<?= htmlspecialchars($user['user_last_name_2']); ?>">
            </div>

            <div class="mb-3">
                <label class="form-label" for="id_gender">Género</label>
                <select name="id_gender" class="form-control">
                    <?php
                    $genders = getAllGenders($con);

                    if ($user['id_gender']) {

                        $gender = getGender($con, $user['id_gender']);
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
                <label class="form-label" for="id_country">País</label>
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
                <label class="form-label">Fecha de nacimiento:</label>

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
                <label class="form-label">Correo Electrónico</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label" for="id_rol">Tipo de usuario</label>
                <select name="id_rol" class="form-control">
                    <?php
                    $rols = getAllRols($con);

                    while ($row = mysqli_fetch_assoc($rols)) {
                        echo '<option value="' . $row['id_rol'] . '">' . $row['rol'] . '</option>';
                    };
                    ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Nueva Contraseña (opcional)</label>
                <input type="password" name="password" class="form-control" placeholder="Dejar en blanco para no cambiar">
            </div>

            <button type="submit" class="btn btn-success">Guardar Cambios</button>
            <a href="admin_dashboard.php" class="btn btn-secondary">Cancelar</a>

        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>