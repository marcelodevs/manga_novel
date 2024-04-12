<?php

namespace NovelRealm;

require_once __DIR__ . '\..\..\autoload.php';

use NovelRealm\Database;
use NovelRealm\GenereosModel;
use NovelRealm\MangaGeneroModel;

class GenerosDao
{
  private $con;

  public function __construct()
  {
    $this->con = new Database;
  }

  /**
   * CRUD
   * 
   * @author Marcelo
   */

  public function list_genres(): array
  {
    $con = $this->con->connect();

    $query = mysqli_query($con, "SELECT * FROM generos");

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
          "data" => "Nenhum gênero existente no banco de dados"
        ];
      }
    }
  }

  public function list_genres_id($data): array
  {
    $con = $this->con->connect();

    // var_dump($data);

    $id_genre = mysqli_real_escape_string($con, $data);

    $dao = new GenereosModel;
    $dao->setId_genero($id_genre);
    $dao->setGenero_nome($id_genre);

    $query = mysqli_query($con, "SELECT * FROM generos WHERE id_genero = " . (int)$dao->getId_genero() . " OR genero_nome = '" . $dao->getGenero_nome() . "'");

    if ($query) {
      $response = mysqli_fetch_assoc($query);

      if (count($response) > 0) {
        // var_dump($response);
        return [
          "status" => true,
          "data" => $response
        ];
      } else {
        return [
          "status" => false,
          "data" => "Nenhum gênero existente no banco de dados"
        ];
      }
    }
  }

  public function list_genres_manga($data): array
  {
    $con = $this->con->connect();

    $id = mysqli_real_escape_string($con, $data);

    $dao = new MangaGeneroModel;
    $dao->setId_manga($id);

    $query = mysqli_query($con, "SELECT * FROM manga_genero WHERE id_manga = " . (int)$dao->getId_manga());

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
          "data" => "Nenhum gênero existente no banco de dados"
        ];
      }
    }
  }
}
