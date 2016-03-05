<?php

class Application_Form_Incidenciafungibledet extends Twitter_Form {

    public function init() {
        $this->setName('formulario');
        $this->setAttrib("class", "form-horizontal");

        $incidencias_id_incidencia = new Zend_Form_Element_Text('incidencias_id_incidencia');
        $incidencias_id_incidencia->setAttribs(array('style' => 'display:none;'));
        $this->addElement($incidencias_id_incidencia);

        $causa = new Zend_Form_Element_Text('causa');
        $causa->setLabel('Causa:')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->setAttribs(array('class' => 'span12', 'name' => 'causa', 'maxlength' => '100'));
        $this->addElement($causa);

        $descripcion_detalle = new Zend_Form_Element_Textarea('descripcion_detalle');
        $descripcion_detalle->setLabel('Descripción:')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->setAttribs(array('class' => 'span12', 'name' => 'descripcion_detalle', 'maxlength' => '2000'));
        $this->addElement($descripcion_detalle);

        $hoy = date("d-m-Y");
        $fecha = new Zend_Form_Element_Text('fecha');
        $fecha->setLabel('Fecha:')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->setValue($hoy)
                ->setAttribs(array('class' => 'span12 focused', 'name' => 'fecha', 'maxlength' => '12', 'style'=>'display:none;', 'value' => $hoy));
        $this->addElement($fecha);

        $usuarios_id_usuario = new Zend_Form_Element_Select('usuarios_id_usuario');
        $usuarios_id_usuario->setLabel('Reasignar a:')
                ->setRegisterInArrayValidator(FALSE)
                ->setAttribs(array('maxlength' => '50', 'class' => 'span12 combobox', 'data-provide' => 'typeahead', 'data-items' => 4, 'style'=>'display:none;'));
        /* ->setValue('Zaragoza'); */
        $this->addElement($usuarios_id_usuario);

        $tipo = new Zend_Form_Element_Select('tipo');
        $tipo->setLabel('Estado:')
                ->setMultiOptions(array('Cierre' => 'Cerrar incidencia', 'Reasignacion' => 'Reasignar incidencia', 'Abierta en Proveedor' => 'Actualizar estado incidencia a abierta en Proveedor', 'Abierta en Centro' => 'Actualizar estado incidencia a abierta en Centro') )
                ->setAttribs(array('maxlength' => '50', 'class' => 'span12 combobox', 'data-provide' => 'typeahead', 'data-items' => 4,
                    'data-source' => '["Cerrar incidencia", "Reasignar incidencia", "Actualizar estado incidencia a abierta en Proveedor", "Actualizar estado incidencia a abierta en Centro"]'));
        /* ->setValue('Zaragoza'); */
        $this->addElement($tipo);
            
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

