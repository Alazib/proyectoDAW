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
if (!$user) {
    die("Usuario no encontrado.");
}

$subscription_date = $user['subscription_date'];
$datetime1 = new DateTime($subscription_date);
$datetime2 = new DateTime();
$interval = $datetime1->diff($datetime2);
$time_elapsed = $interval->y . " años, " . $interval->m . " meses y " . $interval->d . " días";

// Definir la ruta de la imagen de perfil
$default_image = "images/profile/default.png";
$local_image = "images/profile/" . $id_user . ".jpg";
$robohash_avatar = "https://robohash.org/" . md5($id_user) . "?set=set4&size=200x200";

$profile_image = (file_exists($local_image)) ? $local_image : $robohash_avatar;

// Crear carpeta si no existe
if (!file_exists("images/profile")) {
    mkdir("images/profile", 0777, true);
}

// Procesar formulario si se envió
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
        echo '<div class="alert alert-danger">Todos los campos NO opcionales deben estar completos.</div>';
    } else {
        // Escapar datos
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

        // Actualizar consulta
        $sql_update = "UPDATE users SET alias='$alias',
                                        user_name='$user_name',
                                        user_last_name_1='$user_last_name_1',
                                        user_last_name_2='$user_last_name_2',
                                        id_gender='$id_gender',
                                        id_country='$id_country',
                                        user_birth_date='$user_birth_date',
                                        email='$email',
                                        id_rol='$id_rol'";

        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $sql_update .= ", password_hash='$hashed_password'";
        }

        $sql_update .= " WHERE id_user='$id_user'";

        // Ejecutar actualización
        if (update($con, $sql_update)) {
            // Procesar imagen
            if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
                $allowed = ["jpg" => "image/jpg", "jpeg" => "image/jpeg", "png" => "image/png"];
                $filename = $_FILES['profile_image']['name'];
                $filetype = $_FILES['profile_image']['type'];
                $filesize = $_FILES['profile_image']['size'];
                $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

                if (array_key_exists($ext, $allowed) && in_array($filetype, $allowed) && $filesize <= 5 * 1024 * 1024) {
                    $new_filename = "images/profile/" . $id_user . ".jpg";
                    move_uploaded_file($_FILES['profile_image']['tmp_name'], $new_filename);
                }
            }

            header("Location: admin_dashboard.php");
            exit();
        } else {
            echo '<div class="alert alert-danger">Error al actualizar el usuario: ' . mysqli_error($con) . '</div>';
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
    <style>
        body {
            background-color: #f8f9fa;
        }

        .profile-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-top: 30px;
            margin-bottom: 30px;
        }

        .profile-header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 1px solid #e9ecef;
            padding-bottom: 20px;
        }

        .form-section {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .section-title {
            margin-bottom: 20px;
            color: #343a40;
            font-weight: 600;
        }

        .btn-action {
            margin-top: 10px;
            padding: 10px 25px;
            border-radius: 5px;
        }

        .subscription-info {
            font-size: 14px;
            color: #6c757d;
            margin-bottom: 20px;
        }

        .profile-img-container {
            position: relative;
            width: 150px;
            height: 150px;
            margin: 0 auto 20px auto;
        }

        .profile-img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid #e9ecef;
        }

        .profile-img-upload {
            position: absolute;
            bottom: 0;
            right: 0;
            background:rgb(74, 75, 74);
            color: white;
            border-radius: 50%;
            padding: 8px 10px;
            cursor: pointer;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
            font-size: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid white;
            transition: background 0.3s ease;
        }

        .profile-img-upload:hover {
            background: #218838;
        }


    </style>
</head>

<body>

    <div class="container">
    <div class="card p-4 shadow-sm">
        <h2 class="text-center"><?= htmlspecialchars($user['user_name']) . ' ' . htmlspecialchars($user['user_last_name_1']) ?></h2>
        <p class="text-center text-muted">Suscrito desde <?= $subscription_date ?> (hace <?= $time_elapsed ?>)</p>

        <div class="profile-img-container">
            <img src="<?= $profile_image ?>" alt="Avatar" class="profile-img">
            <label for="profile_image" class="profile-img-upload" title="Cambiar imagen de perfil">
                <input type="file" id="profile_image" name="profile_image" accept="image/*" style="display:none">
                <span style="font-size: 18px;">📷</span>
            </label>
        </div>
        <div class="profile-container">
            <form method="post">
                <div class="form-section">
                    <h4 class="section-title">Información Personal</h4>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><strong>Alias</strong></label>
                            <input type="text" name="alias" class="form-control" value="<?= htmlspecialchars($user['alias']); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><strong>Nombre</strong></label>
                            <input type="text" name="user_name" class="form-control" value="<?= htmlspecialchars($user['user_name']); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><strong>Primer apellido</strong></label>
                            <input type="text" name="user_last_name_1" class="form-control" value="<?= htmlspecialchars($user['user_last_name_1']); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><strong>Segundo apellido</strong> (opcional)</label>
                            <input type="text" name="user_last_name_2" class="form-control" value="<?= htmlspecialchars($user['user_last_name_2']); ?>">
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h4 class="section-title">Información Adicional</h4>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label"><strong>Género</strong> (opcional)</label>
                            <select name="id_gender" class="form-select">
                                <?php
                                $genders = getAllGenders($con);

                                if ($user['id_gender']) {
                                    $gender = getGender($con, $user['id_gender']);
                                    echo '<option value="' . $user['id_gender'] . '">' . $gender['gender'] . '</option>';
                                } else {
                                    echo '<option value="">Seleccione un género</option>';
                                }

                                while ($row = mysqli_fetch_assoc($genders)) {
                                    if ($user['id_gender'] != $row['id_gender']) {
                                        echo '<option value="' . $row['id_gender'] . '">' . $row['gender'] . '</option>';
                                    }
                                };
                                ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label"><strong>País</strong> (opcional)</label>
                            <select name="id_country" class="form-select">
                                <?php
                                $countries = getAllCountries($con);

                                if ($user['id_country']) {
                                    $country = getCountry($con, $user['id_country']);
                                    echo '<option value="' . $user['id_country'] . '">' . $country['country'] . '</option>';
                                } else {
                                    echo '<option value="">Seleccione un país</option>';
                                }

                                while ($row = mysqli_fetch_assoc($countries)) {
                                    if ($user['id_country'] != $row['id_country']) {
                                        echo '<option value="' . $row['id_country'] . '">' . $row['country'] . '</option>';
                                    }
                                };
                                ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label"><strong>Fecha de nacimiento</strong> (opcional)</label>
                            <?php
                            if ($user["user_birth_date"]) {
                                echo '<input type="date" name="user_birth_date" class="form-control" value="' . $user["user_birth_date"] . '" max="' . date('Y-m-d') . '">';
                            } else {
                                echo '<input type="date" name="user_birth_date" class="form-control" max="' . date('Y-m-d') . '">';
                            }
                            ?>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h4 class="section-title">Información de Cuenta</h4>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><strong>Correo Electrónico</strong></label>
                            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><strong>Tipo de usuario</strong></label>
                            <select name="id_rol" class="form-select">
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
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><strong>Nueva Contraseña</strong> (opcional)</label>
                            <input type="password" name="password" class="form-control" placeholder="Dejar en blanco para no cambiar">
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-success btn-action">Guardar Cambios</button>
                <a href="admin_dashboard.php" class="btn btn-secondary btn-action">Cancelar</a>

            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>