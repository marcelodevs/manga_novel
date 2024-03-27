document.querySelector('.toggle-capitulos').addEventListener("click", () => {
  const cap = document.querySelector('.capitulos');
  const showCap = document.querySelector('.show-cap');

  cap.classList.toggle('show');
  showCap.classList.toggle('show-capitulos');
});

function toggleCommentsTab() {
  var commentsTab = document.querySelector('.comments-tab');
  var main = document.querySelector('main');
  var link = document.querySelector('.comments .external-link');

  commentsTab.classList.toggle('show');
  link.classList.toggle('show');
  main.classList.toggle('show-comments');
}

function toggleSinopse() {
  var sinopseTab = document.querySelector('.sinopse-tab');
  var main = document.querySelector('main');
  var link = document.querySelector('.sinopse .external-link');

  sinopseTab.classList.toggle('show');
  link.classList.toggle('show');
  main.classList.toggle('show-sinopse');
}

function stopPropagation(event) {
  event.stopPropagation();
}

const searchInput = document.getElementById('search');
const searchResults = document.getElementById('search-results');

searchInput.addEventListener('keyup', function () {
  const searchText = this.value.trim();

  if (searchText === '')
  {
    searchResults.innerHTML = '';
    return;
  }

  fetch(`http://localhost/dashboard/NovelRealm/app/controllers/searchController.php?search=${searchText}`)
    .then(response => response.json())
    .then(data => {
      if (data.status)
      {
        let html = '';
        data.data.forEach(manga => {
          console.log(manga);
          html += `<div><a href="../manga/index.php?manga=${manga.id_manga}">${manga.nome}</a></div>`;
        });
        searchResults.innerHTML = html;
      } else
      {
        searchResults.innerHTML = '<div>Nenhum mangá encontrado</div>';
      }
    })
    .catch(error => {
      console.error('Erro ao buscar mangás:', error);
    });
});
