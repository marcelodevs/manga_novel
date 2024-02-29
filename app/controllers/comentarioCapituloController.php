<?php

namespace NovelRealm;

session_start();

require_once __DIR__ . '\..\..\autoload.php';

use NovelRealm\CommentsModel;

$obj_comments = new CommentsModel;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  // var_dump($_POST);

  $id_capitulo = filter_input(INPUT_POST, 'id_capitulo');
  $id_user = filter_input(INPUT_POST, 'id_user');
  $comments_capitulo = filter_input(INPUT_POST, 'comments_capitulo');

  $data = array(
    "id_capitulo" => $id_capitulo,
    "id_user" => $id_user,
    "comments_capitulo" => $comments_capitulo,
  );

  // var_dump($data);

  $res = $obj_comments->add_comment_chapter($data);

  if ($res) {
    header("Location: ../views/capitulo/index.php?id=" . $id_capitulo);
  } else {
    echo "Erro ao publicar comentário!";
  }
}
