<?php
namespace Src\BackendBundle\Controller;
use \App\Session;
use \Src\BackendBundle\Clases\TipoVehiculo;
class TiposvehController extends AppController
{
    function __construct() {
        parent::__construct();
    }
    public function index(){
        if($this->checkUser()){
            $this->redirect(array("index.php"), array(
                "tiposveh" => (new TipoVehiculo())->find()
            )); 
        } 
    }
    public function add(){
        if($this->checkUser()){
            if (isset($_POST['btnaceptar'])) {
                if($this->checkDates()) {  
                    $tv = $this->createEntity();
                    $id = $tv->save();
                    Session::set("msg",(isset($id)) ? "Tipo de Vehículo Creado" : Session::get('msg'));
                    header("Location:index.php?b=backend&c=tiposveh&a=index");
                    exit();
                }
            }            
            $this->redirect(array('add.php'));
        }
    }   
    public function edit(){        
        if($this->checkUser()){
            Session::set("id",$_GET['p']);
            if (Session::get('id')!=null && isset($_POST['btnaceptar'])){ 
                if($this->checkDates()) {         
                    $tv = $this->createEntity();
                    $id = $tv->save();
                    Session::set("msg",(isset($id)) ? "Tipo de Vehículos Editado" : Session::get('msg'));
                    header("Location:index.php?b=backend&c=tiposveh&a=index");
                    exit();
                }
            }
            $this->redirect(array('edit.php'),array(
                "tv" => (new TipoVehiculo())->findById(Session::get('id'))
            ));
        }        
    }
    public function delete(){
        if($this->checkUser()){
            if (isset($_GET['p'])){
                $tv = (new TipoVehiculo())->findById($_GET['p']); 
                $id = $tv->del($tv);
                Session::set("msg", (isset($id)) ? "Tipo de Vehículo Borrado" : "No se pudo borrar el tipo");
                header("Location:index.php?b=backend&c=tiposveh&a=index");
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
        $obj = new TipoVehiculo();
        $obj->setId(isset($_POST['hid']) ? $_POST['hid'] : 0);
        $obj->setNombre($_POST['txtnom']);
        return $obj;
    }
}