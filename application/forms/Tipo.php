<?php

class Application_Form_Tipo extends Twitter_Form {

    public function init() {
        $this->setName('formulario');
        $this->setAttrib("class", "form-horizontal");

        $id_tipo = new Zend_Form_Element_Hidden('id_tipo');
        $this->addElement($id_tipo);

        $tipo = new Zend_Form_Element_Text('tipo');
        $tipo->setLabel('Tipo de Material:')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->setAttribs(array('class' => 'input-xlarge focused', 'name' => 'tipo', 'maxlength' => '100'));
        $this->addElement($tipo);

        $mantenimiento = new Zend_Form_Element_Checkbox('mantenimiento');
        $mantenimiento->setLabel('Matenimiento anual:');
        $this->addElement($mantenimiento);

        $enviar = new Zend_Form_Element_Submit('enviar');
        $enviar->setLabel('Guardar')
                ->setAttribs(array('class' => 'btn btn-primary'));
        $this->addElement($enviar);

        $cancelar = new Zend_Form_Element_Reset('cancelar');
        $cancelar->setLabel('Cancelar')
                ->setAttribs(array('class' => 'btn'));
        $this->addElement($cancelar);
    }

}

