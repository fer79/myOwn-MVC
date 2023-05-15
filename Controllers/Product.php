<?php

  class Product extends Controllers{

    public function __construct()
    {
        parent::__construct();
    }

    public function product($idproduct)
    {
      try {

        $method = $_SERVER['REQUEST_METHOD'];
        $response = [];
        if($method == "GET") {

          if(empty($idproduct) or !is_numeric($idproduct)) {

            $response = array('status' => false , 'msg' => 'Parameter error');
            $code = 400;
            jsonResponse($response,$code);
            die();
          }

          $arrData = $this->model->getProduct($idproduct);
          if(empty($arrData)) {

            $response = array('status' => false , 'msg' => 'Record not found'); 
          } else {

            $response = array('status' => true , 'msg' => 'Data found', 'data' => $arrData);
          }

          $code = 200;
        } else {

          $response = array('status' => false , 'msg' => 'Request Failed '.$method);
          $code = 400;
        }

        jsonResponse($response,$code);
        die();
      } catch (Exception $e) {

        echo "Error in the process: ". $e->getMessage();
      }

      die();
    }

    public function products()
    {
      try {

        $method = $_SERVER['REQUEST_METHOD'];
        $response = [];
        if($method == "GET") {

          $arrData = $this->model->getProducts();
          if(empty($arrData)) {

            $response = array('status' => false , 'msg' => 'No data to display', 'data' => "");

          } else {

            $response = array('status' => true , 'msg' => 'Data found', 'data' => $arrData);
          }

          $code = 200;
        } else {

          $response = array('status' => false , 'msg' => 'Request Failed '.$method);
          $code = 400;
        }

        jsonResponse($response,$code);
        die();

      } catch (Exception $e) {

        echo "Error in the process: ". $e->getMessage();
      }

      die();
    }

    public function record()
    { 
      try {

        $method = $_SERVER['REQUEST_METHOD'];
        $response = [];
        if($method == "POST") {

          $_POST = json_decode(file_get_contents('php://input'),true);
          if(empty($_POST['code'])) {

            $response = array('status' => false , 'msg' => 'The code is required');
            jsonResponse($response,200);
            die();
          }

          if(empty($_POST['name'])) {

            $response = array('status' => false , 'msg' => 'The name is required');
            jsonResponse($response,200);
            die();
          }

          if(empty($_POST['description'])) {

            $response = array('status' => false , 'msg' => 'The description is required');
            jsonResponse($response,200);
            die();
          }

          if(empty($_POST['price']) or !is_numeric($_POST['price'])) {

            $response = array('status' => false , 'msg' => 'Error in the price');
            jsonResponse($response,200);
            die();
          }

          $strCode = strClean($_POST['code']);
          $strName = ucwords(strClean($_POST['name']));
          $strDescription = strClean($_POST['description']);
          $strPrice = $_POST['price'];

          $request = $this->model->setProduct($strCode,
            $strName,
            $strDescription,
            $strPrice);

          if($request > 0) {

            $arrProduct = array('idProduct' => $request,
              'code' => $strCode,
              'name' => $strName,
              'description' => $strDescription,
              'price' => $strPrice);

              $response = array('status' => true , 'msg' => 'Data saved successfully', 'data' => $arrProduct);                

            } else {

              $response = array('status' => false , 'msg' => 'The código already exists');
            }

            $code = 200;
          } else {

          $response = array('status' => false , 'msg' => 'Request Failed '.$method);
          $code = 400;
        }

        jsonResponse($response,$code);
        die();
      } catch (Exception $e) {

        echo "Error in the process: ". $e->getMessage();
      }

      die();
    }

    public function update($idproduct)
    {
      try {

        $method = $_SERVER['REQUEST_METHOD'];
        $response = [];
        if($method == "PUT") {

          $arrData = json_decode(file_get_contents('php://input'),true);
          if(empty($idproduct) or !is_numeric($idproduct)){

            $response = array('status' => false , 'msg' => 'Parameter error');
            $code = 400;
            jsonResponse($response,$code);
            die();
          }

          if(empty($arrData['code'])) {

            $response = array('status' => false , 'msg' => 'The code is required');
            jsonResponse($response,200);
            die();
          }
          
          if(empty($arrData['name'])) {

            $response = array('status' => false , 'msg' => 'The name is required');
            jsonResponse($response,200);
            die();
          }

          if(empty($arrData['description'])) {

            $response = array('status' => false , 'msg' => 'The description is required');
            jsonResponse($response,200);
            die();
          }

          if(empty($arrData['price']) or !is_numeric($arrData['price'])) {

            $response = array('status' => false , 'msg' => 'Error in the price');
            jsonResponse($response,200);
            die();
          }

          $strCode = strClean($arrData['code']);
          $strName = ucwords(strClean($arrData['name']));
          $strDescription = strClean($arrData['description']);
          $strPrice = $arrData['price'];

          $search_product = $this->model->getProduct($idproduct);
          if(empty($search_product)) {

            $response = array('status' => false , 'msg' => 'The record does not exist'); 
            jsonResponse($response,200);
            die();
          }
                
          $request = $this->model->putProduct($idproduct,
            $strCode,
            $strName,
            $strDescription,
            $strPrice);

          if($request) {

            $arrProduct = array('idProduct' => $idproduct,
              'code' => $strCode,
              'name' => $strName,
              'description' => $strDescription,
              'price' => $strPrice);

            $response = array('status' => true , 'msg' => 'Data Updated successfully', 'data' => $arrProduct);

          } else {

            $response = array('status' => false , 'msg' => 'The código already exists');
          }

          $code = 200;
        } else {

          $response = array('status' => false , 'msg' => 'Request Failed '.$method);
          $code = 400;
        }

        jsonResponse($response,$code);
        die();

      } catch (Exception $e) {

        echo "Error in the process: ". $e->getMessage();
      }

      die();
    }

    public function delete($idproduct)
    {
      try {

        $method = $_SERVER['REQUEST_METHOD'];
        $response = [];
        if($method == "DELETE") {

          if(empty($idproduct) or !is_numeric($idproduct)) {

            $response = array('status' => false , 'msg' => 'Parameter error');
            $code = 400;
            jsonResponse($response,$code);
            die();
          }

          $request = $this->model->getProduct($idproduct);
          if(empty($request)) {

            $response = array('status' => false , 'msg' => 'The record does not exists or was already deleted');
            jsonResponse($response,400);
            die();

          } else {

            $request = $this->model->deleteProduct($idproduct);

            if($request) {

              $response = array('status' => true , 'msg' => 'Record deleted'); 

            } else {

              $response = array('status' => false , 'msg' => 'Cannot delete record');
            }
          }

          $code = 200;
        } else {

          $response = array('status' => false , 'msg' => 'Request Failed '.$method);
          $code = 400;
        }

        jsonResponse($response,$code);
        die();
      } catch (Exception $e) {

        echo "Error in the process: ". $e->getMessage();
      }
       
      die();
    }
  }


?>