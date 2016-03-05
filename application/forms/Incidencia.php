<?php

class Application_Form_Incidencia extends Twitter_Form {

    public function init() {
        $this->setName('formulario');
        $this->setAttrib("class", "form-horizontal");

        $id_incidencia = new Zend_Form_Element_Text('id_incidencia');
        $id_incidencia->setAttribs(array('style' => 'display:none;'));
        $this->addElement($id_incidencia);

        $inventario_id_inventario = new Zend_Form_Element_Text('inventario_id_inventario');
        $inventario_id_inventario->setAttribs(array('style' => 'display:none;'));
        $this->addElement($inventario_id_inventario);

        $causa = new Zend_Form_Element_Text('causa');
        $causa->setLabel('Causa:')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->setAttribs(array('class' => 'span12 focused', 'name' => 'causa', 'maxlength' => '100'));
        $this->addElement($causa);

        $descripcion = new Zend_Form_Element_Textarea('descripcion');
        $descripcion->setLabel('Descripción:')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->setAttribs(array('class' => 'span12', 'name' => 'descripcion', 'maxlength' => '2000'));
        $this->addElement($descripcion);
        
        $solucion = new Zend_Form_Element_Text('solucion');
        $solucion->setLabel('Solución:')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->setAttribs(array('class' => 'span12 focused', 'name' => 'solucion', 'maxlength' => '100', 'style' => 'display:none;'));
        $this->addElement($solucion);

        $solucion_det = new Zend_Form_Element_Textarea('solucion_det');
        $solucion_det->setLabel('Descripción solución:')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->setAttribs(array('class' => 'span12', 'name' => 'solucion_det', 'maxlength' => '2000', 'style' => 'display:none;'));
        $this->addElement($solucion_det);
              
        

        $hoy = date("d-m-Y");
        $fecha_incidencia = new Zend_Form_Element_Text('fecha_incidencia');
        $fecha_incidencia->setLabel('Fecha Incidencia:')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->setValue($hoy)
                ->setAttribs(array('class' => 'span12 focused', 'name' => 'fecha_incidencia', 'maxlength' => '12'));
        $this->addElement($fecha_incidencia);

        $fecha_solucion = new Zend_Form_Element_Text('fecha_solucion');
        $fecha_solucion->setLabel('Fecha solución:')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->setAttribs(array('class' => 'span12 focused', 'name' => 'fecha_solucion', 'maxlength' => '100'));
        $this->addElement($fecha_solucion);

        $codigo = new Zend_Form_Element_Text('codigo');
        $codigo->setLabel('codigo:')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->setAttribs(array('class' => 'span12 focused', 'name' => 'codigo', 'maxlength' => '100'));
        $this->addElement($codigo);

        $estadomat = new Zend_Form_Element_Select('estadomat');
        $estadomat->setLabel('Estado:')
                ->setMultiOptions(array('En uso' => 'En uso', 'Baja' => 'Baja', 'Averiado' => 'Averiado'))
                ->setAttribs(array('maxlength' => '50', 'class' => 'span12 combobox', 'data-provide' => 'typeahead', 'data-items' => 4,
                    'data-source' => '["En uso", "Baja", "Averiado"]'));
        /* ->setValue('Zaragoza'); */
        $this->addElement($estadomat);

        $estadoinc = new Zend_Form_Element_Select('estadoinc');
        $estadoinc->setLabel('Estado Incidencia:')
                ->setMultiOptions(array('Abierta en Centro' => 'Abierta en Centro',  'Abierta en Proveedor' => 'Abierta en Proveedor', 'Cerrada' => 'Cerrada',))
                ->setAttribs(array('maxlength' => '50', 'class' => 'span12 combobox', 'data-provide' => 'typeahead', 'data-items' => 4,
                    'data-source' => '["Abierta en Centro", "Abierta en Proveedor", "Cerrada"]'));
        /* ->setValue('Zaragoza'); */
        $this->addElement($estadoinc);

        $prioridad = new Zend_Form_Element_Select('prioridad');
        $prioridad->setLabel('Prioridad:')
                ->setMultiOptions(array('Media' => 'Media', 'Alta' => 'Alta', 'Baja' => 'Baja'))
                ->setAttribs(array('maxlength' => '50', 'class' => 'span12 combobox', 'data-provide' => 'typeahead', 'data-items' => 4,
                    'data-source' => '[ "Media","Alta", "Baja"]'))
                ->setValue('Media')
        ;
        /* ->setValue('Zaragoza'); */
        $this->addElement($prioridad);

        $responsable = new Zend_Form_Element_Select('responsable');
        $responsable->setLabel('Asignada a:')
                ->setRegisterInArrayValidator(FALSE)
                ->setAttribs(array('maxlength' => '50', 'class' => 'span12 combobox', 'data-provide' => 'typeahead', 'data-items' => 4));
        /* ->setValue('Zaragoza'); */
        $this->addElement($responsable);

        $detectadapor = new Zend_Form_Element_Select('detectadapor');
        $detectadapor->setLabel('Detectada por:')
                ->setRegisterInArrayValidator(FALSE)
                ->setAttribs(array('style' => 'display:none;', 'maxlength' => '50', 'class' => 'span12 combobox', 'data-provide' => 'typeahead', 'data-items' => 4));
        /* ->setValue('Zaragoza'); */
        $this->addElement($detectadapor);
        

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

