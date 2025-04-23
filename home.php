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
                    <li class="nav-item"><a class="nav-link active" href="index.php">Inicio</a></li>
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
                                        <img src="<?php echo $book['cover']; ?>" class="card-img-top img-fluid img-size" alt="<?php echo $book['title']; ?>">
                                        <div class="card-body text-center">
                                            <h6 class="card-title"><?php echo htmlspecialchars($book['title']); ?></h6>
                                            <p class="text-muted small"><?php echo htmlspecialchars($book['author']); ?></p>
                                        </div>
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
                                        <img src="<?php echo $book['cover']; ?>" class="card-img-top img-fluid img-size" alt="<?php echo $book['title']; ?>">
                                        <div class="card-body text-center">
                                            <h5 class="card-title"><?php echo htmlspecialchars($book['title']); ?></h5>
                                            <p class="text-muted">"Una lectura increíble..."</p>
                                            <span class="badge bg-warning text-dark">★ 5/5</span>
                                        </div>
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
                                        <img src="<?php echo $book['cover']; ?>" class="card-img-top img-fluid img-size" alt="<?php echo $book['title']; ?>">
                                        <div class="card-body text-center">
                                            <h6 class="card-title"><?php echo htmlspecialchars($book['title']); ?></h6>
                                            <p class="text-muted small">Basado en tus gustos</p>
                                        </div>
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

    <!-- Descubre tu género favorito -->
    <div class="container my-5">
        <h2 class="text-center mb-2">Descubre tu género literario favorito</h2>
        <h5 class="text-center text-muted mb-4">TEMÁTICAS DESTACADAS</h5>
        <div class="row row-cols-1 row-cols-md-5 g-4 text-center">

            <!-- Fantasía -->
            <div class="col">
                <div class="card h-100 border-0">
                    <div class="p-4" style="background-color: #8e44ad;">
                        <img src="img/fantasia.png" alt="Fantasía" style="width: 60px;">
                    </div>
                    <div class="card-body">
                        <h6 class="card-title fw-bold">Fantasía</h6>
                        <p class="card-text small text-muted">Viaja a mundos mágicos, conoce criaturas legendarias y vive aventuras épicas donde todo es posible.</p>
                    </div>
                </div>
            </div>

            <!-- Romance -->
            <div class="col">
                <div class="card h-100 border-0">
                    <div class="p-4" style="background-color: #e74c3c;">
                        <img src="img/romance.png" alt="Romance" style="width: 60px;">
                    </div>
                    <div class="card-body">
                        <h6 class="card-title fw-bold">Romance</h6>
                        <p class="card-text small text-muted">Sumérgete en historias de amor intensas, relaciones inolvidables y emociones a flor de piel.</p>
                    </div>
                </div>
            </div>

            <!-- Novela Policiaca -->
            <div class="col">
                <div class="card h-100 border-0">
                    <div class="p-4" style="background-color: #34495e;">
                        <img src="img/policiaco.png" alt="Novela policiaca" style="width: 60px;">
                    </div>
                    <div class="card-body">
                        <h6 class="card-title fw-bold">Novela Policiaca</h6>
                        <p class="card-text small text-muted">Sigue a detectives brillantes y resuelve crímenes intrigantes en tramas llenas de suspenso.</p>
                    </div>
                </div>
            </div>

            <!-- Terror -->
            <div class="col">
                <div class="card h-100 border-0">
                    <div class="p-4" style="background-color: #c0392b;">
                        <img src="img/terror.png" alt="Terror" style="width: 60px;">
                    </div>
                    <div class="card-body">
                        <h6 class="card-title fw-bold">Terror</h6>
                        <p class="card-text small text-muted">Enfréntate a tus miedos con historias que te pondrán los pelos de punta. Misterios, criaturas y horrores sobrenaturales.</p>
                    </div>
                </div>
            </div>

            <!-- Poesía -->
            <div class="col">
                <div class="card h-100 border-0">
                    <div class="p-4" style="background-color: #16a085;">
                        <img src="img/poesia.png" alt="Poesía" style="width: 60px;">
                    </div>
                    <div class="card-body">
                        <h6 class="card-title fw-bold">Poesía</h6>
                        <p class="card-text small text-muted">Descubre la belleza de las palabras en versos que emocionan, inspiran y hacen reflexionar.</p>
                    </div>
                </div>
            </div>

        </div>
    </div>



    <!-- Footer -->
    <footer class="bg-dark text-white text-center mt-5 p-3">
        <p>&copy; <?php echo date("Y"); ?> Proyecto. Proyecto DAW.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>