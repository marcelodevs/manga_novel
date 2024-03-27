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

function toggleBookmarksTab() {
  var commentsTab = document.querySelector('.saves');
  var main = document.querySelector('.card');
  var link = document.querySelector('.bookmarks .external-link');

  commentsTab.classList.toggle('show');
  link.classList.toggle('show');
  main.classList.toggle('show');
}

function toggleMangaTab() {
  var commentsTab = document.querySelector('.recent');
  var main = document.querySelector('.card');
  var mangaCardShow = document.querySelector('.manga-card-show');
  var link = document.querySelector('.projects .external-link');

  commentsTab.classList.toggle('show');
  link.classList.toggle('show');
  mangaCardShow.classList.toggle('show');
  main.classList.toggle('show');
}

function toggleRascunhosTab() {
  var rascunhosTab = document.querySelector('.rascunhos .saves');
  var main = document.querySelector('.card');
  var link = document.querySelector('.rascunhos .external-link');

  rascunhosTab.classList.toggle('show');
  link.classList.toggle('show');
  main.classList.toggle('show');
}
