<?php

class Application_Model_Incidenciasfungibles extends Zend_Db_Table_Abstract {

    protected $_name = "incidenciasfungibles";
    protected $_primary = "id_incidencia";

    public function getAll() {
        return $this->fetchAll(
                        $this->select()
                                ->order('fecha_incidencia DESC')
        );
    }

    public function getAllNew() {
        return $this->fetchAll(
                        $this->select()
                                ->order('fecha_incidencia DESC')
                                ->limit(5)
        );
    }

    public function save($bind, $id_usuario, $url) {
        if ($bind["fecha_incidencia"] != null) {
            $parts = explode('-', $bind["fecha_incidencia"]);
            $bind["fecha_incidencia"] = $parts[2] . '-' . $parts[1] . '-' . $parts[0];
            $fecha = $parts[0] . '-' . $parts[1] . '-' . $parts[2];
        }
        if (!isset($bind["id_incidencia"])) {
            $row = $this->createRow();
        } elseif ($bind["id_incidencia"] == "") {
            $row = $this->createRow();
        } else {
            $row = $this->getRow($bind["id_incidencia"]);
        }
        /* el usuario asignado lo hemos recogido y el usuario que origina la incidencia es el de la sesión, parámetro $id_usuario */
        $usuario_asignado = $bind["responsable"];
        $bind["detectadapor"] = $id_usuario;
        $row->setFromArray($bind);


        $this->getAdapter()->beginTransaction();
        try {
            /* guardamos  el detalle de la incidencia */
            $row->save();
            $id_incidencia = $row->id_incidencia;
            /* Se envía un correo electrónico a todos los usuarios que van a este aula: */
                $model = new Application_Model_Usuarios();
                $usuarios = $model->getProfesores($bind["aulas_id_aula"]);
                $aulas = new Application_Model_Aulas();
                $aula = $aulas->getRow($bind["aulas_id_aula"]);
                $asunto = 'Información incidencia aula ' . $aula->aula;
                foreach ($usuarios as $usuario) {
                    /* El usuario asignado por la incidencia recibe un correo aparte */
                    if ($usuario->id_usuario == $usuario_asignado)
                        continue;
                    $mensaje = "<p>Hola " . $usuario->nombre . " " . $usuario->apellido . ",</p><p>Se ha producido una incidencia en el aula <b>" . $aula->aula . "</b></p>"
                            . "<p><b>Detalles de la incidencia</b></p>"
                            . "<ul><li>Fecha: " . $fecha
                            . "</li><li>Causa: " . $bind["causa"]
                            . "</li><li>Descripcion: " . $bind["descripcion"] . "</li></ul>"
                            . "<p><a href='http://" . $url . "/incidenciasfungibles/editardet/id_incidencia/" . $id_incidencia . "'>Consulta desde aquí el estado de la incidencia.</a>";
                    $stmt = $this->getAdapter()->prepare('insert into mensajes (usuarios_id_usuario,asunto, mensaje, destino) VALUES (?, ?, ?, ?)');
                    $stmt->execute(array($usuario->id_usuario, $asunto, $mensaje, $usuario->email));
                }

            /* creamos un registro con la asignación */
            $users = new Application_Model_Usuarios();
            $usuario = $users->getRow($id_usuario);
            $nombre = $usuario->nombre . " " . $usuario->apellido;

            $stmt = $this->getAdapter()->prepare('insert into incidenciasfungibles_det (incidencias_id_incidencia, fecha, causa, descripcion_detalle, usuarios_id_usuario, tipo) VALUES (? ,? ,?, ?, ?, ?)');
            $stmt->execute(array($id_incidencia, $bind["fecha_incidencia"], $bind["causa"], $bind["descripcion"], $id_usuario, 'Apertura'));
            if ($bind["estadoinc"] == 'Cerrada') {
                $stmt = $this->getAdapter()->prepare('insert into incidenciasfungibles_det (incidencias_id_incidencia, fecha, causa, descripcion_detalle, usuarios_id_usuario, tipo) VALUES (? ,? ,?, ?, ?, ?)');
                $stmt->execute(array($id_incidencia, $bind["fecha_incidencia"], "Incidencia cerrada en su apertura", "Incidencia asignada por $nombre", $usuario_asignado, 'Asignación'));

                $stmt = $this->getAdapter()->prepare('insert into incidenciasfungibles_det (incidencias_id_incidencia, fecha,causa, descripcion_detalle, usuarios_id_usuario, tipo) VALUES (? ,? ,?, ?, ?, ?)');
                $stmt->execute(array($id_incidencia, $bind["fecha_incidencia"], $bind["solucion"], $bind["solucion_det"], $usuario_asignado, 'Cierre'));
            } else {
                $stmt = $this->getAdapter()->prepare('insert into incidenciasfungibles_det (incidencias_id_incidencia, fecha, causa, descripcion_detalle, usuarios_id_usuario, tipo) VALUES (? ,? ,?, ?, ?, ?)');
                $stmt->execute(array($id_incidencia, $bind["fecha_incidencia"], "Incidencia pendiente de resolución", "Incidencia asignada por $nombre", $usuario_asignado, 'Asignación'));
                /* Se envía un correo electrónico a la persona asignada para la incidencia */
                $asunto = 'Asignación incidencia aula ' . $aula->aula;
                $usuario = $model->getRow($usuario_asignado);
                $mensaje = "<p>Hola " . $usuario->nombre . " " . $usuario->apellido . ",</p><p>Tienes que resolver una incidencia en el aula <b>" . $aula->aula . ".</b> Puedes consultar desde <a href='http://" . $url . "/incidenciasfungibles/editardet/id_incidencia/" . $id_incidencia . "'>aquí</a> el estado de la incidencia.</p>"
                        . "<p><b>Detalles de la incidencia</b></p>"
                        . "<ul><li>Fecha: " . $fecha
                        . "</li><li>Causa: " . $bind["causa"]
                        . "</li><li>Descripcion: " . $bind["descripcion"] . "</li></ul>";

                $stmt = $this->getAdapter()->prepare('insert into mensajes (usuarios_id_usuario,asunto, mensaje, destino) VALUES (?, ?, ?, ?)');
                $stmt->execute(array($usuario->id_usuario, $asunto, $mensaje, $usuario->email));
            }
            /* si todo va bien hacemos el commit */
            $this->getAdapter()->commit();
            return $row;
        } catch (exception $e) {
            $this->getAdapter()->rollBack();
            throw $e;
        }
    }

    public function getRow($id_incidencia) {

        $id_incidencia = (int) $id_incidencia;
        $row = $this->find($id_incidencia)->current();
        return $row;
    }

}
