<?php

class Application_Form_Departamento extends Twitter_Form {

    public function init() {
        $this->setName('formulario');
        $this->setAttrib("class", "form-horizontal");

        $id_departamento = new Zend_Form_Element_Text('id_departamento');
        $id_departamento->setAttribs(array('style' => 'display:none;'));
        $this->addElement($id_departamento);

        $departamento = new Zend_Form_Element_Text('departamento');
        $departamento->setLabel('Departamento:')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->setAttribs(array('class' => 'input-xlarge focused', 'name' => 'departamento', 'maxlength' => '100'));
        $this->addElement($departamento);

        $descripcion = new Zend_Form_Element_Textarea('descripcion');
        $descripcion->setLabel('Descripción:')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->setAttribs(array('class' => 'input-xlarge', 'name' => 'descripcion', 'maxlength' => '100'));
        $this->addElement($descripcion);

        $codigo= new Zend_Form_Element_Text('codigo');
        $codigo->setLabel('Código del departamento:')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->setAttribs(array('class' => 'input-xlarge', 'name' => 'codigo', 'maxlength' => '10'));
        $this->addElement($codigo);
        
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

