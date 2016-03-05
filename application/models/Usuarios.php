<?php

class Application_Model_Usuarios extends Zend_Db_Table_Abstract {

    protected $_name = "usuarios";
    protected $_primary = "id_usuario";

    public function getAll() {
        return $this->fetchAll(
                        $this->select()
                                ->order('nick ASC')
        );
    }

    public function getAllNew() {
        return $this->fetchAll(
                        $this->select()
                                ->order('nick ASC')
                                ->limit(5)
        );
    }

    public function actualizar($bind) {
        $row = $this->getRow($bind["id_usuario"]);
        if (isset($bind["password"]))
            $bind["password"] = sha1($bind["password"]);
        $row->setFromArray($bind);
        $row->save();
    }

    public function save($bind, $url) {
        /* Si el usuario no existía, se le mandará un enlace para activar su cuenta
         * y se le mandará por correo electrónico
         */
        if (!isset($bind["id_usuario"]) || ($bind["id_usuario"] == "")) {
            $row = $this->createRow();
            $generatedKey = sha1(mt_rand(10000, 99999) . time() . $bind["email"]);
            $activacion = array('activacion' => $generatedKey);
            $bind = array_merge((array) $bind, (array) $activacion);
        } else {
            $row = $this->getRow($bind["id_usuario"]);
        }
        $row->setFromArray($bind);
        $this->getAdapter()->beginTransaction();
        try {
            /* guardamos sus datos en la tabla de usuarios */
            $row->save();
            /* guardamos las aulas en la tabla usuario_aulas */
            $id_usuario = $row->id_usuario;
            /* borramos los registros de las aulas donde ya no entra el usuario */
            $condicion = "";
            if (isset($bind["id_usuario"])) {
                /* Construimos el array de datos para la tabla de usuarios_aulas */
                if ($bind["id_aulas"] != "")
                    $condicion = " and  aulas_id_aula not in (" . implode(",", $bind["id_aulas"]) . ")";
                $stmt = $this->getAdapter()->prepare("delete from usuarios_aulas where usuarios_id_usuario=" . $id_usuario . $condicion);
                $stmt->execute();
            }
            /* insertamos todas las aulas donde entra, en las que ya estaba darán error de clave duplicada, por eso el ignore */
            $stmt = $this->getAdapter()->prepare('insert ignore into usuarios_aulas  (aulas_id_aula, usuarios_id_usuario) VALUES (?, ?)');
            if ($bind["id_aulas"] != "") {
                foreach ($bind["id_aulas"] as $id_aula) {
                    $stmt->execute(array($id_aula, $id_usuario));
                }
            }
            /* Si el usuario es nuevo hay que guardarlo en la tabla de mensajes para que active su cuenta */
            if (!isset($bind["id_usuario"]) || ($bind["id_usuario"] == "")) {
                $key = $generatedKey . "/id/" . $id_usuario;
                $mensaje = "<p>Hola " . $bind["nombre"] . " " . $bind["apellido"] . ",</p><p>Has recibido este correo porque has sido dado de alta en app-name.<br/>Puedes activar tu cuenta de usuario pulsando <a href='http://" . $url . "/usuarios/activar/key/" . $key . "'> aquí</a>.</p>";
                $asunto = 'Activación de cuenta de usuario';
                $destino = $bind["email"];
                $stmt = $this->getAdapter()->prepare('insert ignore into mensajes (usuarios_id_usuario,asunto, mensaje, destino) VALUES (?, ?, ?, ?)');
                $stmt->execute(array($id_usuario, $asunto, $mensaje, $destino));
            }



            /* si todo va bien hacemos el commit */
            $this->getAdapter()->commit();

            return $row;
        } catch (exception $e) {
            $this->getAdapter()->rollBack();
            throw $e;
        }
    }

