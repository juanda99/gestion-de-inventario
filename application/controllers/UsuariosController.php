<?php

class UsuariosController extends Zend_Controller_Action {

    protected function _getApplicationUrl() {
        return $_SERVER['SERVER_NAME'];
    }

    public function init() {
        /* Initialize action controller here */
    }

    public function indexAction() {
        $formulario = new Application_Form_Usuario();
        $this->view->formulario = $formulario;
    }

    public function borrarAction() {
        if (!$this->_hasParam('id_usuario')) {
            $result = array('respuesta' => "Los datos enviados no son válidos",
                'estado' => 1);
            echo Zend_Json::encode($result);
            exit;
        }
        $model = new Application_Model_Usuarios();
        $row = $model->getRow($this->_getParam('id_usuario'));
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

    public function cambiarestadoAction() {
        if (!$this->_hasParam('id_usuario') || !$this->_hasParam('estado')) {
            $result = array('respuesta' => "Los datos enviados no son válidos",
                'estado' => 1);
            echo Zend_Json::encode($result);
            exit;
        }
        $model = new Application_Model_Usuarios();
        $url = array('host' => $this->_getApplicationUrl(), 'path' => '/correos/enviar');
        try {
            $model->cambiarestado($this->_getParam('id_usuario'), $this->_getParam('estado'), $url["host"]);
            $result = array('respuesta' => "El registro se ha actualizado", 'estado' => 0);
            $parametros = array();
            $this->_helper->Mail($url, $parametros);
        } catch (Exception $e) {
            $result = array('respuesta' => $e->getMessage(), 'estado' => 2);
        }
        echo Zend_Json::encode($result);
        exit;
    }

    public function guardarAction() {
        $form = new Application_Form_Usuario();
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->_getAllParams())) {
                $model = new Application_Model_Usuarios();
                $url = array('host' => $this->_getApplicationUrl(), 'path' => '/correos/enviar');
                try {
                    $model->save($form->getValues(), $url["host"]);
                    $result = array('respuesta' => "El registro se ha guardado", 'estado' => 0);
                    if ($this->getParam("id_usuario") == "") {
                        $parametros = array();
                        $this->_helper->Mail($url, $parametros);
                    }
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

    public function preactivarAction() {
        /* aquí nos encargaremos de generar la key para que un usuario pueda acceder */
        if ($this->_hasParam('email')) {
            $url = array('host' => $this->_getApplicationUrl(), 'path' => '/correos/enviar');
            $usuarios = new Application_Model_Usuarios();
            $parametros = array('email' => $this->getParam("email"));
            try {
                $usuarios->genkey($parametros, $url["host"]);
                /* Si todo ha ido bien, mandamos los correos de rigor */
                $parametros = array();
                $this->_helper->Mail($url, $parametros);
                $this->view->mensaje = "Se te ha enviado un enlace para establecer tu contraseña al correo electrónico que nos has proporcionado.";
            } catch (Exception $e) {
                $this->view->mensaje = "¡Uuuups! Ha ocurrido un error:<br/>" . htmlentities($e);
            }
        }
    }

    public function activarAction() {
        /* Si el usuario manda la nueva contraseña, validamos el key-id_usuario y la cambiamos */
        $formulario = new Application_Form_Activacion();
        $this->view->formulario = $formulario;
        if ($this->hasParam("password")) {
            if ($this->hasParam("id") && $this->hasParam("key")) {
                /* Comprobamos el key para ver si procede... */
                $usuarios = new Application_Model_Usuarios();
                $usuario = $usuarios->getRow($this->getParam("id"));
                if ($usuario->email == $this->getParam("usuario") && $usuario->activacion == $this->getParam("key") && $usuario->estado == 1) {
                    $parametros = array('id_usuario' => $usuario->id_usuario, 'activacion' => new Zend_Db_Expr('NULL'), 'password' => $this->getParam("password"));
                    $usuarios->actualizar($parametros);
                    $sesion = new Zend_Session_Namespace("Usuario");
                    $auth = Zend_Auth::getInstance();
                    $authStorage = $auth->getStorage();
                    $authStorage->write($usuario);
                    $sesion->id_departamento = $usuario->departamentos_id_departamento;
                    $sesion->nombre = $usuario->nombre . " " . $usuario->apellido;
                    $sesion->id_usuario = $usuario->id_usuario;
                    $this->_redirect("/");
                }
                $this->view->id = $this->getParam("id");
                $this->view->key = $this->getParam("key");
                $this->view->mensaje = "Acceso no permitido";
            }
        } else {
            $this->view->id = $this->getParam("id");
            $this->view->key = $this->getParam("key");
        }
    }

    public function loginAction() {
        # Si el usuario está logeado puede ser que haya que cambiar su departamento de trabajo:
        if (Zend_Auth::getInstance()->hasIdentity()) {
            //$this->_redirect('/');
            if ($this->hasParam("departamento"))  {
                 $sesion = new Zend_Session_Namespace("Usuario");
                if ($sesion->id_perfil > 2){ 
                $departamentos = new Application_Model_Departamentos();
                $departamento = $departamentos->getId(trim($this->getParam("departamento")));
                $sesion->id_departamento = $departamento->id_departamento;
                $sesion->departamento = $departamento->departamento;
                $result = array('respuesta' => "Los datos enviados son válidos", 'estado' => 0, 'id_departamento' => $sesion->id_departamento, 'departamento' => $sesion->departamento);
                echo Zend_Json::encode($result);
                exit;
                }
            }
            else{
                $sesion = new Zend_Session_Namespace("Usuario");
                if ($sesion->nombre!=null) {/*exit;*/}
               
            }
            //hay veces que se pierde sesión, Zend_Auth funciona pero no hay sesion, tendríamos que cargarla aquí, por eso el último else, para no hacer un exit.
        }

        $request = $this->getRequest();
        $loginForm = new Application_Form_Login();
        $errorMessage = "";

        if ($request->isPost()) {
            if ($loginForm->isValid($request->getPost())) {
                # get the username and password from the form
                $username = $loginForm->getValue('email');
                $password = $loginForm->getValue('password');
                $authAdapter = new Myclasses_Autenticacion($username, $password);
                # pass to the adapter the submitted username and password
                /*
                  $authAdapter = $this->getAuthAdapter();
                  $authAdapter->setIdentity($username)
                  ->setCredential($password);
                 */

                $auth = Zend_Auth::getInstance();
                $result = $auth->authenticate($authAdapter);
                # is the user a valid one?
                if ($result->isValid()) {
                    # all info about this user from the login table
                    # ommit only the password, we don't need that
                    /* guardamos todos los datos que necesitamos en una sesión: */
                    $sesion = new Zend_Session_Namespace("Usuario");
                    $model = new Application_Model_Usuarios();
                    $usuario = $model->getRowemail($username);
                    $authStorage = $auth->getStorage();
                    $authStorage->write($usuario);
                    $sesion->id_departamento = $usuario->departamentos_id_departamento;
                    $sesion->nombre = $usuario->nombre . " " . $usuario->apellido;
                    $sesion->id_usuario = $usuario->id_usuario;
                    $sesion->id_perfil = $usuario->perfil;
                    /*Que dure un buen rato la sesión, sobre todo por falta de sesión vía ajax, que no se ve claro*/
                    $sesion->setExpirationSeconds(7200);
                    $departamentos = new Application_Model_Departamentos();
                    $departamento = $departamentos->getRow($usuario->departamentos_id_departamento);
                    $sesion->departamento = $departamento->departamento;
                    $sesion->departamentos = array();
                    foreach ($departamentos->getAll() as $departamento) {
                        $sesion->departamentos[] = $departamento->departamento;
                    }
                    $sesionlogin = new Zend_Session_Namespace("login");
                    $url = $sesionlogin->url;
                    $this->_redirect($url);
                } else {
                    $errorMessage = implode("<br/>", $result->getMessages());
                }
            }
        }
        /* En caso de que pida otra contraseña tendremos que poner el enlace */
        $this->view->enlace = $this->_getApplicationUrl() . "/usuarios/preactivar";
        $this->view->errorMessage = $errorMessage;
        $this->view->loginForm = $loginForm;
    }

    public function logoutAction() {
# clear everything - session is cleared also!
        Zend_Auth::getInstance()->clearIdentity();
        Zend_Session::namespaceUnset('login');
        $this->_redirect('/');
    }

    /**
     * Gets the adapter for authentication against a database table
     *
     * @return object
     */
    protected function getAuthAdapter() {
        $dbAdapter = Zend_Db_Table::getDefaultAdapter();
        $authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter);

        $authAdapter->setTableName('usuarios')
                ->setIdentityColumn('email')
                ->setCredentialColumn('password')
                ->setCredentialTreatment('sha1(?) and activacion is NULL and estado=1')
        ;

        return $authAdapter;
    }

}
