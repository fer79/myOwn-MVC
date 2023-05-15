<?php

  class AccountModel extends Mysql
  {
    private $intIdAccount;
    private $intIdClient;
    private $intIdProduct;
    private $intIdFrequency;
    private $intAmount;
    private $intDues;
    private $intAmountDues;
    private $intCharge;
    private $intBalance;

    public function __construct()
    {
      parent::__construct();
    }

    public function setAccount(int $idclient, int $idproduct, int $idfrequency, float $amount, int $dues, float $amountdues, float $charge, float $balance)
    {
      $this->intIdClient = $idclient;
      $this->intIdProduct = $idproduct;
      $this->intIdFrequency = $idfrequency;
      $this->intAmount = $amount;
      $this->intDues = $dues;
      $this->intAmountDues = $amountdues;
      $this->intCharge = $charge;
      $this->intBalance = $balance;

      $sql = "INSERT INTO account(clientid,productid,frequencyid,amount,dues,amount_dues,charge,balance) 
              VALUES (:idcl,:idpr,:idfr,:amount,:dues,:mtdues,:charge,:balance)";

      $arrData = array(":idcl" => $this->intIdClient,
        ":idpr" => $this->intIdProduct,
        ":idfr" => $this->intIdFrequency,
        ":amount" => $this->intAmount,
        ":dues" => $this->intDues,
        ":mtdues" => $this->intAmountDues,
        ":charge" => $this->intCharge,
        ":balance" => $this->intBalance);
          
      $request_insert = $this->insert($sql,$arrData);
      return $request_insert;
    }

      public function getAccount(int $idaccount)
      {
        $this->intIdAccount = $idaccount;
        $sql = "SELECT c.idaccount, c.frequencyid, f.frequency, c.amount, c.dues, c.amount_dues, c.charge, c.balance,
                DATE_FORMAT(c.datecreated, '%d-%m-%Y') as dateRecord,
                c.clientid, cl.names, cl.lastnames, cl.phone, cl.email, cl.address, cl.tin, cl.taxname,
                cl.taxadress,
                c.productid, p.code as cod_product, p.name
                FROM account c 
                INNER JOIN frequency f
                ON c.frequencyid = f.idfrequency
                INNER JOIN client cl
                ON c.clientid = cl.idclient
                INNER JOIN product p
                ON c.productid = p.idproduct
                WHERE c.idaccount  = :idaccount ";
          
        $arrData = array(":idaccount" => $this->intIdAccount);
        $request = $this->select($sql,$arrData);
        return $request;
      }

      public function getMovements(int $idaccount)
      {
          $this->intIdAccount = $idaccount;
          $sql = "SELECT m.idmovement, m.amount, m.description, DATE_FORMAT(m.datecreated, '%d-%m-%Y') as date,
                  tm.idmovementtype, tm.movement, tm.movement_type
                  FROM movement m 
                  INNER JOIN movement_type tm
                  ON m.movementtypeid = tm.idmovementtype
                  WHERE m.accountid = $this->intIdAccount AND m.status != 0 ";
          $request = $this->select_all($sql);
          return $request;
      }

      public function getAccounts()
      {
        $sql = "SELECT c.idaccount,
                DATE_FORMAT(c.datecreated, '%d-%m-%Y') as dateRecord,
                concat(cl.names,' ',cl.lastnames) as client,
                f.frequency,
                c.dues, c.amount_dues,
                c.charge, c.balance
                FROM account c 
                INNER JOIN frequency f
                ON c.frequencyid = f.idfrequency
                INNER JOIN client cl
                ON c.clientid = cl.idclient
                WHERE c.status != 0 ORDER BY c.idaccount DESC ";

        $request = $this->select_all($sql);
        return $request;
      }
  }
?>