<?php

namespace NovelRealm;

header('Content-Type: application/json');

require_once __DIR__ . '\..\..\autoload.php';

use NovelRealm\MangaDao;

$obj_manga = new MangaDao;

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['search'])) {
  $search = mb_convert_case($_GET['search'], MB_CASE_TITLE);

  $manga_search = $obj_manga->search_manga($search);

  if ($manga_search['status']) {
    echo json_encode(['status' => true, 'data' => $manga_search['data']]);
  } else {
    echo json_encode(['status' => false, 'message' => 'Nenhum mangá encontrado']);
  }

  exit;
}
