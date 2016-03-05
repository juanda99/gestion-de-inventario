<?php

class Application_Model_Mensajes extends Zend_Db_Table_Abstract {

    protected $_name = "mensajes";
    protected $_primary = "id_mensaje";

    public function getAll() {
        return $this->fetchAll(
                        $this->select()
                                ->order('id_mensaje ASC')
        );
    }

    public function getAllNew() {
        return $this->fetchAll(
                        $this->select()
                                ->order('id_mensaje ASC')
                                ->limit(5)
        );
    }

    public function save($bind) {
        $row = $this->createRow();
        $row->setFromArray($bind);
        return $row->save();
    }

    public function getRow($id_mensaje) {

        $id_mensaje = (int) $id_mensaje;
        $row = $this->find($id_mensaje)->current();
        return $row;
    }

}

