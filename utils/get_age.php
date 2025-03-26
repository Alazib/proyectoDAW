<?php


function getAge($user_birth_date) {
    $user_birth_date = date_create($user_birth_date); // Convierte la fecha en un objeto DateTime
    $today = date_create(); // Obtiene la fecha actual
    $age = date_diff($today, $user_birth_date); // Calcula la diferencia

    return $age->y; // Retorna solo los años
}



?>