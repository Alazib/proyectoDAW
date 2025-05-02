<?php

require("utils/api_openlibrary.php");

session_start();

$alias = $_SESSION['alias'];
$id_user = $_SESSION['id_user'];
$user_is_logged = isset($_SESSION['alias'], $_SESSION['id_user']);

if (!isset($_SESSION['id_user'])) {
    header('Location: index.php');
    exit();
}

$novedades = getRandomBooks(12);
$destacados = getRandomBooks(12);
$recomendaciones = getRandomBooks(12);

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proyecto - Inicio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="styles/home.css">
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">Proyecto</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active" href="home.php">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link" href="libros.php">Libros</a></li>
                    <li class="nav-item"><a class="nav-link" href="autores.php">Autores</a></li>
                    <li class="nav-item"><a class="nav-link" href="ranking.php">Ranking</a></li>
                    <li class="nav-item"><a class="nav-link" href="wishlist.php">Wishlist</a></li>
                    <li class="nav-item">
                        <a class="nav-link" href="user_profile.php?id=<?php echo $id_user; ?>">
                            Perfil de <?php echo htmlspecialchars($alias); ?>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Banner -->
    <div class="bg-light py-4 text-center border-bottom">
        <h1 class="display-5">Descubre, puntúa y comenta tus libros favoritos</h1>
        <p class="lead text-muted">Bienvenido a la comunidad de lectores.</p>
    </div>

    <!-- Novedades -->
    <div class="container my-5">
        <h2 class="mb-4">📕 Novedades</h2>
        <div id="novedadesCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <?php
                $chunks = array_chunk($novedades, 3);
                foreach ($chunks as $index => $chunk):
                ?>
                    <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                        <div class="row">
                            <?php foreach ($chunk as $book): ?>
                                <div class="col-4">
                                    <div class="card h-100">
                                        <a href="book.php?id=<?php echo urlencode(substr($book['id'], 7)); ?>">
                                            <img src="<?php echo $book['cover']; ?>" class="card-img-top img-fluid img-size" alt="<?php echo $book['title']; ?>">
                                            <div class="card-body text-center">
                                                <h6 class="card-title"><?php echo htmlspecialchars($book['title']); ?></h6>
                                                <p class="text-muted small"><?php echo htmlspecialchars($book['author']); ?></p>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#novedadesCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Anterior</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#novedadesCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Siguiente</span>
            </button>
        </div>
    </div>

    <!-- Lo más destacado -->
    <div class="container my-5">
        <h2 class="mb-4">🌟 Lo más destacado</h2>
        <div id="destacadosCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <?php
                $chunks = array_chunk($destacados, 3);
                foreach ($chunks as $index => $chunk):
                ?>
                    <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                        <div class="row">
                            <?php foreach ($chunk as $book): ?>
                                <div class="col-4">
                                    <div class="card h-100 border-warning">
                                        <a href="book.php?id=<?php echo urlencode(substr($book['id'], 7)); ?>">
                                            <img src="<?php echo $book['cover']; ?>" class="card-img-top img-fluid img-size" alt="<?php echo $book['title']; ?>">
                                            <div class="card-body text-center">
                                                <h5 class="card-title"><?php echo htmlspecialchars($book['title']); ?></h5>
                                                <p class="text-muted">"Una lectura increíble..."</p>
                                                <span class="badge bg-warning text-dark">★ 5/5</span>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#destacadosCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Anterior</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#destacadosCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Siguiente</span>
            </button>
        </div>
    </div>

    <!-- Recomendaciones -->
    <div class="container my-5">
        <h2 class="mb-4">🎯 Recomendaciones para ti</h2>
        <div id="recomendacionesCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <?php
                $chunks = array_chunk($recomendaciones, 3);
                foreach ($chunks as $index => $chunk):
                ?>
                    <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                        <div class="row">
                            <?php foreach ($chunk as $book): ?>
                                <div class="col-4">
                                    <div class="card h-100">
                                        <a href="book.php?id=<?php echo urlencode(substr($book['id'], 7)); ?>">
                                            <img src="<?php echo $book['cover']; ?>" class="card-img-top img-fluid img-size" alt="<?php echo $book['title']; ?>">
                                            <div class="card-body text-center">
                                                <h6 class="card-title"><?php echo htmlspecialchars($book['title']); ?></h6>
                                                <p class="text-muted small">Basado en tus gustos</p>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#recomendacionesCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Anterior</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#recomendacionesCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Siguiente</span>
            </button>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center mt-5 p-3">
        <p>&copy; <?php echo date("Y"); ?> Proyecto. Proyecto DAW.</p>
    </footer>
</body>

</html>