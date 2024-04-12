<?php

namespace NovelRealm;

class MangaGeneroModel {
  private int $id_manga;
  private int $id_genero;

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
   * Get the value of id_manga
   */ 
  public function getId_manga()
  {
    return $this->id_manga;
  }

  /**
   * Set the value of id_manga
   *
   * @return  self
   */ 
  public function setId_manga($id_manga)
  {
    $this->id_manga = $id_manga;

    return $this;
  }
}
