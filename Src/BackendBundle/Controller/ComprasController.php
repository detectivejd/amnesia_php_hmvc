<?php
namespace Src\BackendBundle\Controller;
use \App\Session;
use \Src\BackendBundle\Clases\TipoCompra;
use \Src\BackendBundle\Clases\Usuario;
use \Src\BackendBundle\Clases\Vehiculo;
use \Src\BackendBundle\Clases\Compra;
class ComprasController extends AppController
{
    function __construct() {
        parent::__construct();        
    }
    public function index(){
        if($this->checkUser()){
            Session::set('tic', '');
            Session::set('cli', '');
            Session::set('veh', '');
            Session::set('p', isset($_GET['p']) ? $_GET['p'] : 1);
            Session::set('b',(isset($_POST['txtbuscador'])) ? $_POST['txtbuscador'] : Session::get('b'));
            $compras =(Session::get('b')!="") ? $this->getPaginator()->paginar((new Compra())->find(Session::get('b')), Session::get('p')) : array();
            $this->redirect(array('index.php'),array(
                "compras" => $compras,
                "paginador" => $this->getPaginator()->getPages()
            ));
        }
    }
    public function add(){
        if($this->checkUser()){            
            Session::set('tic', isset($_POST['txttipcom']) ? $_POST['txttipcom'] : Session::get('tic'));
            Session::set('cli', isset($_POST['txtcli']) ? $_POST['txtcli'] : Session::get('cli'));
            Session::set('veh', isset($_POST['txtveh']) ? $_POST['txtveh'] : Session::get('veh'));
            $clientes = (Session::get('cli')!="") ? (new Compra())->findByClientes(Session::get('cli')) : array();
            $vehiculos = (Session::get('veh')!="") ? (new Compra())->findByVeh(Session::get('veh')) : array();
            if(isset($_POST['btnaceptar'])){
                if($this->checkDates()) {
                    $com = $this->createEntity();
                    $com->save();
                    $com->getVeh()->quitarStock($com->getCant());
                    $com->getVeh()->save();
                    Session::set("msg","Compra Creada");
                    header("Location:index.php?b=backend&c=compras&a=index");
                    exit();
                }
            }
            $this->redirect(array('add.php'),array(
                'clientes' => $clientes,
                'vehiculos' => $vehiculos,
                'tiposcom' => (new TipoCompra())->find()
            ));
        }
    }
    public function edit(){
        if($this->checkUser()){
            Session::set("id",$_GET['p']);
            $compra=(new Compra())->findById(Session::get('id'));
            Session::set('tic', isset($_POST['txttipcom']) ? $_POST['txttipcom'] : $compra->getTipo()->getId());
            Session::set('cli', isset($_POST['txtcli']) ? $_POST['txtcli'] : $compra->getUser()->getId());
            Session::set('veh', isset($_POST['txtveh']) ? $_POST['txtveh'] : $compra->getVeh()->getId());
            $clientes = (Session::get('cli')!="") ? (new Compra())->findByClientes(Session::get('cli')) : array();
            $vehiculos = (Session::get('veh')!="") ? (new Compra())->findByVeh(Session::get('veh')) : array();
            if (Session::get('id')!=null && isset($_POST['btnaceptar'])){
                 if($this->checkDates()) {
                    $com = $this->createEntity();
                    $com->save();
                    $com->getVeh()->quitarStock($com->getCant());
                    $com->getVeh()->save();
                    Session::set("msg","Compra Editada");
                    header("Location:index.php?b=backend&c=compras&a=index");
                    exit();
                 }
            }
            $this->redirect(array('edit.php'),array(
                'clientes' => $clientes,
                'vehiculos' => $vehiculos,
                'tiposcom' => (new TipoCompra())->find(),
                'compra' =>  $compra
            ));
        }
    }
    public function view(){
        if($this->checkUser()){
            Session::set("id",$_GET['p']);
            $this->redirect(array('view.php'),array(
                'compra' => (new Compra())->findById(Session::get('id'))
            ));
        }        
    }
    private function checkDates(){
        if(empty($_POST['txttipcom']) or empty($_POST['txtcli']) or empty($_POST['txtveh']) or empty($_POST['dtfecha']) or empty($_POST['txtcant'])){
            Session::set("msg","Ingrese los datos obligatorios (*) para continuar.");
            return false;
        }
        else if(!ctype_digit($_POST['txtcuotas']) or !ctype_digit($_POST['txtcant'])){
            Session::set("msg","Asegurese de que la cantidad y/o las cuotas sean nros enteros");
            return false;
        }
        else if((new Vehiculo())->findById($_POST['txtveh'])->getCant() < $_POST['txtcant']){
            Session::set("msg","El vehículo no cuenta con la cantidad ingresada");
            return false;
        }
        else {
            return true;
        }
    }
    private function checkTypeCuota($tipo){
        return ($tipo->getNombre() == "CONTADO" ? 1 : $_POST['txtcuotas']);
    }
    protected function getMessageRole() {
        return "administrador";
    }
    protected function getTypeRole() {
        return "ADMIN";
    }
    public function createEntity() {
        $tipo =  (new TipoCompra())->findById($_POST['txttipcom']);
        $user = (new Usuario())->findById($_POST['txtcli']);
        $veh = (new Vehiculo())->findById($_POST['txtveh']); 
        $obj = new Compra();
        $obj->setId((isset($_POST['hid']) ? $_POST['hid'] : 0));
        $obj->setFecha($_POST['dtfecha']);
        $obj->setCuotas($this->checkTypeCuota($tipo));
        $obj->setCant($_POST['txtcant']);
        $obj->setTipo($tipo);
        $obj->setUser($user);
        $obj->setVeh($veh);
        return $obj;
    }
}