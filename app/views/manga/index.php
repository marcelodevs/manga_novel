<?php

namespace NovelRealm;

require_once __DIR__ . '\..\..\..\autoload.php';

session_start();

use NovelRealm\MangaModel;
use NovelRealm\UserModel;
use NovelRealm\ChapterModel;
use NovelRealm\CommentsModel;
use NovelRealm\GenerosModel;

$obj_manga = new MangaModel;
$obj_user = new UserModel;
$obj_chapter = new ChapterModel;
$obj_comments = new CommentsModel;
$obj_genres = new GenerosModel;

if (isset($_SESSION['login_user'])) {
  $user = $obj_user->list_user($_SESSION['login_user'])['data'];
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
    header("Location: ../usuario/index.php");
  }

  $chapter = $obj_chapter->list_chapter(['id_manga' => $id_manga]);

  $comments = $obj_comments->list_comments_manga($id_manga);

  // var_dump($chapter);
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
  <title>MangáRealm • <?php echo $manga['nome'] ?></title>
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
        <p>Autor:</p>
        <a href="../usuario/show_author.php?author=<?php echo $autor['nome'] ?>" style="color: #fff; cursor: pointer;">
          <?php echo $autor['nome'] ?>
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
    <div class="show-cap">
      <button class="toggle-capitulos">Mostrar capítulos</button>
      <img src="../Icons/down.png" alt="">
      <div class="capitulos">
        <?php if ($chapter['status']) : ?>
          <ul>
            <?php foreach ($chapter['data'] as $capitulo) : ?>
              <li><a href="../capitulo/index.php?id=<?php echo $capitulo['id'] ?>">Capítulo <?php echo $capitulo['id_capitulo'] ?></a></li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
      </div>
    </div>
  </main>

  <script src="./script.js"></script>
</body>

</html>