    public function genkey($bind, $url) {

        $row = $this->getRowemail($bind["email"]);
        $generatedKey = sha1(mt_rand(10000, 99999) . time() . $bind["email"]);
        $activacion = array('activacion' => $generatedKey);
        $bind = array_merge((array) $bind, (array) $activacion);
        $row->setFromArray($bind);
        $this->getAdapter()->beginTransaction();
        try {
            /* Actualizamos la tabla con la clave de activación */
            $row->save();
            /* Si el usuario es nuevo hay que guardarlo en la tabla de mensajes para que active su cuenta */
            if (!isset($bind["id_usuario"]) || ($bind["id_usuario"] == "")) {
                $key = $generatedKey . "/id/" . $row->id_usuario;
                $mensaje = "<p>Hola " . $row->nombre . " " . $row->apellido . ",</p><p>Puedes activar tu usuario o resetear tu contraseña en app-name pulsando <a href='http://" . $url . "/usuarios/activar/key/" . $key . "'> aquí</a>.</p>";
                $asunto = 'Cambio contraseña de cuenta de usuario';
                $destino = $bind["email"];
                $stmt = $this->getAdapter()->prepare('insert ignore into mensajes (usuarios_id_usuario,asunto, mensaje, destino) VALUES (?, ?, ?, ?)');
                $stmt->execute(array($row->id_usuario, $asunto, $mensaje, $destino));
            }
            /* si todo va bien hacemos el commit */
            $this->getAdapter()->commit();
            return $row;
        } catch (exception $e) {
            $this->getAdapter()->rollBack();
            throw $e;
        }
    }

    public function getRow($id_usuario) {
        $id_usuario = (int) $id_usuario;
        $row = $this->find($id_usuario)->current();
        return $row;
    }

    public function cambiarestado($id_usuario, $estado, $url) {
        /* actualizaremos el usuario y guardaremos un email para ser enviado */


        $usuario = $this->getRow($id_usuario);
        if ($estado == 0) {
            $asunto = 'Baja de cuenta de usuario';
            $mensaje = "<p>Hola " . $usuario->nombre . " " . $usuario->apellido . ",</p><p>Has recibido este correo porque has sido dado de baja en app-name.</p>";
        } else {
            $asunto = 'Activación de cuenta de usuario';
            $mensaje = "<p>Hola " . $usuario->nombre . " " . $usuario->apellido . ",</p><p>Has recibido este correo porque se ha activado tu cuenta en app-name.<br/>Puedes acceder a la aplicación pulsando <a href='http://" . $url . "'>aquí</a>.</p>";
        }
        $destino = $usuario->email;

        $this->getAdapter()->beginTransaction();
        try {
            $stmt = $this->getAdapter()->prepare('update usuarios set estado=? where id_usuario=?');
            $stmt->execute(array($estado, $id_usuario));
            /* Si el usuario lo damos de baja, lo borramos de la lista de aulas donde estuviera */
            $stmt = $this->getAdapter()->prepare('delete from usuarios_aulas where usuarios_id_usuario=?');
            $stmt->execute(array($id_usuario));
            $stmt = $this->getAdapter()->prepare('insert ignore into mensajes (usuarios_id_usuario,asunto, mensaje, destino) VALUES (?, ?, ?, ?)');
            $stmt->execute(array($id_usuario, $asunto, $mensaje, $destino));
            $this->getAdapter()->commit();
        } catch (exception $e) {
            $this->getAdapter()->rollBack();
            throw $e;
        }
    }

    public function getRowemail($email) {
        return $this->fetchRow(
                        $this->select()
                                ->where('email=?', $email)
        );
    }

    public function getProfesores($id_aula) {
        $select = $this->select()
                ->setIntegrityCheck(false)
                ->from(array('a' => 'usuarios_aulas'))
                ->join(array('u' => 'usuarios'), 'u.id_usuario = a.usuarios_id_usuario')
                ->where('aulas_id_aula=?', $id_aula);
        return $this->fetchAll($select);
    }

    public function getResponsables($id_aula) {
        $select = $this->select()
                ->setIntegrityCheck(false)
                ->from(array('a' => 'aulas'))
                ->join(array('ua' => 'usuarios_aulas'), 'a.id_aula = ua.aulas_id_aula')
                ->join(array('u' => 'usuarios'), 'u.id_usuario = ua.usuarios_id_usuario', array('id_usuario', 'apellido', 'nombre', 'email'))
                ->where('id_aula=?', $id_aula)
                 ->where('responsable=1')
                ->order('apellido');
        $kk = $select->__toString();
        return $this->fetchAll($select);
    }

}

