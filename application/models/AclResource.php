<?php

class Application_Model_AclResource extends Zend_Db_Table_Abstract {

    protected $_name = "acl";
    protected $_primary = "id";

    public function getAll() {
        return $this->fetchAll(
                        $this->select()
                                ->order('id ASC')
        );
    }

    public function getRow($id) {

        $id = (int) $id;
        $row = $this->find($id)->current();
        return $row;
    }

    public function resourceExists($controller = null, $action = null) {
        if (!$controller || !$action)
            throw new Exception("Error resourceExists(), the controller/action is empty");

        $select = $this->select()
                ->where('controller=?', $controller)
                ->where('action=?', $action);
        $result = $this->fetchAll($select);
        if (count($result)) {
            return true;
        }
        return false;
    }

    public static function resourceValid($request) {
        
        // Check if controller exists and is valid
        $dispatcher = Zend_Controller_Front::getInstance()->getDispatcher();
        if (!$dispatcher->isDispatchable($request)) {
            return false;
        }
        // Check if action exist and is valid
        $front = Zend_Controller_Front::getInstance();
        $dispatcher = $front->getDispatcher();
        $controllerClass = $dispatcher->getControllerClass($request);
        $controllerclassName = $dispatcher->loadClass($controllerClass);
        $actionName = $dispatcher->getActionMethod($request);
        $controllerObject = new ReflectionClass($controllerclassName);
        if (!$controllerObject->hasMethod($actionName)) {
            return false;
        }
        return true;
         
         
    }

    public function createResource($controller = null, $action = null) {
        if (!$controller || !$action)
            throw new Exception("Error resourceExists(), the controller/action is empty");
        $data = array('controller' => $controller, 'action' => $action);
        $row = $this->createRow();
        $row->setFromArray($data);
        return $row->save();
    }

    /* PENDIENTE SOLO ESTÁ!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! */

    public function getCurrentRoleAllowedResources($perfil_id = null) {
        if (!$perfil_id)
            throw new Exception("Error getCurrentUserPermissions(), the perfil_id is empty");

        $select = $this->select()
                ->setIntegrityCheck(false)
                ->from(array('a' => 'acl'), array("controller", "action"))
                ->join(array('acl_perfiles' => 'acl_to_perfiles'), 'a.id = acl_perfiles.acl_id')
                  ->where('perfil_id=?', $perfil_id)
                ->order('a.controller');
        $out = $this->fetchAll($select);
        $controller = '';
        $resources = array();
        foreach ($out as $value) {
            if ($value['controller'] != $controller) {
                $controller = $value['controller'];
            }
            $resources[$controller][] = $value['action'];
        }
        return $resources;
    }

    public function getAllResources() {
        //$sql = 'SELECT controller FROM acl GROUP BY controller';
        $select = $this->select()
                ->group('controller');

        return $this->fetchAll($select);
    }

}

