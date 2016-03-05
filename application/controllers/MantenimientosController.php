<?php

class MantenimientosController extends Zend_Controller_Action {

    protected function _getApplicationUrl() {
        return $_SERVER['SERVER_NAME'];
    }

    public function init() {
        /* Initialize action controller here */
    }

    public function indexAction() {
        $this->view->mostrar_mensaje = false;
        if ($this->_hasParam('insertada')) {
            $this->view->mostrar_mensaje = true;
        }
        $sesion = new Zend_Session_Namespace("Usuario");
        $id_usuario = $sesion->id_usuario;
        $this->view->id_usuario = $id_usuario;
    }

    public function borrarAction() {
        if (!$this->_hasParam('id_mantenimiento')) {
            $result = array('respuesta' => "Los datos enviados no son válidos",
                'estado' => 1);
            echo Zend_Json::encode($result);
            exit;
        }
        $model = new Application_Model_Mantenimientos();
        $row = $model->getRow($this->_getParam('id_mantenimiento'));
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
        $form = new Application_Form_Mantenimiento();
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->_getAllParams())) {
                $model = new Application_Model_Mantenimientos();
                try {
                    /* la incidencia la origina el usuario de la sesión */
                    $sesion = new Zend_Session_Namespace("Usuario");
                    $url = array('host' => $this->_getApplicationUrl(), 'path' => '/correos/enviar');
                    $model->save($form->getValues(), $sesion->id_usuario, $url["host"]);
                    $result = array('respuesta' => "El registro se ha guardado", 'estado' => 0);
                    /* envíamos los correos electrónicos */
                    $parametros = array();
                    $this->_helper->Mail($url, $parametros);
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

    public function editarAction() {
        $formulario = new Application_Form_Mantenimiento();
        $this->view->formulario = $formulario;

        $sesion = new Zend_Session_Namespace("Usuario");
        $id_usuario = $sesion->id_usuario;
        $this->view->id_usuario = $id_usuario;
    }

    public function editardetAction() {
        $id_mantenimiento = $this->getParam("id_mantenimiento");
        if ($id_mantenimiento == null) {
            exit;
        }

        $formulario = new Application_Form_Mantenimiento();
        /* Obtenemos los datos del mantenimiento: */
        $mantenimientos = new Application_Model_Vmantenimientos();
        $mantenimiento = $mantenimientos->getRow($id_mantenimiento);
        /* inicializamos los valores: */
        $formulario->id_mantenimiento->setValue($id_mantenimiento);
        $formulario->aulas_id_aula->setValue($mantenimiento->aulas_id_aula);
        $formulario->causa->setValue($mantenimiento->causa);
        $formulario->descripcion->setValue($mantenimiento->descripcion);
        if ($mantenimiento->fecha_mantenimiento != null&&$mantenimiento->fecha_mantenimiento !='0000-00-00' ) {
            $parts = explode('-', $mantenimiento->fecha_mantenimiento);
            $fecha = $parts[2] . '-' . $parts[1] . '-' . $parts[0];
            $formulario->fecha_mantenimiento->setValue($fecha);
        }
        if ($mantenimiento->fecha_solucion != null && $mantenimiento->fecha_solucion !='0000-00-00') {
            $parts = explode('-', $mantenimiento->fecha_solucion);
            $fecha = $parts[2] . '-' . $parts[1] . '-' . $parts[0];
            $formulario->fecha_solucion->setValue($fecha);
        }
        $formulario->prioridad->setValue($mantenimiento->prioridad);
        $formulario->responsable->setValue($mantenimiento->responsable);
        $formulario->detectadapor->setValue($mantenimiento->detectadapor);
        $this->view->formulario = $formulario;
    }

}
