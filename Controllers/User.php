<?php
  class User extends Controllers{

    public function __construct()
    {
        parent::__construct();
    }

    public function user($iduser)
    {
      try {

        $method = $_SERVER['REQUEST_METHOD'];
        $response = [];
        if($method == "GET") {

          if(empty($iduser) or !is_numeric($iduser)){

            $response = array('status' => false , 'msg' => 'Parameter error');
            jsonResponse($response,400);
            die();
          }

          $arrUser = $this->model->getUser($iduser);
          if(empty($arrUser)) {

            $response = array('status' => false , 'msg' => 'Record not found');
          } else {

            $response = array('status' => true , 'msg' => 'Data found', 'data' => $arrUser);
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

    public function record(){
      try {

        $method = $_SERVER['REQUEST_METHOD'];
        $response = [];
        if($method == "POST") {

          $_POST = json_decode(file_get_contents('php://input'),true);
          if(empty($_POST['names']) or !testString($_POST['names'])) {

            $response = array('status' => false , 'msg' => 'Error in the names');
            jsonResponse($response,200);
            die();
          }

          if(empty($_POST['lastnames']) or !testString($_POST['lastnames'])) {

            $response = array('status' => false , 'msg' => 'Error in the last names');
            jsonResponse($response,200);
            die();
          }

          if(empty($_POST['email']) or !testEmail($_POST['email'])) {

            $response = array('status' => false , 'msg' => 'Error in the email');
            jsonResponse($response,200);
            die();
          }

          if(empty($_POST['password'])) {

            $response = array('status' => false , 'msg' => 'El password es requerido');
            jsonResponse($response,200);
            die();
          }

          $strNames = ucwords(strClean($_POST['names']));
          $strLastnames = ucwords(strClean($_POST['lastnames']));
          $strEmail = strClean($_POST['email']);
          $strPassword = hash("SHA256",$_POST['password']);
                
          $request = $this->model->setUser($strNames,
            $strLastnames,
            $strEmail, 
            $strPassword);

          if($request > 0) {

            $arrUser = array('id' => $request);
            $response = array('status' => true , 'msg' => 'Data saved successfully', 'data' => $arrUser);

          } else {

            $response = array('status' => false , 'msg' => 'The email already exists');
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

    public function update($iduser)
    {
      try {

        $method = $_SERVER['REQUEST_METHOD'];
        $response = [];
        if($method == "PUT") {

          $data = json_decode(file_get_contents('php://input'),true);
          if(empty($iduser) or !is_numeric($iduser)) {

            $response = array('status' => false , 'msg' => 'Parameter error');
            $code = 400;
            jsonResponse($response,$code);
            die();
          }

          if(empty($data['names']) or !testString($data['names'])) {

            $response = array('status' => false , 'msg' => 'Error in the names');
            jsonResponse($response,200);
            die();
          }

          if(empty($data['lastnames']) or !testString($data['lastnames'])) {

            $response = array('status' => false , 'msg' => 'Error in the last names');
            jsonResponse($response,200);
            die();
          }

          if(empty($data['email']) or !testEmail($data['email'])) {

            $response = array('status' => false , 'msg' => 'Error in the email');
            jsonResponse($response,200);
            die();
          }

          $strNames = ucwords(strClean($data['names']));
          $strLastnames = ucwords(strClean($data['lastnames']));
          $strEmail = strClean($data['email']);
          $strPassword = !empty($data['password']) ? hash("SHA256",$data['password']) : "";

          $search_user = $this->model->getUser($iduser);
          if(empty($search_user)) {

            $response = array('status' => false , 'msg' => 'El user no existe');
            $code = 400;
            jsonResponse($response,$code);
            die();
          }

          $request = $this->model->putUser($iduser,
            $strNames,
            $strLastnames,
            $strEmail, 
            $strPassword);

          if($request > 0) {

            $arrUser = array('iduser' => $iduser,
              'names' => $strNames,
              'lastnames' => $strLastnames,
              'email' => $strEmail);

            $response = array('status' => true , 'msg' => 'Data Updated successfully', 'data' => $arrUser);                

          } else {

            $response = array('status' => false , 'msg' => 'The email already exists');
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

    public function users()
    {
      try {

        $method = $_SERVER['REQUEST_METHOD'];
        $response = [];
        if($method == "GET") {

          $arrData = $this->model->getUsers();
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

    public function delete($iduser)
    {
      try {

        $method = $_SERVER['REQUEST_METHOD'];
        $response = [];
        if($method == "DELETE") {

          if(empty($iduser) or !is_numeric($iduser)) {

            $response = array('status' => false , 'msg' => 'Parameter error');
            jsonResponse($response,400);
            die();
          }

          $search_user = $this->model->getUser($iduser);
          if(empty($search_user)) {

            $response = array('status' => false , 'msg' => 'El user no existe o ya fue eliminado');
            jsonResponse($response,400);
            die();
          }

          $request = $this->model->deleteUser($iduser);
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

    public function login()
    {
      try {

        $method = $_SERVER['REQUEST_METHOD'];
        $response = [];
        if($method == "POST") {

          $_POST = json_decode(file_get_contents('php://input'),true);
          if(empty($_POST['email']) || empty($_POST['password'])) {

            $response = array('status' => false, 'msg' => 'Error de datos' );
            jsonResponse($response,200);
            die();
          }

          $strEmail  =  strClean($_POST['email']);
          $strPassword = hash("SHA256",$_POST['password']);
          $requestUser = $this->model->loginUser($strEmail, $strPassword);

          if(empty($requestUser)){

            $response = array('status' => false, 'msg' => 'El user o la contraseña es incorrecto.' ); 

          } else {

            $response = array('status' => true, 'msg' => '¡Bienvenido al sistema!', 'data' => $requestUser);
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