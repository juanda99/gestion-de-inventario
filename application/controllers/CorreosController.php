<?php

class CorreosController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
    }

    public function indexAction() {
        
    }

    public function enviarAction() {
        /* Obtenemos los datos* para los correos y los envíamos */
        $model = new Application_Model_Mensajes();
        $mensajes = $model->fetchAll();
        foreach ($mensajes as $mensaje) {
            $tr = new Zend_Mail_Transport_Smtp('smtp-server', array(
                        'auth' => 'login',
                        'username' => 'smtp-user',
                        'password' => 'smtp-password',
                            )
            );
            Zend_Mail::setDefaultTransport($tr);
            $mail = new Zend_Mail('UTF-8');
            $mail->setBodyHtml($mensaje->mensaje)
                    ->setFrom('email-address', 'app-name')
                    ->addTo($mensaje->destino)
                    ->setSubject($mensaje->asunto)
                    ->send();
            /*borramos el registro*/
            $row = $model->getRow($mensaje->id_mensaje);
            $row->delete();
        }
        exit;
    }
}

