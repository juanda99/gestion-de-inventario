<?php

class Application_Model_Vinventario extends Zend_Db_Table_Abstract {

    protected $_name = "vinventario";
    protected $_primary = "id_inventario";

    public function getRow($id_inventario) {

        $id_inventario = (int) $id_inventario;
        $row = $this->find($id_inventario)->current();
        return $row;
    }


}

