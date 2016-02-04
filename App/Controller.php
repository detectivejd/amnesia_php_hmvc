<?php
namespace App;
use Lib\Paginador;
abstract class Controller 
{
    private $paginador;
    function __construct() {
        session_start();
        $this->paginador = new Paginador();
    }
    public function redirect($file = array(), $dates = array()) {
        try {
            $ns = explode('\\', get_called_class());
            $path = $this->createFile(APPLICATION_PATH . DS . $ns[0] . DS . $ns[1] . DS . "View" . DS . str_replace("Controller", "", $ns[3]) . DS . $file[0], $dates);
            echo $this->createFile(APPLICATION_PATH . DS . 'Public' . DS . 'layout.php', array('content' => $path));
        } 
        catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }
    private function createFile($file, $dates = array()) {
        try {
            extract($dates);
            ob_start();
            require $file;
            return ob_get_clean();
        } 
        catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }
    protected function getPaginator() {
        return $this->paginador;
    }
    protected function checkUser() {
        if (Session::get("log_in") != null and Session::get("log_in")->getRol()->getNombre() == $this->getTypeRole()) {
            return true;
        } else {
            Session::set("msg", "Debe loguearse como " . $this->getMessageRole() . " para acceder.");
            header("Location:index.php?c=main&a=index");
        }
    }
    protected function getMessageRole() { }
    protected function getTypeRole() { }    
}