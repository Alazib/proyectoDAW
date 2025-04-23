<?php

require("api_openlibrary.php");

$libros = getRandomBooks();

foreach ($libros as $libro) {
    echo "<img src='{$libro['cover']}' alt='Portada'><br>";
    echo "<strong>{$libro['title']}</strong> de {$libro['author']}<br><br>";
}
