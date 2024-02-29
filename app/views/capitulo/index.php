<?php

namespace NovelRealm;

require_once __DIR__ . '\..\..\..\autoload.php';

session_start();

use NovelRealm\UserModel;
use NovelRealm\ChapterModel;
use NovelRealm\MangaModel;
use NovelRealm\GenerosModel;

$obj_user = new UserModel;
$obj_chapter = new ChapterModel;
$obj_manga = new MangaModel;
$obj_genres = new GenerosModel;

if (isset($_SESSION['login_user'])) {
  $user = $obj_user->list_user($_SESSION['login_user'])['data'];
}

if (isset($_GET['id'])) {
  $id_chapter = $_GET['id'];

  $chapter = $obj_chapter->list_chapter(['id_chapter' => $id_chapter])['data'][0];

  $manga = $obj_manga->list_manga_id($chapter['id_manga'])['data'][0];

  $genero_manga = $obj_genres->list_genres_manga($manga['id_manga'])['data'];

  // var_dump($generos);
} else {
  header("Location: ../usuario/index.php");
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <link rel="shortcut icon" href="../Icons/book.png" type="image/x-icon">
  <title>MangáRealm</title>
</head>

<body>
  <header>
    <nav>
      <h1><a href="../usuario/index.php">MangáRealm</a></h1>
      <div class="search-input">
        <input type="text" name="search" id="search" placeholder="Pesquisar por nome do mangá" autocomplete="off">
        <div id="search-results"></div>
      </div>
      <?php if (isset($_SESSION['login_user'])) { ?>
        <div class="lr-group">
          <div class="create-manga">
            <button type="button"><a href="../manga/new_manga.php"><img src="../Icons/create.png">Criar</a></button>
          </div>
          <div class="profile">
            <a href="../usuario/show_profile.php"><img src="data:image/*;base64,<?php echo $user['img'] ?>"><?php echo $user['nome'] ?></a>
          </div>
        </div>
      <?php } else { ?>
        <div class="lr-group">
          <div class="login">
            <button type="button" class="in"><a href="../usuario/login.php"><img src="../Icons/sign-in.png">Entre</a></button>
          </div>
          <div class="register">
            <button type="button" class="up"><a href="../usuario/registrar.php"><img src="../Icons/add-user.png">Inscreva-se</a></button>
          </div>
        </div>
      <?php } ?>
    </nav>
    <nav>
      <div class="btn-group">
        <p>Gêneros: </p>
        <div class="genero">
          <?php
          $genero_array = array();
          foreach ($genero_manga as $genero) {
            $genero_array[] = ($obj_genres->list_genres_id($genero['id_genero'])['data']['genero_nome']);
            $implode_genero = implode(', ', $genero_array);
          }
          ?>
          <p><?php echo $implode_genero ?></p>
        </div>
      </div>
    </nav>
  </header>

  <main>
    <div class="manga">
      <div class="manga_nome">
        <p><?php echo $manga['nome'] ?></p>
      </div>
      <br>
      <div class="numero_capitulo">
        <p>Capítulo <?php echo $chapter['id_capitulo'] ?></p>
      </div>
      <br>
      <div class="title">
        <p><?php echo $chapter['title'] ?></p>
      </div>
      <br>
      <div class="content">
        <pre style="white-space: pre-wrap;"><?php echo $chapter['content'] ?></pre>
      </div>
    </div>
    <div class="acoes">
      <button><a href="./index.php?id=<?php echo ($chapter['id_capitulo'] - 1) ?>">Anterior</a></button>
      <button><a href="../manga/index.php">Mangá</a></button>
      <button><a href="./index.php?id=<?php echo ($chapter['id_capitulo'] + 1) ?>">Próximo</a></button>
    </div>
  </main>

  <script>
    const searchInput = document.getElementById('search');
    const searchResults = document.getElementById('search-results');

    searchInput.addEventListener('keyup', function() {
      const searchText = this.value.trim();

      if (searchText === '') {
        searchResults.innerHTML = '';
        return;
      }

      fetch(`http://localhost/dashboard/NovelRealm/app/controllers/searchController.php?search=${searchText}`)
        .then(response => response.json())
        .then(data => {
          if (data.status) {
            let html = '';
            data.data.forEach(manga => {
              console.log(manga);
              html += `<div><a href="../manga/index.php?manga=${manga.id_manga}">${manga.nome}</a></div>`;
            });
            searchResults.innerHTML = html;
          } else {
            searchResults.innerHTML = '<div>Nenhum mangá encontrado</div>';
          }
        })
        .catch(error => {
          console.error('Erro ao buscar mangás:', error);
        });
    });
  </script>
</body>

</html>
