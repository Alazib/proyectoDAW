<?php
function getRandomBooks($count = 10)
{
    $randomSubjects = ['fantasy', 'science_fiction', 'romance', 'mystery', 'history', 'biography', 'thriller', 'children', 'horror', 'adventure'];
    $subject = $randomSubjects[array_rand($randomSubjects)];
    $url = "https://openlibrary.org/subjects/$subject.json?limit=10";

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

        $result[] = [
            'title' => $title,
            'author' => $author,
            'cover' => $coverUrl,
        ];
    }

    return $result;
}
