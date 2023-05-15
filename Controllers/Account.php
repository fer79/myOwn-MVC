<?php

  class Account extends Controllers{

    public function __construct()
    {
      parent::__construct();
    }

      public function account($idaccount)
      {
        try {

          $method = $_SERVER['REQUEST_METHOD'];
          $response = [];
          if($method == "GET") {

            if(empty($idaccount) or !is_numeric($idaccount)){

              $response = array('status' => false , 'msg' => 'Parameter error');
              jsonResponse($response,400);
              die();
            }

            $arrAccount = $this->model->getAccount($idaccount);
            if(empty($arrAccount)) {

              $response = array('status' => false , 'msg' => 'Record not found');

            } else {

              $arrMovements = $this->model->getMovements($idaccount);
              $arrAccount['movements'] = $arrMovements;
              $response = array('status' => true , 'msg' => 'Data found', 'data' => $arrAccount);   
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

      public function accounts()
      {
        try {

          $method = $_SERVER['REQUEST_METHOD'];
          $response = [];
          if($method == "GET") {

            $arrAccounts = $this->model->getAccounts();
            if(empty($arrAccounts)) {

              $response = array('status' => false , 'msg' => 'No data to display', 'data' => ""); 

            } else {

              $response = array('status' => true , 'msg' => 'Data found', 'data' => $arrAccounts);
            }

            $code = 200;

          } else {

            $response = array('status' => false , 'msg' => 'Request Failed '.$method);
            $code = 400;
          }

          jsonResponse($response,$code);
          die();

        } catch (\Throwable $th) {

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
            if(empty($_POST['clientId']) or !is_numeric($_POST['clientId'])) {

              $response = array('status' => false , 'msg' => 'The client is required');
              jsonResponse($response,200);
              die();
            }

            if(empty($_POST['productId']) or !is_numeric($_POST['productId'])) {

              $response = array('status' => false , 'msg' => 'The product is required');
              jsonResponse($response,200);
              die();
            }

            if(empty($_POST['frequencyId']) or !is_numeric($_POST['frequencyId'])) {

              $response = array('status' => false , 'msg' => 'The frequency is required');
              jsonResponse($response,200);
              die();
            }

            if(empty($_POST['amount']) or !is_numeric($_POST['amount'])) {

              $response = array('status' => false , 'msg' => 'The amount is required');
              jsonResponse($response,200);
              die();
            }

            if(empty($_POST['dues']) or !is_numeric($_POST['dues'])) {

              $response = array('status' => false , 'msg' => 'The due is required');
              jsonResponse($response,200);
              die();
            }

            if(empty($_POST['due_amount']) or !is_numeric($_POST['due_amount'])) {

              $response = array('status' => false , 'msg' => 'The due amount is required');
              jsonResponse($response,200);
              die();
            }

            if(empty($_POST['charge']) or !is_numeric($_POST['charge'])) {

              $response = array('status' => false , 'msg' => 'The charge is required');
              jsonResponse($response,200);
              die();
            }

            if(empty($_POST['balance']) or !is_numeric($_POST['balance'])) {

              $response = array('status' => false , 'msg' => 'The balance is required');
              jsonResponse($response,200);
              die();
            }

            $intClientId = strClean($_POST['clientId']);
            $intProductId = strClean($_POST['productId']);
            $intFrequencyId = strClean($_POST['frequencyId']);
            $strAmount = strClean($_POST['amount']);
            $strDues = strClean($_POST['dues']);
            $strAmountDues = strClean($_POST['due_amount']);
            $strCharge = strClean($_POST['charge']);
            $strBalance = strClean($_POST['balance']);

            $request = $this->model->setAccount($intClientId,
              $intProductId,
              $intFrequencyId,
              $strAmount,
              $strDues,
              $strAmountDues,
              $strCharge,
              $strBalance);

            if(is_numeric($request) and $request > 0) {

              $arrAccount = array('idContrado' => $request);
              $response = array('status' => true , 'msg' => 'Data saved successfully', 'data' => $arrAccount); 

            } else {

              $response = array('status' => false , 'msg' => 'Cannot create contract','msg_tecnito' =>$request );
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

      public function order($idaccount)
      {
        try {

        $method = $_SERVER['REQUEST_METHOD'];
        $response = [];
        if($method == "GET") {

          if(empty($idaccount) or !is_numeric($idaccount)) {

            $response = array('status' => false , 'msg' => 'Parameter error');
            jsonResponse($response,400);
            die();
          }

          $arrAccount = $this->model->getAccount($idaccount);
          if(empty($arrAccount)) {

            $response = array('status' => false , 'msg' => 'Record not found');

          } else {

            $response = array('status' => true , 'msg' => 'Data found', 'data' => $arrAccount);   
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