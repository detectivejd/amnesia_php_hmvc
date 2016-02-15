<?php
namespace Src\BackendBundle\Clases;
use \App\IPersiste;
use \Src\BackendBundle\Model\RolModel;
class Rol implements IPersiste 
{
    private $id;
    private $nombre;
    function getId() {
        return $this->id;
    }
    function getNombre() {
        return $this->nombre;
    }
    function setId($id) {
        $this->id = $id;
    }
    function setNombre($nombre) {
        $this->nombre = strtoupper($nombre);
    }
    function __construct() { }
    public function equals(Rol $obj) {
        return $this->nombre == $obj->nombre;                
    }
    public function save(){
        return ($this->id == 0) ? (new RolModel())->create($this) : (new RolModel())->update($this); 
    }
    public function del(){
        return (new RolModel())->delete($this);
    }
    public function find($criterio = null){
        return (new RolModel())->find();
    }
    public function findById($id){
        return (new RolModel())->findById($id);
    }
}