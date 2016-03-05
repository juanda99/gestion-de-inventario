<?php

class IncidenciasController extends Zend_Controller_Action {

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
    }

    public function borrarAction() {
        if (!$this->_hasParam('id_incidencia')) {
            $result = array('respuesta' => "Los datos enviados no son válidos",
                'estado' => 1);
            echo Zend_Json::encode($result);
            exit;
        }
        $model = new Application_Model_Incidencias();
        $row = $model->getRow($this->_getParam('id_incidencia'));
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

    public function editarAction() {
        $formulario = new Application_Form_Incidencia();
        $this->view->formulario = $formulario;

        $sesion = new Zend_Session_Namespace("Usuario");
        $id_usuario = $sesion->id_usuario;
        $this->view->id_usuario = $id_usuario;
    }

    public function editardetAction() {
        $id_incidencia = $this->getParam("id_incidencia");
        if ($id_incidencia == null)
            exit;
        /* obtenemos el responsable de la incidencia en caso de ser nosostros, podremos rellenar formulario,
         * en otro caso, podremos reasignarla  */
        $sesion = new Zend_Session_Namespace("Usuario");
        $id_usuario = $sesion->id_usuario;
        $this->view->id_usuario = $id_usuario;

        $formulario = new Application_Form_Incidenciadet();
        $this->view->formulario = $formulario;

        /* Obtenemos la incidencia original: */
        $incidencias = new Application_Model_Incidencias();
        $incidencia = $incidencias->getRow($id_incidencia);
        $this->view->incidencia = $incidencia;
               
        /* Obtenemos el departamento, que deberá ser de donde sea el cacharro */
        $inventario = new Application_Model_Vinventario();
        $equipo = $inventario->getRow($incidencia->inventario_id_inventario);
        $id_aula = $equipo->aulas_id_aula;
        $this->view->id_aula = $id_aula;
        $this->view->id_inventario = $incidencia->inventario_id_inventario;
        $this->view->equipo = $equipo;

        $id_departamento = $equipo->id_departamento;
        $this->view->id_departamento = $id_departamento;
        
        /*Comprabamos si somos un usuario válido*/
        $aula = new Application_Model_Aulas(); 
        $profesores = $aula->getResponsables($id_aula);
        if (isset($profesores[$id_usuario]) && $profesores[$id_usuario]!=null)
            $this->view->usuario_valido=true;
        else
            $this->view->usuario_valido=false;
        

        /* Obtenemos los detalles de la incidencia */
        $model = new Application_Model_Incidenciasusuarios();
        $incidencias_det = $model->getRows($id_incidencia);
        $this->view->incidencias_det = $incidencias_det;
    }

    public function guardarAction() {
        $form = new Application_Form_Incidencia();
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->_getAllParams())) {
                $model = new Application_Model_Incidencias();
                try {
                    /* la incidencia la origina el usuario de la sesión */
                    $sesion = new Zend_Session_Namespace("Usuario");
                    $url = array('host' => $this->_getApplicationUrl(), 'path' => '/correos/enviar');
                    $model->save($form->getValues(), $sesion->id_usuario, $this->getParam("id_aula"), $url["host"]);
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

    public function guardardetAction() {
        $form = new Application_Form_Incidenciadet();
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->_getAllParams())) {
                $model = new Application_Model_Incidenciasdet();
                try {
                    /* la incidencia la origina el usuario de la sesión */
                    $sesion = new Zend_Session_Namespace("Usuario");
                    $url = array('host' => $this->_getApplicationUrl(), 'path' => '/correos/enviar'); 
                    $model->save($form->getValues(), $sesion->id_usuario, $this->getParam("id_aula"), $this->getParam("id_inventario"), $url["host"]);
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

}

