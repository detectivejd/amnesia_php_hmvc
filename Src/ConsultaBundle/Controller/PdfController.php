<?php
namespace Src\ConsultaBundle\Controller;
class PdfController extends AppController
{
    function __construct() {
        parent::__construct();
    }
    public function c1(){
        echo 'consulta 1';
    }
    public function c2(){
        echo 'consulta 2';
    }
    public function c3(){
        echo 'consulta 3';
    }
    public function c4(){
        echo 'consulta 4';
    }
    protected function getMessageRole() {
        return "cliente";
    }
    protected function getTypeRole() {
        return "NORMAL";
    }
}
