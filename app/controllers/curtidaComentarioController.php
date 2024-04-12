<?php

namespace NovelRealm;

header('Content-Type: application/json');

require_once __DIR__ . '\..\..\autoload.php';

use NovelRealm\ChapterDao;

$obj_chapter = new ChapterDao;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $id_comments = $_POST['comment_id'];
  $id_user = $_POST['user_id'];

  $already_liked = $obj_chapter->check_if_user_liked_comment($id_comments, $id_user);

  if ($already_liked) {
    echo json_encode(['status' => false, 'data' => 'Você já curtiu este comentário.']);
    exit;
  }

  $count_likes = $obj_chapter->search_likes($id_comments);

  if ($count_likes['status']) {
    $likes_search = intval($count_likes['data'][0]['curtidas']) + 1;

    $data = array(
      "id_comment" => $id_comments,
      "curtidas" => $likes_search
    );

    $update_likes_count = $obj_chapter->like_update($data);

    if ($update_likes_count) {
      $obj_chapter->save_user_like($id_comments, $id_user);

      $res = $obj_chapter->search_likes($id_comments);
      echo json_encode(['status' => true, 'data' => $res['data']]);
    }
  } else {
    echo json_encode(['status' => false, 'data' => 'Nenhum mangá encontrado']);
  }

  exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  $id_comments = $_GET['comment_id'];
  $id_user = $_GET['user_id'];

  $already_liked = $obj_chapter->check_if_user_liked_comment($id_comments, $id_user);

  if ($already_liked) {
    
    exit;
  }

  $count_likes = $obj_chapter->search_likes($id_comments);

  if ($count_likes['status']) {
    $likes_search = count($count_likes['data']) + 1;

    $data = array(
      "id_comment" => $id_comments,
      "curtidas" => $likes_search
    );

    $update_likes_count = $obj_chapter->like_update($data);

    if ($update_likes_count) {
      $obj_chapter->save_user_like($id_comments, $id_user);

      $res = $obj_chapter->search_likes($id_comments);
      echo json_encode(['status' => true, 'data' => $res['data']]);
    }
  } else {
    echo json_encode(['status' => false, 'data' => $count_likes['data']]);
  }

  exit;
}
