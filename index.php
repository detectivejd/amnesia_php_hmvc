<?php
    define("APPLICATION_PATH", dirname(__FILE__));
    define("DS", DIRECTORY_SEPARATOR);
    spl_autoload_register(function($clase) {
        try {
            $file = APPLICATION_PATH . DS . str_replace("\\", DS, $clase) . ".php";
            require_once $file;        
        } 
        catch (Exception $ex) {
            echo $ex->getMessage();
        }
    });
    $bundle = (!empty($_GET['b'])) ? ucwords($_GET['b']) . 'Bundle' : "FrontendBundle";
    $controlador = (!empty($_GET['c'])) ? ucwords($_GET['c']) . 'Controller' : "MainController";
    $accion = (!empty($_GET['a'])) ? $_GET['a'] : "index";
    try {
        $controlador = "Src\\". $bundle . "\\Controller\\" . $controlador;
        $controlo = new $controlador();
        $controlo->$accion();
    } catch (Exception $ex) {
        echo $ex->getMessage();
    }