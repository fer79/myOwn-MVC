<?php
  
  class Client extends Controllers {

    public function __construct()
    {
        parent::__construct();
    }

    public function client($idclient)
    {
      try {
        $method = $_SERVER['REQUEST_METHOD'];
        $response = [];
        if($method == "GET") {

          if(empty($idclient) or !is_numeric($idclient)) {

            $response = array('status' => false , 'msg' => 'Parameter error');
            $code = 400;
            jsonResponse($response,$code);
            die();
          }

          $arrClient = $this->model->getClient($idclient);
          if(empty($arrClient)) {

            $response = array('status' => false , 'msg' => 'Record not found');

          } else {

            $response = array('status' => true , 'msg' => 'Data found', 'data' => $arrClient);
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
          if(empty($_POST['identification'])) {

            $response = array('status' => false , 'msg' => 'Identification required');
            jsonResponse($response,200);
            die();
          }

          if(empty($arrData['names']) or !testString($arrData['names'])) {

            $response = array('status' => false , 'msg' => 'Error in the names');
            jsonResponse($response,200);
            die();
          }

          if(empty($arrData['lastnames']) or !testString($arrData['lastnames'])) {

            $response = array('status' => false , 'msg' => 'Error in the last names');
            jsonResponse($response,200);
            die();
          }

          if(empty($arrData['phone']) or !testEntero($arrData['phone'])) {

            $response = array('status' => false , 'msg' => 'Error in the phone');
            jsonResponse($response,200);
            die();
          }

          if(empty($arrData['email']) or !testEmail($arrData['email'])) {

            $response = array('status' => false , 'msg' => 'Error in the email');
            jsonResponse($response,200);
            die();
          }

          if(empty($_POST['address'])) {
                    
            $response = array('status' => false , 'msg' => 'The address is required');
            jsonResponse($response,200);
            die();
          }

          $strIdentification = $_POST['identification'];
          $strNames = ucwords(strtolower($_POST['names']));
          $strLastnames = ucwords(strtolower($_POST['lastnames']));
          $intPhone = $_POST['phone'];
          $strEmail = strtolower($_POST['email']);
          $strAddress = $_POST['address'];
          $strTin = !empty($_POST['tin']) ? strClean($_POST['tin']) : "";
          $strTaxname = !empty($_POST['taxname']) ? strClean($_POST['taxname']) : "";
          $strTaxadress = !empty($_POST['taxadress']) ? strClean($_POST['taxadress']) : "";

          $request = $this->model->setClient(
            $strIdentification,
            $strNames,
            $strLastnames,
            $intPhone,
            $strEmail,
            $strAddress,
            $strTin,
            $strTaxname,
            $strTaxadress);
                
          if($request > 0) {

            $arrClient = array('idclient' => $request,
              'identification' => $strIdentification,
              'names' => $strNames,
              'lastnames' => $strLastnames,
              'phone' => $intPhone,
              'email' => $strEmail,
              'address' => $strAddress,
              'tin' => $strTin,
              'taxname' => $strTaxname,
              'taxadress' => $strTaxadress);
              
            $response = array('status' => true , 'msg' => 'Data saved successfully', 'data' => $arrClient);                

          } else {

            $response = array('status' => false , 'msg' => 'The identification or email already exists');
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

    public function clients()
    {
      try {
      
        $method = $_SERVER['REQUEST_METHOD'];
        $response = [];
        if($method == "GET") {

          $arrData = $this->model->getClients();
          if(empty($arrData)) {

            $response = array('status' => false , 'msg' => 'No data to display', 'data' => '');

          } else {

            $response = array('status' => true , 'msg' => 'Data found ', 'data' =>  $arrData);
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

    public function update($idclient)
    {
      try {

        $method = $_SERVER['REQUEST_METHOD'];
        $response = [];                
        if($method == "PUT") {

          $arrData = json_decode(file_get_contents('php://input'),true);
          if(empty($idclient) or !is_numeric($idclient)) {

            $response = array('status' => false , 'msg' => 'Parameter error');
            $code = 400;
            jsonResponse($response,$code);
            die();
          }

          if(empty($arrData['identification'])) {

            $response = array('status' => false , 'msg' => 'Identification required');
            jsonResponse($response,200);
            die();
          }

          if(empty($arrData['names']) or !testString($arrData['names'])) {

            $response = array('status' => false , 'msg' => 'Error in the names');
            jsonResponse($response,200);
            die();
          }

          if(empty($arrData['lastnames']) or !testString($arrData['lastnames'])) {

            $response = array('status' => false , 'msg' => 'Error in the last names');
            jsonResponse($response,200);
            die();
          }

          if(empty($arrData['phone']) or !testEntero($arrData['phone'])) {

            $response = array('status' => false , 'msg' => 'Error in the phone');
            jsonResponse($response,200);
            die();
          }

          if(empty($arrData['email']) or !testEmail($arrData['email'])) {

            $response = array('status' => false , 'msg' => 'Error in the email');
            jsonResponse($response,200);
            die();
          }

          if(empty($arrData['address'])) {

            $response = array('status' => false , 'msg' => 'The address is required');
            jsonResponse($response,200);
            die();
          }

          $strIdentification = $arrData['identification'];
          $strNames = ucwords(strtolower($arrData['names']));
          $strLastnames = ucwords(strtolower($arrData['lastnames']));
          $intPhone = $arrData['phone'];
          $strEmail = strtolower($arrData['email']);
          $strAddress = $arrData['address'];
          $strTin = !empty($arrData['tin']) ? strClean($arrData['tin']) : "";
          $strTaxname = !empty($arrData['taxname']) ? strClean($arrData['taxname']) : "";
          $strTaxadress = !empty($arrData['taxadress']) ? strClean($arrData['taxadress']) : "";

          $search_client = $this->model->getClient($idclient);
          if(empty($search_client)) {

            $response = array('status' => false , 'msg' => 'The client does not exist');
            $code = 400;
            jsonResponse($response,$code);
            die();
          }

          $request = $this->model->putClient($idclient,
            $strIdentification,
            $strNames,
            $strLastnames,
            $intPhone,
            $strEmail,
            $strAddress,
            $strTin,
            $strTaxname,
            $strTaxadress);

          if($request) {

            $arrClient = array('idclient' => $idclient,
              'identification' => $strIdentification,
              'names' => $strNames,
              'lastnames' => $strLastnames,
              'phone' => $intPhone,
              'email' => $strEmail,
              'address' => $strAddress,
              'tin' => $strTin,
              'taxname' => $strTaxname,
              'taxadress' => $strTaxadress);

            $response = array('status' => true , 'msg' => 'Data Updated successfully', 'data' => $arrClient);

          } else {

            $response = array('status' => true , 'msg' => 'The identification or email already exists');

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

    public function delete($idclient)
    {
      try {
        $method = $_SERVER['REQUEST_METHOD'];
        $response = [];
        if($method == "DELETE") {
                
          if(empty($idclient) or !is_numeric($idclient)) {

            $response = array('status' => false , 'msg' => 'Parameter error');
            $code = 400;
            jsonResponse($response,$code);
            die();
          }

          $search_client = $this->model->getClient($idclient);
          if(empty($search_client)) {

            $response = array('status' => false , 'msg' => 'The client does not exists or was already deleted');
            $code = 400;
            jsonResponse($response,$code);
            die();
          }

          $request = $this->model->deleteClient($idclient);
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