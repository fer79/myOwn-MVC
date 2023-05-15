<?php

  class ProductModel extends Mysql
  {
    private $intIdProduct;
    private $strCode;
    private $strName;
    private $strDescription;
    private $strPrice;
    private $intStatus;

    public function __construct()
    {
      parent::__construct();
    }

    public function setProduct(string $code, string $name, string $description, string $price)
    {
      $this->strCode = $code;
      $this->strName = $name;
      $this->strDescription = $description;
      $this->strPrice = $price;

      $sql = "SELECT * FROM product WHERE code = :cod AND status = 1";
      $arrData = array(":cod" => $this->strCode);
      $product = $this->select($sql,$arrData);

      if(empty($product)) {

        $query_insert = "INSERT INTO product(code,name,description,price) 
                          VALUES (:cod,:nom,:desc,:price)";

        $arrData = array(":cod" => $this->strCode,
                        ":nom" => $this->strName,
                        ":desc" => $this->strDescription,
                        ":price" => $this->strPrice);

        $request = $this->insert($query_insert,$arrData);
        return $request;
      } else {

        return false;
      }
    }

    public function getProduct(int $idproduct)
    {
      $this->intIdProduct = $idproduct; 
      $sql = "SELECT idproduct,code,name,description,price,
              DATE_FORMAT(datecreated, '%d-%m-%Y') as dateRecord
              FROM product WHERE idproduct = :id AND status != 0";

      $arrData = array(":id" => $this->intIdProduct);
      $request = $this->select($sql,$arrData);
      return $request;
    }

    public function getProducts()
    {
      $sql = "SELECT idproduct,code,name,description,price,
              DATE_FORMAT(datecreated, '%d-%m-%Y') as dateRecord
              FROM product WHERE status != 0 ORDER BY idproduct DESC ";

      $request = $this->select_all($sql);
      return $request;
    }

    public function putProduct(int $idproduct, string $code, string $name, string $description, string $price)
    {
      $this->intIdProduct = $idproduct; 
      $this->strCode = $code;
      $this->strName = $name;
      $this->strDescription = $description;
      $this->strPrice = $price;
      $this->intIdProduct = $idproduct; 

      $sql = "SELECT * FROM product WHERE (code = :cod AND idproduct != :id) AND status != 0";
      $arrData = array(":cod" => $this->strCode, ":id" => $this->intIdProduct);
      $request = $this->select($sql,$arrData);

      if(empty($request)) {

        $sql = "UPDATE product SET code=:cod, name=:nom, description=:desc, price=:price
                WHERE idproduct = :id ";

        $arrData = array(":cod" => $this->strCode,
                        ":nom" => $this->strName,
                        ":desc" => $this->strDescription,
                        ":price" => $this->strPrice,
                        ":id" => $this->intIdProduct);

        $request_update = $this->update($sql,$arrData);
        return $request_update;
      } else {

        return false;
      }
    }

    public function deleteProduct(int $idproduct)
    {
      $this->intIdProduct = $idproduct;
      $sql = "UPDATE product SET status = :state WHERE idproduct = :id ";
      $arrData = array(":state" => 0, ":id" => $this->intIdProduct);
      $request_update = $this->update($sql, $arrData);
      return $request_update;
    }
  }
?>