<?php

namespace NovelRealm;

class GenereosModel {
  private int $id_genero;
  private string $genero_nome;

  

  /**
   * Get the value of id_genero
   */ 
  public function getId_genero()
  {
    return $this->id_genero;
  }

  /**
   * Set the value of id_genero
   *
   * @return  self
   */ 
  public function setId_genero($id_genero)
  {
    $this->id_genero = $id_genero;

    return $this;
  }

  /**
   * Get the value of genero_nome
   */ 
  public function getGenero_nome()
  {
    return $this->genero_nome;
  }

  /**
   * Set the value of genero_nome
   *
   * @return  self
   */ 
  public function setGenero_nome($genero_nome)
  {
    $this->genero_nome = $genero_nome;

    return $this;
  }
}
