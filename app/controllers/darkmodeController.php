<?php

namespace NovelRealm;

require_once __DIR__ . '\..\..\autoload.php';

use NovelRealm\UserModel;

$obj_user = new UserModel;

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

  $dar_mode = $obj_user->get_preferences_dark_mode($_GET['id']);

  var_dump($dar_mode);

  if ($dar_mode) {
    $res = $obj_user->set_preferences_dark_mode($_GET['id'], 'N');
  } else {
    $res = $obj_user->set_preferences_dark_mode($_GET['id'], 'S');
  }

  if ($res) {
    header("Location: ../views/usuario/show_profile.php");
  } else {
    echo "Erro ao atualizar as preferências de modo escuro.";
  }
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
  var_dump($_GET);
}
