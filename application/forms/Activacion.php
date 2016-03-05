<?php

class Application_Form_Activacion extends Twitter_Form {

    public function init() {
        $this->setName('formulario');
        $this->setAttrib("class", "form-horizontal");

        $id = new Zend_Form_Element_Text('id');
        $id->setAttribs(array('style' => 'display:none;'));
        $this->addElement($id);

        $key = new Zend_Form_Element_Text('key');
        $key->setAttribs(array('style' => 'display:none;'));
        $this->addElement($key);

        $email = new Zend_Form_Element_Text('email');
        $email->setLabel('email:')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->setAttribs(array('class' => 'span12 focused', 'name' => 'email', 'maxlength' => '100'));
        $this->addElement($email);

        $password = new Zend_Form_Element_Password('password');
        $password->setLabel('Contraseña:')
                ->setAttribs(array('class' => 'span12 focused', 'name' => 'password', 'maxlength' => '100'));
        $this->addElement($password);

        $password2 = new Zend_Form_Element_Password('password2');
        $password2->setLabel('Contraseña:')
                ->setAttribs(array('class' => 'span12 focused', 'name' => 'password2', 'maxlength' => '100'));
        $this->addElement($password2);

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

