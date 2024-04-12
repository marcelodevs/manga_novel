<?php

namespace NovelRealm;

session_start();

require_once __DIR__ . '\..\..\autoload.php';

use NovelRealm\UserDao;

$obj_user = new UserDao;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  // var_dump($_POST);

  $nome = filter_input(INPUT_POST, 'username');
  $email = filter_input(INPUT_POST, 'username');
  $senha = filter_input(INPUT_POST, 'password');


  $data = array(
    "nome" => $nome,
    "email" => $email,
    "senha" => $senha,
  );

  // var_dump($data);

  $login = $obj_user->login_user($data);
  if ($login['status']) {
    $_SESSION['login_user'] = $login['data'];
    header("Location: ../views/usuario/index.php");
  } else {
    echo $login['data'];
  }
}
