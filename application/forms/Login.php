<?php

class Application_Form_Login extends Twitter_Form {

    public function init() {
        $this->setName('formulario');
        $this->setAttrib("class", "form-horizontal");

        $email = new Zend_Form_Element_Text('email');
        $email->setLabel('Usuario:')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->setAttribs(array('class' => 'span12 focused', 'name' => 'email', 'maxlength' => '100'));
        $this->addElement($email);
        
        $password = new Zend_Form_Element_Password('password');
        $password->setLabel ('Contraseña:')
                        ->setAttribs(array('class' => 'span12 focused', 'name' => 'password', 'maxlength' => '100'));
        $this->addElement($password);
        
 

        $enviar = new Zend_Form_Element_Submit('guardar');
        $enviar->setLabel('Guardar')
                ->setAttribs(array('class' => 'btn btn-primary'));
        $this->addElement($enviar);

        $cancelar = new Zend_Form_Element_Reset('cancelar');
        $cancelar->setLabel('Cancelar')
                ->setAttribs(array('class' => 'btn'));
        $this->addElement($cancelar);
    }

}

