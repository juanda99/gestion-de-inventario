<?php

class Myplugins_Layouts extends Zend_Controller_Plugin_Abstract {

    public function preDispatch(Zend_Controller_Request_Abstract $request) {
        /* Para enviar correos no debemos estar autenticados si no, no funciona en background */
        if ($this->getRequest()->getControllerName() == 'correos' && $this->getRequest()->getActionName() == 'enviar') {
            /* no hacemos nada */
        } elseif ($this->getRequest()->getControllerName() == 'usuarios' && $this->getRequest()->getActionName() == 'preactivar') {
            /* no hacemos nada */
        } elseif ($this->getRequest()->getControllerName() == 'usuarios' && $this->getRequest()->getActionName() == 'activar') {
            /* no hacemos nada */
        } elseif ($this->getRequest()->getControllerName() == 'error') {
            
            
            
            /* recuperamos los datos de la sesión */
            /* Datos para la sesión */
            /* si estamos logueados, asignamos las variables de sesion para el layout: */
            $sesion = new Zend_Session_Namespace("Usuario");
            $sesion->setExpirationSeconds(14400);

            $layout = Zend_Layout::getMvcInstance();
            $view = $layout->getView();
            $view->snombre = $sesion->nombre;
            $view->sdepartamentos = $sesion->departamentos;
            $view->sdepartamento = $sesion->departamento;
            $view->sid_departamento = $sesion->id_departamento;
            $view->sperfil=$sesion->id_perfil;
        } else {


            $auth = Zend_Auth::getInstance();
            $sesion = new Zend_Session_Namespace("Usuario");
            $sesion->setExpirationSeconds(14400);
            if (!$auth->hasIdentity() || $sesion->nombre==null) {
                $sesionlogin = new Zend_Session_Namespace('login');
                if (!isset($sesionlogin->url) && $this->getRequest()->getControllerName() == 'usuarios' && $this->getRequest()->getActionName() == 'login') {
                    $sesionlogin->url = '/';
                } elseif ($this->getRequest()->getControllerName() != 'usuarios' && $this->getRequest()->getActionName() != 'login') {
                    $sesionlogin->url = $this->getRequest()->getRequestUri();
                }
                /* ahora redirigimos a /usuarios/login si no está hecho ya: */
                if ($this->getRequest()->getControllerName() == 'usuarios' && $this->getRequest()->getActionName() == 'login') {
                    /* Entonces la cosa va bien, no hacemos nada */
                } else {
                    $this->getResponse()->setRedirect('/usuarios/login/')->sendResponse();
                }
            } else {

                /* Datos para la sesión */
                /* si estamos logueados, asignamos las variables de sesion para el layout: */
               /* $sesion = new Zend_Session_Namespace("Usuario");*/
                $layout = Zend_Layout::getMvcInstance();
                $view = $layout->getView();
                $view->snombre = $sesion->nombre;
                $view->sdepartamentos = $sesion->departamentos;
                $view->sdepartamento = $sesion->departamento;
                $view->sid_departamento = $sesion->id_departamento;
                $view->sperfil=$sesion->id_perfil;

                /* comprobaremos las acls */
                $aclResource = new Application_Model_AclResource();
                //Check if the request is valid and controller an action exists. If not redirects to an error page.
                if (!$aclResource->resourceValid($request)) {
                    $request->setControllerName('error');
                    $request->setActionName('error');
                    return;
                }

                $controller = $request->getControllerName();
                $action = $request->getActionName();
                //Check if the requested resource exists in database. If not it will add it
                if (!$aclResource->resourceExists($controller, $action)) {
                    $aclResource->createResource($controller, $action);
                }
                //Get role_id
                $perfil_id = $sesion->id_perfil;
                $perfil = $sesion->perfil;
                //$perfil = Application_Model_Role::getById($perfil_id);
                //$perfil = $perfil[0]->perfil;
                // setup acl
                $acl = new Zend_Acl();
                // add the role
                $acl->addRole(new Zend_Acl_Role($perfil_id));
                //cargamos los resources
                //Create all the existing resources
                $resources = $aclResource->getAllResources();
                // Add the existing resources to ACL
                foreach ($resources as $resource) {
                    $acl->add(new Zend_Acl_Resource($resource["controller"]));
                }
                if ($perfil_id == 6) {//If role_id=6 "Admin" don't need to create the resources
                    $acl->allow($perfil_id);
                } else {
                    //Create user AllowedResources
                    $userAllowedResources = $aclResource->getCurrentRoleAllowedResources($perfil_id);

                    // Add the user permissions to ACL
                    foreach ($userAllowedResources as $controllerName => $allowedActions) {
                        $arrayAllowedActions = array();
                        foreach ($allowedActions as $allowedAction) {
                            $arrayAllowedActions[] = $allowedAction;
                        }
                        $acl->allow($perfil_id, $controllerName, $arrayAllowedActions);
                    }
                }
                //Save ACL so it can be used later to check current user permissions
                Zend_Registry::set('acl', $acl);
                //Check if user is allowed to acces the url and redirect if needed
                if (!$acl->isAllowed($perfil_id, $controller, $action)) {

                    $this->getResponse()->setRedirect('/error/accessdenied/')->sendResponse();
                    //$request->setControllerName('error');
                    //$request->setActionName('accessdenied');
                    return;
                }
            }
        }
    }

}

?>
