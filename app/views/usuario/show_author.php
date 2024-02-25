<?php

namespace NovelRealm;

session_start();

require_once __DIR__ . '\..\..\..\autoload.php';

use NovelRealm\UserModel;
use NovelRealm\MangaModel;
use NovelRealm\GenerosModel;

$obj_user = new UserModel;
$obj_manga = new MangaModel;
$obj_genres = new GenerosModel;

if (isset($_SESSION['login_user'])) {
  $user = $obj_user->list_user($_SESSION['login_user'])['data'];

  $preferences_dark_mode = $obj_user->get_preferences_dark_mode($user['id_user']);
}

if (isset($_GET['author'])) {
  $author = $obj_user->list_user(["email" => $_GET['author']])['data'];

  $manga_author = $obj_manga->list_manga($author);

  // var_dump($manga_author);
}

// var_dump($manga_user);

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./show_author.css">
  <link rel="shortcut icon" href="../Icons/book.png" type="image/x-icon">
  <title>MangáRealm • <?php echo $user['nome'] ?></title>
</head>

<body>
  <header>
    <nav>
      <h1><a href="./index.php">MangáRealm</a></h1>
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
            <a href="./show_profile.php"><img src="data:image/*;base64,<?php echo $user['img'] ?>"><?php echo $user['nome'] ?></a>
          </div>
        </div>
      <?php } else { ?>
        <div class="lr-group">
          <div class="login">
            <button type="button" class="in"><a href="./login.php"><img src="../Icons/sign-in.png">Entre</a></button>
          </div>
          <div class="register">
            <button type="button" class="up"><a href="./registrar.php"><img src="../Icons/add-user.png">Inscreva-se</a></button>
          </div>
        </div>
      <?php } ?>
    </nav>
  </header>

  <main>

    <div class="card">
      <div class="left">
        <img src="data:image/*;base64,<?php echo $author['img']; ?>" alt="Hembo Tingor" class="avatar">
        <h1><?php echo $author['nome']; ?></h1>
        <h4><?php echo count($manga_author['data']) ?> Mangás</h1>
      </div>
      <div class="right">
        <div class="projects">
          <p>Mangás</p>
          <div class="recent">
            <?php
            if ($manga_author['status']) {
              foreach ($manga_author['data'] as $manga) {
                $genero_manga = $obj_genres->list_genres_manga($manga['id_manga'])['data'];
                $genero_array = array();
                foreach ($genero_manga as $genero) {
                  $genero_array[] = ($obj_genres->list_genres_id($genero['id_genero'])['data']['genero_nome']);
                  $implode_genero = implode(', ', $genero_array);
                }
                echo "
                <a href='../manga/index.php?manga= " . $manga['id_manga'] . "'>
                  <div class='card-manga'>
                    <div class='card-content'>
                      <p> " . $manga['nome'] . "</p>
                      <div class='transparent-p'>
                        <p> " . $implode_genero . "</p>
                        <p>Capítulos</p>
                        <p> " . $manga['quantidade_capitulo'] . "</p>
                      </div>
                    </div>
                  </div>
                </a>
                ";
              }
            } else {
              echo "<p class='text-gray'>" . $manga_author['data'] . "</p>";
            }
            ?>
          </div>
        </div>
      </div>
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
