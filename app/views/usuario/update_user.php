<?php

namespace NovelRealm;

session_start();

require_once __DIR__ . '\..\..\..\autoload.php';

use NovelRealm\UserModel;

$obj_user = new UserModel;

if (isset($_SESSION['login_user'])) {
  $user = $obj_user->list_user($_SESSION['login_user'])['data'];
} else {
  header("Location: ./index.php");
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="update_user.css">
  <link rel="shortcut icon" href="../Icons/book.png" type="image/x-icon">
  <title>MangáRealm • Atualizar perfil</title>
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
    <div class="container">
      <form action="../../controllers/updateUserController.php" method="post" enctype="multipart/form-data" autocomplete="off" class="form">
        <h2>Atualizar Perfil</h2>
        <div class="form-group">
          <img src="data:image/*;base64,<?php echo $user['img'] ?>" class="profile" id="profileImage">
          <img src="../Icons/camera.png" class="camera" onclick="selectPhoto()">
          <input type="file" id="photoInput" accept="image/*" style="display: none;" name="img_profile">
        </div>
        <div class="form-group">
          <label for="username">Usuário</label>
          <input type="text" id="username" name="username" value="<?php echo $user['nome'] ?>">
        </div>
        <div class="form-group">
          <label for="email">E-mail</label>
          <input type="email" id="email" name="email" value="<?php echo $user['email'] ?>">
        </div>
        <button type="submit">Atualizar perfil</button>
      </form>
    </div>
  </main>

  <script>
    function selectPhoto() {
      var photoInput = document.getElementById('photoInput');
      photoInput.click();
    }

    var profileImage = document.getElementById('profileImage');
    var photoInput = document.getElementById('photoInput');
    photoInput.addEventListener('change', function(event) {
      var file = event.target.files[0];
      var reader = new FileReader();
      reader.onload = function(e) {
        profileImage.src = e.target.result;
      };
      reader.readAsDataURL(file);
    });

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
