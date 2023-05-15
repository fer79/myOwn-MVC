<?php

  class MovementModel extends Mysql
  {
    private $intIdMovementtype;
    private $strMovement;
    private $intMovementtype;
    private $strDescMovementtype;
    private $intIdMovement;
    private $intCuendaID;
    private $description;
    private $intAmount;
    private $strDate;

    public function __construct()
    {
        parent::__construct();
    }

    public function setMovementtype(string $movement, int $movementtype, string $description)
    {
      $this->strMovement = $movement;
      $this->intMovementtype = $movementtype;
      $this->strDescMovementtype = $description;

      $sql = "SELECT * FROM movement_type WHERE movement = :mov AND status != 0 ";
      $arrData = array(":mov" => $this->strMovement);
      $request = $this->select($sql,$arrData);
      if(empty($request)) {

        $sql_insert = "INSERT INTO movement_type(movement,movement_type,description)
                        VALUES(:mov,:tipo_mov,:desc)";

        $arrData = array(":mov" => $this->strMovement,
                        ":tipo_mov" =>  $this->intMovementtype,
                        ":desc" => $this->strDescMovementtype);

        $request_insert = $this->insert($sql_insert,$arrData);
        return $request_insert;

      } else {

        return false;
      }
    }

    public function getTiposMovement()
    {
      $sql = "SELECT idmovementtype,movement,movement_type 
              FROM movement_type WHERE status !=0  ORDER BY idmovementtype DESC ";

      $request = $this->select_all($sql);

        return $request;
    }

    public function setMovement(int $idaccount, int $idmovement, int $movement, float $amount, string $description)
    {
      $this->intCuendaID = $idaccount;
      $this->intIdMovement = $idmovement;
      $this->intMovementtype = $movement;
      $this->intAmount = $amount;
      $this->description = $description;
      $sql = "INSERT INTO movement(accountid,movementtypeid,movement,amount,description)
              VALUES(:idaccount,:tpmovement,:movement,:amount,:description)";
    
      $arrData = array(":idaccount" => $this->intCuendaID,
                      ":tpmovement" => $this->intIdMovement,
                      ":movement" => $this->intMovementtype,
                      ":amount" => $this->intAmount,
                      ":description" => $this->description);

      $request_insert = $this->insert($sql,$arrData);
      return $request_insert;
    }

    public function getMovement(int $idmovement)
    {
      $this->intIdMovement = $idmovement;
      $sql = "SELECT m.idmovement, m.accountid, m.movement, m.amount, m.description, 
              DATE_FORMAT(m.datecreated, '%d-%m-%Y') as date,
              tm.idmovementtype, tm.movement as nameMovement 
              FROM movement m
              INNER JOIN movement_type tm
              ON m.movementtypeid = tm.idmovementtype
              WHERE m.idmovement = :idmovement AND m.status !=0 ";

      $arrData = array(":idmovement" => $this->intIdMovement); 
      $request = $this->select($sql,$arrData);
      return $request;
    }

    public function getMovements()
    {
      $sql = "SELECT m.idmovement, m.accountid, m.amount, 
              DATE_FORMAT(m.datecreated, '%d-%m-%Y') as date,
              tm.movement as nameMovement 
              FROM movement m
              INNER JOIN movement_type tm
              ON m.movementtypeid = tm.idmovementtype
              WHERE m.status !=0 ORDER BY m.idmovement DESC ";

      $request = $this->select_all($sql);
      return $request;
    }

    public function cancelMovement(int $idmovement)
    {
      $this->intIdMovement = $idmovement;
      $sql = "CALL cancel_movement(:idmovement)";
      $arrData = array(":idmovement" => $this->intIdMovement);
      $request = $this->call_execute($sql,$arrData);
      return $request;
    }
  }
?>