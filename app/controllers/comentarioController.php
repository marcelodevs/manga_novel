<?php

namespace NovelRealm;

session_start();

require_once __DIR__ . '\..\..\autoload.php';

use NovelRealm\CommentsDao;

$obj_comments = new CommentsDao;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  // var_dump($_POST);

  $id_manga = filter_input(INPUT_POST, 'id_manga');
  $id_user = filter_input(INPUT_POST, 'id_user');
  $comments_manga = filter_input(INPUT_POST, 'comments_manga');

  $data = array(
    "id_manga" => $id_manga,
    "id_user" => $id_user,
    "comments_manga" => $comments_manga,
  );

  // var_dump($data);

  $res = $obj_comments->add_comment_manga($data);

  if ($res) {
    header("Location: ../views/manga/index.php?manga=" . $id_manga);
  } else {
    echo "Erro ao publicar comentário!";
  }
}
