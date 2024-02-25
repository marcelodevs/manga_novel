<?php

namespace NovelRealm;

require_once __DIR__ . '\..\..\autoload.php';

use NovelRealm\Database;

class ChapterModel extends Database
{
  public $con;

  public function __construct()
  {
    $this->con = new Database;
  }

  /**
   * CRUD
   * 
   * @author Marcelo
   */

  public function add_chapter($data): bool
  {
    $con = $this->con->connect();

    $id_capitulo = mysqli_real_escape_string($con, $data['id_capitulo']);
    $id_manga = mysqli_real_escape_string($con, $data['id_manga']);
    $title = mysqli_real_escape_string($con, $data['title']);
    $content = mysqli_real_escape_string($con, $data['content']);

    $query = mysqli_query($con, "INSERT INTO capitulo (id_capitulo, id_manga, title, content) VALUES ('$id_capitulo', '$id_manga', '$title', '$content')");

    if ($query) {
      return true;
    } else {
      return false;
    }
  }

  public function list_chapter($data = null): array
  {
    $con = $this->con->connect();

    if (!is_null($data) && is_array($data)) {
      $id_manga = isset($data['id_manga']) ? mysqli_real_escape_string($con, $data['id_manga']) : '';
      $id_chapter = isset($data['id_chapter']) ? mysqli_real_escape_string($con, $data['id_chapter']) : '';

      $query = mysqli_query($con, "SELECT * FROM capitulo WHERE id_capitulo = " . (int)$id_chapter . " OR id_manga = " . (int)$id_manga . " ORDER BY id_capitulo ASC");

      if ($query) {
        $response = mysqli_fetch_all($query, MYSQLI_ASSOC);

        if (count($response) > 0) {
          return ["status" => true, "data" => $response];
        } else {
          return ["status" => false, "data" => "Nenhum capítulo encontrado"];
        }
      } else {
        return ["status" => false, "data" => "Erro ao executar a consulta: " . mysqli_error($con)];
      }
    } else {
      $query = mysqli_query($con, "SELECT * FROM capitulo ORDER BY id_capitulo ASC");

      if ($query) {
        $response = array();
        while ($row = mysqli_fetch_assoc($query)) {
          $response[] = $row;
        }

        if (count($response) > 0) {
          return ["status" => true, "data" => $response];
        } else {
          return ["status" => false, "data" => "Nenhum capítulo existente no banco de dados"];
        }
      } else {
        return ["status" => false, "data" => "Erro ao executar a consulta: " . mysqli_error($con)];
      }
    }
  }
}
