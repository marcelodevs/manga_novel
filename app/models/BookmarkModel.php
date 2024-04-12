<?php

namespace NovelRealm;

class BookmarkModel {
  private int $id_user;
  private int $id_manga;

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

  /**
   * Get the value of id_user
   */ 
  public function getId_user()
  {
    return $this->id_user;
  }

  /**
   * Set the value of id_user
   *
   * @return  self
   */ 
  public function setId_user($id_user)
  {
    $this->id_user = $id_user;

    return $this;
  }
}
