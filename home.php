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
                <li class="nav-item"><a class="nav-link" href="perfil.php">Perfil</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Banner -->
<div class="bg-light py-4 text-center border-bottom">
    <h1 class="display-5">Descubre, puntúa y comenta tus libros favoritos</h1>
    <p class="lead text-muted">Bienvenido a la comunidad de lectores</p>
</div>

<!-- Novedades -->
<div class="container my-5">
    <h2 class="mb-4">📕 Novedades</h2>
    <div class="row row-cols-2 row-cols-sm-3 row-cols-md-5 g-4">
        <div class="col"><div class="card h-100"><img src="img/novedad1.jpg" class="card-img-top img-novedad"><div class="card-body text-center"><h6 class="card-title">El Juego de los Dioses</h6><p class="text-muted small">Abigail Owen</p></div></div></div>
        <div class="col"><div class="card h-100"><img src="img/novedad2.jpg" class="card-img-top img-novedad"><div class="card-body text-center"><h6 class="card-title">Quicksilver</h6><p class="text-muted small">Callie Hart</p></div></div></div>
        <div class="col"><div class="card h-100"><img src="img/novedad3.jpg" class="card-img-top img-novedad"><div class="card-body text-center"><h6 class="card-title">Dirty Diana</h6><p class="text-muted small">Shana Fest y Jen Besser</p></div></div></div>
        <div class="col"><div class="card h-100"><img src="img/novedad4.webp" class="card-img-top img-novedad"><div class="card-body text-center"><h6 class="card-title">La vida es física</h6><p class="text-muted small">Alba Moreno</p></div></div></div>
        <div class="col"><div class="card h-100"><img src="img/novedad5.jpg" class="card-img-top img-novedad"><div class="card-body text-center"><h6 class="card-title">Todos mis poemas hablan de ti</h6><p class="text-muted small">Manu Erena</p></div></div></div>
    </div>
</div>

<!-- Lo más destacado -->
<div class="container my-5">
    <h2 class="mb-4">🌟 Lo más destacado</h2>
    <div class="row row-cols-1 row-cols-md-3 g-4">
        <div class="col"><div class="card h-100 border-warning"><img src="img/destacado1.jpg" class="card-img-top"><div class="card-body text-center"><h5 class="card-title">El nombre del viento</h5><p class="text-muted">"Una lectura increíble..."</p><span class="badge bg-warning text-dark">★ 5/5</span></div></div></div>
        <div class="col"><div class="card h-100 border-warning"><img src="img/destacado2.jpg" class="card-img-top"><div class="card-body text-center"><h5 class="card-title">El señor de los anillos</h5><p class="text-muted">"Muy recomendado..."</p><span class="badge bg-warning text-dark">★ 4.5/5</span></div></div></div>
        <div class="col"><div class="card h-100 border-warning"><img src="img/destacado3.jpg" class="card-img-top"><div class="card-body text-center"><h5 class="card-title">Alas de sangre</h5><p class="text-muted">"Obra maestra literaria"</p><span class="badge bg-warning text-dark">★ 4.8/5</span></div></div></div>
    </div>
</div>

<!-- Recomendaciones -->
<div class="container my-5">
    <h2 class="mb-4">🎯 Recomendaciones para ti</h2>
    <div class="row row-cols-1 row-cols-md-4 g-4">
        <div class="col"><div class="card h-100"><img src="img/recomendado1.jpg" class="card-img-top"><div class="card-body text-center"><h6 class="card-title">Harry Potter y la piedra filosofal</h6><p class="text-muted small">Basado en tus gustos</p></div></div></div>
        <div class="col"><div class="card h-100"><img src="img/recomendado2.jpg" class="card-img-top"><div class="card-body text-center"><h6 class="card-title">Nacidos de la Bruma</h6><p class="text-muted small">Basado en tus gustos</p></div></div></div>
        <div class="col"><div class="card h-100"><img src="img/recomendado3.jpg" class="card-img-top"><div class="card-body text-center"><h6 class="card-title">Juego de tronos</h6><p class="text-muted small">Basado en tus gustos</p></div></div></div>
        <div class="col"><div class="card h-100"><img src="img/recomendado4.jpg" class="card-img-top"><div class="card-body text-center"><h6 class="card-title">IT</h6><p class="text-muted small">Basado en tus gustos</p></div></div></div>
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
