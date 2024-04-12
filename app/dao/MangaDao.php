<?php

namespace NovelRealm;

require_once __DIR__ . '\..\..\autoload.php';

use NovelRealm\Database;
use NovelRealm\MangaModel;

class MangaDao
{
  public $con;

  public function __construct()
  {
    $this->con = new Database;
  }

  /**
   * CRUD
   * @author Marcelo
   */

  public function add_manga($data, $id_genero): bool
  {
    $con = $this->con->connect();

    $autor = mysqli_real_escape_string($con, $data['autor']);
    $nome = mysqli_real_escape_string($con, $data['nome']);
    $quantidade_capitulo = mysqli_real_escape_string($con, $data['quantidade_capitulo']);
    $sinopse = mysqli_real_escape_string($con, $data['sinopse']);
    $img = mysqli_real_escape_string($con, $data['img']);

    $dao = new MangaModel;
    $dao->setAutor($autor);
    $dao->setNome($nome);
    $dao->setQuantidade_capitulo($quantidade_capitulo);
    $dao->setSinopse($sinopse);
    $dao->setImg($img);

    $query = mysqli_query($con, "INSERT INTO manga (autor, nome, quantidade_capitulo, sinopse, img) VALUES ('" . $dao->getAutor() . "', '" . $dao->getNome() . "', '" . $dao->getQuantidade_capitulo() . "', '" . $dao->getSinopse() . "', '" . $dao->getImg() . "')");

    if ($query) {
      $id_manga = mysqli_insert_id($con); // Obtém o ID do mangá inserido

      foreach ($id_genero as $genero) {
        $query_gen = mysqli_query($con, "INSERT INTO manga_genero (id_manga, id_genero) VALUES ('$id_manga', '$genero')");
        if (!$query_gen) {
          mysqli_query($con, "DELETE FROM manga WHERE id_manga = $id_manga");
          return false;
        }
      }

      return true;
    } else {
      return false;
    }
  }

  public function update_manga($data): bool
  {
    $con = $this->con->connect();

    $id_manga = mysqli_real_escape_string($con, $data['id_manga']);
    $nome = mysqli_real_escape_string($con, $data['nome']);
    $sinopse = mysqli_real_escape_string($con, $data['sinopse']);

    $query = mysqli_query($con, "UPDATE manga SET nome = '$nome', sinopse = '$sinopse' WHERE id_manga = " . (int)$id_manga);

    if ($query) {
      mysqli_query($con, "DELETE FROM manga_genero WHERE id_manga = " . (int)$id_manga);

      // var_dump($data['genero']);

      for ($i = 0; $i < count($data['genero']); $i++) {
        $genero = $data['genero'][$i]['id_genero']; // Acessa o primeiro elemento do subarray
        var_dump($genero);
        $query_gen = mysqli_query($con, "INSERT INTO manga_genero (id_manga, id_genero) VALUES ('$id_manga', '$genero')");
        if (!$query_gen) {
          mysqli_query($con, "ROLLBACK");
          return false;
        }
      }
      return true;
    } else {
      return false;
    }
  }

  public function delete_manga($id_manga): bool
  {
    $con = $this->con->connect();

    $query = mysqli_query($con, "DELETE FROM manga WHERE id_manga = $id_manga");

    if ($query) {
      mysqli_query($con, "DELETE FROM manga_genero WHERE id_manga = $id_manga");
      return true;
    } else {
      return false;
    }
  }


