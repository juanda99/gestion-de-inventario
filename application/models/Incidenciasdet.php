<?php

class Application_Model_Incidenciasdet extends Zend_Db_Table_Abstract {

    protected $_name = "incidencias_det";
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

    public function save($bind, $id_usuario, $id_aula, $id_inventario, $url) {
        if ($bind["fecha"] != null) {
            $parts = explode('-', $bind["fecha"]);
            $bind["fecha"] = $parts[2] . '-' . $parts[1] . '-' . $parts[0];
            $fecha = $parts[0] . '-' . $parts[1] . '-' . $parts[2];
        }

        /* seleccionamos el aula y el equipo, nos hará falta para el envío de correos */
        $aulas = new Application_Model_Aulas();
        $aula = $aulas->getRow($id_aula);
        $equipos = new Application_Model_Inventario();
        $equipo = $equipos->getRow($id_inventario);

        /* el usuario asignado lo hemos recogido y el usuario que origina la incidencia es el de la sesión, parámetro $id_usuario */

        if ($bind["tipo"] == "Cierre") {

            $bind["usuarios_id_usuario"] = $id_usuario;
            /* actualizamos la cabecera de la incidencia y el estado del equipo si procede */
            $this->getAdapter()->beginTransaction();
            try {
                /* insertaremos un nuevo registro por la incidencia solucionada */
                /* generamos el registro de la nueva incidencia */
                $row = $this->createRow();
                $row->setFromArray($bind);
                $row->save();
                
                /* Actualizamos también el resumen de la incidencia */
                $stmt = $this->getAdapter()->prepare("update incidencias set estadoinc='Cerrada', fecha_solucion=? where id_incidencia=?");
                $stmt->execute(array($bind["fecha"], $bind["incidencias_id_incidencia"]));


                /* Actualizamos el estado del equipo en el inventario */
                $stmt = $this->getAdapter()->prepare('update inventario set estado = ? where id_inventario=?');
                $stmt->execute(array($bind["estadomat"], $id_inventario));



                /* Si la incidencia se da por cerrada, será necesario mandar un correo a los usuarios del aula */
                /* Se envía un correo electrónico a todos los usuarios que van a este aula: */
                $model = new Application_Model_Usuarios();
                $usuarios = $model->getProfesores($id_aula);
                $asunto = 'Cierre Incidencia aula ' . $aula->aula;
                //$usuario_asignado=$bind["usuarios_id_usuario"];
                $usuario_asignado = $model->getRow($bind["usuarios_id_usuario"]);
                foreach ($usuarios as $usuario) {
                    /* El usuario asignado por la incidencia recibe un correo aparte */
                    //if ($usuario->id_usuario == $usuario_asignado)
                    //    continue;
                    $mensaje = "<p>Hola " . $usuario->nombre . " " . $usuario->apellido . ",</p><p>Se ha cerrado una incidencia en el aula <b>" . $aula->aula . "</b></p><p><b>Detalles del equipo:</b></p>"
                            . "<ul><li>Modelo: " . $equipo->modelo
                            . "</li><li>Código: " . $equipo->codigo
                            . "</li><li>Puesto: " . $equipo->puesto
                            . "</li><li>Observaciones: " . $equipo->obs . "</li></ul>"
                            . "<p><b>Detalles de la incidencia</b></p>"
                            . "<ul><li>Fecha: " . $fecha
                            . "</li><li>Solucionada por: " . $usuario_asignado->nombre . " " . $usuario_asignado->apellido
                            . "</li><li>Solución: " . $bind["causa"]
                            . "</li><li>Descripcion: " . $bind["descripcion_detalle"] . "</li></ul>"
                            . "<p><a href='http://" . $url . "/incidencias/editardet/id_incidencia/" . $bind["incidencias_id_incidencia"] . "'>Consulta desde aquí el estado de la incidencia.</a>";
                    $stmt = $this->getAdapter()->prepare('insert into mensajes (usuarios_id_usuario,asunto, mensaje, destino) VALUES (?, ?, ?, ?)');
                    $stmt->execute(array($usuario->id_usuario, $asunto, $mensaje, $usuario->email));
                }
                /* si todo va bien hacemos el commit */
                $this->getAdapter()->commit();
            } catch (exception $e) {
                $this->getAdapter()->rollBack();
                throw $e;
            }
        }
        elseif  ($bind["tipo"] == "Abierta en Centro"||$bind["tipo"] == "Abierta en Proveedor") {
            $bind["usuarios_id_usuario"] = $id_usuario;
            /* actualizamos la cabecera de la incidencia y el estado del equipo si procede */
            $this->getAdapter()->beginTransaction();
            try {
     
                /*Actualizamos la incidencia a abierta en lo que corresponda*/
                $stmt = $this->getAdapter()->prepare("update incidencias set estadoinc=? where id_incidencia=?");
                $stmt->execute(array($bind["tipo"], $bind["incidencias_id_incidencia"]));
                
               /* insertaremos un nuevo registro por la incidencia solucionada */
                /* generamos el registro de la nueva incidencia */
                /*Cambiamos el tipo a uno genérico de actualización*/
                $bind["tipo"]="Actualización";
                $row = $this->createRow();
                $row->setFromArray($bind);
                $row->save();  
                
                /* Actualizamos el estado del equipo en el inventario */
                $stmt = $this->getAdapter()->prepare('update inventario set estado = ? where id_inventario=?');
                $stmt->execute(array($bind["estadomat"], $id_inventario));
                $this->getAdapter()->commit();
            } catch (exception $e) {
                $this->getAdapter()->rollBack();
                throw $e;
            }
        }
        elseif  ($bind["tipo"] == "Cambioestadoequipo") {
            
            $bind["usuarios_id_usuario"] = $id_usuario;
            /* actualizamos la cabecera de la incidencia y el estado del equipo si procede */
            $this->getAdapter()->beginTransaction();
            try {
                /* insertaremos un nuevo registro por la incidencia solucionada */
                /* generamos el registro de la nueva incidencia */
                $row = $this->createRow();
                $row->setFromArray($bind);
                $row->save();

                /* Actualizamos el estado del equipo en el inventario */
                $stmt = $this->getAdapter()->prepare('update inventario set estado = ? where id_inventario=?');
                $stmt->execute(array($bind["estadomat"], $id_inventario));
                /* si todo va bien hacemos el commit */
                $this->getAdapter()->commit();
            } catch (exception $e) {
                $this->getAdapter()->rollBack();
                throw $e;
            }
        }
        else{
            /*reasignación de la incidencia*/
            /* actualizamos la cabecera de la incidencia y el estado del equipo si procede */
            $this->getAdapter()->beginTransaction();
            try {
                /* generamos el registro de la nueva incidencia */
                $row = $this->createRow();
                $row->setFromArray($bind);
                $row->save();
                
                
                /* Actualizamos la cabecera de la incidencia con la nueva persona asignada */
                $stmt = $this->getAdapter()->prepare("update incidencias set responsable=? where id_incidencia=?");
                $stmt->execute(array($bind["usuarios_id_usuario"], $bind["incidencias_id_incidencia"]));
                
                /* Actualizamos el estado del equipo en el inventario */
                $stmt = $this->getAdapter()->prepare('update inventario set estado = ? where id_inventario=?');
                $stmt->execute(array($bind["estadomat"], $id_inventario));


                /* Debemos mandar un correo al usuario al que se ha reasignado la incidencia: */
                $usuarios = new Application_Model_Usuarios();
                $usuario = $usuarios->getRow($bind["usuarios_id_usuario"]);
                $asunto = 'Asignación incidencia aula ' . $aula->aula;
                $mensaje = "<p>Hola " . $usuario->nombre . " " . $usuario->apellido . ",</p><p>Tienes que resolver una incidencia en el aula <b>" . $aula->aula . ".</b> Puedes consultar desde <a href='http://" . $url . "/incidencias/editardet/id_incidencia/" . $bind["incidencias_id_incidencia"] . "'>aquí</a> el estado de la incidencia.</p><p><b>Detalles del equipo:</b></p>"
                        . "<ul><li>Modelo: " . $equipo->modelo
                        . "</li><li>Código: " . $equipo->codigo
                        . "</li><li>Puesto: " . $equipo->puesto
                        . "</li><li>Observaciones: " . $equipo->obs . "</li></ul>"
                        . "<p><b>Detalles de la incidencia</b></p>"
                        . "<ul><li>Fecha: " . $fecha
                        . "</li><li>Causa: " . $bind["causa"]
                        . "</li><li>Descripcion: " . $bind["descripcion_detalle"] . "</li></ul>";
                $stmt = $this->getAdapter()->prepare('insert into mensajes (usuarios_id_usuario,asunto, mensaje, destino) VALUES (?, ?, ?, ?)');
                $stmt->execute(array($usuario->id_usuario, $asunto, $mensaje, $usuario->email));
                /* si todo va bien hacemos el commit */
                $this->getAdapter()->commit();
            } catch (exception $e) {
                $this->getAdapter()->rollBack();
                throw $e;
            }
        }
    }

    public function getRow($id_incidencia_det) {

        $id_incidencia_det = (int) $id_incidencia_det;
        $row = $this->find($id_incidencia_det)->current();
        return $row;
    }

    public function getRows($id_incidencia) {
        return $this->fetchAll(
                        $this->select()
                                ->where("incidencias_id_incidencia=" . $id_incidencia)
                                ->order('id_incidencia_det ASC')
                                ->limit(5)
        );
    }

}

