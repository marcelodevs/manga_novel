document.getElementById('category-select').addEventListener('change', function () {
  var selectedCategory = this.value;
  if (selectedCategory == 'Tudo') window.location.href = 'index.php';
  else window.location.href = 'index.php?category=' + selectedCategory;
});

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
