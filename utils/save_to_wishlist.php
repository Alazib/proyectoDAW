<?php
session_start();
require_once '../database.php';
$con = conectar();


header('Content-Type: application/json');

// Recibir y decodificar los datos enviados por fetch
$data = json_decode(file_get_contents('php://input'), true);

// Variables recibidas del formulario
$id_book = $data['id_book'];          // ID del libro
$id_user = $data['id_user'];          // ID del usuario logueado
$wishlist_id = $data['wishlist_id'];  // ID de la wishlist seleccionada
$new_wishlist_name = trim($data['new_wishlist_name']);  // Nombre de una nueva wishlist, si se crea

// ✅ Verificar si el libro ya existe en la tabla 'book'
$checkBookQuery = "SELECT * FROM book WHERE id_book = ?";
$checkStmt = $con->prepare($checkBookQuery);
$checkStmt->bind_param("s", $id_book);
$checkStmt->execute();
$result = $checkStmt->get_result();

if ($result->num_rows === 0) {
    // Si no existe el libro, lo insertamos en la tabla 'book'
    $insertBookQuery = "INSERT INTO book (id_book, rating_total_addition, ratings_total_number) VALUES (?, 0, 0)";
    $insertStmt = $con->prepare($insertBookQuery);
    $insertStmt->bind_param("s", $id_book);
    $insertStmt->execute();
    $insertStmt->close();
}
$checkStmt->close();

// Verificación de conexión a la base de datos
if (!$con) {
    echo json_encode(["message" => "Error al conectar con la base de datos."]);
    exit();
}

// Comprobación de si se quiere crear una nueva wishlist
if ($new_wishlist_name !== "") {
    // Inserta una nueva wishlist en la base de datos si el nombre está definido
    $query = "INSERT INTO wishlist (id_user, wishlist_name, created_at) VALUES (?, ?, NOW())";
    $stmt = $con->prepare($query);

    if (!$stmt) {
        echo json_encode(["message" => "Error al preparar la consulta SQL."]);
        exit();
    }

    $stmt->bind_param("is", $id_user, $new_wishlist_name);

    if (!$stmt->execute()) {
        echo json_encode(["message" => "Error al ejecutar la inserción en wishlist."]);
        exit();
    }

    // Obtenemos el ID de la nueva wishlist
    $wishlist_id = $stmt->insert_id;
    $stmt->close();
}

// Guardar en la tabla 'wishlist_book'
$query = "INSERT INTO wishlist_book (id_book, id_wishlist, added_at) VALUES (?, ?, NOW())";
$stmt = $con->prepare($query);

if (!$stmt) {
    echo json_encode(["message" => "Error al preparar la inserción en wishlist_book."]);
    exit();
}

$stmt->bind_param("si", $id_book, $wishlist_id);

if ($stmt->execute()) {
    // Respuesta exitosa en JSON
    echo json_encode(["message" => "Libro agregado correctamente a la Wishlist."]);
} else {
    // Respuesta de error en JSON
    echo json_encode(["message" => "Error al insertar en wishlist_book."]);
}

$stmt->close();
$con->close();
