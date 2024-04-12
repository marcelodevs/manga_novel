<?php

namespace NovelRealm;

session_start();

require_once __DIR__ . '\..\..\..\autoload.php';

use NovelRealm\UserDao;
use NovelRealm\MangaDao;
use NovelRealm\GenerosDao;

$obj_user = new UserDao;
$obj_manga = new MangaDao;
$obj_genres = new GenerosDao;

if (isset($_SESSION['login_user'])) {
  $user = $obj_user->list_user($_SESSION['login_user'])['data'];
  $mangas = $obj_manga->list_manga(["id_user" => $user['id_user']]);

  $genero_manga = $obj_genres->list_genres()['data'];

  // var_dump($genero_manga);
} else {
  header("Location: ../usuario/index.php");
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../src/styles/new_manga.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
  <link rel="shortcut icon" href="../Icons/book.png" type="image/x-icon">
  <title>MangáRealm • Criar novo <?php if (isset($_GET['add'])) {
                                    if ($_GET['add'] == 'manga') {
                                      echo 'mangá';
                                    } elseif ($_GET['add'] == 'cap') {
                                      echo 'capítulo';
                                    }
                                  } ?></title>

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
    <?php if (empty($_GET)) { ?>
      <a href="?add=manga" class="card_manga">
        <img src="../Icons/create.png" alt="">
        <p>Novo <strong>Mangá</strong></p>
      </a>
      <a href="?add=cap" class="card_capther">
        <img src="../Icons/create.png" alt="">
        <p>Novo <strong>Capítulo</strong></p>
      </a>
    <?php } else if ($_GET['add'] == 'manga') { ?>
      <form action="../../controllers/novoMangaController.php" enctype="multipart/form-data" method="post" autocomplete="off" class="new_manga">
        <h1>Novo Mangá</h1>
        <div class="form-group">
          <label for="img">Imagem:</label>
          <input type="file" name="img" id="img" accept="image/*" required>
          <img src="" id="profileImage">
        </div>
        <div class="form-group">
          <label for="nome">Nome do mangá/light novel:</label>
          <input type="text" name="nome" id="nome" required>
        </div>
        <div class="form-group">
          <label for="genero">Gêneros:</label>
          <select class="js-example-basic-multiple" id="genero" name="genero[]" multiple="multiple" style="width: 100%;" required>
            <?php
            foreach ($genero_manga as $genero) {
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
        <div class="form-group">
          <label for="sinopse">Sinopse:</label>
          <textarea name="sinopse" id="sinopse" maxlength="500" accesskey="i" required></textarea>
          <span id="char-count">0</span>/500
        </div>
        <input type="hidden" name="autor" value="<?php echo $user['id_user']; ?>">
        <br>
        <button type="submit">Enviar</button>
      </form>

    <?php } else if ($_GET['add'] == 'cap') { ?>
      <form action="../../controllers/novoCapituloController.php" method="post" autocomplete="off" class="new-chapter">
        <h1>Novo Capítulo</h1>
        <div class="form-group">
          <label for="id">Nº Capítulo</label>
          <input type="number" name="id_capitulo" id="id" required>
        </div>
        <div class="form-group">
          <label for="manga">Mangá: </label>
          <input type="text" list="mangas" name="id_manga" id="manga" required>
          <datalist id="mangas">
            <?php foreach ($mangas['data'] as $manga) : ?>
              <option value="<?php echo $manga['id_manga'] ?>"><?php echo $manga['id_manga'] ?> - <?php echo $manga['nome'] ?></option>
            <?php endforeach; ?>
          </datalist>
        </div>
        <div class="form-group">
          <label for="title">Título do Capítulo</label>
          <input type="text" name="title" id="title" required>
        </div>
        <div class="form-group">
          <label for="content">Conteúdo</label>
          <textarea name="content" id="content" spellcheck="false" required></textarea>
        </div>
        <input type="hidden" name="id_user" value="<?php echo $user['id_user']; ?>">
        <div class="btn-group">
          <button type="submit" name="rascunho">Salvar Rascunho</button>
          <button type="submit">Enviar</button>
        </div>
      </form>
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

    $(document).ready(function() {
      $('.js-example-basic-multiple').select2();
    });

    var profileImage = document.getElementById('profileImage');
    var photoInput = document.getElementById('img');

    photoInput.addEventListener('change', function(event) {
      var file = event.target.files[0];
      var reader = new FileReader();

      reader.onload = function(e) {
        profileImage.src = e.target.result;
        adjustImageSize(profileImage);
      };

      reader.readAsDataURL(file);
    });

    function adjustImageSize(img) {
      img.onload = function() {
        var maxWidth = 100; // Máxima largura desejada
        var maxHeight = 100; // Máxima altura desejada

        var width = img.width;
        var height = img.height;

        var ratio = 1;

        // Calcula a proporção para ajustar a largura e altura
        if (width > maxWidth) {
          ratio = maxWidth / width;
          height *= ratio;
          width = maxWidth;
        }

        if (height > maxHeight) {
          ratio = maxHeight / height;
          width *= ratio;
          height = maxHeight;
        }

        img.width = width;
        img.height = height;
      };
    }

    document.getElementById('sinopse').addEventListener('input', function() {
      const maxLength = 0;
      const currentLength = this.value.length;
      const remaining = maxLength + currentLength;
      const charCount = document.getElementById('char-count');

      charCount.textContent = remaining;

      if (remaining >= 290) charCount.style.color = 'red';
      else charCount.style.color = '';
    });
  </script>
</body>

</html>
