<?php
namespace Src\BackendBundle\Controller;
use \App\Session;
use \Src\BackendBundle\Clases\Marca;
class MarcasController extends AppController
{
    function __construct() {        
        parent::__construct();
    }
    public function index(){  
        if($this->checkUser()){
            Session::set('p', isset($_GET['p']) ? $_GET['p'] : 1);
            Session::set('b',(isset($_POST['txtbuscador'])) ? $_POST['txtbuscador'] : Session::get('b'));
            $marcas =(Session::get('b')!="") ? $this->getPaginator()->paginar((new Marca())->find(Session::get('b')), Session::get('p')) : array();
            $this->redirect(array("index.php"), array(
                "marcas" => $marcas,
                "paginador" => $this->getPaginator()->getPages()
            ));         
        }
    }
    public function add(){
        if($this->checkUser()){
            if (isset($_POST['btnaceptar'])) {
                if($this->checkDates()) {  
                    $marca = $this->createEntity();
                    $id = $marca->save();
                    Session::set("msg",(isset($id)) ? "Marca Creada" : Session::get('msg')); 
                    header("Location:index.php?b=backend&c=marcas&a=index");
                    exit();
                }
            }
            $this->redirect(array('add.php'));
        }
    }
    public function edit(){
        Session::set("id",$_GET['p']);
        if($this->checkUser()){
            if (Session::get('id')!=null && isset($_POST['btnaceptar'])){                             
                if($this->checkDates()) {  
                    $marca = $this->createEntity();
                    $id = $marca->save();
                    Session::set("msg",(isset($id)) ? "Marca Editada" : Session::get('msg'));
                    header("Location:index.php?b=backend&c=marcas&a=index");
                    exit();
                }
            }
            $this->redirect(array('edit.php'),array(
                "marca" => (new Marca())->findById(Session::get('id'))
            ));
        }       
    }   
    public function delete(){
        if($this->checkUser()){
            if (isset($_GET['p'])){
                $marca = (new Marca())->findById($_GET['p']);
                $id = $marca->del();
                Session::set("msg", (isset($id)) ? "Marca Borrada" : "No se pudo borrar la marca");
                header("Location:index.php?b=backend&c=marcas&a=index");
            }                           
        }
    }
    private function checkDates(){
        if(empty($_POST['txtnom'])){
            Session::set("msg","Ingrese los datos obligatorios (*) para continuar.");
            return false;
        }
        else {
            return true;
        }
    }
    protected function getMessageRole() {
        return "administrador";
    }
    protected function getTypeRole() {
        return "ADMIN";
    }
    protected function createEntity() {         
        $obj = new Marca();
        $obj->setId(isset($_POST['hid']) ? $_POST['hid'] : 0);
        $obj->setNombre($_POST['txtnom']);
        return $obj;
    }
}