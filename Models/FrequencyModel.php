<?php

    class FrequencyModel extends Mysql
    {
      private $intIdFrequency;
      private $strFrequency;
      private $strDate;
      private $intStatus;

      public function __construct()
      {
          parent::__construct();
      }

      public function setFrequency(string $frequency)
      {
        $this->strFrequency = $frequency;

        $sql  = "SELECT * FROM frequency WHERE frequency = :frequency AND status != 0 ";
        $arrData = array(":frequency" => $this->strFrequency);
        $request = $this->select($sql,$arrData);

        if(empty($request)) {

          $sql_insert = "INSERT INTO frequency(frequency) VALUES(:frequency)";
          $arrData = array(":frequency" => $this->strFrequency);
          $request_insert = $this->insert($sql_insert,$arrData);
          return $request_insert;

        } else {

          return false;
        }
      }

      public function getFrequency(int $idfrequency)
      {
        $this->intIdFrequency = $idfrequency; 
        $sql = "SELECT idfrequency,
                frequency,
                DATE_FORMAT(datecreated, '%d-%m-%Y') as dateRecord
                FROM frequency WHERE idfrequency = :idfrequency AND status != 0 "; 

        $arrData = array(":idfrequency" => $this->intIdFrequency);
        $request = $this->select($sql,$arrData);
        return $request;
      }

      public function getFrequencys()
      {
        $sql = "SELECT idfrequency,
                frequency,
                DATE_FORMAT(datecreated, '%d-%m-%Y') as dateRecord
                FROM frequency WHERE status != 0 ORDER BY idfrequency DESC"; 

        $request = $this->select_all($sql);
        return $request;
      }
        
      public function putFrequency(int $idfrequency, string $frequency)
      {
        $this->intIdFrequency = $idfrequency; 
        $this->strFrequency = $frequency;
        $sql = "SELECT * FROM frequency WHERE 
                (frequency = :fr AND idfrequency != :idfr) AND status != 0 ";
        
        $arrData = array(":fr" => $this->strFrequency,
                        ":idfr" => $this->intIdFrequency);

        $request = $this->select($sql,$arrData);

        if(empty($request)) {

          $sql = "UPDATE frequency SET frequency = :fr WHERE idfrequency = :idfr";
          $arrData = array(":fr" => $this->strFrequency,
                            ":idfr" => $this->intIdFrequency);

          $request_update = $this->update($sql,$arrData);
          return $request_update;

        } else {

          return false;
        }
      }

      public function deleteFrequency(int $idfrequency)
      {
        $this->intIdFrequency = $idfrequency; 
        $sql = "UPDATE frequency SET status = :state WHERE idfrequency = :idfr";
        $arrData = array(":state" => 0,
                        ":idfr" => $this->intIdFrequency);

        $request_update = $this->update($sql,$arrData);
        return $request_update;
      }
    }
?>