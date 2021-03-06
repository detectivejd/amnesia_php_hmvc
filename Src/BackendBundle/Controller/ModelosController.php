<?php
namespace Src\BackendBundle\Controller;
use \App\Session;
use \Src\BackendBundle\Clases\Marca;
use \Src\BackendBundle\Clases\Modelo;
class ModelosController extends AppController
{
    function __construct() {
        parent::__construct();
    }
    public function index(){
        if($this->checkUser()){
            Session::set('mar', '');
            Session::set('p', isset($_GET['p']) ? $_GET['p'] : 1);
            Session::set('b',(isset($_POST['txtbuscador'])) ? $_POST['txtbuscador'] : Session::get('b'));
            $modelos =(Session::get('b')!="") ? $this->getPaginator()->paginar((new Modelo)->find(Session::get('b')), Session::get('p')) : array();
            $this->redirect(array("index.php"),array(
                "modelos" => $modelos,
                "paginador" => $this->getPaginator()->getPages()
            ));         
        }
    }
    public function add(){
        if($this->checkUser()){ 
            Session::set('mar', isset($_POST['txtmar']) ? $_POST['txtmar'] : Session::get('mar'));
            $marcas = (Session::get('mar')!="") ? (new Modelo)->findByMarcas(Session::get('mar')) : array();
            if (isset($_POST['btnaceptar'])) {
                if($this->checkDates()) {
                    $modelo = $this->createEntity();
                    $id = $modelo->save();
                    Session::set("msg",(isset($id)) ? "Modelo Creado" : Session::get('msg')); 
                    header("Location:index.php?b=backend&c=modelos&a=index");
                    exit();
                }
            }
            $this->redirect(array('add.php'),array(
                'marcas' => $marcas
            ));
        }
    }
    public function edit(){
        if($this->checkUser()){           
            Session::set("id",$_GET['p']);
            Session::set('mar', isset($_POST['txtmar']) ? $_POST['txtmar'] : Session::get('mar'));
            $marcas = (Session::get('mar')!="") ? (new Modelo)->findByMarcas(Session::get('mar')) : array();
            if (Session::get('id')!=null && isset($_POST['btnaceptar'])){                                         
                if($this->checkDates()) { 
                    $modelo = $this->createEntity();
                    $id = $modelo->save();
                    Session::set("msg",(isset($id)) ? "Modelo Editado" : Session::get('msg'));
                    header("Location:index.php?b=backend&c=modelos&a=index");
                    exit();
                }
            }
            $this->redirect(array('edit.php'),array(
                "modelo" => (new Modelo)->findById(Session::get('id')),
                'marcas' => $marcas
            ));
        }
    }
    public function delete(){
        if($this->checkUser()){
            if (isset($_GET['p'])){
                $modelo= (new Modelo)->findById($_GET['p']);
                $id = $modelo->del();
                Session::set("msg", (isset($id)) ? "Modelo Borrado" : "No se pudo borrar el modelo");
                header("Location:index.php?b=backend&c=modelos&a=index"); 
            }                           
        }
    }
    private function checkDates(){
        if(empty($_POST['txtnom']) || empty($_POST['txtmar'])){
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
        $marca = (new Marca())->findById($_POST['txtmar']);
        $obj = new Modelo();
        $obj->setId(isset($_POST['hid']) ? $_POST['hid'] : 0);
        $obj->setNombre($_POST['txtnom']);
        $obj->setMarca($marca);
        return $obj;
    }
}