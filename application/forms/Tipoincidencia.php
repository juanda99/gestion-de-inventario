<?php

class Application_Form_Tipoincidencia extends Twitter_Form {

    public function init() {
        $this->setName('formulario');
        $this->setAttrib("class", "form-horizontal");
        
        $id_tipo_incidencia = new Zend_Form_Element_Text('id_tipo_incidencia');
        $id_tipo_incidencia->setAttribs(array('style' => 'display:none;'));
        $this->addElement($id_tipo_incidencia);
        
        $tipo_incidencia = new Zend_Form_Element_Text('tipo_incidencia');
        $tipo_incidencia->setLabel('Tipo Incidencia:')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->setAttribs(array('class' => 'input-xlarge focused', 'name' => 'tipo_incidencia', 'maxlength' => '100'));
        $this->addElement($tipo_incidencia);

        $descripcion = new Zend_Form_Element_Textarea('descripcion');
        $descripcion->setLabel('Descripción:')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->setAttribs(array('class' => 'input-xlarge', 'name' => 'descripcion', 'maxlength' => '100'));
        $this->addElement($descripcion);



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

