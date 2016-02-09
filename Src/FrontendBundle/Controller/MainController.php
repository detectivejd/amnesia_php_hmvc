<?php
namespace Src\FrontendBundle\Controller;
class MainController extends AppController 
{    
    function __construct() {
        parent::__construct();
    }
    public function index() {
        $this->redirect(array('index.php'));
    }
}