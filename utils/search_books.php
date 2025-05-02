<?php
if (!isset($_GET['q']) || strlen(trim($_GET['q'])) < 3) {
    echo json_encode([]);
    exit;
}

$query = urlencode($_GET['q']);
$url = "https://openlibrary.org/search.json?title=$query&limit=10";

$response = file_get_contents($url);
if ($response === FALSE) {
    echo json_encode([]);
    exit;
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
