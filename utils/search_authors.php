<?php
require_once 'http_helper.php';

header('Content-Type: application/json');

$query = $_GET['q'] ?? '';

if (strlen($query) < 3) {
    echo json_encode([]);
    exit();
}

$url = "https://openlibrary.org/search/authors.json?q=" . urlencode($query);
$response = safe_file_get_contents($url);
if ($response === false) {
    // manejar error: mostrar mensaje o fallback
    echo "<p class='text-center text-danger'>No se pudo obtener información de Open Library. Inténtalo más tarde.</p>";
    exit();
}

$data = json_decode($response, true);
$results = [];

if (isset($data['docs'])) {
    foreach (array_slice($data['docs'], 0, 10) as $author) {
        $name = $author['name'] ?? 'Autor desconocido';
        $id = $author['key'] ?? null;

        $results[] = [
            'name' => $name,
            'id' => $id,

        ];
    }
}

echo json_encode($results);
