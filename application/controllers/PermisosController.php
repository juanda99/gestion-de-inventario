<?php

class PermisosController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
    }

    public function indexAction() {
        
    }

    public function guardarAction() {
        if (!$this->_hasParam('acl_id') || !$this->_hasParam('perfil_id')) {
            $result = array('respuesta' => "Los datos enviados no son válidos",
                'estado' => 1);
            echo Zend_Json::encode($result);
            exit;
        }
        $model = new Application_Model_Reglas();
        try {
            $model->save($this->_getParam('acl_id'), $this->_getParam('perfil_id'));
            $result = array('respuesta' => "El registro se ha guardado", 'estado' => 0);
        } catch (Exception $e) {
            $result = array('respuesta' => $e->getMessage(), 'estado' => 2);
        }
        echo Zend_Json::encode($result);
        exit;
    }

    public function borrarAction() {
        if (!$this->_hasParam('regla_id')) {
            $result = array('respuesta' => "Los datos enviados no son válidos",
                'estado' => 1);
            echo Zend_Json::encode($result);
            exit;
        }
        $model = new Application_Model_Reglas();
        $row = $model->getRow($this->_getParam('regla_id'));
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

}
