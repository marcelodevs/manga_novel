<?php

namespace NovelRealm;

session_start();

require_once __DIR__ . '\..\..\..\autoload.php';

use NovelRealm\UserModel;
use NovelRealm\MangaModel;
use NovelRealm\ChapterModel;
use NovelRealm\GenerosModel;

$obj_user = new UserModel;
$obj_manga = new MangaModel;
$obj_chapter = new ChapterModel;
$obj_genres = new GenerosModel;

if (isset($_SESSION['login_user'])) {
  $user = $obj_user->list_user($_SESSION['login_user'])['data'];
}

$authors = $obj_user->list_user()['data'];

$mangas = $obj_manga->list_manga();

$genero_manga = $obj_genres->list_genres();

// var_dump($mangas);

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <link rel="shortcut icon" href="../Icons/book.png" type="image/x-icon">
  <title>MangáRealm <?php if (isset($_GET['authors'])) {
                      echo "• Autores";
                    } elseif (isset($_GET['category'])) {
                      echo "• " . $_GET['category'];
                    } ?></title>
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
    <hr>
    <nav>
      <div class="btn-group">
        <?php if (isset($_GET['authors']) or isset($_GET['category'])) : ?>
          <button><a href="index.php">Página Inicial</a></button>
        <?php endif; ?>
        <?php if (!isset($_GET['authors'])) : ?>
          <!-- <select id="category-select">
            <option value="Tudo" <?php echo (!isset($_GET['category'])) ? 'selected' : '' ?>>Tudo</option>
            <option value="Romance" <?php echo (isset($_GET['category']) and $_GET['category'] == 'Romance') ? 'selected' : '' ?>>Romance</option>
            <option value="Drama" <?php echo (isset($_GET['category']) and $_GET['category'] == 'Drama') ? 'selected' : '' ?>>Drama</option>
            <option value="Escolar" <?php echo (isset($_GET['category']) and $_GET['category'] == 'Escolar') ? 'selected' : '' ?>>Escolar</option>
            <option value="Shounen" <?php echo (isset($_GET['category']) and $_GET['category'] == 'Shounen') ? 'selected' : '' ?>>Shounen</option>
            <option value="Shoujo" <?php echo (isset($_GET['category']) and $_GET['category'] == 'Shoujo') ? 'selected' : '' ?>>Shoujo</option>
            <option value="Yuri" <?php echo (isset($_GET['category']) and $_GET['category'] == 'Yuri') ? 'selected' : '' ?>>Yuri</option>
            <option value="Yaoi" <?php echo (isset($_GET['category']) and $_GET['category'] == 'Yaoi') ? 'selected' : '' ?>>Yaoi</option>
            <option value="Harém" <?php echo (isset($_GET['category']) and $_GET['category'] == 'Harém') ? 'selected' : '' ?>>Harém</option>
            <option value="Hentai" <?php echo (isset($_GET['category']) and $_GET['category'] == 'Hentai') ? 'selected' : '' ?>>Hentai</option>
            <option value="Mecha" <?php echo (isset($_GET['category']) and $_GET['category'] == 'Mecha') ? 'selected' : '' ?>>Mecha</option>
            <option value="Seinen" <?php echo (isset($_GET['category']) and $_GET['category'] == 'Seinen') ? 'selected' : '' ?>>Seinen</option>
            <option value="Ação" <?php echo (isset($_GET['category']) and $_GET['category'] == 'Ação') ? 'selected' : '' ?>>Ação</option>
            <option value="Comédia" <?php echo (isset($_GET['category']) and $_GET['category'] == 'Comédia') ? 'selected' : '' ?>>Comédia</option>
          </select> -->
          <select class="category-select">
            <option value="Tudo" <?php echo (!isset($_GET['category'])) ? 'selected' : '' ?>>Tudo</option>
            <?php foreach ($genero_manga['data'] as $genero) { ?>
              <option value="<?php echo $genero['genero_nome']; ?>" <?php echo (isset($_GET['category']) and $_GET['category'] == 'Romance') ? 'selected' : '' ?>><?php echo $genero['genero_nome'] ?></option>
            <?php } ?>
          </select>
        <?php endif; ?>
        <button><a href="?authors">Autores</a></button>
      </div>
    </nav>
  </header>

  <main>
    <!-- AUTORES -->
    <?php if (isset($_GET['authors'])) { ?>
      <?php if (!empty($authors)) { ?>
        <?php foreach ($authors as $author) :
          $manga_autor = $obj_manga->list_manga(["id_user" => $author['id_user']]);
        ?>
          <?php if ($manga_autor['status']) : ?>
            <!-- AUTORES -->
            <a href="./show_author.php?author=<?php echo $author['nome'] ?>">
              <div class="card">
                <div class="card-img" style="border-bottom: 1px solid #fff;">
                  <img src="data:image/*;base64,<?php echo $author['img'] ?>" alt="Descrição da imagem">
                </div>
                <div class="card-content">
                  <p style="color: #fff;"><?php echo $author['nome']; ?></p>
                  <!-- <?php foreach ($manga_autor['data'] as $manga) : ?>
                    <p><?php echo $manga['nome'] ?></p>
                  <?php endforeach; ?> -->
                  <p><?php echo count($manga_autor['data']); ?> Mangás</p>
                </div>
              </div>
            </a>
          <?php endif; ?>
        <?php endforeach; ?>
      <?php } else { ?>
        <p style="color: red;">Nenhum autor adicionado!</p>
      <?php } ?>
      <!-- FILTRO -->
    <?php } else if (isset($_GET['category'])) {
      $category = $_GET['category'];
      $filteredMangas = array();

      foreach ($mangas['data'] as $manga) {
        $genres = explode(', ', $manga['generos']);

        if (in_array($category, $genres)) {
          $filteredMangas[] = $manga;
        }
      }

      $mangas['data'] = $filteredMangas;

    ?>
      <?php if ($mangas['status'] and !empty($mangas['data'])) { ?>
        <?php foreach ($mangas['data'] as $manga) :
          $chapter_count = $obj_chapter->list_chapter($manga['id_manga']); ?>
          <!-- FILTRO -->
          <a href="../manga/index.php?manga=<?php echo $manga['id_manga']; ?>">
            <div class="card">
              <div class="card-img">
                <img src="data:image/*;base64,<?php echo $manga['img'] ?>" alt="Descrição da imagem">
              </div>
              <div class="card-content">
                <p><?php echo $manga['nome']; ?></p>
                <div class="transparent-p">
                  <p>
                    <?php
                    $genero_manga = $obj_genres->list_genres_manga($manga['id_manga'])['data'];
                    $genero_array = array();
                    foreach ($genero_manga as $genero) {
                      $genero_array[] = ($obj_genres->list_genres_id($genero['id_genero'])['data']['genero_nome']);
                      $implode_genero = implode(', ', $genero_array);
                    }
                    echo $implode_genero;
                    ?>
                  </p>
                  <p>Capítulos</p>
                  <p><?php echo count($chapter_count['data']); ?></p>
                </div>
              </div>
            </div>
          </a>
        <?php endforeach; ?>
      <?php } else { ?>
        <p style="color: red;">Nenhum mangá adicionado!</p>
      <?php } ?>
    <?php } else { ?>
      <!-- MENU PRINCIPAL -->
      <?php if ($mangas['status']) { ?>
        <?php foreach ($mangas['data'] as $manga) :
          $chapter_count = $obj_chapter->list_chapter(['id_manga' => $manga['id_manga']]);
        ?>
          <!-- MENU PRINCIPAL -->
          <a href="../manga/index.php?manga=<?php echo $manga['id_manga']; ?>">
            <div class="card">
              <div class="card-img">
                <img src="data:image/*;base64,<?php echo $manga['img'] ?>" alt="Descrição da imagem">
              </div>
              <div class="card-content">
                <p><?php echo $manga['nome']; ?></p>
                <div class="transparent-p">
                  <p>
                    <?php
                    $genero_manga = $obj_genres->list_genres_manga($manga['id_manga'])['data'];
                    $genero_array = array();
                    foreach ($genero_manga as $genero) {
                      $genero_array[] = ($obj_genres->list_genres_id($genero['id_genero'])['data']['genero_nome']);
                      $implode_genero = implode(', ', $genero_array);
                    }
                    echo $implode_genero;
                    ?>
                  </p>
                  <p>Capítulos</p>
                  <p><?php echo ($chapter_count['status'] ? count($chapter_count['data']) : '0') ?></p>
                </div>
              </div>
            </div>
          </a>
        <?php endforeach; ?>
      <?php } else { ?>
        <p style="color: red;">Nenhum mangá adicionado!</p>
      <?php } ?>
    <?php } ?>
  </main>

  <script src="./script.js"></script>
</body>

</html>
