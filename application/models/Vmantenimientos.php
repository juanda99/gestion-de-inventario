<?php

class Application_Model_Vmantenimientos extends Zend_Db_Table_Abstract {
    protected $_name = "vmantenimientos";
    protected $_primary = "id_mantenimiento";

   public function getRow($id_mantenimiento) {
        $id_mantenimiento = (int) $id_mantenimiento;
        $row = $this->find($id_mantenimiento)->current();
        return $row;
    }
}

