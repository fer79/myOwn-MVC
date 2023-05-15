<?php

  class Mysql extends Connection
  {
    private $connection;
    private $strquery;
    private $arrValues;

    public function __construct()
    {
      $this->connection = new Connection();
      $this->connection = $this->connection->connect();
    }

    //Insert a record
    public function insert(string $query, array $arrValues)
    {
      try {

        $this->strquery = $query;
        $this->arrValues = $arrValues;
        $insert = $this->connection->prepare($this->strquery);
        $resInsert = $insert->execute($this->arrValues);
        $idInsert = $this->connection->lastInsertId();
        $insert->closeCursor();
        return $idInsert;

      } catch (Exception $e) {

        $response = "Error: ". $e->getMessage();
        return $response;
      }
    }

    // Returns all records
    public function select_all(string $query)
    {
      try {

        $this->strquery = $query;
        $execute = $this->connection->query($this->strquery);
        $request = $execute->fetchall(PDO::FETCH_ASSOC); //ARRAY
        $execute->closeCursor();
        return $request;

      } catch (Exception $e) {

        $response = "Error: ". $e->getMessage();
        return $response;
      }
    }

    // Search for one record
    public function select(string $query, array $arrValues)
    {
      try {

        $this->strquery = $query;
        $this->arrValues = $arrValues;
        $query = $this->connection->prepare($this->strquery);
        $query->execute($this->arrValues);
        $request = $query->fetch(PDO::FETCH_ASSOC); //ARRAY
        $query->closeCursor();
        return $request;

      } catch (Exception $e) {

        $response = "Error: ". $e->getMessage();
        return $response;
      }
    }
    
    // Update record
    public function update(string $query, array $arrValues)
    {
      try {

        $this->strquery = $query;
        $this->arrValues = $arrValues;
        $update = $this->connection->prepare($this->strquery);
        $resUdpate = $update->execute($this->arrValues);
        $update->closeCursor();
        return $resUdpate;

      } catch (Exception $e) {

        $response = "Error: ". $e->getMessage();
        return $response;
      }
    }

    //Delete a record
    public function delete(string $query, array $arrValues)
    {
      try {

        $this->strquery = $query;
        $this->arrValues = $arrValues;
        $delete = $this->connection->prepare($this->strquery);
        $del = $delete->execute($this->arrValues); 
        return $del;

      } catch (Exception $e) {

        $response = "Error: ". $e->getMessage();
        return $response;
      }
    }

    // Execute Store Procedure
    public function call_execute(string $query, array $arrValues)
    {
      try {

        $this->strquery = $query;
        $this->arrValues = $arrValues;
        $query = $this->connection->prepare($this->strquery);
        $query->execute($this->arrValues);
        $request = $query->fetchall(PDO::FETCH_ASSOC); //ARRAY
        $query->closeCursor();
        return $request;

      } catch (Exception $e) {

        $response = "Error: ". $e->getMessage();
        return $response;
      }
    }
  }
?>