<?php
// Cuando esté la BBD lista conectar aquí con ella para guardar la puntuación real

$data = json_decode(file_get_contents("php://input"), true);

$bookId = $data['book_id'] ?? null;
$userId = $data['user_id'] ?? null;
$rating = $data['rating'] ?? null;


// Simulación de éxito
if ($bookId && $userId && $rating >= 1 && $rating <= 5) {
    // Simulación de guardado en BD
    http_response_code(200);
    echo json_encode(['success' => true]);
} else {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Datos inválidos']);
}
