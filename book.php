<?php

$bookId = isset($_GET['id']) ? $_GET['id'] : null;

if ($bookId) {
    // Realizar una llamada a la API para obtener más detalles sobre el libro
    $url = "https://openlibrary.org/works/$bookId.json";
    $response = file_get_contents($url);
    if ($response === FALSE) {
        echo "No se pudo obtener información del libro.";
    } else {
        $bookDetails = json_decode($response, true);
        echo "<h1>" . htmlspecialchars($bookDetails['title']) . "</h1>";
        echo "<p><strong>Autor:</strong> " . htmlspecialchars($bookDetails['authors'][0]['type']['key']) . "</p>";
        echo "<p><strong>Descripción:</strong> " . (isset($bookDetails['description']) ? htmlspecialchars($bookDetails['description']) : "Descripción no disponible") . "</p>";
    }
} else {
    echo "No se ha proporcionado un ID de libro válido.";
}
