<?php

class Application_Model_Mantenimientos extends Zend_Db_Table_Abstract {
    protected $_name = "mantenimientos";
    protected $_primary = "id_mantenimiento";

    public function getAll() {
        return $this->fetchAll(
                        $this->select()
                                ->order('fecha_mantenimiento DESC')
        );
    }

    public function getAllNew() {
        return $this->fetchAll(
                        $this->select()
                                ->order('fecha_mantenimiento DESC')
                                ->limit(5)
        );
    }

    public function save($bind, $id_usuario, $url) {
        if ($bind["fecha_mantenimiento"] != null) {
            $parts = explode('-', $bind["fecha_mantenimiento"]);
            $bind["fecha_mantenimiento"] = $parts[2] . '-' . $parts[1] . '-' . $parts[0];
        }
        if ($bind["fecha_solucion"] != null) {
            $parts = explode('-', $bind["fecha_solucion"]);
            $bind["fecha_solucion"] = $parts[2] . '-' . $parts[1] . '-' . $parts[0];
        }
        if (!isset($bind["id_mantenimiento"]) || $bind["id_mantenimiento"] == "") {
            /* INSERTAMOS UN MANTENIMIENTO NUEVO Y TODOS LOS EQUIPOS QUE DEPENDEN DE EL */
            $row = $this->createRow();
            /* el usuario asignado lo hemos recogido y el usuario que origina la incidencia es el de la sesión, parámetro $id_usuario */
            $bind["detectadapor"] = $id_usuario;
            $row->setFromArray($bind);

            $this->getAdapter()->beginTransaction();
            try {
                /* guardamos  el detalle de la incidencia */
                $row->save();
                $id_mantenimiento = $row->id_mantenimiento;
                /* Se envía un correo electrónico a todos los usuarios QUE ENTRAN a el aula: */
                if ($bind["aulas_id_aula"] != null) {                   
                    $model = new Application_Model_Usuarios();
                    //$usuarios = $model->getResponsables($bind["aulas_id_aula"]);
                    $usuarios = $model->getProfesores($bind["aulas_id_aula"]);
                    $asignado = $model->getRow($bind["responsable"]);
                    $aulas = new Application_Model_Aulas();
                    $aula = $aulas->getRow($bind["aulas_id_aula"]);
                    $asunto = 'Mantenimiento aula ' . $aula->aula;
                    /* Debemos obtener la lista de equipos del aula para el mantenimiento */
                    $model_equipos = new Application_Model_Inventario();
                    $equipos = $model_equipos->getEquiposMantenimiento($bind["aulas_id_aula"]);

                    /* La lista de equipos para el mensaje y para actualizar el detalle del mantenimiento */
                    $lista = "<p>La <b>lista de equipos</b> que tiene el aula:</p><ul>";
                    foreach ($equipos as $equipo) {
                        $observacion = "";
                        if ($equipo->estado == "Averiado")
                            $observacion = "Está averiado. <br/>";
                        $lista = $lista . "<li>Modelo: " . $equipo->modelo . ""
                                . "</li><li style='list-style:none'>Código: " . $equipo->codigo
                                . "</li><li style='list-style:none'>Puesto: " . $equipo->puesto
                                . "</li><li style='list-style:none'>Observaciones: " . $observacion . $equipo->obs . "</li>";
                        /* actualizamos el detalle del mantenimiento */
                        $stmt = $this->getAdapter()->prepare('insert into mantenimientos_det (inventario_id_inventario, mantenimientos_id_mantenimiento) VALUES (? ,? )');
                        $stmt->execute(array($equipo->id_inventario, $id_mantenimiento));
                    }
                    $lista = $lista . "</ul>";

                    foreach ($usuarios as $usuario) {
                        /* El correo llega a todos los responsables del aula, así que marcamos quien está asignado */
                        $mensaje = "<p>Hola " . $usuario->nombre . " " . $usuario->apellido . ",</p><p>Hay que realizar un mantenimiento en el aula <b>" . $aula->aula . "</b></p>"
                                . "<p><b>Detalles del mantenimiento:</b></p>"
                                . "<p><ul><li>Fecha: " . $bind["fecha_mantenimiento"]
                                . "</li><li>Causa: " . $bind["causa"]
                                . "</li><li>Responsable: " . $asignado->nombre . " " . $asignado->apellido
                                . "</li><li>Descripcion: " . $bind["descripcion"] . "</li></ul></p>"
                                . "<p><a href='http://" . $url . "/mantenimientos/editardet/id_mantenimiento/" . $id_mantenimiento . "'>Consulta desde aquí el estado del mantenimiento.</a>";
                        $mensaje = $mensaje . $lista;
                        $stmt = $this->getAdapter()->prepare('insert into mensajes (usuarios_id_usuario,asunto, mensaje, destino) VALUES (?, ?, ?, ?)');
                        $stmt->execute(array($usuario->id_usuario, $asunto, $mensaje, $usuario->email));
                    }
                }

                /* si todo va bien hacemos el commit */
                $this->getAdapter()->commit();

                return $row;
            } catch (exception $e) {
                $this->getAdapter()->rollBack();
                throw $e;
            }
        } else {
            /* ACTUALIZAMOS LOS DETALLES DEL MANTENIMIENTO, EN PRINCIPIO PARA MARCARLO COMO SOLUCIONADO */
            $row = $this->getRow($bind["id_mantenimiento"]);
            $row->setFromArray($bind);
            $row->save();
        }
    }

    public function getRow($id_incidencia) {

        $id_incidencia = (int) $id_incidencia;
        $row = $this->find($id_incidencia)->current();
        return $row;
    }
    

}