  public function list_manga($data = null): array
  {
    $con = $this->con->connect();

    if (!is_null($data) && is_array($data)) {
      $id = isset($data['manga_id']) ? mysqli_real_escape_string($con, $data['manga_id']) : '';
      $autor = isset($data['autor']) ? mysqli_real_escape_string($con, $data['autor']) : mysqli_real_escape_string($con, $data['id_user']);

      $query = mysqli_query($con, "SELECT manga.*, GROUP_CONCAT(genero_nome) AS generos FROM manga LEFT JOIN manga_genero ON manga.id_manga = manga_genero.id_manga LEFT JOIN generos ON manga_genero.id_genero = generos.id_genero WHERE manga.id_manga = " . (int)$id . " OR manga.autor = '$autor' OR manga.nome = '$autor' GROUP BY manga.id_manga");

      if ($query) {
        $response = array();
        while ($row = mysqli_fetch_assoc($query)) {
          $row['generos'] = explode(',', $row['generos']);
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
            "data" => "Nenhum mangá encontrado"
          ];
        }
      } else {
        return [
          "status" => false,
          "data" => "Erro ao executar a consulta: " . mysqli_error($con)
        ];
      }
    } else {
      $query = mysqli_query($con, "SELECT manga.*, GROUP_CONCAT(genero_nome) AS generos FROM manga LEFT JOIN manga_genero ON manga.id_manga = manga_genero.id_manga LEFT JOIN generos ON manga_genero.id_genero = generos.id_genero GROUP BY manga.id_manga");

      if ($query) {
        $response = array();
        while ($row = mysqli_fetch_assoc($query)) {
          $row['generos'] = explode(', ', $row['generos']);
          $response[] = $row;
          // var_dump($row);
        }

        // var_dump($response);

        if (count($response) > 0) {
          return [
            "status" => true,
            "data" => $response
          ];
        } else {
          return [
            "status" => false,
            "data" => "Nenhum mangá existente no banco de dados"
          ];
        }
      } else {
        return [
          "status" => false,
          "data" => "Erro ao executar a consulta: " . mysqli_error($con)
        ];
      }
    }
  }

  /**
   * OUTROS MÉTODOS
   * @author Marcelo
   */

  public function list_manga_id($data): array
  {
    $con = $this->con->connect();

    $id = isset($data) ? mysqli_real_escape_string($con, $data) : '';

    $query = mysqli_query($con, "SELECT manga.*, GROUP_CONCAT(genero_nome) AS generos FROM manga LEFT JOIN manga_genero ON manga.id_manga = manga_genero.id_manga LEFT JOIN generos ON manga_genero.id_genero = generos.id_genero WHERE manga.id_manga = " . (int)$id . " GROUP BY manga.id_manga");

    if ($query) {
      $response = array();
      while ($row = mysqli_fetch_assoc($query)) {
        $row['generos'] = explode(',', $row['generos']);
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
          "data" => "Nenhum mangá encontrado"
        ];
      }
    } else {
      return [
        "status" => false,
        "data" => "Erro ao executar a consulta: " . mysqli_error($con)
      ];
    }
  }

  public function update_qntd_chapter($data): bool
  {
    $con = $this->con->connect();

    $quantidade_incrementada = mysqli_real_escape_string($con, $data['quantidade_incrementada']);
    $id_manga = mysqli_real_escape_string($con, $data['id_manga']);

    $query = mysqli_query($con, "UPDATE manga SET quantidade_capitulo = '$quantidade_incrementada' WHERE id_manga = " . (int)$id_manga);

    if ($query) {
      return true;
    } else {
      return false;
    }
  }

  public function search_manga($data): array
  {
    $con = $this->con->connect();

    $nome = mysqli_real_escape_string($con, $data);

    $query = mysqli_query($con, "SELECT id_manga, nome FROM manga WHERE nome LIKE '%$nome%'");

    if ($query) {
      $response = mysqli_fetch_all($query, MYSQLI_ASSOC);

      if (count($response) > 0) {
        return [
          "status" => true,
          "data" => $response
        ];
      } else {
        return [
          "status" => false,
          "data" => "Nenhum mangá encontrado"
        ];
      }
    } else {
      return [
        "status" => false,
        "data" => "Erro ao executar a consulta: " . mysqli_error($con)
      ];
    }
  }
}
