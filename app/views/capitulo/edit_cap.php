<?php

namespace NovelRealm;

require_once __DIR__ . '\..\..\..\autoload.php';

session_start();

use NovelRealm\UserDao;
use NovelRealm\ChapterDao;
use NovelRealm\MangaDao;
use NovelRealm\GenerosDao;
use NovelRealm\CommentsDao;

$obj_user = new UserDao;
$obj_chapter = new ChapterDao;
$obj_manga = new MangaDao;
$obj_genres = new GenerosDao;
$obj_comments = new CommentsDao;

if (isset($_SESSION['login_user'])) {
  $user = $obj_user->list_user($_SESSION['login_user'])['data'];
}

if (isset($_GET['cap'])) {

  // var_dump($_GET);

  $id_chapter = $_GET['cap'];

  $chapter = $obj_chapter->return_sketch($id_chapter);

  // var_dump($chapter);

  if (isset($chapter) && $chapter['status']) {
    $chapter = $chapter['data'][0];
    $comments = $obj_comments->list_comments_chapter($chapter['id']);
  } else {
    header("Location: ../../../404.php?type=cap&error=2");
  }

  $manga = $obj_manga->list_manga_id($chapter['id_manga'])['data'][0];

  $genero_manga = $obj_genres->list_genres_manga($manga['id_manga'])['data'];

  // var_dump($generos);
} else if (isset($_GET['edit'])) {

  // var_dump($_GET);

  $id_chapter = $_GET['edit'];

  $chapter = $obj_chapter->return_sketch($id_chapter);

  // var_dump($chapter);

  if (isset($chapter) && $chapter['status']) {
    $chapter = $chapter['data'][0];
    $comments = $obj_comments->list_comments_chapter($chapter['id']);
  } else {
    header("Location: ../../../404.php?type=cap&error=2");
  }

  $manga = $obj_manga->list_manga_id($chapter['id_manga'])['data'][0];

  $genero_manga = $obj_genres->list_genres_manga($manga['id_manga'])['data'];
} else {
  header("Location: ../../../404.php?type=cap&error=1");
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../src/styles/edit_cap.css">
  <link rel="shortcut icon" href="../Icons/book.png" type="image/x-icon">
  <title>MangáRealm • <?php echo $manga['nome'] ?> - Capítulo <?php echo $chapter['id_capitulo'] ?></title>
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
    <hr>
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
    <!-- EDITAR CAPÍTULO -->
    <?php if (isset($_GET['edit'])) : ?>
      <form method="post" id="form-update" autocomplete="off" class="manga">
        <input type="hidden" name="id_ras" class="id_ras" value="<?php echo $chapter['id'] ?>">
        <div class="manga_nome">
          <p><?php echo $manga['nome'] ?></p>
        </div>
        <br>
        <div class="numero_capitulo">
          <p>Capítulo:</p>
          <input type="number" value="<?php echo $chapter['id_capitulo'] ?>" name="id_capitulo">
        </div>
        <br>
        <div class="title">
          <p>Título:</p>
          <input type="text" value="<?php echo $chapter['title'] ?>" name="title">
        </div>
        <br>
        <div class="content">
          <textarea id="chapter-content" style="white-space: pre-wrap;" name="content"><?php echo $chapter['content'] ?></textarea>
        </div>
      </form>
      <div class="acoes">
        <button type="submit" form="form-update" formaction="../../controllers/novoCapituloController.php" style="display: flex; align-items: center; gap: 10px;">
          <img src="../Icons/save-update.png" width="15" height="15">
          Salvar como <br> rascunho
        </button>
        <abbr title="Ao salvar como definitivo, você não poderá mais atualizar o capítulo!">
          <button class="salvar-definitivo" type="submit" form="form-update" formaction="../../controllers/updateCapituloController.php" style="display: flex; align-items: center; gap: 10px;">
            <img src="../Icons/save-update.png" width="15" height="15">
            Salvar como <br> definitivo
          </button>
        </abbr>
        <button type="delete" form="form-update" formaction="../../controllers/deleteCapituloController.php" style="display: flex; align-items: center; gap: 10px;">
          <img src="../Icons/delte.png" width="15" height="15">
          Excluir
        </button>
        <a href="?cap=<?php echo $chapter['id'] ?>">
          <button type="button" style="display: flex; align-items: center; gap: 10px;">
            <img src="../Icons/delte.png" width="15" height="15">
            Cancelar
          </button>
        </a>
      </div>
      <!-- VISUALIZAR CAPÍTULO -->
    <?php elseif (isset($_GET['cap'])) : ?>
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
        <a href="?edit=<?php echo $chapter['id'] ?>">
          <button type="button" style="display: flex; align-items: center; gap: 10px">
            <img src="../Icons/edit.png" width="15" height="15"> Editar
          </button>
        </a>
      </div>
    <?php endif; ?>
    <div class="comentarios-container">
      <h1>Comentários</h1>
      <div class="public-comment">
        <form action="../../controllers/comentarioCapituloController.php" method="post" class="send-message" autocomplete="off">
          <input type="hidden" name="id_capitulo" value="<?php echo isset($_SESSION['login_user']) ? $chapter['id'] : '' ?>">
          <input type="hidden" name="id_user" value="<?php echo isset($_SESSION['login_user']) ? $user['id_user'] : '' ?>">
          <input type="text" name="comments_capitulo" placeholder="<?php echo isset($_SESSION['login_user']) ? 'Deixe seu comentário' : 'Faça o login para comentar' ?>" <?php echo isset($_SESSION['login_user']) ? '' : 'disabled' ?>>
          <button type="submit" <?php echo isset($_SESSION['login_user']) ? '' : 'disabled' ?>>Enviar</button>
        </form>
      </div>
      <hr>
      <?php if ($comments['status']) : ?>
        <?php
        foreach ($comments['data'] as $comentarios) :
          $user_comment = $obj_user->list_user(['id_user' => $comentarios['id_user']])['data'];
        ?>
          <div class="comments" id="comment_<?php echo $comentarios['id_comments']; ?>">
            <div class="info-user">
              <img src="data:image/*;base64,<?php echo $user_comment['img'] ?>" width="20" height="20">
              <p><?php echo $user_comment['nome'] ?></p>
            </div>
            <p class="comment-text"><?php echo $comentarios['comments_capitulo']; ?></p>
            <div class="actions">
              <img src="../Icons/like.png" width="15" height="15" class="like-btn" data-comment-id="<?php echo $comentarios['id_comments']; ?>">
              <span class="likes-count"><?php echo $comentarios['curtidas']; ?></span>
              <p>Responder</p>
            </div>
            <br>
          </div>
        <?php endforeach; ?>
      <?php else : ?>
        <p>Nenhum comentário adicionado, seja o primeiro a comentar!</p>
      <?php endif; ?>
    </div>
  </main>

  <script src="../src/scripts/jquery-3.7.1.min.js"></script>
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

    $(document).ready(function() {
      $('.like-btn').click(function() {
        var commentId = $(this).data('comment-id');
        var userId = $(this).data('user-id');
        var likesCount = $(this).siblings('.likes-count');

        if ($(this).hasClass('liked')) {
          console.dir(newLikesCount);
          return;
        }

        $.ajax({
          type: 'POST',
          url: '../../controllers/curtidaComentarioController.php',
          data: {
            comment_id: commentId,
            user_id: userId
          },
          success: function(newLikesCount) {
            if (newLikesCount.status) {
              likesCount.text(newLikesCount.data[0].curtidas);
            }
          },
          error: function(error) {
            alert(error);
          }
        });
      });
    });

    var textarea = document.getElementById('chapter-content');

    textarea.addEventListener('input', function() {
      var startPos = textarea.selectionStart;
      var endPos = textarea.selectionEnd;
      var text = textarea.value;

      // Aplica negrito para texto envolto por **
      text = text.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');

      // Adicione mais expressões regulares para outras formatações, como itálico, sublinhado, etc.

      textarea.innerHTML = text;

      // Reposiciona o cursor
      textarea.setSelectionRange(startPos, endPos);
    });
  </script>
</body>

</html>
