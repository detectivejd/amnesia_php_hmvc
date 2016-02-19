<?php
    define("APPLICATION_PATH", dirname(__FILE__));
    define("DS", DIRECTORY_SEPARATOR);
    spl_autoload_register(function($clase) {
        try {
            require_once APPLICATION_PATH . DS . str_replace("\\", DS, $clase) . ".php";        
        } 
        catch (Exception $ex) {
            echo $ex->getMessage();
        }
    });
    $controlador = (!empty($_GET['c'])) ? ucwords($_GET['c']) . 'Controller' : "MainController";
    $accion = (!empty($_GET['a'])) ? $_GET['a'] : "index";
    foreach(glob(APPLICATION_PATH . DS . "Src". DS . "*") as $dir) {
        foreach(glob($dir . DS . "Controller" . DS . "*") as $file){
            $c = str_replace($dir . DS . "Controller" . DS, "", $file);
            $c2 = str_replace(".php","",$c);
            if($c2 == $controlador){
                $bundle = str_replace(APPLICATION_PATH . DS . "Src". DS, "", $dir);
                if(method_exists("Src\\". $bundle . "\\Controller\\" . $controlador, $accion)){
                    break 2; 
                }
            }
        }
    }        
    try {
        $controlador = "Src\\". $bundle . "\\Controller\\" . $controlador;
        $controlo = new $controlador();
        $controlo->$accion();
    } catch (Exception $ex) {
        echo $ex->getMessage();
    }