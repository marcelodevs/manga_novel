<?php

namespace NovelRealm;

require_once __DIR__ . '\..\..\autoload.php';

use NovelRealm\Database;

class CommentsModel extends Database
{
  public $con;

  public function __construct()
  {
    $this->con = new Database;
  }

  /**
   * CRUD DE COMENTÁRIOS PARA O MANGÁ
   * 
   * @author Marcelo
   */

  public function add_comment_manga($data): bool
  {
    $con = $this->con->connect();

    $id_manga = mysqli_real_escape_string($con, $data['id_manga']);
    $id_user = mysqli_real_escape_string($con, $data['id_user']);
    $comments_manga = mysqli_real_escape_string($con, $data['comments_manga']);

    $query = mysqli_query($con, "INSERT INTO comentarios_manga (id_manga, id_user, comments_manga) VALUES ('$id_manga', '$id_user', '$comments_manga')");

    if ($query) {
      return true;
    } else {
      return false;
    }
  }

  public function list_comments($data): array
  {
    $con = $this->con->connect();

    $id = mysqli_real_escape_string($con, $data);

    $query = mysqli_query($con, "SELECT * FROM comentarios_manga WHERE id_manga = " . (int)$id);

    if ($query) {
      $response = array();
      while ($row = mysqli_fetch_assoc($query)) {
        $response[] = $row;
      }

      if (count($response) > 0) {
        return [
          "status" => true,
          "data" => $response
        ];
      } else {
        return [
          "status" => false,
          "data" => "Nenhum comentário existente no banco de dados"
        ];
      }
    } else {
      return [
        "status" => false,
        "data" => "Erro ao executar a consulta: " . mysqli_error($con)
      ];
    }
  }


  /**
   * CRUD DE COMENTÁRIOS PARA O CAPÍTULO
   * 
   * @author Marcelo
   */

  public function add_comment_chapter($data): bool
  {
    $con = $this->con->connect();

    $id_capitulo = mysqli_real_escape_string($con, $data['id_capitulo']);
    $id_user = mysqli_real_escape_string($con, $data['id_user']);
    $comments_capitulo = mysqli_real_escape_string($con, $data['comments_capitulo']);

    $query = mysqli_query($con, "INSERT INTO comentarios_capitlo (id_capitulo, id_user, comments_capitulo) VALUES ('$id_capitulo', '$id_user', '$comments_capitulo')");

    if ($query) {
      return true;
    } else {
      return false;
    }
  }
}
