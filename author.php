<?php
require('./components/navbar.php');
require_once('./utils/http_helper.php');

// Validación del parámetro
$authorId = isset($_GET['id']) ? $_GET['id'] : null;
if (!$authorId) {
    echo "<p class='text-center mt-5'>No se proporcionó un ID de autor válido.</p>";
    exit();
}

// Obtener datos del autor
$infoUrl = "https://openlibrary.org/authors/$authorId.json";
$infoResponse = safe_file_get_contents($infoUrl);
if ($infoResponse === false) {
    // manejar error: mostrar mensaje o fallback
    echo "<p class='text-center text-danger'>No se pudo obtener información de Open Library. Inténtalo más tarde.</p>";
    exit();
}
$author = json_decode($infoResponse, true);

// Obtener lista de obras
$worksUrl = "https://openlibrary.org/authors/$authorId/works.json?limit=50";

$worksResponse = safe_file_get_contents($worksUrl);
if ($worksResponse === false) {
    // manejar error: mostrar mensaje o fallback
    echo "<p class='text-center text-danger'>No se pudo obtener información de Open Library. Inténtalo más tarde.</p>";
    exit();
}
$works = $worksResponse ? json_decode($worksResponse, true)['entries'] : [];

$authorName = $author['personal_name'] ?? $author['name'] ?? 'Autor desconocido';
$bio = is_array($author['bio'] ?? '') ? $author['bio']['value'] : ($author['bio'] ?? 'Biografía no disponible.');
$birth = $author['birth_date'] ?? 'Fecha de nacimiento desconocida';
$photoUrl = "https://covers.openlibrary.org/a/olid/{$authorId}-L.jpg";
$officialLink = $author['links'][0]['url'] ?? null;
$alternateNames = $author['alternate_names'] ?? [];

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Autor - <?php echo htmlspecialchars($authorName); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/author.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <main class="container content-container">
        <div class="card book-card shadow-lg my-4">
            <div class="row g-4 align-items-center">
                <div class="col-md-4 text-center">
                    <img src="<?php echo $photoUrl; ?>" alt="Foto del autor" class="img-fluid rounded shadow author-photo">
                </div>
                <div class="col-md-8">
                    <h2><?php echo htmlspecialchars($authorName); ?></h2>
                    <p class="text-muted">Nacido el: <?php echo htmlspecialchars($birth); ?></p>
                    <?php if (!empty($alternateNames)): ?>
                        <p><strong>También conocido como:</strong> <?php echo implode(", ", $alternateNames); ?></p>
                    <?php endif; ?>
                    <?php if ($officialLink): ?>
                        <p><a href="<?php echo htmlspecialchars($officialLink); ?>" target="_blank" class="btn btn-sm btn-outline-secondary">🌐 Sitio Oficial</a></p>
                    <?php endif; ?>
                    <p><?php echo nl2br(htmlspecialchars($bio)); ?></p>
                </div>
            </div>
        </div>

        <!-- Obras -->
        <section class="my-5">
            <h4 class="mb-4">📚 Obras destacadas</h4>
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <?php
                $validBooks = [];
                foreach ($works as $work) {
                    $coverId = $work['covers'][0] ?? null;
                    if (!$coverId) continue; // Si el libro no tiene cover pasa de libro y ve al siguiente.

                    $title = $work['title'] ?? 'Sin título';
                    $id = isset($work['key']) ? substr($work['key'], 7) : '';
                    $cover = "https://covers.openlibrary.org/b/id/{$coverId}-M.jpg";
                    $description = '';
                    if (isset($work['description'])) {
                        $description = is_array($work['description']) ? $work['description']['value'] : $work['description'];
                    }

                    $validBooks[] = [
                        'id' => $id,
                        'title' => $title,
                        'cover' => $cover,
                        'description' => $description
                    ];

                    if (count($validBooks) >= 12) break;
                }
                ?>


                <?php foreach ($validBooks as $book): ?>
                    <div class="col">
                        <div class="card h-100">
                            <a href="book.php?id=<?php echo urlencode($book['id']); ?>">
                                <img src="<?php echo $book['cover']; ?>" class="card-img-top img-fluid" alt="<?php echo htmlspecialchars($book['title']); ?>">
                                <div class="card-body">
                                    <h6 class="card-title"><?php echo htmlspecialchars($book['title']); ?></h6>
                                    <p class="card-text text-muted small">
                                        <?php echo htmlspecialchars(mb_strimwidth($book['description'], 0, 100, "...")); ?>
                                    </p>
                                </div>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>

        </section>
    </main>

    <?php require('./components/footer.php'); ?>
</body>

</html>