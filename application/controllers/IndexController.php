<?php

class IndexController extends Zend_Controller_Action
{

    public function init() {
        /*
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->_redirect('usuarios/login');
        }
        $usuario = new Zend_Session_Namespace('Usuario');
        $this->view->sid_departamento = $usuario->id_departamento;
        $this->view->snombre= $usuario->nombre;
         
         */
    }

    public function indexAction()
    {
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->_redirect('usuarios/login');
        }
    }


}

