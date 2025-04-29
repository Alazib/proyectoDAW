document.addEventListener("DOMContentLoaded", function () {
  const stars = document.querySelectorAll("#star-rating .star");
  const result = document.getElementById("rating-result");
  let currentRating = 0;
  const bookId = "<?php echo $bookId; ?>";
  const userId = "<?php echo $_SESSION['id_user'] ?? '1'; ?>"; // Sustituir con ID real de usuario cuando esté disponible

  function updateStars(rating) {
    stars.forEach((star) => {
      const value = parseInt(star.getAttribute("data-value"));
      star.textContent = value <= rating ? "★" : "☆";
      star.classList.toggle("selected", value <= rating);
    });
  }

  stars.forEach((star) => {
    star.addEventListener("mouseover", () => {
      const hoverValue = parseInt(star.getAttribute("data-value"));
      updateStars(hoverValue);
    });

    star.addEventListener("mouseout", () => {
      updateStars(currentRating);
    });

    star.addEventListener("click", () => {
      currentRating = parseInt(star.getAttribute("data-value"));
      updateStars(currentRating);
      result.textContent = `Has puntuado este libro con ${currentRating} estrella${
        currentRating > 1 ? "s" : ""
      }.`;

      // Llamada AJAX simulada a la BBDD. Cuando estos datos estén preparados en la BBDD habrá que hacer la llamada real.
      fetch("./utils/save_book_rating.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          book_id: bookId,
          user_id: userId,
          rating: currentRating,
        }),
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            result.textContent += " ¡Gracias por tu puntuación!";
          } else {
            result.textContent += " Ocurrió un error al guardar.";
          }
        })
        .catch((error) => {
          result.textContent += " Error de conexión.";
          console.error("Error:", error);
        });
    });
  });
});
