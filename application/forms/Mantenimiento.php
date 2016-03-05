<?php

class Application_Form_Mantenimiento extends Twitter_Form {

    public function init() {
        $this->setName('formulario');
        $this->setAttrib("class", "form-horizontal");

        $id_mantenimiento = new Zend_Form_Element_Text('id_mantenimiento');
        $id_mantenimiento->setAttribs(array('style' => 'display:none;'));
        $this->addElement($id_mantenimiento);


        /* el listado debería cambiar en función del departamento al que pertenezca el usuario */
        $aulas_id_aula = new Zend_Form_Element_Select('aulas_id_aula');
        $model = new Application_Model_Aulas();
        $aulas[] = "";
        $sesion = new Zend_Session_Namespace("Usuario");
        foreach ($model->getAllDepartamento($sesion->id_departamento) as $aula) {
            $aulas_id_aula->addMultiOption($aula->id_aula, $aula->aula);
            $aulas[] = $aula->aula;
        }
        $lista_aulas = implode(",", $aulas);
        $aulas_id_aula->setLabel('Aula:')
                ->setAttribs(array('maxlength' => '50', 'size' => '1', 'name' => 'aulas_id_aula', 'class' => 'span12 combobox', 'data-provide' => 'typeahead', 'data-items' => 4,
                    'data-source' => "[$lista_aulas]"))
                ->addFilter('StripTags')
                ->addFilter('StringTrim');
        $this->addElement($aulas_id_aula);

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

        $hoy = date("d-m-Y");
        $fecha_mantenimiento = new Zend_Form_Element_Text('fecha_mantenimiento');
        $fecha_mantenimiento->setLabel('Fecha Mantenimiento:')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->setValue($hoy)
                ->setAttribs(array('class' => 'span12 focused', 'name' => 'fecha_mantenimiento', 'maxlength' => '12'));
        $this->addElement($fecha_mantenimiento);

        $fecha_solucion = new Zend_Form_Element_Text('fecha_solucion');
        $fecha_solucion->setLabel('Fecha solución:')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->setAttribs(array('class' => 'span12 focused', 'name' => 'fecha_solucion', 'maxlength' => '100'));
        $this->addElement($fecha_solucion);

        /*
        $estadoman = new Zend_Form_Element_Select('estadoman');
        $estadoman->setLabel('Estado:')
                ->setMultiOptions(array('Abierta' => 'Abierta', 'Cerrada' => 'Cerrada'))
                ->setAttribs(array('maxlength' => '50', 'class' => 'span12 combobox', 'data-provide' => 'typeahead', 'data-items' => 4,
                    'data-source' => '["Abierta", "Cerrada"]'));
        $this->addElement($estadoman);
         * */

        $prioridad = new Zend_Form_Element_Select('prioridad');
        $prioridad->setLabel('Prioridad:')
                ->setMultiOptions(array('Alta' => 'Alta', 'Media' => 'Media', 'Baja' => 'Baja'))
                ->setAttribs(array('maxlength' => '50', 'class' => 'span12 combobox', 'data-provide' => 'typeahead', 'data-items' => 4,
                    'data-source' => '["Alta", "Media", "Baja"]'));
        /* ->setValue('Zaragoza'); */
        $this->addElement($prioridad);

        $responsable = new Zend_Form_Element_Select('responsable');
        $responsable->setLabel('Asignada a:')
                ->setRegisterInArrayValidator(FALSE)
                ->setAttribs(array('maxlength' => '50', 'class' => 'span12 combobox', 'data-provide' => 'typeahead', 'data-items' => 4));
        /* ->setValue('Zaragoza'); */
        $this->addElement($responsable);

        $detectadapor = new Zend_Form_Element_Text('detectadapor');
        $detectadapor->setAttribs(array('style' => 'display:none;'));
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

