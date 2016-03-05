<?php

class Myclasses_Autenticacion implements Zend_Auth_Adapter_Interface {

    /**
     * Username
     *
     * @var string
     */
    protected $username = null;

    /**
     * Password
     *
     * @var string
     */
    protected $password = null;

    /**
     * Class constructor
     *
     * The constructor sets the username and password
     *
     * @param string $username
     * @param string $password
     */
    public function __construct($username, $password) {
        $this->email = $username;
        $this->password = $password;
    }

    /**
     * Authenticate
     *
     * Authenticate the username and password
     *
     * @return Zend_Auth_Result
     */
    public function authenticate() {
        // Try to fetch the user from the database using the model
        $usuarios = new Application_Model_Usuarios();
        $select = $usuarios->select()
                ->where('email = ?', $this->email);
        $usuario = $usuarios->fetchRow($select);

        // Initialize return values
        $code = Zend_Auth_Result::FAILURE;
        $identity = null;
        $messages = array();

        // Do we have a valid user?
        if (is_null($usuario)) {
            $messages[] = 'El usuario no es correcto. Recuerda que es tu email.';
        } else {
            if ($usuario->estado == 0) {
                $messages[] = 'Usuario desactivado por el jefe de Departamento';
            } elseif (!is_null($usuario->activacion)) {
                $messages[] = 'Tienes que activar el usuario. <br/>Pide una contraseña desde el enlace inferior.';
            } elseif (sha1($this->password) != $usuario->password) {
                $messages[] = 'La contraseña no es correcta.<br/> Puedes solicitar una nueva desde el enlace inferior';
            } else {
                $code = Zend_Auth_Result::SUCCESS;
                $identity = $usuario;
            }
        }
        return new Zend_Auth_Result($code, $identity, $messages);
    }

}

?>