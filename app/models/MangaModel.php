<?php

namespace NovelRealm;

class MangaModel
{
  private int $id_manga;
  private int $autor;
  private string $nome;
  private int $quantidade_capitulo;
  private string $sinopse;
  private string $img;

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
   * Get the value of autor
   */
  public function getAutor()
  {
    return $this->autor;
  }

  /**
   * Set the value of autor
   *
   * @return  self
   */
  public function setAutor($autor)
  {
    $this->autor = $autor;

    return $this;
  }

  /**
   * Get the value of nome
   */
  public function getNome()
  {
    return $this->nome;
  }

  /**
   * Set the value of nome
   *
   * @return  self
   */
  public function setNome($nome)
  {
    $this->nome = $nome;

    return $this;
  }

  /**
   * Get the value of quantidade_capitulo
   */
  public function getQuantidade_capitulo()
  {
    return $this->quantidade_capitulo;
  }

  /**
   * Set the value of quantidade_capitulo
   *
   * @return  self
   */
  public function setQuantidade_capitulo($quantidade_capitulo)
  {
    $this->quantidade_capitulo = $quantidade_capitulo;

    return $this;
  }

  /**
   * Get the value of sinopse
   */
  public function getSinopse()
  {
    return $this->sinopse;
  }

  /**
   * Set the value of sinopse
   *
   * @return  self
   */
  public function setSinopse($sinopse)
  {
    $this->sinopse = $sinopse;

    return $this;
  }

  /**
   * Get the value of img
   */
  public function getImg()
  {
    return $this->img;
  }

  /**
   * Set the value of img
   *
   * @return  self
   */
  public function setImg($img)
  {
    $this->img = $img;

    return $this;
  }
}
