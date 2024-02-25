<?php

namespace NovelRealm;

use Error;
use mysqli;

class Database
{
  private $conn;
  private function get_connection()
  {
    try {
      $this->conn = new mysqli("localhost", "root", "marcelo123==", "mangarealm");
      return $this->conn;
    } catch (Error $e) {
      die("Error: " . $e->getMessage());
    }
  }

  public function connect()
  {
    return $this->get_connection();
  }
}
