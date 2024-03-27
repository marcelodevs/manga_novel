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
  $id_user = filter_input(INPUT_POST, 'id_user');
  $title = filter_input(INPUT_POST, 'title');
  $content = filter_input(INPUT_POST, 'content');

  if (isset($_POST['rascunho'])) {
    $rascunho = 'S';

    $data = array(
      "id_capitulo" => $id_capitulo,
      "id_manga" => $id_manga,
      "id_user" => $id_user,
      "title" => $title,
      "content" => $content,
      "rascunho" => $rascunho,
    );
  } else if (isset($_POST['id_ras'])) {
    $id_rascunho = filter_input(INPUT_POST, 'id_ras');
    $verification_rascunho = $obj_chapter->return_sketch($id_rascunho);
    if ($verification_rascunho['status']) {
      $rascunho = 'S';

      $data = array(
        "id" => $id_rascunho,
        "id_capitulo" => $id_capitulo,
        "title" => $title,
        "content" => $content,
        "rascunho" => $rascunho,
      );

      $res = $obj_chapter->update_sketch($data);

      if ($res) {
        header("Location: ../views/capitulo/edit_cap.php?edit=" . $id_rascunho);
      } else {
        echo "deu erro irmão";
      }
    }
  } else {
    $rascunho = 'N';

    $data = array(
      "id_capitulo" => $id_capitulo,
      "id_manga" => $id_manga,
      "id_user" => $id_user,
      "title" => $title,
      "content" => $content,
      "rascunho" => $rascunho,
    );
  }

  var_dump($data);

  // $res = $obj_chapter->add_chapter($data);

  // if ($res) {
  //   $manga_adicionado = $obj_manga->list_manga_id($id_manga);
  //   if ($manga_adicionado) {
  //     $quantidade_incrementada = $manga_adicionado['data'][0]['quantidade_capitulo'] + 1;
  //     $data_update = array("quantidade_incrementada" => $quantidade_incrementada, "id_manga" => $id_manga);
  //     var_dump($data_update);
  //     $up = $obj_manga->update_qntd_chapter($data_update);
  //     // var_dump($up);
  //     if ($up) {
  //       header("Location: ../views/usuario/index.php");
  //     } else {
  //       echo $up['data'];
  //     }
  //   }
  // } else {
  //   echo $res['data'];
  // }
}
