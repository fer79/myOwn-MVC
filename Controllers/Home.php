<?php
    class Home extends Controllers{

        public function __construct()
        {
            parent::__construct();
        }

        public function home($params)
        {
            $data['page_tag'] = "Home";
            $data['page_title'] = "Home page";
            $data['page_name'] = "home";
            $this->views->getView($this,"home",$data);
        }


    }


?>