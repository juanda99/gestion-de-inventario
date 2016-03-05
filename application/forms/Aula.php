<?php

class Application_Form_Aula extends Twitter_Form {

    public function init() {
        $this->setName('formulario');
        $this->setAttrib("class", "form-horizontal");

        $id_aula = new Zend_Form_Element_Text('id_aula');
        $id_aula->setAttribs(array('style' => 'display:none;'));
        $this->addElement($id_aula);

        $aula = new Zend_Form_Element_Text('aula');
        $aula->setLabel('Aula:')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->setAttribs(array('class' => 'span12 focused', 'name' => 'aula', 'maxlength' => '100'));
        $this->addElement($aula);


        $descripcion = new Zend_Form_Element_Textarea('descripcion');
        $descripcion->setLabel('Descripción:')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->setAttribs(array('class' => 'span12', 'name' => 'descripcion', 'maxlength' => '1000'));
        $this->addElement($descripcion);

        $departamentos_id_departamento = new Zend_Form_Element_Select('departamentos_id_departamento');
        $model = new Application_Model_Departamentos();
        $departamentos[] = "";
        foreach ($model->getAll() as $departamento) {
            $departamentos_id_departamento->addMultiOption($departamento->id_departamento, $departamento->departamento);
            $departamentos[] = $departamento->departamento;
        }
        $lista_departamentos = implode(",", $departamentos);
        $departamentos_id_departamento->setLabel('Departamentos:')
                ->setAttribs(array('maxlength' => '50', 'size' => '1', 'name' => 'departamentos_id_departamento', 'class' => 'span12 combobox', 'data-provide' => 'typeahead', 'data-items' => 4,
                    'data-source' => "[$lista_departamentos]"))
                ->addFilter('StripTags')
                ->addFilter('StringTrim');
        $this->addElement($departamentos_id_departamento);

        $id_usuarios = new Zend_Form_Element_Multiselect('id_usuarios');
        /* se rellenará vía ajax al seleccionar un departamento */
        $id_usuarios->setLabel('id_usuarios::')
                ->setAttribs(array('maxlength' => '50', 'size' => '4', 'name' => 'id_usuarios', 'class' => 'span12 combobox', 'data-provide' => 'typeahead', 'data-items' => 4,
                    'data-source' => "[]"))
                ->setRegisterInArrayValidator(FALSE)
                ->addFilter('StripTags')
                ->addFilter('StringTrim');
        $this->addElement($id_usuarios);






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

