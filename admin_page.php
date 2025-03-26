<?php
session_start();
require("database.php");
require("utils/get_age.php");
$con = conectar();

// Obtener los usuarios de la base de datos (ahora incluyendo la contraseña encriptada)
$sql = "SELECT id_user, user_name, user_last_name_1, user_last_name_2, id_gender, user_birth_date, user_image_url, alias, email, id_rol, password_hash FROM users";
$result = mysqli_query($con, $sql);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios - Panel Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/admin_page.css" type="text/css">
</head>
<body>

<div class="container mt-5 ">
    <h2 class="text-center">Gestión de Usuarios</h2>

    <!-- Mostrar la tabla de usuarios -->
    <table class="table mt-5 table-striped-columns">
        <thead>
            <tr>
                <th>Avatar</th>                
                <th>ID</th>
                <th>Nombre</th>
                <th>Primer Apellido</th> 
                <th>Segundo Apellido</th>
                <th>Edad</th>                   
                <th>Género</th>                                
                <th>Alias</th>
                <th>Correo Electrónico</th>
                <th>Contraseña</th> <!-- Nueva columna -->
                <th>Tipo</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Verificar si hay resultados
            if (mysqli_num_rows($result) > 0) {

                while ($row = mysqli_fetch_assoc($result)) {
                    // Mostrar cada usuario
                    $rol = getRol($con, $row['id_rol']);
                    $rol_name = $rol['rol'];

                    $gender = getGender($con, $row['id_gender']);
                    $gender_name= $gender? $gender['gender'] : ' ';
                    //¿Por qué se hace este operador ternario? --> Al registrarse el usuario no introduce su género, 
                    // por lo que al intentar recuperarlo por primera vez el parámetro en la función entra como 'null'
                    // y esta devolverá 'null' dando un error cuando se intenta hacer $gender['gender']
                    //  (pues $gender no será un array, si no null).

                    $avatar = ($row['user_image_url']) ? $row['user_image_url'] : 'https://robohash.org/afa6ef28c0eb2dead79abc791b4886b5?set=set4&bgset=&size=200x200';

                    $age = getAge($row['user_birth_date']);

                    echo "<tr>
                            <td><img src=$avatar width=70></td>
                            <td>{$row['id_user']}</td>                            
                            <td>{$row['user_name']}</td>
                             <td>{$row['user_last_name_1']}</td>   
                             <td>{$row['user_last_name_2']}</td>
                             <td>$age</td>                             
                            <td>$gender_name</td>                                                                                 
                            <td>{$row['alias']}</td>
                            <td>{$row['email']}</td>
                            <td>••••••</td> <!-- No mostrar la contraseña real -->
                            <td>$rol_name </td>
                            <td>
                                <a href='editar_usuario.php?id={$row['id_user']}' class='btn btn-warning btn-sm'>Editar</a>
                                <a href='eliminar_usuario.php?id={$row['id_user']}' class='btn btn-danger btn-sm'>Eliminar</a>
                            </td>
                        </tr>";
                }
            } else {
                echo "<tr><td colspan='7' class='text-center'>No hay usuarios registrados.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Botón para cerrar sesión -->
    <a href="logout.php" class="btn btn-danger">Log out</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
