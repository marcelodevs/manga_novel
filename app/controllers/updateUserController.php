<?php

namespace NovelRealm;

session_start();

require_once __DIR__ . '\..\..\autoload.php';

use NovelRealm\UserModel;

$obj_user = new UserModel;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  $user = $obj_user->list_user($_POST['email'])['data'][0];

  if (empty($_FILES['img_profile']['tmp_name'])) {
    $imagemBase64 = $user['img'];
  } else {
    $caminhoArquivoTemporario = $_FILES['img_profile']['tmp_name'];
    $conteudoArquivo = file_get_contents($caminhoArquivoTemporario);
    $imagemBase64 = base64_encode($conteudoArquivo);
  }

  // var_dump($_POST);

  $id = $user['id_user'];
  $nome = filter_input(INPUT_POST, 'username');
  $email = filter_input(INPUT_POST, 'email');
  $senha = filter_input(INPUT_POST, 'password');


  $data = array(
    "id" => $id,
    "nome" => $nome,
    "email" => $email,
    "img" => $imagemBase64
  );

  // var_dump($data);

  $res = $obj_user->update_user($data);

  if ($res['status']) {
    header("Location: ../views/usuario/update_user.php");
  } else {
    echo $res['data'];
  }
}
