<?php
namespace Src\BackendBundle\Controller;
use \Src\BackendBundle\Clases\Vehiculo;
class PdfController extends AppController
{
    function __construct() {
        parent::__construct();
    }
    public function rep_vehiculos(){
        if($this->checkUser()){
            $this->getPdf()->AddPage();
            $this->getPdf()->SetFont('Arial','B',16);
            $this->getPdf()->Cell(40,10,utf8_decode('Reporte de Vehículos'));
            $this->getPdf()->Ln(5);
            $this->getPdf()->SetFont('Arial','B',12);
            $this->getPdf()->Ln(8);
            foreach ((new Vehiculo())->find() as $vehiculo){
                $this->getPdf()->Cell(20,5,$this->getPdf()->Image($vehiculo->getFoto(),null,null,40,40));
                $this->getPdf()->Cell(30);
                $this->getPdf()->Cell(20,-70,utf8_decode('Vehículo:')." ".$vehiculo->getId()." ".utf8_decode('Matrícula:')." ".$vehiculo->getMat()." Tipo:"." ".$vehiculo->getTipo()->getNombre());                
                $this->getPdf()->Ln(5);
                $this->getPdf()->Cell(50);
                $this->getPdf()->Cell(20,-70,"Precio: ".$vehiculo->getPrecio()." Cantidad: ".$vehiculo->getCant()." Modelo: ".$vehiculo->getModelo()->getNombre());
                $this->getPdf()->Ln(5);
                $this->getPdf()->Cell(50);
                $this->getPdf()->Cell(20,-70,utf8_decode('Descripción:'));
                $this->getPdf()->Ln(5);
                $this->getPdf()->Cell(50);
                $this->getPdf()->Cell(20,-70,$vehiculo->getDescrip());
                $this->getPdf()->Ln(10);
            }
            $this->getPdf()->Output();
        }
    }
    protected function getMessageRole() {
        return "administrador";
    }
    protected function getTypeRole() {
        return "ADMIN";
    }
}