// Buscador de libros por título
document.getElementById("search-book").addEventListener("input", function () {
  const query = this.value;
  const resultsList = document.getElementById("search-results");

  if (query.length < 3) {
    resultsList.style.display = "none";
    resultsList.innerHTML = "";
    return;
  }

  fetch(`./utils/search_books.php?q=${encodeURIComponent(query)}`)
    .then((response) => response.json())
    .then((data) => {
      resultsList.innerHTML = "";
      if (data.length === 0) {
        resultsList.style.display = "none";
        return;
      }

      data.forEach((book) => {
        const li = document.createElement("li");
        li.className = "list-group-item d-flex align-items-center";
        li.innerHTML = `
                      ${
                        book.cover
                          ? `<img src="${book.cover}" alt="Portada" class="me-2" width="30">`
                          : ""
                      }
                      <div>
                          <strong>${book.title}</strong><br>
                          <small>${book.author}</small>
                      </div>
                  `;
        li.style.cursor = "pointer";
        li.onclick = () => {
          window.location.href = `book.php?id=${encodeURIComponent(
            book.id.replace("/works/", "")
          )}`;
        };
        resultsList.appendChild(li);
      });

      resultsList.style.display = "block";
    });
});

// Buscador de autores
document.getElementById("search-author").addEventListener("input", function () {
  const query = this.value;
  const authorResultsList = document.getElementById("author-results");

  if (query.length < 3) {
    authorResultsList.style.display = "none";
    authorResultsList.innerHTML = "";
    return;
  }

  fetch(`./utils/search_authors.php?q=${encodeURIComponent(query)}`)
    .then((response) => response.json())
    .then((data) => {
      authorResultsList.innerHTML = "";
      if (data.length === 0) {
        authorResultsList.style.display = "none";
        return;
      }

      data.forEach((author) => {
        const li = document.createElement("li");
        li.className = "list-group-item d-flex align-items-center";
        li.innerHTML = `

                      <div>
                          <strong>${author.name}</strong>
                      </div>
                  `;
        li.style.cursor = "pointer";
        li.onclick = () => {
          window.location.href = `author.php?id=${encodeURIComponent(
            author.id.replace("/authors/", "")
          )}`;
        };
        authorResultsList.appendChild(li);
      });

      authorResultsList.style.display = "block";
    });
});
