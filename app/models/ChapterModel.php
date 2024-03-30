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
    $id_user = mysqli_real_escape_string($con, $data['id_user']);
    $title = mysqli_real_escape_string($con, $data['title']);
    $content = mysqli_real_escape_string($con, $data['content']);
    $rascunho = mysqli_real_escape_string($con, $data['rascunho']);

    $query = mysqli_query($con, "INSERT INTO capitulo (id_capitulo, id_manga, title, content, rascunho) VALUES ('$id_capitulo', '$id_manga', '$title', '$content', '$rascunho')");

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
      $id_chapter = isset($data['id']) ? mysqli_real_escape_string($con, $data['id']) : '';
      $chapter = isset($data['chapter']) ? mysqli_real_escape_string($con, $data['chapter']) : '';

      $query = mysqli_query($con, "SELECT * FROM capitulo WHERE (id = " . (int)$id_chapter . " AND rascunho = 'N') OR (id_manga = " . (int)$id_manga . " AND rascunho = 'N') OR (id_manga = " . (int)$id_manga . " AND id_capitulo = '$chapter') ORDER BY id_capitulo ASC");

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
      $query = mysqli_query($con, "SELECT * FROM capitulo WHERE rascunho = 'N' ORDER BY id_capitulo ASC");

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

  /**
   * MÉTODO PARA RETORNAR OS CAPÍTULOS SALVOS NO RASCUNHO
   * 
   * @author Marcelo
   */

  public function return_sketch($data): array
  {
    $con = $this->con->connect();

    $id_author = mysqli_real_escape_string($con, $data);

    $query = mysqli_query($con, "SELECT * FROM capitulo WHERE (id_usuario = " . (int)$id_author . " AND rascunho = 'S') OR (id = " . (int)$id_author . " AND rascunho = 'S')");

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
          "data" => "Nenhum rascunho existente no banco de dados"
        ];
      }
    }
  }

  /**
   * MÉTODO PARA ATUALIZAR RASCUNHO
   * 
   * @author Marcelo
   */

  public function update_sketch($data): bool
  {
    $con = $this->con->connect();

    $id = mysqli_real_escape_string($con, $data['id']);
    $id_capitulo = mysqli_real_escape_string($con, $data['id_capitulo']);
    $title = mysqli_real_escape_string($con, $data['title']);
    $content = mysqli_real_escape_string($con, $data['content']);
    $rascunho = mysqli_real_escape_string($con, $data['rascunho']);

    $query = mysqli_query($con, "UPDATE capitulo SET id_capitulo = '$id_capitulo', title = '$title', content = '$content', rascunho = '$rascunho' WHERE id = '$id'");

    if ($query) {
      return true;
    } else {
      return false;
    }
  }

  public function list_chapter_id($data): array
  {
    $con = $this->con->connect();
    $id_manga = isset($data['id_manga']) ? mysqli_real_escape_string($con, $data['id_manga']) : '';
    $chapter = isset($data['chapter']) ? mysqli_real_escape_string($con, $data['chapter']) : '';

    $query = mysqli_query($con, "SELECT * FROM capitulo WHERE id_manga = " . (int)$id_manga . " AND id_capitulo = '$chapter' AND rascunho = 'N' ORDER BY id_capitulo ASC");

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
  }

  /**
   * MÉTODO PARA PASSAR OU VOLTAR OS CAPÍTULOS
   * 
   * @author Marcelo
   */

  public function validation_chapter($cap, $manga, $action)
  {
    $manga_validation = ChapterModel::list_chapter(['id_manga' => $manga]);
    $res = null;


    if ($action == "pro") {
      foreach ($manga_validation['data'] as $validation) {
        // var_dump($validation);
        if ($cap + 1 == $validation['id_capitulo'] && $validation['id_manga'] == $manga) {
          $res = $cap + 1;
          break;
        }
      }
    } elseif ($action == "ant") {
      foreach ($manga_validation['data'] as $validation) {
        if ($cap - 1 == $validation['id_capitulo'] && $validation['id_manga'] == $manga) {
          $res = $cap - 1;
          break;
        }
      }
    }

    if ($res != null) {
      $handle_chapter = ChapterModel::list_chapter_id(['chapter' => $res, 'id_manga' => $manga]);
      return $handle_chapter['data'][0]['id'];
    }
  }
}
