<?php

namespace NovelRealm;

require_once __DIR__ . '\..\..\autoload.php';

use NovelRealm\Database;
use NovelRealm\ChapterModel;

class ChapterDao
{
  public Database $con;
  public ChapterModel $chapter_model;

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

    $dao = $this->chapter_model = new ChapterModel($id_capitulo, $id_manga, $id_user, $title, $content, $rascunho);

    $query = mysqli_query($con, "INSERT INTO capitulo (id_capitulo, id_manga, title, content, rascunho, id_usuario) VALUES ('" . $dao->getId_capitulo() . "', '" . $dao->getId_manga() . "', '" . $dao->getTitle() . "', '" . $dao->getContent() . "', '" . $dao->getRascunho() . "', '" . $dao->getId_user() . "')");

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
   * @param Número do Capítulo
   * @param Id do Mangá
   * @param Ação (pro: próximo capítulo; ant: capítulo anterior)
   * 
   * @author Marcelo
   */

  public function validation_chapter($cap, $manga, $action)
  {
    $manga_validation = $this->list_chapter(['id_manga' => $manga]);
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
      $handle_chapter = $this->list_chapter_id(['chapter' => $res, 'id_manga' => $manga]);
      return $handle_chapter['data'][0]['id'];
    }
  }

  /**
   * MÉTODO PARA BUSCAR A QUANTIDADE DE CURTIDAS DOS COMENTÁRIOS 
   *
   * @author Marcelo
   */

  public function search_likes($data): array
  {
    $con = $this->con->connect();

    $id_comment = mysqli_real_escape_string($con, $data) ?? ''; // SE FOR NULO, RETORNA O DA ESQUERDA, E VICE VERSA

    $query = mysqli_query($con, "SELECT * FROM curtidas_comentarios_capitulo");

    if ($query) {
      $response = array();
      while ($row = mysqli_fetch_assoc($query)) {
        $response[] = $row;
      }

      if (count($response) >= 0) {
        return [
          "status" => true,
          "data" => $response
        ];
      } else {
        return [
          "status" => false,
          "data" => "Nenhuma curtida existente no banco de dados"
        ];
      }
    }
  }

  /**
   * MÉTODO PARA ATUALIZAR A QUANTIDADE DE CURTIDAS DOS COMENTÁRIOS
   * 
   * @author Marcelo
   */

  public function like_update($data): bool
  {
    $con = $this->con->connect();

    $id_comment = mysqli_real_escape_string($con, $data['id_comment']) ?? '';
    $curtidas = mysqli_real_escape_string($con, $data['curtidas']) ?? '';

    $query = mysqli_query($con, "UPDATE comentarios_capitulo SET curtidas = '$curtidas' WHERE id_comments = " . (int)$id_comment);

    if ($query) {
      return true;
    } else {
      return false;
    }
  }

  public function check_if_user_liked_comment($id_comment, $id_user): bool
  {
    $con = $this->con->connect();

    $id_comment = mysqli_real_escape_string($con, $id_comment);
    $id_user = mysqli_real_escape_string($con, $id_user);

    $query = mysqli_query($con, "SELECT * FROM curtidas_comentarios_capitulo WHERE id_comment = $id_comment AND id_user = $id_user");

    if ($query) {
      if (mysqli_num_rows($query) > 0) {
        return true;
      } else {
        return false;
      }
    } else {
      return false;
    }
  }

  public function save_user_like($id_comment, $id_user): array
  {
    $con = $this->con->connect();

    $id_comment = mysqli_real_escape_string($con, $id_comment);
    $id_user = mysqli_real_escape_string($con, $id_user);

    if ($this->check_if_user_liked_comment($id_comment, $id_user)) {
      return [
        "status" => false,
        "data" => "Você já curtiu este comentário."
      ];
    }

    $query = mysqli_query($con, "INSERT INTO curtidas_comentarios_capitulo (id_comment, id_user) VALUES ('$id_comment', '$id_user')");

    if ($query) {
      return [
        "status" => true,
        "data" => "Curtida adicionada com sucesso."
      ];
    } else {
      return [
        "status" => false,
        "data" => "Erro ao adicionar a curtida: " . mysqli_error($con)
      ];
    }
  }

  public function delete_like($data): bool
  {
    $con = $this->con->connect();

    $id_comment = mysqli_real_escape_string($con, $data['id_comment']);
    $id_user = mysqli_real_escape_string($con, $data['id_user']);

    $query = mysqli_query($con, "DELETE FROM curtidas_comentarios_capitulo WHERE id_comment = $id_comment AND id_user = $id_user");

    if ($query) {
      return true;
    } else {
      return false;
    }
  }
}
