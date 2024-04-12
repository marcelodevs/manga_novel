<?php

namespace NovelRealm;

class CommentsMangaDao
{
  private int $id_comments;
  private int $id_manga;
  private int $id_user;
  private string $comments_manga;

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

  /**
   * Get the value of comments_manga
   */ 
  public function getComments_manga()
  {
    return $this->comments_manga;
  }

  /**
   * Set the value of comments_manga
   *
   * @return  self
   */ 
  public function setComments_manga($comments_manga)
  {
    $this->comments_manga = $comments_manga;

    return $this;
  }
}
