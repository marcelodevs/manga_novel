<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./registrar.css">
  <link rel="shortcut icon" href="../Icons/book.png" type="image/x-icon">
  <title>MangáRealm • Cadastro</title>
</head>

<body>
  <div class="container">
    <form action="../../controllers/registrarController.php" method="post" enctype="multipart/form-data" autocomplete="off" class="form">
      <h2>Cadastro</h2>
      <div class="form-group">
        <img src="../Icons/profile.png" class="profile" id="profileImage">
        <img src="../Icons/camera.png" class="camera" onclick="selectPhoto()">
        <input type="file" id="photoInput" accept="image/*" style="display: none;" name="img_profile">
      </div>
      <div class="form-group">
        <label for="username">Usuário</label>
        <input type="text" id="username" name="username" required>
      </div>
      <div class="form-group">
        <label for="email">E-mail</label>
        <input type="email" id="email" name="email" required>
      </div>
      <div class="form-group">
        <label for="password">Senha</label>
        <input type="password" id="password" name="password" minlength="8" maxlength="10" pattern="[A-Za-z0-9!@.]*" title="A senha deve possuir no mínimo 5 caracteres e não pode conter aspas, espaços e o caracter +" required>
      </div>
      <button type="submit">Cadastrar</button>
      <br>
      <p style="text-align: center;">Já é membro? <a href="./login.php" style="color: white;">Conecte-se</a></p>
    </form>
  </div>

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
  </script>
</body>

</html>
