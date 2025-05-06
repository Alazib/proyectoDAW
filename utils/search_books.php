<?php

require_once 'http_helper.php';

if (!isset($_GET['q']) || strlen(trim($_GET['q'])) < 3) {
    echo json_encode([]);
    exit;
}

$query = urlencode($_GET['q']);
$url = "https://openlibrary.org/search.json?title=$query&limit=10";

$response = safe_file_get_contents($url);
if ($response === false) {
    // manejar error: mostrar mensaje o fallback
    echo "<p class='text-center text-danger'>No se pudo obtener información de Open Library. Inténtalo más tarde.</p>";
    exit();
}

$data = json_decode($response, true);
$results = [];

if (isset($data['docs'])) {
    foreach ($data['docs'] as $doc) {
        $results[] = [
            'title' => $doc['title'] ?? 'Sin título',
            'author' => $doc['author_name'][0] ?? 'Autor desconocido',
            'cover' => isset($doc['cover_i']) ? "https://covers.openlibrary.org/b/id/{$doc['cover_i']}-S.jpg" : null,
            'id' => $doc['key'] ?? null
        ];
    }
}

header('Content-Type: application/json');
echo json_encode($results);
