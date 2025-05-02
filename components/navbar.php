<?php

session_start();
$alias = $_SESSION['alias'];
$id_user = $_SESSION['id_user'];

?>


<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Proyecto - Inicio</title>
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
    rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="styles/home.css" />
</head>

<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a class="navbar-brand" href="index.php">Proyecto</a>
      <button
        class="navbar-toggler"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link active" href="home.php">Inicio</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="libros.php">Libros</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="autores.php">Autores</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="ranking.php">Ranking</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="wishlist.php">Wishlist</a>
          </li>
          <li class="nav-item">
            <a
              class="nav-link"
              href="user_profile.php">
              Perfil de
              <?php echo htmlspecialchars($alias); ?>
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</body>

</html>