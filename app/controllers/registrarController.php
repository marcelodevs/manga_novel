<?php

namespace NovelRealm;

session_start();

require_once __DIR__ . '\..\..\autoload.php';

use NovelRealm\UserModel;

$obj_user = new UserModel;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  if (empty($_FILES['img_profile']['tmp_name'])) {
    $caminhoArquivoTemporario = '../views/Icons/profile.png';
    $conteudoArquivo = file_get_contents($caminhoArquivoTemporario);
    $imagemBase64 = base64_encode($conteudoArquivo);
  } else {
    $caminhoArquivoTemporario = $_FILES['img_profile']['tmp_name'];
    $conteudoArquivo = file_get_contents($caminhoArquivoTemporario);
    $imagemBase64 = base64_encode($conteudoArquivo);
  }

  // var_dump($_POST);

  $nome = filter_input(INPUT_POST, 'username');
  $email = filter_input(INPUT_POST, 'email');
  $senha = filter_input(INPUT_POST, 'password');

  if (strlen($senha) > 8) {
    $email_validation = $obj_user->list_user($email);

    if (!$email_validation) {
      $data = array(
        "nome" => $nome,
        "email" => $email,
        "senha" => $senha,
        "img" => $imagemBase64
      );

      // var_dump($data);

      $res = $obj_user->add_user($data);

      if ($res['status']) {
        $login = $obj_user->login_user($data);
        if ($login['status']) {
          $_SESSION['login_user'] = $login['data'];
          header("Location: ../views/usuario/index.php");
        }
      }
    } else {
      echo "E-mail já usado, experimente fazer o login";
    }
  } else {
    echo "A senha precisa de, no mínimo, 8 caracteres!";
  }

}
