<?php

class Application_Model_Reglas extends Zend_Db_Table_Abstract {

    protected $_name = "acl_to_perfiles";
    protected $_primary = "id";

    public function getAll() {
        return $this->fetchAll(
                        $this->select()
                                ->order('id ASC')
        );
    }

    public function getAllNew() {
        return $this->fetchAll(
                        $this->select()
                                ->order('id ASC')
                                ->limit(5)
        );
    }

    public function save($acl_id, $perfil_id) {
        //creamos una nueva fila, ya que cuando modificamos (bloquear regla) lo que se hace realmente es borrar registro

        $row = $this->createRow();
        $bind = array(
            "acl_id" => $acl_id,
            "perfil_id" => $perfil_id,
        );
        $row->setFromArray($bind);
        try {
            $row->save();
            return $row;
        } catch (exception $e) {
            $this->getAdapter()->rollBack();
            throw $e;
        }
    }

    public function getRow($id) {

        $id = (int) $id;
        $row = $this->find($id)->current();
        return $row;
    }

}
