<?php

namespace NovelRealm;

session_start();

require_once __DIR__ . '\..\..\autoload.php';

use NovelRealm\MangaDao;

$obj_manga = new MangaDao;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  // var_dump($_POST);

  $nome = filter_input(INPUT_POST, 'nome');
  $autor = filter_input(INPUT_POST, 'autor');

  $caminhoArquivoTemporario = $_FILES['img']['tmp_name'];
  $conteudoArquivo = file_get_contents($caminhoArquivoTemporario);
  $imagemBase64 = base64_encode($conteudoArquivo);

  $generos = $_POST['genero'];
  $genero = implode(', ', $generos);

  // var_dump(explode(', ', $genero));

  $data = array(
    "autor" => $autor,
    "nome" => $nome,
    "generos" => $genero,
    "quantidade_capitulo" => 0,
    "img" => $imagemBase64
  );

  // var_dump($data);

  $res = $obj_manga->add_manga($data);

  if ($res) {
    header("Location: ../views/usuario/index.php");
  } else {
    echo "Erro ao adicionar mangá!";
  }
}
