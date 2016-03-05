<?php

class Application_Model_Materiales extends Zend_Db_Table_Abstract {

    protected $_name = "tipos_materiales";
    protected $_primary = "id_tipo";

    public function getAll() {
        return $this->fetchAll(
                        $this->select()
                                ->order('tipo ASC')
        );
    }

    public function getAllNew() {
        return $this->fetchAll(
                        $this->select()
                                ->order('tipo ASC')
                                ->limit(5)
        );
    }

    public function save($bind) {
        if (!isset($bind["id_tipo"])) {
            $row = $this->createRow();
        } elseif ($bind["id_tipo"] == "") {
            unset($bind["id_tipo"]);
            $row = $this->createRow();
        } else {
            $row = $this->getRow($bind["id_tipo"]);
        }

        $row->setFromArray($bind);
        return $row->save();
    }

    public function getRow($id_tipo) {

        $id_tipo = (int) $id_tipo;
        $row = $this->find($id_tipo)->current();
        return $row;
    }

}
