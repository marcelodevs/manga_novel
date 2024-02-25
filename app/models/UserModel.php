<?php

namespace NovelRealm;

require_once __DIR__ . '\..\..\autoload.php';

use NovelRealm\Database;

class UserModel extends Database
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

  public function add_user($data): bool
  {
    // var_dump($data);

    $con = $this->con->connect();
    $nome = mysqli_real_escape_string($con, $data['nome']);
    $email = mysqli_real_escape_string($con, $data['email']);
    $senha = mysqli_real_escape_string($con, $data['senha']);
    $img = mysqli_real_escape_string($con, $data['img']);

    $query = mysqli_query($con, "INSERT INTO usuarios (nome, email, senha, img) VALUES ('$nome', '$email', '$senha', '$img')");

    if ($query) {
      return true;
    } else {
      return false;
    }
  }

  public function list_user($data = null): array
  {
    $con = $this->con->connect();

    if (!is_null($data) && is_array($data)) {
      $id = isset($data['id_user']) ? mysqli_real_escape_string($con, $data['id_user']) : '';
      $email = isset($data['email']) ? mysqli_real_escape_string($con, $data['email']) : '';

      $query = mysqli_query($con, "SELECT * FROM usuarios WHERE id_user = " . (int)$id . " OR email = '$email' OR nome = '$email'");

      if ($query) {
        $response = mysqli_fetch_all($query, MYSQLI_ASSOC);

        if (count($response) > 0) {
          return [
            "status" => true,
            "data" => $response[0]
          ];
        } else {
          return [
            "status" => false,
            "data" => "Nenhum usuário encontrado"
          ];
        }
      } else {
        return [
          "status" => false,
          "data" => "Erro ao executar a consulta: " . mysqli_error($con)
        ];
      }
    } else {
      $query = mysqli_query($con, "SELECT * FROM usuarios");

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
            "data" => "Nenhum usuário existente no banco de dados"
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

  public function update_user($data): bool
  {
    $con = $this->con->connect();
    $nome  = mysqli_real_escape_string($con, $data['nome']);
    $email = mysqli_real_escape_string($con, $data['email']);
    $img = mysqli_real_escape_string($con, $data['img']);

    $query = mysqli_query(
      $con,
      "UPDATE usuarios SET email = '$email', img = '$img', nome = '$nome' WHERE email = '$email'"
    );

    // var_dump($query);

    if ($query) {
      return true;
    } else {
      return false;
    }
  }

  /**
   * OUTROS MÉTODOS
   * @author Marcelo
   */

  public function login_user($data): array
  {
    $conn = $this->con->connect();

    $email = mysqli_real_escape_string($conn, $data['email']);
    $nome = mysqli_real_escape_string($conn, $data['nome']);
    $password = mysqli_real_escape_string($conn, $data['senha']);

    // var_dump($data);

    $query = mysqli_query(
      $conn,
      "SELECT * FROM usuarios WHERE (email = '$email' AND senha = '$password') OR (nome = '$nome' AND senha = '$password')"
    );

    // var_dump($query);

    if ($query) {
      if (mysqli_num_rows($query) > 0) {
        $response = mysqli_fetch_assoc($query);
        return [
          "status" => true,
          "data" => $response
        ];
      } else {
        return [
          "status" => false,
          "data" => "Nenhum usuario foi encontrado"
        ];
      }
    } else {
      return "erro ao executar mysql";
    }
  }

  public function get_preferences_dark_mode($data)
  {
    $con = $this->con->connect();
    $id = mysqli_real_escape_string($con, $data);

    $query = mysqli_query($con, "SELECT dark_mode FROM usuarios WHERE id_user = " . (int)$id);

    if ($query) {
      $result = mysqli_fetch_assoc($query);
      if ($result && $result['dark_mode'] === 'S') {
        return true;
      }
    }
    return false;
  }

  public function set_preferences_dark_mode($data, $situacao)
  {
    $con = $this->con->connect();
    $username = mysqli_real_escape_string($con, $data);
    $situacao = mysqli_real_escape_string($con, $situacao);

    $query = mysqli_query($con, "UPDATE usuarios SET dark_mode = '$situacao' WHERE id_user = '$username'");

    if ($query) {
      return true;
    }
    return false;
  }
}
