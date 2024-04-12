<?php

namespace NovelRealm;


class ChapterModel
{
  private int $id_capitulo;
  private int $id_manga;
  private int $id_user;
  private string $title;
  private string $content;
  private string $rascunho;

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
   * Get the value of title
   */ 
  public function getTitle()
  {
    return $this->title;
  }

  /**
   * Set the value of title
   *
   * @return  self
   */ 
  public function setTitle($title)
  {
    $this->title = $title;

    return $this;
  }

  /**
   * Get the value of content
   */ 
  public function getContent()
  {
    return $this->content;
  }

  /**
   * Set the value of content
   *
   * @return  self
   */ 
  public function setContent($content)
  {
    $this->content = $content;

    return $this;
  }

  /**
   * Get the value of rascunho
   */ 
  public function getRascunho()
  {
    return $this->rascunho;
  }

  /**
   * Set the value of rascunho
   *
   * @return  self
   */ 
  public function setRascunho($rascunho)
  {
    $this->rascunho = $rascunho;

    return $this;
  }
}
