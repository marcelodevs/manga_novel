<?php

namespace NovelRealm;

require_once __DIR__ . '\..\..\..\autoload.php';

session_start();

use NovelRealm\MangaDao;
use NovelRealm\UserDao;
use NovelRealm\ChapterDao;
use NovelRealm\CommentsDao;
use NovelRealm\GenerosDao;

$obj_manga = new MangaDao;
$obj_user = new UserDao;
$obj_chapter = new ChapterDao;
$obj_comments = new CommentsDao;
$obj_genres = new GenerosDao;

if (isset($_SESSION['login_user'])) {
  $user = $obj_user->list_user($_SESSION['login_user'])['data'];
} else {
  header("Location: ../../../404.php?type=manga&error=3");
}

if (isset($_GET['manga'])) {
  // var_dump($_GET);

  $id_manga = $_GET['manga'];

  // var_dump('id: ' . $id_manga);

  $manga = $obj_manga->list_manga_id($id_manga);

  if ($manga['status']) {
    $manga = $obj_manga->list_manga_id($id_manga)['data'][0];

    $autor = $obj_user->list_user(["id_user" => $manga['autor']])['data'];

    $genero_manga = $obj_genres->list_genres_manga($id_manga)['data'];
  } else {
    header("Location: ../../../404.php?type=manga&error=2");
  }

  $chapter = $obj_chapter->list_chapter(['id_manga' => $id_manga]);

  $comments = $obj_comments->list_comments_manga($id_manga);

  // var_dump($chapter);
} else if (isset($_GET['edit'])) {
  // var_dump($_GET);

  $id_manga = $_GET['edit'];

  // var_dump('id: ' . $id_manga);

  $manga = $obj_manga->list_manga_id($id_manga);

  if ($manga['status']) {
    $manga = $obj_manga->list_manga_id($id_manga)['data'][0];

    // var_dump($manga);

    $generos = $obj_genres->list_genres()['data'];

    $generos_manga = $obj_genres->list_genres_manga($id_manga)['data'];

    // var_dump($generos_manga);

    $autor = $obj_user->list_user(["id_user" => $manga['autor']])['data'];
  } else {
    header("Location: ../../../404.php?type=manga&error=2");
  }

  $chapter = $obj_chapter->list_chapter(['id_manga' => $id_manga]);

  $comments = $obj_comments->list_comments_manga($id_manga);
} else {
  header("Location: ../../../404.php?type=manga&error=1");
  // var_dump($_GET);
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../src/styles/edit_manga.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
  <link rel="shortcut icon" href="../Icons/book.png" type="image/x-icon">
  <title>MangáRealm • Editar <?php echo $manga['nome'] ?></title>
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
            <button type="button"><a href="./new_manga.php"><img src="../Icons/create.png">Criar</a></button>
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
  </header>

  <main class="container">
    <?php if (isset($_GET['edit'])) { ?>
      <!-- EDITAR MANGÁ -->
      <div class="image-manga">
        <img src="data:image/*;base64,<?php echo $manga['img'] ?>">
      </div>
      <form class="content" id="form-update" method="post" autocomplete="off">
        <input type="hidden" name="id_manga" value="<?php echo $manga['id_manga'] ?>">
        <div class="nome">
          <p>Nome:</p>
          <input type="text" name="nome" value="<?php echo $manga['nome'] ?>">
        </div>
        <br>
        <div class="genero">
          <p>Gênero:</p>
          <select class="js-example-basic-multiple" name="genero[]" multiple="multiple">
            <?php
            foreach ($generos as $genero) {
              $id_genero = $genero['id_genero'];
              $selected = '';
              foreach ($generos_manga as $genero_manga) {
                if ($genero_manga['id_genero'] == $id_genero) {
                  $selected = 'selected';
                  break;
                }
              }
              echo "<option value=\"$id_genero\" $selected>{$genero['genero_nome']}</option>";
            }
            ?>
          </select>
        </div>
        <br>
        <div class="sinopse">
          <p>Sinopse:</p>
          <textarea id="sinopse" name="sinopse" maxlength="500"><?php echo $manga['sinopse'] ?></textarea>
          <span id="char-count"><?php echo strlen($manga['sinopse']) ?></span>/500
        </div>
      </form>
      <div class="lr-group" style="margin-top: -70px;">
        <button type="submit" form="form-update" formaction="../../controllers/updateMangaController.php" style="display: flex; align-items: center; gap: 10px;">
          <img src="../Icons/save-update.png" width="15" height="15">
          Salvar
        </button>
        <button type="delete" form="form-update" formaction="../../controllers/deleteMangaController.php" style="display: flex; align-items: center; gap: 10px;">
          <img src="../Icons/delte.png" width="15" height="15">
          Excluir
        </button>
      </div>
    <?php } elseif (isset($_GET['manga'])) { ?>
      <!-- VISUALIZAR MANGÁ -->
      <div class="image-manga">
        <img src="data:image/*;base64,<?php echo $manga['img'] ?>">
      </div>
      <div class="content">
        <div class="nome">
          <p>Nome:</p>
          <p><?php echo $manga['nome'] ?></p>
        </div>
        <br>
        <div class="nome-popular">
          <p>Ver página do mangá:</p>
          <a href="./index.php?manga=<?php echo $manga['id_manga'] ?>" style="color: #fff; cursor: pointer;">
            <?php echo $manga['nome'] ?>
          </a>
        </div>
        <br>
        <div class="genero">
          <p>Gênero:</p>
          <p>
            <?php
            $genero_array = array();
            foreach ($genero_manga as $genero) {
              $genero_array[] = ($obj_genres->list_genres_id($genero['id_genero'])['data']['genero_nome']);
              $implode_genero = implode(', ', $genero_array);
            }
            echo $implode_genero;
            ?>
          </p>
        </div>
        <br>
        <div class="qntd-capitulos">
          <p>Capítulos:</p>
          <p><?php echo $manga['quantidade_capitulo'] ?></p>
        </div>
        <br>
        <div class="sinopse" onclick="toggleSinopse()">
          <abbr title="Ler sinopse">
            <p>Sinopse <img src="../Icons/down.png" class="external-link" width="15" height="15"></p>
          </abbr>
          <div class="sinopse-tab" onclick="stopPropagation(event)">
            <p><?php echo $manga['sinopse'] ?></p>
          </div>
        </div>
        <br>
        <div class="comments" onclick="toggleCommentsTab()">
          <abbr title="Ver comentários">
            <p>Comentários <img src="../Icons/external-link.png" class="external-link" width="15" height="15"></p>
          </abbr>
          <div class="comments-tab" onclick="stopPropagation(event)">
            <?php if ($comments['status']) : ?>
              <div class="comments-group">
                <?php
                foreach ($comments['data'] as $comentarios) :
                  $user_comment = $obj_user->list_user(['id_user' => $comentarios['id_user']])['data'];
                ?>
                  <p><img src="data:image/*;base64,<?php echo $user_comment['img'] ?>" width="20" height="20"><?php echo $user_comment['nome'] ?></p>
                  <span><?php echo $comentarios['comments_manga']; ?></span>
                  <hr style="border: 1px solid #fff;">
                  <br>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
            <form action="../../controllers/comentarioController.php" method="post" class="send-message" autocomplete="off">
              <input type="hidden" name="id_manga" value="<?php echo isset($_SESSION['login_user']) ? $manga['id_manga'] : '' ?>">
              <input type="hidden" name="id_user" value="<?php echo isset($_SESSION['login_user']) ? $user['id_user'] : '' ?>">
              <input type="text" name="comments_manga" placeholder="<?php echo isset($_SESSION['login_user']) ? 'Deixe seu comentário' : 'Faça o login para comentar' ?>" <?php echo isset($_SESSION['login_user']) ? '' : 'disabled' ?>>
              <button type="submit" <?php echo isset($_SESSION['login_user']) ? '' : 'disabled' ?>>Enviar</button>
            </form>
          </div>
        </div>
      </div>
      <a href="?edit=<?php echo $manga['id_manga'] ?>" class="lr-group" style="margin-top: -70px;">
        <button type="button" style="display: flex; align-items: center; gap: 10px">
          <img src="../Icons/edit.png" width="15" height="15"> Editar
        </button>
      </a>
    <?php } ?>
  </main>

  <script src="http://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
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

    <?php if (isset($_GET['edit'])) { ?>

      document.getElementById('sinopse').addEventListener('input', function() {
        const maxLength = 0;
        const currentLength = this.value.length;
        const remaining = maxLength + currentLength;
        const charCount = document.getElementById('char-count');

        charCount.textContent = remaining;

        if (remaining >= 290) charCount.style.color = 'red';
        else charCount.style.color = '';
      });

      $(document).ready(function() {
        $('.js-example-basic-multiple').select2();
      });

    <?php } else { ?>

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

    <?php } ?>
  </script>
</body>

</html>
