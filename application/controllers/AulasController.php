<?php

class AulasController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
    }

    public function indexAction() {
        $formulario = new Application_Form_Aula();
        $this->view->formulario = $formulario;
    }

    public function borrarAction() {
        if (!$this->_hasParam('id_aula')) {
            $result = array('respuesta' => "Los datos enviados no son válidos",
                'estado' => 1);
            echo Zend_Json::encode($result);
            exit;
        }
        $model = new Application_Model_Aulas();
        $row = $model->getRow($this->_getParam('id_aula'));
        if ($row) {
            try {
                $row->delete();
                $result = array('respuesta' => "El registro se ha borrado", 'estado' => 0);
            } catch (Exception $e) {
                $result = array('respuesta' => $e->getMessage(), 'estado' => 2);
            }
        } else {
            $result = array('respuesta' => "No se ha encontrado el registro para eliminar en la base de datos.", 'estado' => 2);
        }
        echo Zend_Json::encode($result);
        exit;
    }

    public function guardarAction() {
        $form = new Application_Form_Aula();
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->_getAllParams())) {
                $model = new Application_Model_Aulas();
                try {
                    $model->save($form->getValues());
                    $result = array('respuesta' => "El registro se ha guardado", 'estado' => 0);
                } catch (Exception $e) {
                    $result = array('respuesta' => $e->getMessage(), 'estado' => 2);
                }
            } else {
                $result = array('respuesta' => "Los datos enviados no son válidos", 'estado' => 1);
            }
            echo Zend_Json::encode($result);
            exit;
        }
    }

    public function getresponsablesAction() {
        if (!$this->_hasParam('id_aula')) {
            $result = array('respuesta' => "Los datos enviados no son válidos",
                'estado' => 1);
            echo Zend_Json::encode($result);
            exit;
        }
        $model = new Application_Model_Aulas();
        $registros = $model->getResponsables($this->_getParam('id_aula'));
        echo Zend_Json::encode($registros);
        exit;
    }

    public function getprofesoresAction() {
        if (!$this->_hasParam('id_aula')) {
            $result = array('respuesta' => "Los datos enviados no son válidos",
                'estado' => 1);
            echo Zend_Json::encode($result);
            exit;
        }
        $model = new Application_Model_Aulas();
        $registros = $model->getProfesores($this->_getParam('id_aula'));
        echo Zend_Json::encode($registros);
        exit;
    }

}

