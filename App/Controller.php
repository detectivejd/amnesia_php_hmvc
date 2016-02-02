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
    public function redirect($route = array(), $dates = array()) {
        try {
            $folder = (count($route) > 1) ? $route[0] : $this->deleteWordController();
            $file = (count($route) > 1) ? $route[1] : $route[0];
            var_dump($folder);
            $path = $this->createFile(APPLICATION_PATH . DS . explode('\\', get_called_class())[0] . DS . explode('\\', get_called_class())[1] . DS . "View" . DS . $folder . DS . $file, $dates);
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
    private function deleteWordController() {
        $word = preg_replace("/[^a-zA-Z0-9]+/", "", str_replace("Controller", "", get_class($this)));
        $word = preg_replace("/[^a-zA-Z0-9]+/", "", str_replace("Src", "", $word));
        $word = preg_replace("/[^a-zA-Z0-9]+/", "", str_replace("Bundle", "", $word));
        /* ------------------------------ */
        $word = preg_replace("/[^a-zA-Z0-9]+/", "", str_replace("Backend", "", $word));
        $word = preg_replace("/[^a-zA-Z0-9]+/", "", str_replace("Frontend", "", $word));
        $word = preg_replace("/[^a-zA-Z0-9]+/", "", str_replace("Consulta", "", $word));
        return $word;
        
    }
    protected function getPaginator() {
        return $this->paginador;
    }
    protected function checkUser() {
        if (Session::get("log_in") != null and Session::get("log_in")->getRol()->getNombre() == $this->getTypeRole()) {
            return true;
        } else {
            Session::set("msg", "Debe loguearse como " . $this->getMessageRole() . " para acceder.");
            $this->redirect(array('Main', 'index.php'));
        }
    }
    protected function getMessageRole() { }
    protected function getTypeRole() { }
}