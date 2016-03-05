<?php

class Application_Model_Aulas extends Zend_Db_Table_Abstract {

    protected $_name = "aulas";
    protected $_primary = "id_aula";

    public function getAll() {
        return $this->fetchAll(
                        $this->select()
                                ->order('aula ASC')
        );
    }
    
    public function getAllDepartamento($id_departamento) {
        return $this->fetchAll(
                        $this->select()
                             ->where ('departamentos_id_departamento=?', $id_departamento)
                                ->order('aula ASC')
        );
    }
    
    public function getResponsables($id_aula) {
        $select = $this->select()
                ->setIntegrityCheck(false)
                ->from(array('a' => 'aulas'))
                ->join(array('ua' => 'usuarios_aulas'), 'a.id_aula = ua.aulas_id_aula')
                ->join(array('u' => 'usuarios'), 'u.id_usuario = ua.usuarios_id_usuario', array('id_usuario', 'apellido', 'nombre'))
                ->where('id_aula=?', $id_aula)
                ->where('responsable=1')
                ->order('apellido');
        //$rows = $this->fetchAll($select);
        $profesores = array();
        foreach ($this->fetchAll($select) as $profesor) {
            $profesores[$profesor->id_usuario] = $profesor->nombre . " " . $profesor->apellido;
        }
        return ($profesores);
    }
    
        public function getProfesores($id_aula) {
        $select = $this->select()
                ->setIntegrityCheck(false)
                ->from(array('a' => 'aulas'))
                ->join(array('ua' => 'usuarios_aulas'), 'a.id_aula = ua.aulas_id_aula')
                ->join(array('u' => 'usuarios'), 'u.id_usuario = ua.usuarios_id_usuario', array('id_usuario', 'apellido', 'nombre'))
                ->where('id_aula=?', $id_aula)
                ->order('apellido');
        //$rows = $this->fetchAll($select);
        $profesores = array();
        foreach ($this->fetchAll($select) as $profesor) {
            $profesores[$profesor->id_usuario] = $profesor->nombre . " " . $profesor->apellido;
        }
        return ($profesores);
    }
    

    public function getAllNew() {
        return $this->fetchAll(
                        $this->select()
                                ->order('aula ASC')
                                ->limit(5)
        );
    }

    public function save($bind) {
        if (!isset($bind["id_aula"])) {
            $row = $this->createRow();
        } elseif ($bind["id_aula"] == "") {
            unset($bind["id_aula"]);
            $row = $this->createRow();
        } else {
            $row = $this->getRow($bind["id_aula"]);
        }

        $row->setFromArray($bind);
        $this->getAdapter()->beginTransaction();
        try {
            /* guardamos sus datos en la tabla de aulas */
            $row->save();
            /* borramos los registros de las aulas donde ya no entra el usuario */
            if (isset($bind["id_aula"])) {
                /* Construimos el array de datos para la tabla de usuarios_aulas */
                $stmt = $this->getAdapter()->prepare("update usuarios_aulas set responsable=0 where aulas_id_aula=" . $bind["id_aula"]);
                $stmt->execute();
                if ($bind["id_usuarios"] != "") {
                    $condicion = " and  usuarios_id_usuario  in (" . implode(",", $bind["id_usuarios"]) . ")";
                    $stmt = $this->getAdapter()->prepare("update usuarios_aulas set responsable=1 where aulas_id_aula=" . $bind["id_aula"] . $condicion);
                    $stmt->execute();
                }
            }
            /* si todo va bien hacemos el commit */
            $this->getAdapter()->commit();
            return $row;
        } catch (exception $e) {
            $this->getAdapter()->rollBack();
            throw $e;
        }
    }

    public function getRow($id_aula) {

        $id_aula = (int) $id_aula;
        $row = $this->find($id_aula)->current();
        return $row;
    }

}

