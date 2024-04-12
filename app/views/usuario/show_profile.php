<?php

namespace NovelRealm;

session_start();

require_once __DIR__ . '\..\..\..\autoload.php';

use NovelRealm\UserDao;
use NovelRealm\MangaDao;
use NovelRealm\GenerosDao;
use NovelRealm\BookmarkDao;
use NovelRealm\ChapterDao;

$obj_user = new UserDao;
$obj_manga = new MangaDao;
$obj_genres = new GenerosDao;
$obj_bookmark = new BookmarkDao;
$obj_chapter = new ChapterDao;

if (isset($_SESSION['login_user'])) {
  $user = $obj_user->list_user($_SESSION['login_user'])['data'];

  $manga_user = $obj_manga->list_manga($user);

  $preferences_dark_mode = $obj_user->get_preferences_dark_mode($user['id_user']);

  $favorito = $obj_bookmark->list_bookmark_user(["id_user" => $user['id_user']]);

  $count_rascunho = $obj_chapter->return_sketch($user['id_user']);

  // var_dump($count_rascunho);
} else {
  header("Location: Location: ../../../404.php?type=user&error=1");
}

// var_dump($manga_user);

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../src/styles/show_profile.css">
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
        <div class="bookmarks">
          <p onclick="toggleBookmarksTab()">Favoritos <img src="../Icons/external-link-black.png" class="external-link" width="15" height="15"></p>
          <div class="saves">
            <?php if ($favorito['status']) { ?>
              <div class="bookmarks-manga">
                <?php foreach ($favorito['data'] as $fa) :
                  $bookmark_manga = $obj_manga->list_manga_id($fa['id_manga'])['data'];
                  foreach ($bookmark_manga as $manga) {
                    $genero_manga = $obj_genres->list_genres_manga($manga['id_manga'])['data'];
                    $genero_array = array();
                    foreach ($genero_manga as $genero) {
                      $genero_array[] = ($obj_genres->list_genres_id($genero['id_genero'])['data']['genero_nome']);
                      $implode_genero = implode(', ', $genero_array);
                    }
                    echo "
                    <a href='../manga/index.php?manga=" . $manga['id_manga'] . "'>
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
                ?>
                <?php endforeach; ?>
              </div>
            <?php } else { ?>
              <p><?php echo $favorito['data'] ?></p>
            <?php } ?>
          </div>
        </div>
        <div class="projects">
          <p onclick="toggleMangaTab()">Mangás <img src="../Icons/external-link-black.png" class="external-link" width="15" height="15"></p>
          <div class="recent">
            <div class="manga-card-show">
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
                echo "<p class='transparent-p'>" . $manga_user['data'] . "</p>";
              }
              ?>
            </div>
          </div>
        </div>
        <?php if ($count_rascunho['status']) : ?>
          <div class="rascunhos">
            <p onclick="toggleRascunhosTab()">Rascunhos <img src="../Icons/external-link-black.png" class="external-link" width="15" height="15"></p>
            <div class="saves">
              <div class="rascunhos-manga">
                <?php foreach ($count_rascunho['data'] as $rascunho) {
                  $rascunho_manga = $obj_manga->list_manga_id($rascunho['id_manga'])['data'];
                  foreach ($rascunho_manga as $manga) {
                    echo "
                    <a href='../capitulo/edit_cap.php?cap=" . $rascunho['id'] . "'>
                      <div class='card-manga'>
                        <div class='card-content'>
                          <p> " . $manga['nome'] . "</p>
                          <div class='transparent-p'>
                            <p>Capítulo:</p>
                            <p> " . $rascunho['id_capitulo'] . "</p>
                          </div>
                        </div>
                      </div>
                    </a>
                    ";
                  }
                }
                ?>
              </div>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </main>

  <script src="../src/scripts/show_profile.js"></script>
</body>

</html>
