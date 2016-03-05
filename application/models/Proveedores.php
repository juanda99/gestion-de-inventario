<?php

class Application_Model_Proveedores extends Zend_Db_Table_Abstract{

    protected $_name = "proveedores";
    protected $_primary = "id_proveedor";

    public function getAll() {
        return $this->fetchAll(
                        $this->select()
                                ->order('proveedor ASC')
        );
    }

    public function getAllNew() {
        return $this->fetchAll(
                        $this->select()
                                ->order('proveedor ASC')
                                ->limit(5)
        );
    }

    public function save($bind) {
        if (!isset($bind["id_proveedor"])) {
            $row = $this->createRow();
        }
        elseif ($bind["id_proveedor"]==""){
            unset ($bind["id_proveedor"]);
            $row = $this->createRow();
        }
       
        else {
            $row = $this->getRow($bind["id_proveedor"]);
        }

        $row->setFromArray($bind);
        $this->getAdapter()->beginTransaction();
        try {
            /* guardamos sus datos en la tabla de proveedores */
            $row->save();
            /* guardamos los departamentos asociados al proveedor */
            $id_proveedor = $row->id_proveedor;


                $stmt = $this->getAdapter()->prepare("delete from proveedores_departamento where proveedores_id_proveedor=" . $id_proveedor );
                $stmt->execute();

            $stmt = $this->getAdapter()->prepare('insert into proveedores_departamento (departamentos_id_departamento, proveedores_id_proveedor) VALUES (?, ?)');
            if ($bind["id_departamento"] != "") {
                foreach ($bind["id_departamento"] as $iddepartamento) {
                    $stmt->execute(array($iddepartamento, $id_proveedor));
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

    public function getRow($id_proveedor) {

        $id_proveedor = (int) $id_proveedor;
        $row = $this->find($id_proveedor)->current();
        return $row;
    }

}

