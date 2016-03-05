<?php

class InventarioController extends Zend_Controller_Action {

    protected function _getApplicationUrl() {
        return $_SERVER['SERVER_NAME'];
    }

    public function init() {
        /* Initialize action controller here */
    }

    public function indexAction() {
        $formulario = new Application_Form_Inventario();
        $this->view->formulario = $formulario;
    }

    public function historialAction() {
        if (!$this->_hasParam('id_inventario')) {
            exit;
        }
        $model = new Application_Model_Vinventario();
        $row = $model->getRow($this->_getParam('id_inventario'));
        if ($row == null) {
            exit;
        }
        $formulario = new Application_Form_Inventario();
        $this->view->formulario = $formulario;
        $this->view->equipo = $row; /* solo lo utilizamos para obtener el id_inventario */
        /* Mostramos los datos en el formulario */
        $formulario->populate($row->toArray());
        if ($row->fecha_adquisicion != null && $row->fecha_adquisicion != '0000-00-00') {
            $parts = explode('-', $row->fecha_adquisicion);
            $fecha = $parts[2] . '-' . $parts[1] . '-' . $parts[0];
            $formulario->fecha_adquisicion->setValue($fecha);
        }
        /*el populate de antes, no recoge este valor!*/
        $formulario->proveedores_id_proveedor->setVAlue($row->id_proveedor);
    }

    public function borrarAction() {
        if (!$this->_hasParam('id_inventario')) {
            $result = array('respuesta' => "Los datos enviados no son válidos",
                'estado' => 1);
            echo Zend_Json::encode($result);
            exit;
        }
        $model = new Application_Model_Inventario();
        $row = $model->getRow($this->_getParam('id_inventario'));
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
        $form = new Application_Form_Inventario();
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->_getAllParams())) {
                $model = new Application_Model_Inventario();
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

    public function obtenercodigoAction() {
        if (!$this->_hasParam('id_departamento')) {
            $result = array('respuesta' => "Los datos enviados no son válidos",
                'estado' => 1);
            echo Zend_Json::encode($result);
            exit;
        }
        $model = new Application_Model_Inventario();
        $codigo = $model->getCodigo($this->_getParam('id_departamento'));
        if ($codigo == null) {
            $result = array('respuesta' => "El código obtenido es NULL :-(", 'estado' => 2);
            echo Zend_Json::encode($result);
            exit;
        }
        $departamentos = new Application_Model_Departamentos();
        $departamento = $departamentos->getRow($this->_getParam('id_departamento'));
        if ($departamento["codigo"] != "") {
            $codigo = $departamento["codigo"] . "-" . $codigo;
        }
        $result = array('respuesta' => $codigo, 'estado' => 0);
        echo Zend_Json::encode($result);
        exit;
    }

}
