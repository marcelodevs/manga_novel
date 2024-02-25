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

  $manga_user = $obj_manga->list_manga($user);

  $preferences_dark_mode = $obj_user->get_preferences_dark_mode($user['id_user']);
} else {
  header("Location: ./index.php");
}

// var_dump($manga_user);

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="show_profile.css">
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
        <img src="data:image/*;base64,<?php echo $user['img']; ?>" alt="Hembo Tingor" class="avatar">
        <h1><?php echo $user['nome']; ?></h1>
        <br>
        <a href="../../controllers/darkmodeController.php?id=<?php echo $user['id_user'] ?>" style="margin: 2px;">
          <abbr title="Modo <?php echo $preferences_dark_mode ? 'claro' : 'escuro' ?>">
            <img src="../Icons/<?php echo $preferences_dark_mode ? 'sun' : 'moon' ?>.png" width="30" height="30">
          </abbr>
        </a>
        <a href="./update_user.php" style="margin: 2px;">
          <abbr title="Editar perfil">
            <img src="../Icons/edit.png" width="30" height="30">
          </abbr>
        </a>
        <a href="../../controllers/logoff.php" style="margin: 2px;">
          <abbr title="Sair da conta">
            <img src="../Icons/exit.png" width="30" height="30">
          </abbr>
        </a>
      </div>
      <div class="right">
        <div class="info">
          <p>Informações</p>
          <p>Email:</p>
          <p class="text-gray"><?php echo $user['email']; ?></p>
        </div>
        <div class="projects">
          <p>Mangás</p>
          <div class="recent">
            <?php
            if ($manga_user['status']) {
              foreach ($manga_user['data'] as $manga) {
                $genero_manga = $obj_genres->list_genres_manga($manga['id_manga'])['data'];
                $genero_array = array();
                foreach ($genero_manga as $genero) {
                  $genero_array[] = ($obj_genres->list_genres_id($genero['id_genero'])['data']['genero_nome']);
                  $implode_genero = implode(', ', $genero_array);
                }
                echo "
                <a href='../manga/edit_manga.php?manga=" . $manga['id_manga'] . "'>
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
              echo "<p class='text-gray'>" . $manga_user['data'] . "</p>";
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
