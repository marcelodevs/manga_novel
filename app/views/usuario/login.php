<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="login.css">
  <link rel="shortcut icon" href="../Icons/book.png" type="image/x-icon">
  <title>MangáRealm • Login</title>
</head>

<body>
  <div class="container">
    <form action="../../controllers/loginController.php" method="post" class="form">
      <h2>Login</h2>
      <div class="form-group">
        <label for="username">Nome ou e-mail</label>
        <input type="text" id="username" name="username" required>
      </div>
      <div class="form-group">
        <label for="password">Senha</label>
        <input type="password" id="password" name="password" required>
      </div>
      <button type="submit">Entrar</button>
      <br>
      <p style="text-align: center;">Não possui conta? <a href="./registrar.php" style="color: white;">Cadastre-se</a></p>
    </form>
  </div>
</body>

</html>
