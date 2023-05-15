<?php

  class Movement extends Controllers{

    public function __construct()
    {
        parent::__construct();
    }

    public function recordMovementType()
    {
      try {

        $method = $_SERVER['REQUEST_METHOD'];
        $response = [];
        if($method == "POST") {

          $_POST = json_decode(file_get_contents('php://input'),true);
          if(empty($_POST['movement'])) {

            $response = array('status' => false , 'msg' => 'The movement is required');
            jsonResponse($response,200);
            die();
          }

          if(empty($_POST['movement_type']) or ($_POST['movement_type'] != 1 and $_POST['movement_type'] != 2)) {

            $response = array('status' => false , 'msg' => 'Error in the Movement type');
            jsonResponse($response,200);
            die();
          }

          if(empty($_POST['description'])) {

            $response = array('status' => false , 'msg' => 'The description is required');
            jsonResponse($response,200);
            die();
          }

          $strMovement = ucwords(strClean($_POST['movement']));
          $intMovementtype = $_POST['movement_type'];
          $strDescription = strClean($_POST['description']);
                  
          $request = $this->model->setMovementtype($strMovement,$intMovementtype,$strDescription);
          if($request > 0) {

            $arrMovement = array("idmovementtype" => $request,
              "movement" =>  $strMovement,
              "movement_type" => $intMovementtype,
              "description" => $strDescription );

            $response = array('status' => true , 'msg' => 'Data saved successfully', 'data' => $arrMovement);

          } else {

            $response = array('status' => false , 'msg' => 'The movement type already exists');
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

    public function tiposMovement()
    {
      try {

        $method = $_SERVER['REQUEST_METHOD'];
        $response = [];
        if($method == "GET") {

          $arrData = $this->model->getTiposMovement();
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

    public function recordMovement()
    {
      try {

        $method = $_SERVER['REQUEST_METHOD'];
        $response = [];
        if($method == "POST") {

          $_POST = json_decode(file_get_contents('php://input'),true);
          if(empty($_POST['accountid']) or !is_numeric($_POST['accountid'])) {

            $response = array('status' => false , 'msg' => 'Error in the id account');
            jsonResponse($response,200);
            die();
          }
          
          if(empty($_POST['movementtypeid']) or !is_numeric($_POST['movementtypeid'])) {

            $response = array('status' => false , 'msg' => 'Error in the id Movement type');
            jsonResponse($response,200);
            die();
          }

          if(empty($_POST['movement']) or ($_POST['movement'] != 1 and $_POST['movement'] != 2)) {

            $response = array('status' => false , 'msg' => 'Error in the Movement');
            jsonResponse($response,200);
            die();
          }

          if(empty($_POST['amount']) or !is_numeric($_POST['amount'])) {

            $response = array('status' => false , 'msg' => 'Error in the amount');
            jsonResponse($response,200);
            die();
          }

          if(empty($_POST['description']) ) {

            $response = array('status' => false , 'msg' => 'Error en la descripción');
            jsonResponse($response,200);
            die();
          }

          $intAccountID = strClean($_POST['accountid']);
          $intMovementID = strClean($_POST['movementtypeid']);
          $intMovement = strClean($_POST['movement']);
          $strAmount = strClean($_POST['amount']);
          $strDescription = strClean($_POST['description']);
          $arrMovement = $this->model->setMovement($intAccountID,$intMovementID,$intMovement,$strAmount,$strDescription);
          if(is_numeric($arrMovement) and $arrMovement > 0) {

            $arrMovement = array('idMovement' => $arrMovement);
            $response = array('status' => true , 'msg' => 'Data saved successfully', 'data' => $arrMovement); 

          } else {

            $response = array('status' => false , 'msg' => 'Is not possible to register the movement','msg_tecnito' =>$arrMovement );
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

    public function movement($idmovement)
    {
      try {

        $method = $_SERVER['REQUEST_METHOD'];
        $response = [];
        if($method == "GET") {

          if(empty($idmovement) or !is_numeric($idmovement)) {

            $response = array('status' => false , 'msg' => 'Parameter error');
            jsonResponse($response,400);
            die();
          }

          $idmovement = strClean($idmovement);
          $arrMovement = $this->model->getMovement($idmovement);
          if(empty($arrMovement)) {

            $response = array('status' => false , 'msg' => 'Record not found'); 

          } else {

            $response = array('status' => true , 'msg' => 'Data found', 'data' => $arrMovement);
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

    public function movements()
    {
      try {

        $method = $_SERVER['REQUEST_METHOD'];
        $response = [];
        if($method == "GET") {

          $arrMovements = $this->model->getMovements();
          if(empty($arrMovements)) {

            $response = array('status' => false , 'msg' => 'No data to display', 'data' => "");

          } else {

            $response = array('status' => true , 'msg' => 'Data found', 'data' => $arrMovements);
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

    public function cancel($idmovement)
    {
      try {

        $method = $_SERVER['REQUEST_METHOD'];
        $response = [];
        if($method == "DELETE") {

          if(empty($idmovement) or !is_numeric($idmovement)) {

            $response = array('status' => false , 'msg' => 'Parameter error');
            $code = 400;
            jsonResponse($response,$code);
            die();
          }

          $request = $this->model->getMovement($idmovement);
          if(empty($request)) {

            $response = array('status' => false , 'msg' => 'The record does not exists or was already deleted');
            jsonResponse($response,400);
            die();

          } else {

            $request = $this->model->cancelMovement($idmovement);
            if(!empty($request)) {

              $response = array('status' => true , 'msg' => 'Movement canceled', 'data' =>  $request[0]); 

            } else {

              $response = array('status' => false , 'msg' => 'Is not possible to delete the movement');
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