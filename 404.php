<?php

if (isset($_GET['type']) && isset($_GET['error'])) {
  $type = $_GET['type'];
  $error = $_GET['error'];

  if ($type == "cap") {
    $error_message = array(
      "1" => "página não encontrada",
      "2" => "esse capítulo não existe!"
    );
  } elseif ($type == "manga") {
    $error_message = array(
      "1" => "página não encontrada",
      "2" => "esse mangá não existe!",
      "3" => "você não está logado!"
    );
  } elseif ($type == "user") {
    $error_message = array(
      "1" => "você não está logado!"
    );
  }

  $show_error = $error_message[$error] ?? "erro desconhecido";

  if (($type == 'user' || $type == 'manga' || $type == 'cap') && $error == '1') {
    $page_return = './app/views/usuario/login.php';
    exit;
  } elseif ($_SERVER['HTTP_REFERER'] == null) {
    $page_return = "./index.php";
    exit;
  } elseif ($_SERVER['HTTP_REFERER']) {
    $page_return = $_SERVER['HTTP_REFERER'];
    exit;
  }


  // var_dump(explode('/', $_SERVER['HTTP_REFERER'])[7]);
  // var_dump($_SERVER['HTTP_REFERER']);
} else {
  $show_error = "erro desconhecido";
  $page_return = "./index.php";
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="./app/views/Icons/book.png" type="image/x-icon">
  <title>MangáRealm • <?php echo mb_convert_case($show_error, MB_CASE_TITLE); ?></title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f3f3f3;
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      flex-direction: column;

      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);

      /* background: url(./app/views/Icons/404-aqua.gif);
      background-repeat: no-repeat;
      background-clip: border-box;
      background-size: 70%;
      background-position: 200px 40px;
      filter: brightness(0.25) opacity(0.75); */
      width: 100%;
    }

    h1 {
      font-size: 3rem;
      color: #333;
      /* color: #fff;
      -webkit-text-stroke: #000 .5px; */
      font-weight: 900;
      margin-bottom: 20px;
      text-align: center;
    }

    p {
      font-size: 1.2rem;
      color: #555;
      /* color: #fff;
      -webkit-text-stroke: #000 .5px; */
      font-weight: 900;
      text-align: center;
      margin-bottom: 40px;
    }

    img {
      max-width: 100%;
      height: auto;
    }

    a {
      text-decoration: none;
      color: #007bff;
      font-weight: 900;
    }

    @media (max-width: 768px) {
      h1 {
        font-size: 3rem;
      }

      p {
        font-size: 1rem;
      }
    }
  </style>
</head>

<body>
  <h1>Ops, <?php echo $show_error; ?></h1>
  <p>Parece que você entrou em uma dimensão desconhecida...</p>
  <img src="./app/views/Icons/404-aqua.gif" alt="404 - Página não encontrada">
  <p>Volte para a <a href="<?php echo $page_return; ?>">página anterior</a> ou tente novamente mais tarde.</p>
</body>

</html>
