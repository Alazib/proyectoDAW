<?php
function getRandomBooks($count)
{
    $randomSubjects = ['fantasy', 'science_fiction', 'romance', 'mystery', 'history', 'biography', 'thriller', 'children', 'horror', 'adventure'];
    $subject = $randomSubjects[array_rand($randomSubjects)];
    $offset = rand(0, 100);
    $url = "https://openlibrary.org/subjects/$subject.json?limit=$count&offset=$offset";

    $response = file_get_contents($url);
    if ($response === FALSE) {
        return [];
    }

    $data = json_decode($response, true);
    if (!isset($data['works'])) {
        return [];
    }


    shuffle($data['works']);
    $books = array_slice($data['works'], 0, $count);

    $result = [];
    foreach ($books as $book) {
        $title = $book['title'] ?? 'Sin título';
        $author = $book['authors'][0]['name'] ?? 'Autor desconocido';
        $coverId = $book['cover_id'] ?? null;
        $coverUrl = $coverId ? "https://covers.openlibrary.org/b/id/{$coverId}-L.jpg" : null;
        $bookId = $book['key'] ?? null;

        $result[] = [
            'title' => $title,
            'author' => $author,
            'cover' => $coverUrl,
            'id' => $bookId
        ];
    }

    return $result;
}

function searchBooks()
{

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
}
