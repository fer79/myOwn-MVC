<?php

    class Frequency extends Controllers{

      public function __construct()
      {
          parent::__construct();
      }

      public function frequency($idfrequency)
      {
        try {

          $method = $_SERVER['REQUEST_METHOD'];
          $response = [];
          if($method == "GET") {

            if(empty($idfrequency) or !is_numeric($idfrequency)){

              $response = array('status' => false , 'msg' => 'Parameter error');
              jsonResponse($response,400);
              die();
            }

            $search_frequency = $this->model->getFrequency($idfrequency);
            if(empty($search_frequency)) {

              $response = array('status' => false , 'msg' => 'The record does not exist');

            } else {

              $response = array('status' => true , 'msg' => 'Data found', 'data' => $search_frequency);
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

      public function frequencys()
      {
        try {

          $method = $_SERVER['REQUEST_METHOD'];
          $response = [];
          if($method == "GET") {

            $arrData = $this->model->getFrequencys();
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
          if(empty($_POST['frequency'])) {

            $response = array('status' => false , 'msg' => 'Frequency required');
            jsonResponse($response,200);
            die();
          }

          $strFrequency = ucwords(strClean($_POST['frequency']));
          $request = $this->model->setFrequency($strFrequency);
          if($request > 0) {

            $arrFrequency = array('idFrequency' => $request,
              'frequency' => $strFrequency);

            $response = array('status' => true , 'msg' => 'Data saved successfully', 'data' => $arrFrequency);   

          } else {

            $response = array('status' => false , 'msg' => 'The frequency already exists');
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

    public function update($idfrequency)
    {
      try {

        $method = $_SERVER['REQUEST_METHOD'];
        $response = [];
        if($method == "PUT") {

          $arrData = json_decode(file_get_contents('php://input'),true);
          if(empty($idfrequency) or !is_numeric($idfrequency)) {

            $response = array('status' => false , 'msg' => 'Parameter error');
            $code = 400;
            jsonResponse($response,$code);
            die();
          }

          if(empty($arrData['frequency'])) {

            $response = array('status' => false , 'msg' => 'Frequency required');
            jsonResponse($response,200);
            die();
          }

          $strFrequency = ucwords(strClean($arrData['frequency']));
          $search_frequency = $this->model->getFrequency($idfrequency);

          if(empty($search_frequency)) {

            $response = array('status' => false , 'msg' => 'The record does not exist');
            jsonResponse($response,200);
          }

          $request = $this->model->putFrequency($idfrequency,$strFrequency);
          if($request) {

            $arrFrequency = array('idFrequency' => $idfrequency,
              'frequency' => $strFrequency);

            $response = array('status' => true , 'msg' => 'Data Updated successfully', 'data' => $arrFrequency);

          } else {

            $response = array('status' => false , 'msg' => 'The record already exists');
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

    public function delete($idfrequency)
    {
      try {

        $method = $_SERVER['REQUEST_METHOD'];
        $response = [];
        if($method == "DELETE") {

          if(empty($idfrequency) or !is_numeric($idfrequency)){

            $response = array('status' => false , 'msg' => 'Parameter error');
            jsonResponse($response,400);
            die();
          }

          $search_frequency = $this->model->getFrequency($idfrequency);
          if(empty($search_frequency)) {

            $response = array('status' => false , 'msg' => 'The record does not exists or was already deleted');
            jsonResponse($response,200);
            die();
          }

          $request = $this->model->deleteFrequency($idfrequency);
          if($request) {

            $response = array('status' => true , 'msg' => 'Record deleted');

          } else {

            $response = array('status' => false , 'msg' => 'Cannot delete record');
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