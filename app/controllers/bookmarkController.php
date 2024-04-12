<?php

namespace NovelRealm;

session_start();

require_once __DIR__ . '\..\..\autoload.php';

use NovelRealm\BookmarkDao;

$obj_bookmark = new BookmarkDao;

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

  // var_dump($_GET);

  $id_user = $_GET['id_user'];
  $id_manga = $_GET['id_manga'];


  $data = array(
    "id_user" => $id_user,
    "id_manga" => $id_manga
  );

  $validation_favorite = $obj_bookmark->validation_bookmarker($data);

  if (!$validation_favorite) {
    $res = $obj_bookmark->add_bookmark($data);

    if ($res) header("Location: ../views/manga/index.php?manga=" . $id_manga);
    else echo "Erro ao favoritar mangá!";
  } else {
    $res = $obj_bookmark->delete_bookmark($data);

    if ($res) header("Location: ../views/manga/index.php?manga=" . $id_manga);
    else echo "Erro ao desfavoritar o mangá!";
  }

  // var_dump($data);
}
