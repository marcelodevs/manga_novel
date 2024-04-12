<?php

namespace NovelRealm;

class CommentsCapDao
{
  private int $id_comments;
  private int $id_capitulo;
  private int $id_user;
  private string $comments_capitulo;
  private int $curtidas;

  /**
   * Get the value of id_comments
   */
  public function getId_comments()
  {
    return $this->id_comments;
  }

  /**
   * Set the value of id_comments
   *
   * @return  self
   */
  public function setId_comments($id_comments)
  {
    $this->id_comments = $id_comments;

    return $this;
  }

  /**
   * Get the value of id_capitulo
   */
  public function getId_capitulo()
  {
    return $this->id_capitulo;
  }

  /**
   * Set the value of id_capitulo
   *
   * @return  self
   */
  public function setId_capitulo($id_capitulo)
  {
    $this->id_capitulo = $id_capitulo;

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

  /**
   * Get the value of comments_capitulo
   */
  public function getComments_capitulo()
  {
    return $this->comments_capitulo;
  }

  /**
   * Set the value of comments_capitulo
   *
   * @return  self
   */
  public function setComments_capitulo($comments_capitulo)
  {
    $this->comments_capitulo = $comments_capitulo;

    return $this;
  }

  /**
   * Get the value of curtidas
   */
  public function getCurtidas()
  {
    return $this->curtidas;
  }

  /**
   * Set the value of curtidas
   *
   * @return  self
   */
  public function setCurtidas($curtidas)
  {
    $this->curtidas = $curtidas;

    return $this;
  }
}
