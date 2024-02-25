<?php

namespace NovelRealm;

session_start();

require_once __DIR__ . '\..\..\autoload.php';

use NovelRealm\MangaModel;
use NovelRealm\GenerosModel;

$obj_manga = new MangaModel;
$obj_gerens = new GenerosModel;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  // var_dump($_POST);

  $id_manga = filter_input(INPUT_POST, 'id_manga');
  $nome = filter_input(INPUT_POST, 'nome');
  $sinopse = filter_input(INPUT_POST, 'sinopse');

  $genero = array();

  foreach ($_POST['genero'] as $g) {
    $gen_id = $obj_gerens->list_genres_id($g)['data'];
    $genero[] = $gen_id;
  }

  $data = array(
    "id_manga" => $id_manga,
    "nome" => $nome,
    "genero" => $genero,
    "sinopse" => $sinopse
  );

  // var_dump($data);

  $res = $obj_manga->update_manga($data);

  if ($res) {
    header("Location: ../views/manga/edit_manga.php?edit=" . $id_manga);
  } else {
    echo "Ocorreu um erro ao atualizar o mangá!";
  }
}
