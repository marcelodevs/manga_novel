<?php

namespace NovelRealm;

class UserModel
{
  private int $id_user;
  private string $nome;
  private string $email;
  private string $senha;
  private string $image;
  private string $dark_mode;
  private string $show_fone;

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
   * Get the value of email
   */
  public function getEmail()
  {
    return $this->email;
  }

  /**
   * Set the value of email
   *
   * @return  self
   */
  public function setEmail($email)
  {
    $this->email = $email;

    return $this;
  }

  /**
   * Get the value of senha
   */
  public function getSenha()
  {
    return $this->senha;
  }

  /**
   * Set the value of senha
   *
   * @return  self
   */
  public function setSenha($senha)
  {
    $this->senha = $senha;

    return $this;
  }

  /**
   * Get the value of image
   */
  public function getImage()
  {
    return $this->image;
  }

  /**
   * Set the value of image
   *
   * @return  self
   */
  public function setImage($image)
  {
    $this->image = $image;

    return $this;
  }

  /**
   * Get the value of dark_mode
   */
  public function getDark_mode()
  {
    return $this->dark_mode;
  }

  /**
   * Set the value of dark_mode
   *
   * @return  self
   */
  public function setDark_mode($dark_mode)
  {
    $this->dark_mode = $dark_mode;

    return $this;
  }

  /**
   * Get the value of show_fone
   */
  public function getShow_fone()
  {
    return $this->show_fone;
  }

  /**
   * Set the value of show_fone
   *
   * @return  self
   */
  public function setShow_fone($show_fone)
  {
    $this->show_fone = $show_fone;

    return $this;
  }
}
