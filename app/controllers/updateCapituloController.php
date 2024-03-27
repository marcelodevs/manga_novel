<?php

namespace NovelRealm;

session_start();

require_once __DIR__ . '\..\..\autoload.php';

use NovelRealm\ChapterModel;

$obj_chapter = new ChapterModel;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  // var_dump($_POST);

  $id = filter_input(INPUT_POST, "id_ras");
  $id_capitulo = filter_input(INPUT_POST, "id_capitulo");
  $title = filter_input(INPUT_POST, "title");
  $content = filter_input(INPUT_POST, "content");
  $rascunho = "N";

  $data = array(
    "id" => $id,
    "id_capitulo" => $id_capitulo,
    "title" => $title,
    "content" => $content,
    "rascunho" => $rascunho,
  );

  var_dump($data);

  if ($rascunho == "N") {
    $res = $obj_chapter->update_sketch($data);

    if ($res) {
      header("Location: http://localhost/dashboard/NovelRealm/app/views/capitulo/edit_cap.php?edit=" . $id);
    }
  }
}
