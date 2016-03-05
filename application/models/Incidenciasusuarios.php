<?php

class Application_Model_Incidenciasusuarios extends Zend_Db_Table_Abstract {

    protected $_name = "vincidencias_usuarios";
    protected $_primary = "id_incidencia_det";

    public function getAll() {
        return $this->fetchAll(
                        $this->select()
                                ->order('id_incidencia_det ASC')
        );
    }

    public function getAllNew() {
        return $this->fetchAll(
                        $this->select()
                                ->order('id_incidencia_det ASC')
                                ->limit(5)
        );
    }
    public function getRows($id_incidencia) {
        return $this->fetchAll(
                        $this->select()
                                ->where("incidencias_id_incidencia=" . $id_incidencia)
                                ->order('id_incidencia_det ASC')
                                
        );
    }

}

