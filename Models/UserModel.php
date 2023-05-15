<?php

  class UserModel extends Mysql
  {
    private $intIdUser;
    private $strNames;
    private $strLastnames;
    private $strEmail;
    private $strPassword;

    public function __construct()
    {
      parent::__construct();
    }

    public function getUser(int $iduser)
    {
      $this->intIdUser = $iduser;
      $sql = "SELECT id_user, name, lastname, email,
              DATE_FORMAT(datecreated, '%d-%m-%Y') as dateRecord
              FROM user WHERE id_user = :iduser AND status != :status";

      $arrData = array(":iduser" => $this->intIdUser, ":status" => 0);
      $request = $this->select($sql,$arrData);
      return $request;
    }

    public function setUser(string $names, string $lastnames, string $email, string $password)
    {
      $this->strNames = $names;
      $this->strLastnames = $lastnames;
      $this->strEmail = $email;
      $this->strPassword = $password;

      $sql = "SELECT email FROM user WHERE email = '$this->strEmail' AND status != 0";
      $request = $this->select_all($sql);
      if(empty($request)) {

        $sql_insert = "INSERT INTO user(name,lastname,email,password)
                        VALUES(:nom,:last,:email,:pass)";

        $arrData = array(":nom" => $this->strNames,
                        ":last" => $this->strLastnames,
                        ":email" => $this->strEmail,
                        ":pass" => $this->strPassword);

        $request_insert = $this->insert($sql_insert,$arrData);
        return $request_insert;
      } else {

        return false;
      }
    }

    public function putUser(int $iduser, string $names, string $lastnames, string $email, string $password)
    {
      $this->intIdUser = $iduser;
      $this->strNames = $names;
      $this->strLastnames = $lastnames;
      $this->strEmail = $email;
      $this->strPassword = $password;

      $sql = "SELECT email FROM user WHERE (email = :email AND id_user != :id ) AND status != 0";
      $arrData = array(":email" => $this->strEmail,":id" => $this->intIdUser);
      $request_user = $this->select($sql,$arrData);
      if(empty($request_user)) {

        if($this->strPassword == "") {

          $sql = "UPDATE user SET name = :nom, lastname = :last, email = :email WHERE id_user = :id ";
          $arrData = array(":nom" => $this->strNames,
                          ":last" =>  $this->strLastnames,
                          ":email" => $this->strEmail,
                          ":id" => $this->intIdUser);

        } else {

          $sql = "UPDATE user SET name = :nom, lastname = :last, email = :email, password = :pass WHERE id_user = :id ";
          $arrData = array(":nom" => $this->strNames,
                          ":last" =>  $this->strLastnames,
                          ":email" => $this->strEmail,
                          ":pass" => $this->strPassword,
                          ":id" => $this->intIdUser);
        }

        $request = $this->update($sql,$arrData);
        return $request;
      } else {

        return false;
      }
    }

    public function getUsers()
    {
      $sql = "SELECT id_user, name, lastname, email,
            DATE_FORMAT(datecreated, '%d-%m-%Y') as dateRecord
            FROM user WHERE status != 0 ORDER BY id_user DESC ";

      $request = $this->select_all($sql);
      return $request;
    }

    public function deleteUser($iduser)
    {
      $this->intIdUser = $iduser;
      $sql = "UPDATE user SET status = :state WHERE id_user = :id ";
      $arrData = array(":state" => 0, ":id" => $this->intIdUser );
      $request = $this->update($sql,$arrData);
      return $request;
    }

    public function loginUser(string $email, string $password)
    {
      $this->strEmail = $email;
      $this->strPassword = $password;

      $sql = "SELECT id_user, status FROM user WHERE
              email = BINARY :email AND password = BINARY :pass AND status != 0 ";

      $arrData = array(":email" => $this->strEmail,
                      ":pass" => $this->strPassword );

      $request = $this->select($sql,$arrData);
      return $request;
    }
  }
?>