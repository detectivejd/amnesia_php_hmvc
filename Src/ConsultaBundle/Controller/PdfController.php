<?php
namespace Src\ConsultaBundle\Controller;
use \App\Session;
use \Src\ConsultaBundle\Clases\Consulta;
class PdfController extends AppController
{
    function __construct() {
        parent::__construct();
    }
    public function c1(){
        if($this->checkUser()){
            Session::set('p', isset($_GET['p']) ? $_GET['p'] : Session::get('p'));             
            $compras=array();
            if(Session::get('p') == "d"){
                $compras = (new Consulta())->cons1ByDay(Session::get('log_in')->getId());
            }
            else if(Session::get('p') == "m"){
                $compras = (new Consulta())->cons1ByMonth(Session::get('log_in')->getId());
            }
            else {
                $compras = (new Consulta())->cons1ByYear(Session::get('log_in')->getId());
            } 
            $this->getPdf()->AddPage();
            $this->getPdf()->AliasNbPages();
            $this->getPdf()->SetFont('Arial','B',16);
            $this->getPdf()->Cell(40,10,utf8_decode('Mostrar Compras por período'));
            $this->getPdf()->SetFont('Arial','B',12);            
            $this->getPdf()->Ln(20);
            $this->getPdf()->Cell(20,5,"Cliente: ". Session::get('log_in')->getApellido() ." ".Session::get('log_in')->getNombre());
            $this->getPdf()->Ln(10);
            $this->getPdf()->Cell(20,5,"Compra",'B',null,"C");
            $this->getPdf()->Cell(20,5,"Fecha",'B',null,"C");
            $this->getPdf()->Cell(30,5,"Cuotas",'B',null,"C");
            $this->getPdf()->Ln(8);
            foreach ($compras as $compra){
                $this->getPdf()->Cell(20,5,$compra->getId(),null,null,"C");
                $this->getPdf()->Cell(20,5,$compra->getFecha(),null,null,"C");
                $this->getPdf()->Cell(20,5,count($compra->getPagos())."/".$compra->getCuotas(),null,null,"C");
                $this->getPdf()->Ln(5);
            }
            $this->getPdf()->SetFont('Arial','BI',12);
            // Número de página
            $this->getPdf()->SetY(265);
            $this->getPdf()->Cell(0,10,utf8_decode('Página: ').$this->getPdf()->PageNo(),'T',0,'C');
            $this->getPdf()->Output();
        }
    }
    public function c2(){
        if($this->checkUser()){
            $compras=(new Consulta())->cons2(Session::get('log_in')->getId());
            $this->getPdf()->AddPage();
            $this->getPdf()->AliasNbPages();
            $this->getPdf()->SetFont('Arial','B',16);
            $this->getPdf()->Cell(40,10,'Mostrar Compras sin pagar');
            $this->getPdf()->SetFont('Arial','B',12);            
            $this->getPdf()->Ln(20);
            $this->getPdf()->Cell(20,5,"Cliente: ". Session::get('log_in')->getApellido() ." ".Session::get('log_in')->getNombre());
            $this->getPdf()->Ln(10);
            $this->getPdf()->Cell(20,5,"Compra",'B',null,"C");
            $this->getPdf()->Cell(20,5,"Fecha",'B',null,"C");
            $this->getPdf()->Cell(30,5,"Cuotas",'B',null,"C");
            $this->getPdf()->Ln(8);
            foreach ($compras as $compra){
                $this->getPdf()->Cell(20,5,$compra->getId(),null,null,"C");
                $this->getPdf()->Cell(20,5,$compra->getFecha(),null,null,"C");
                $this->getPdf()->Cell(20,5,count($compra->getPagos())."/".$compra->getCuotas(),null,null,"C");
                $this->getPdf()->Ln(5);
            }
            $this->getPdf()->SetFont('Arial','BI',12);
            // Número de página
            $this->getPdf()->SetY(265);
            $this->getPdf()->Cell(0,10,utf8_decode('Página: ').$this->getPdf()->PageNo(),'T',0,'C');
            $this->getPdf()->Output();
        }
    }
    public function c3(){
        if($this->checkUser()){
            $compras=array();            
            if(isset($_GET["d1"]) and isset($_GET["d2"])){
                if($_GET["d1"] > $_GET["d2"]){
                    Session::set('msg', "La fecha de inicio debe ser menor a la fecha de cierre");
                }
                else {
                    $compras = (new Consulta())->cons3($_GET["d1"], $_GET["d2"], Session::get('log_in')->getId());
                    $this->getPdf()->AddPage();
                    $this->getPdf()->AliasNbPages();
                    $this->getPdf()->SetFont('Arial','B',16);
                    $this->getPdf()->Cell(40,10,'Mostrar Compras por fechas');
                    $this->getPdf()->SetFont('Arial','B',12);            
                    $this->getPdf()->Ln(20);
                    $this->getPdf()->Cell(20,5,"Cliente: ". Session::get('log_in')->getApellido() ." ".Session::get('log_in')->getNombre());
                    $this->getPdf()->Ln(10);
                    $this->getPdf()->Cell(20,5,"Compra",'B',null,"C");
                    $this->getPdf()->Cell(20,5,"Fecha",'B',null,"C");
                    $this->getPdf()->Cell(30,5,"Cuotas",'B',null,"C");
                    $this->getPdf()->Ln(8);
                    foreach ($compras as $compra){
                        $this->getPdf()->Cell(20,5,$compra->getId(),null,null,"C");
                        $this->getPdf()->Cell(20,5,$compra->getFecha(),null,null,"C");
                        $this->getPdf()->Cell(20,5,count($compra->getPagos())."/".$compra->getCuotas(),null,null,"C");
                        $this->getPdf()->Ln(5);
                    }
                    $this->getPdf()->SetFont('Arial','BI',12);
                    // Número de página
                    $this->getPdf()->SetY(265);
                    $this->getPdf()->Cell(0,10,utf8_decode('Página: ').$this->getPdf()->PageNo(),'T',0,'C');
                    $this->getPdf()->Output();
                }
            }
        }
    }
    public function c4(){
        if($this->checkUser()){
            $compras=(new Consulta())->cons4(Session::get('log_in')->getId());
            $this->getPdf()->AddPage();
            $this->getPdf()->AliasNbPages();
            $this->getPdf()->SetFont('Arial','B',16);
            $this->getPdf()->Cell(40,10,'Mostrar Mis Compras y Pagos');
            $this->getPdf()->SetFont('Arial','B',12);            
            $this->getPdf()->Ln(20);
            $this->getPdf()->Cell(20,5,"Cliente: ". Session::get('log_in')->getApellido() ." ".Session::get('log_in')->getNombre());
            $this->getPdf()->Ln(8);
            foreach ($compras as $compra){
                $this->getPdf()->Cell(20,5,"Compra: ".$compra->getId()." Fecha: ".$compra->getFecha()." Cuotas: ".count($compra->getPagos())."/".$compra->getCuotas());
                $this->getPdf()->Ln(10);
                $this->getPdf()->Cell(20,5,"Pago",'B',null,"C");
                $this->getPdf()->Cell(30,5,"Fecha de Pago",'B',null,"C");
                $this->getPdf()->Cell(40,5,"Fecha de Venc",'B',null,"C");
                $this->getPdf()->Cell(30,5,"Monto",'B',null,"C");
                $this->getPdf()->Ln(8);
                foreach ($compra->getPagos() as $pago){
                    $this->getPdf()->Cell(20,5,$pago->getId(),null,null,"C");
                    $this->getPdf()->Cell(30,5,$pago->getFecpago(),null,null,"C");
                    $this->getPdf()->Cell(40,5,$pago->getFecvenc(),null,null,"C");
                    $this->getPdf()->Cell(30,5,"$".$pago->getMonto(),null,null,"C");
                    $this->getPdf()->Ln(5);
                }
                $this->getPdf()->Ln(10);
            }
            $this->getPdf()->SetFont('Arial','BI',12);
            // Número de página
            $this->getPdf()->SetY(265);
            $this->getPdf()->Cell(0,10,utf8_decode('Página: ').$this->getPdf()->PageNo(),'T',0,'C');
            $this->getPdf()->Output();
        }
    }
    protected function getMessageRole() {
        return "cliente";
    }
    protected function getTypeRole() {
        return "NORMAL";
    }
}