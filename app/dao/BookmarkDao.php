<?php

namespace NovelRealm;

require_once __DIR__ . "\..\..\autoload.php";

use NovelRealm\Database;
use NovelRealm\BookmarkModel;

class BookmarkDao
{
  private $con;

  public function __construct()
  {
    $this->con = new Database;
  }

  /**
   * Método para adicionar favoritos
   * 
   * @author Marcelo
   */

  public function add_bookmark($data): bool
  {
    $con = $this->con->connect();

    $id_user = mysqli_real_escape_string($con, $data['id_user']);
    $id_manga = mysqli_real_escape_string($con, $data['id_manga']);

    $dao = new BookmarkModel;
    $dao->setId_manga($id_manga);
    $dao->setId_user($id_user);

    $query = mysqli_query($con, "INSERT INTO favorito (id_user, id_manga) VALUES ('" . $dao->getId_user() . "', '" . $dao->getId_manga() . "')");

    if ($query) return true;
    else return false;
  }

  /**
   * Método para listar todos os favoritos
   * 
   * @author Marcelo
   */

  public function list_bookmark(): array
  {
    $con = $this->con->connect();

    $query = mysqli_query($con, "SELECT * FROM favoritos");

    if ($query) {
      $reponse = array();
      while ($row = mysqli_fetch_assoc($query)) {
        $reponse[] = $row;
      }

      if (count($reponse) > 0) {
        return [
          "status" => true,
          "data" => $reponse
        ];
      } else {
        return [
          "status" => false,
          "data" => "Nenhum mangá adicionado à lista de favortios!"
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
   * Método para listar os favoritos por usuário
   * 
   * @author Marcelo
   */

  public function list_bookmark_user($data): array
  {
    $con = $this->con->connect();

    $id_user = mysqli_real_escape_string($con, $data['id_user']);

    $dao = new BookmarkModel;
    $dao->setId_user($id_user);

    $query = mysqli_query($con, "SELECT * FROM favorito WHERE id_user = '" . $dao->getId_user() . "'");

    if ($query) {
      $reponse = array();
      while ($row = mysqli_fetch_assoc($query)) {
        $reponse[] = $row;
      }

      if (count($reponse) > 0) {
        return [
          "status" => true,
          "data" => $reponse
        ];
      } else {
        return [
          "status" => false,
          "data" => "Nenhum mangá adicionado à lista de favoritos!"
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
   * Método para verificar se o mangá é favoritado pelo usuário logado
   * 
   * @author
   */

  public function validation_bookmarker($data): bool
  {
    $con = $this->con->connect();

    $id_user = mysqli_real_escape_string($con, $data['id_user']);
    $id_manga = mysqli_real_escape_string($con, $data['id_manga']);

    $dao = new BookmarkModel;
    $dao->setId_manga($id_manga);
    $dao->setId_user($id_user);

    $query = mysqli_query($con, "SELECT * FROM favorito WHERE id_user = '" . $dao->getId_user() . "' AND id_manga = '" . $dao->getId_manga() . "'");

    if ($query->num_rows) return true;
    else return false;
  }

  /**
   * Método para apagar um favorito
   * 
   * @author Marcelo
   */

  public function delete_bookmark($data): bool
  {
    $con = $this->con->connect();

    $id_user = mysqli_real_escape_string($con, $data['id_user']);
    $id_manga = mysqli_real_escape_string($con, $data['id_manga']);

    $dao = new BookmarkModel;
    $dao->setId_manga($id_manga);
    $dao->setId_user($id_user);

    $query = mysqli_query($con, "DELETE FROM favorito WHERE id_user = '" . $dao->getId_user() . "' AND id_manga = '" . $dao->getId_manga() . "'");

    if ($query) return true;
    else return false;
  }
}
