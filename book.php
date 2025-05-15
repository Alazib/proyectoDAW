<?php

require('./components/navbar.php');
require_once('./utils/http_helper.php');
require_once('./database.php');
$con = conectar();


$bookId = isset($_GET['id']) ? $_GET['id'] : null;

$urlBookDetails = "https://openlibrary.org/works/$bookId.json";

$responseBookDetails = safe_file_get_contents($urlBookDetails);
if ($responseBookDetails === false) {
    // manejar error: mostrar mensaje o fallback
    echo "<p class='text-center text-danger'>No se pudo obtener información de Open Library. Inténtalo más tarde.</p>";
    exit();
}

$bookDetails = json_decode($responseBookDetails, true);

$authorId = $bookDetails['authors'][0]['author']['key'];
$urlAuthorDetails = "https://openlibrary.org/$authorId.json";

$responseAuthorDetails = safe_file_get_contents($urlAuthorDetails);
if ($responseAuthorDetails === false) {
    // manejar error: mostrar mensaje o fallback
    echo "<p class='text-center text-danger'>No se pudo obtener información de Open Library. Inténtalo más tarde.</p>";
    exit();
}

$authorDetails = json_decode($responseAuthorDetails, true);

$authorPhotoId = $authorDetails['photos'][0];
$urlAuthorPhoto = "//covers.openlibrary.org/a/id/$authorPhotoId-M.jpg";

$bookCoverId = $bookDetails['covers'][0];
$urlBookCover = "https://covers.openlibrary.org/b/id/$bookCoverId-L.jpg";


// Obtener las wishlists del usuario
$id_user = $_SESSION['id_user'];
$query_wishlist = "SELECT * FROM wishlist WHERE id_user = ?";
$stmt_wishlist = $con->prepare($query_wishlist);
$stmt_wishlist->bind_param("i", $id_user);
$stmt_wishlist->execute();
$result_wishlist = $stmt_wishlist->get_result();
$wishlists = $result_wishlist->fetch_all(MYSQLI_ASSOC);




?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Libro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="scripts/star-rating.js"></script>
    <link rel="stylesheet" href="styles/book.css">
</head>

<body>

    <main class="container content-container">
        <?php if ($bookId && $responseBookDetails): ?>
            <div class="card book-card shadow-lg">
                <div class="row g-4 align-items-center">
                    <div class="col-md-4 text-center">
                        <img src="<?php echo $urlBookCover; ?>" alt="Portada del libro" class="img-fluid rounded shadow">
                    </div>
                    <div class="col-md-8">
                        <h2><?php echo htmlspecialchars($bookDetails['title']); ?></h2>
                        <a class="d-flex align-items-center mb-3 link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover" style=" width: fit-content"
                            href="author.php?id=<?php echo urlencode(str_replace('/authors/', '', $authorId)); ?>">

                            <img src=" <?php echo $urlAuthorPhoto; ?>" alt="Foto del autor" class="rounded-circle me-3 shadow author-photo">
                            <div>
                                <h5 class="mb-0"><?php echo htmlspecialchars($authorDetails['personal_name']); ?></h5>
                                <small class="text-muted">Autor</small>
                            </div>

                        </a>
                        <p class="book-description">
                            <?php echo isset($bookDetails['description'])
                                ? (is_array($bookDetails['description'])
                                    ? htmlspecialchars($bookDetails['description']['value'])
                                    : htmlspecialchars($bookDetails['description']))
                                : "Descripción no disponible."; ?>
                        </p>
                    </div>
                </div>
            </div>
            <!-- Botón wishlist -->
            <div class="text-center my-4">
                <button class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#wishlistModal">
                    Agregar a Wishlist
                </button>
            </div>

            <!-- Modal wishlist -->
            <div class="modal fade" id="wishlistModal" tabindex="-1" aria-labelledby="wishlistModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="wishlistModalLabel">Selecciona una Wishlist o crea una nueva</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="wishlistForm">
                                <div class="mb-3">
                                    <label for="wishlistSelect" class="form-label">Elige una Wishlist:</label>
                                    <select class="form-select" name="wishlist_id" id="wishlistSelect">
                                        <?php foreach ($wishlists as $wishlist): ?>
                                            <option value="<?php echo $wishlist['id_wishlist']; ?>">
                                                <?php echo $wishlist['wishlist_name']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="newWishlistName" class="form-label">O crear una nueva:</label>
                                    <input type="text" class="form-control" id="newWishlistName" placeholder="Nombre de la nueva wishlist">
                                </div>
                                <button type="submit" class="btn btn-success">Guardar en Wishlist</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>


            <script>
                document.getElementById('wishlistForm').addEventListener('submit', function(e) {
                    e.preventDefault();

                    const wishlistId = document.getElementById('wishlistSelect').value;
                    const newWishlistName = document.getElementById('newWishlistName').value;

                    fetch('utils/save_to_wishlist.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                id_book: '<?php echo $bookId; ?>',
                                id_user: '<?php echo $id_user; ?>',
                                wishlist_id: wishlistId,
                                new_wishlist_name: newWishlistName
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            alert(data.message);
                            location.reload();
                        })
                        .catch(error => console.error('Error:', error));
                });
            </script>


            <!-- Puntuación -->
            <div class="rating-section text-center mb-4">
                <h5 class="card-title">Puntuar libro</h5>
                <div class="star-rating" id="star-rating">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <span class="star" data-value="<?php echo $i; ?>">☆</span> <!-- Estrella vacía -->
                    <?php endfor; ?>
                </div>
                <p id="rating-result" class="mt-2 text-muted">Haz clic en una estrella para puntuar.</p>

            </div>



            <!-- Comentarios -->
            <div class="comments-section mb-5">
                <h5 class="mb-3">Comentarios</h5>
                <div class="comment mb-3 p-3 rounded shadow-sm bg-white d-flex align-items-start">
                    <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="usuario" class="rounded-circle me-3" width="50">
                    <div>
                        <strong>Carlos Ruiz</strong>
                        <p class="mb-1">Una lectura fantástica. Me encantó la narrativa y el desarrollo de los personajes.</p>
                    </div>
                </div>
                <div class="comment mb-3 p-3 rounded shadow-sm bg-white d-flex align-items-start">
                    <img src="https://randomuser.me/api/portraits/women/45.jpg" alt="usuario" class="rounded-circle me-3" width="50">
                    <div>
                        <strong>María Gómez</strong>
                        <p class="mb-1">Muy recomendable. Lo disfruté mucho del principio al final.</p>
                    </div>
                </div>
            </div>

        <?php else: ?>
            <div class="text-center mt-5">
                <h3>No se pudo obtener información del libro.</h3>
            </div>
        <?php endif; ?>
    </main>

    <!-- Footer -->
    <?php

    require('./components/footer.php');
    ?>

</body>

</html>