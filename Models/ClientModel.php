<?php

  class ClientModel extends Mysql
  {
    private $intIdClient;
    private $strIdentification;
    private $strNames;
    private $strLastnames;
    private $intPhone;
    private $strEmail;
    private $strAddress;
    private $strTin;
    private $strTaxname;
    private $strTaxadress;
    private $intStatus;

    public function __construct()
    {
        parent::__construct();
    }

    public function setClient(string $identification, string $names, string $lastnames, int $phone, string $email, string $address, string $tin, string $taxname, string $taxadress)
    {

      $this->strIdentification = $identification;
      $this->strNames = $names;
      $this->strLastnames = $lastnames;
      $this->intPhone = $phone;
      $this->strEmail = $email;
      $this->strAddress = $address;
      $this->strTin = $tin;
      $this->strTaxname = $taxname;
      $this->strTaxadress = $taxadress;

      $sql = "SELECT identification, email FROM client WHERE (email = :email or identification = :ident) and status = :state ";
            
      $arrParams = array(":email" => $this->strEmail,
                        ":ident" =>  $this->strIdentification,
                        ":state" => 1);

      $request = $this->select($sql,$arrParams);

      if(!empty($request)) {

        return false;

      } else {

        $query_inset= "INSERT INTO client(identification,names,lastnames,phone,email,address,tin,taxname,taxadress)
                        VALUES(:ident,:nom,:last,:tel,:email,:dir,:tin,:taxname,:taxadress)";

        $arrData = array(":ident" =>  $this->strIdentification,
                        ":nom" => $this->strNames,
                        ":last" => $this->strLastnames,
                        ":tel" => $this->intPhone,
                        ":email" => $this->strEmail,
                        ":dir" => $this->strAddress,
                        ":tin" => $this->strTin,
                        ":taxname" => $this->strTaxname,
                        ":taxadress" => $this->strTaxadress);

        $request_insert = $this->insert($query_inset,$arrData);
        return $request_insert;
      }
    }

    public function putClient(int $idclient, string $identification, string $names, string $lastnames, int $phone, string $email, string $address, string $tin, string $taxname, string $taxadress)
    {
      $this->intIdClient = $idclient;
      $this->strIdentification = $identification;
      $this->strNames = $names;
      $this->strLastnames = $lastnames;
      $this->intPhone = $phone;
      $this->strEmail = $email;
      $this->strAddress = $address;
      $this->strTin = $tin;
      $this->strTaxname = $taxname;
      $this->strTaxadress = $taxadress;

      $sql = "SELECT identification,email FROM client WHERE 
              (email = :email AND idclient != :id ) OR
              (identification = :ident AND idclient != :id) AND
              status != 0";

      $arrData = array(":email" => $this->strEmail,
                        ":ident" => $this->strIdentification,
                        ":id" =>  $this->intIdClient );
            
      $request_client = $this->select($sql,$arrData);
      if(empty($request_client)) {

        $sql = "UPDATE client SET identification = :ident, names = :nom, lastnames = :last, phone = :tel, email = :email,
                address = :dir, tin = :tin, taxname = :taxname, taxadress = :taxadress
                WHERE idclient = :id ";

        $arrData = array(":ident" =>  $this->strIdentification,
                          ":nom" => $this->strNames,
                          ":last" => $this->strLastnames,
                          ":tel" => $this->intPhone,
                          ":email" => $this->strEmail,
                          ":dir" => $this->strAddress,
                          ":tin" => $this->strTin,
                          ":taxname" => $this->strTaxname,
                          ":taxadress" => $this->strTaxadress,
                          ":id" => $this->intIdClient);

        $request = $this->update($sql,$arrData);
        return $request;

      } else {

        return false;
      }
    }

    public function getClient(int $idclient)
    {
      $this->intIdClient = $idclient;
      $sql = "SELECT idclient,
                    identification,
                    names,
                    lastnames,
                    phone,
                    email,
                    address,
                    tin,
                    taxname,
                    taxadress,
                    DATE_FORMAT(datecreated, '%d-%m-%Y') as dateRecord
                    FROM client WHERE idclient = :id AND status != 0";

      $arrData = array(":id" => $this->intIdClient);
      $request = $this->select($sql,$arrData);
      return $request;
    }

    public function getClients()
    {
      $sql = "SELECT idclient,
              identification,
              names,
              lastnames,
              phone,
              email,
              address,
              tin,
              taxname,
              taxadress,
              DATE_FORMAT(datecreated, '%d-%m-%Y') as dateRecord
              FROM client WHERE status != 0 ORDER BY idclient DESC ";

      $request = $this->select_all($sql);
      return $request;
    }

    public function deleteClient(int $idclient)
    {
      $this->intIdClient = $idclient;
      $sql = "UPDATE client SET status = :state WHERE idclient = :id ";
      $arrData = array(":state" => 0, ":id" => $this->intIdClient );
      $request = $this->update($sql,$arrData);
      return $request;
    }
  }
?>