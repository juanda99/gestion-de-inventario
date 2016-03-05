<?php

class Application_Model_Departamentos extends Zend_Db_Table_Abstract {

    protected $_name = "departamentos";
    protected $_primary = "id_departamento";

    public function getAll() {
        return $this->fetchAll(
                        $this->select()
                                ->order('departamento ASC')
        );
    }

    public function getAllNew() {
        return $this->fetchAll(
                        $this->select()
                                ->order('departamento ASC')
                                ->limit(5)
        );
    }

    public function getProfesores($id_departamento) {
        $select = $this->select()
                ->setIntegrityCheck(false)
                ->from(array('d' => 'departamentos'))
                ->join(array('u' => 'usuarios'), 'd.id_departamento = u.departamentos_id_departamento', array('id_usuario', 'apellido', 'nombre'))
                ->where('id_departamento=?', $id_departamento)
                ->where('estado!=0')
                ->order('nombre');
        //$rows = $this->fetchAll($select);
        $profesores = array();
        foreach ($this->fetchAll($select) as $profesor) {
            $profesores[$profesor->id_usuario] = $profesor->nombre . ' ' . $profesor->apellido;
        }
        return ($profesores);
    }
    
        public function getJefedepartamento($id_departamento) {
        $select = $this->select()
                ->setIntegrityCheck(false)
                ->from(array('d' => 'departamentos'))
                ->join(array('u' => 'usuarios'), 'd.id_departamento = u.departamentos_id_departamento', array('id_usuario', 'apellido', 'nombre'))
                ->join(array('p' => 'perfiles'), 'p.id = u.perfil and p.perfil="Jefe de Departamento"')
                ->where('id_departamento=?', $id_departamento)
                ->order('apellido');
        //$rows = $this->fetchAll($select);
        $profesores = array();
        foreach ($this->fetchAll($select) as $profesor) {
            $profesores[$profesor->id_usuario] = $profesor->apellido . ", " . $profesor->nombre;
        }
        return ($profesores);
    }

    public function save($bind) {
        if (!isset($bind["id_departamento"])) {
            $row = $this->createRow();
        } elseif ($bind["id_departamento"] == "") {
            unset($bind["id_departamento"]);
            $row = $this->createRow();
        } else {
            $row = $this->getRow($bind["id_departamento"]);
        }

        $row->setFromArray($bind);
        return $row->save();
    }

    public function getRow($id_departamento) {

        $id_departamento = (int) $id_departamento;
        $row = $this->find($id_departamento)->current();
        return $row;
    }
    
    public function getId($departamento) {
        return $this->fetchRow(
                        $this->select()
                                ->where("departamento=?", $departamento)
        );
    }

}

