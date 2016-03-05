<?php

class Application_Model_Inventario extends Zend_Db_Table_Abstract {

    protected $_name = "inventario";
    protected $_primary = "id_inventario";

    public function getAll() {
        return $this->fetchAll(
                        $this->select()
                                ->order('modelo ASC')
        );
    }

    public function getAllNew() {
        return $this->fetchAll(
                        $this->select()
                                ->order('modelo ASC')
                                ->limit(5)
        );
    }

    public function save($bind) {
        if ($bind["puesto"] == '')
            $bind["puesto"] = new Zend_Db_Expr('NULL');
        if ($bind["codigo"] == '')
            $bind["codigo"] = new Zend_Db_Expr('NULL');
        if (!isset($bind["id_inventario"])) {
            $row = $this->createRow();
        } elseif ($bind["id_inventario"] == "") {
            $row = $this->createRow();
        } else {
            $row = $this->getRow($bind["id_inventario"]);
        }
        if ($bind["fecha_adquisicion"] != null) {
            $parts = explode('-', $bind["fecha_adquisicion"]);
            $bind["fecha_adquisicion"] = $parts[2] . '-' . $parts[1] . '-' . $parts[0];
        }

        $row->setFromArray($bind);
        return $row->save();
    }

    public function getRow($id_inventario) {

        $id_inventario = (int) $id_inventario;
        $row = $this->find($id_inventario)->current();
        return $row;
    }

    public function getEquiposMantenimiento($id_aula) {
        $select = $this->select()
                ->setIntegrityCheck(false)
                ->from(array('a' => 'aulas'))
                ->join(array('i' => 'inventario'), 'a.id_aula = i.aulas_id_aula')
                ->join(array('t' => 'tipos_materiales'), 't.id_tipo = i.tipos_id_tipo')
                ->where('id_aula=?', $id_aula)
                ->where('mantenimiento=1')
                ->where('estado!=2')
                ->order(array('tipos_id_tipo', 'codigo'));
        //2 es el estado de baja, poniendo baja no funcionaba!!!!
        //$sql = $select->__toString();
        return $this->fetchAll($select);
    }

    public function getCodigo($id_departamento) {
        //$select = $this->select()
         //       ->from(array('i' => 'inventario'), array('codigo' => new Zend_Db_Expr('MAX(CAST(SUBSTRING(codigo, 4, 3)))')))
         //       ->where('departamentos_id_departamento=?', $id_departamento);
        //2 es el estado de baja, poniendo baja no funcionaba!!!!
        //$sql = $select->__toString();
        //$registro = $this->fetchRow($select);
        //$codigo = $registro->codigo;
        //return $codigo + 1;
        $stmt = $this->getAdapter()->prepare("SELECT MAX(CAST(SUBSTRING_INDEX(codigo,'-',-1) as unsigned)) as codigo FROM inventario where departamentos_id_departamento=" . $id_departamento);
        $stmt->execute();
        $row = $stmt->fetch();
        $codigo = $row["codigo"];
        return $codigo + 1;
// return $row;
    }

}
