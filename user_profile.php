<?php

require("database.php");
require('./components/navbar.php');

$con = conectar();
$show_success_alert = false;
$upload_error = "";

// Obtener datos del usuario
$user = getUser($con, $id_user);

// Verificar si el usuario existe
if (!$user) {
    die("Usuario no encontrado.");
}

$subscription_date = $user['subscription_date'];

$datetime1 = new DateTime($subscription_date);
$datetime2 = new DateTime();
$interval = $datetime1->diff($datetime2);

$time_elapsed = $interval->y . " años, " . $interval->m . " meses y " . $interval->d . " días";

// Definir la ruta de la imagen de perfil
$profile_image = "images/profile/" . $id_user . ".jpg";
$default_image = "images/profile/default.png";

// Usar Robohash como avatar si no hay imagen de perfil
$robohash_avatar = "https://robohash.org/" . md5($id_user) . "?set=set4&bgset=&size=200x200";

// Si no existe la carpeta, crearla
if (!file_exists("images/profile")) {
    mkdir("images/profile", 0777, true);
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
        echo '<div class="alert alert-danger">Todos los campos NO opcionales deben estar completos.</div>';
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
                                        email='$email',
                                        id_rol='$id_rol' 
                                        ";

        if (!empty($id_country)) {

            // Añadir a la consulta  el país
            $sql_update = "UPDATE users SET alias='$alias', 
                                        user_name='$user_name', 
                                        user_last_name_1='$user_last_name_1', 
                                        user_last_name_2='$user_last_name_2',
                                        id_gender='$id_gender',
                                        id_country='$id_country',
                                        email='$email',
                                        id_rol='$id_rol' 
                                        ";
        }

        if (!empty($user_birth_date)) {

            // Añadir a la consulta  el país
            $sql_update = "UPDATE users SET alias='$alias', 
                                        user_name='$user_name', 
                                        user_last_name_1='$user_last_name_1', 
                                        user_last_name_2='$user_last_name_2',
                                        id_gender='$id_gender',
                                        user_birth_date='$user_birth_date',
                                        email='$email',
                                        id_rol='$id_rol' 
                                        ";
        }

        if (!empty($id_country) && !empty($user_birth_date)) {

            // Añadir a la consulta  el país y la fecha de nacimiento
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
        }


        // Si el usuario ingresó una nueva contraseña, la encriptamos y la actualizamos
        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $sql_update .= ", password_hash='$hashed_password'";
        }

        // Completar la consulta con la condición WHERE
        $sql_update .= " WHERE id_user='$id_user'";

        // Procesar la subida de imagen si existe
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
            $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "png" => "image/png");
            $filename = $_FILES['profile_image']['name'];
            $filetype = $_FILES['profile_image']['type'];
            $filesize = $_FILES['profile_image']['size'];

            // Validar extensión
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            if (!array_key_exists($ext, $allowed)) {
                $upload_error = "Error: Por favor, selecciona un formato válido de imagen (JPG, JPEG o PNG).";
            }

            // Validar tamaño (max 5MB)
            $maxsize = 5 * 1024 * 1024;
            if ($filesize > $maxsize) {
                $upload_error = "Error: El tamaño de la imagen es demasiado grande. Máximo permitido: 5MB.";
            }

            // Validar tipo MIME
            if (in_array($filetype, $allowed)) {
                // Guardar imagen
                if (empty($upload_error)) {
                    $new_filename = $id_user . "." . $ext;
                    if (move_uploaded_file($_FILES['profile_image']['tmp_name'], "images/profile/" . $new_filename)) {
                        $profile_image = "images/profile/" . $new_filename;

                        // Actualizar la URL de la imagen en la base de datos
                        $sql_image_update = "UPDATE users SET user_image_url='$profile_image' WHERE id_user='$id_user'";
                        mysqli_query($con, $sql_image_update);
                    } else {
                        $upload_error = "Error: Ocurrió un problema al cargar la imagen.";
                    }
                }
            } else {
                $upload_error = "Error: Hay un problema con el formato de la imagen. Por favor, asegúrate de que la imagen es válida.";
            }
        }

        if (update($con, $sql_update)) {
            $show_success_alert = true;
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
    <title>Editar Perfil de Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="styles/user_profile.css">

</head>

<body>
    <?php if ($show_success_alert): ?>
        <script>
            alert("✅ Cambios guardados satisfactoriamente.");
            window.location.href = "home.php";
        </script>
    <?php endif; ?>

    <div class="container">
        <div class="profile-container">
            <div class="profile-header">
                <h2 class="text-center mb-4">Editar Perfil de Usuario</h2>
                <div class="profile-img-container">
                    <?php
                    // Verificar si la imagen existe en el sistema de archivos
                    if (file_exists($profile_image)) {
                        echo '<img src="' . $profile_image . '" class="profile-img" id="profile-preview" alt="Imagen de perfil">';
                    }
                    // Verificar si el usuario tiene una URL de imagen en la base de datos
                    else if (!empty($user['user_image_url'])) {
                        echo '<img src="' . $user['user_image_url'] . '" class="profile-img" id="profile-preview" alt="Imagen de perfil">';
                    }
                    // Usar Robohash si no hay imagen
                    else {
                        echo '<img src="' . $robohash_avatar . '" class="profile-img" id="profile-preview" alt="Avatar generado">';
                    }
                    ?>
                    <label for="profile_image" class="profile-img-upload">
                        <i class="fas fa-camera"></i>
                    </label>
                </div>
                <h3><?= htmlspecialchars($user['user_name']); ?> <?= htmlspecialchars($user['user_last_name_1']); ?></h3>
                <div class="subscription-info">
                    <p><strong>Suscrito desde:</strong> <?= $subscription_date ?> (hace <?= $time_elapsed ?>)</p>
                </div>
                <?php if (!empty($upload_error)): ?>
                    <div class="alert alert-danger"><?= $upload_error ?></div>
                <?php endif; ?>
            </div>

            <form method="post" enctype="multipart/form-data">
                <input type="file" name="profile_image" id="profile_image" accept="image/*" style="display: none;">

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
                        <div class="col-md-12 mb-3">
                            <label class="form-label"><strong>Nueva Contraseña</strong> (opcional)</label>
                            <input type="password" name="password" class="form-control" placeholder="Dejar en blanco para no cambiar">
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-center gap-3 mt-4">
                    <button type="submit" class="btn btn-success btn-action" onclick="return confirm_profile_changes()">
                        <i class="fas fa-save me-2"></i>Guardar Cambios
                    </button>
                    <a href="home.php" class="btn btn-secondary btn-action">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirm_profile_changes() {
            return confirm("¿Estás seguro de que quieres guardar los cambios?");
        }

        // Previsualización de la imagen
        document.getElementById('profile_image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profile-preview').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });

        // Hacer clic en el icono de la cámara para abrir el selector de archivos
        document.querySelector('.profile-img-upload').addEventListener('click', function() {
            document.getElementById('profile_image').click();
        });
    </script>

    <?php require('./components/footer.php'); ?>
</body>

</html>