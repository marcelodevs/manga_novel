<?php

namespace NovelRealm;

session_start();

require_once __DIR__ . '\..\..\autoload.php';

use NovelRealm\ChapterModel;
use NovelRealm\MangaModel;

$obj_chapter = new ChapterModel;
$obj_manga = new MangaModel;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  // var_dump($_POST);

  $id_capitulo = filter_input(INPUT_POST, 'id_capitulo');
  $id_manga = filter_input(INPUT_POST, 'id_manga');
  $title = filter_input(INPUT_POST, 'title');
  $content = filter_input(INPUT_POST, 'content');

  $data = array(
    "id_capitulo" => $id_capitulo,
    "id_manga" => $id_manga,
    "title" => $title,
    "content" => $content,
  );

  // var_dump($data);

  $res = $obj_chapter->add_chapter($data);

  if ($res) {
    $manga_adicionado = $obj_manga->list_manga_id($id_manga);
    if ($manga_adicionado) {
      $quantidade_incrementada = $manga_adicionado['data'][0]['quantidade_capitulo'] + 1;
      $data_update = array("quantidade_incrementada" => $quantidade_incrementada, "id_manga" => $id_manga);
      var_dump($data_update);
      $up = $obj_manga->update_qntd_chapter($data_update);
      // var_dump($up);
      if ($up) {
        header("Location: ../views/usuario/index.php");
      } else {
        echo $up['data'];
      }
    }
  } else {
    echo $res['data'];
  }
}
