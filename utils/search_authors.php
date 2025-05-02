<?php
header('Content-Type: application/json');

$query = $_GET['q'] ?? '';

if (strlen($query) < 3) {
    echo json_encode([]);
    exit();
}

$url = "https://openlibrary.org/search/authors.json?q=" . urlencode($query);
$response = file_get_contents($url);

if ($response === false) {
    echo json_encode([]);
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
