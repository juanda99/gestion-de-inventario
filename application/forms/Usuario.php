<?php

class Application_Form_Usuario extends Twitter_Form {

    public function init() {
        $this->setName('formulario');
        $this->setAttrib("class", "form-horizontal");

        $id_usuario = new Zend_Form_Element_Text('id_usuario');
        $id_usuario->setAttribs(array('style' => 'display:none;'));
        $this->addElement($id_usuario);

        $nick = new Zend_Form_Element_Text('nick');
        $nick->setLabel('nick:')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->setAttribs(array('class' => 'span12 focused', 'name' => 'nick', 'maxlength' => '50'));
        $this->addElement($nick);
        
        $nombrecompleto = new Zend_Form_Element_Text('nombrecompleto');
        $nombrecompleto->setLabel('nombrecompleto:')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->setAttribs(array('class' => 'span12 focused', 'name' => 'nombrecompleto', 'maxlength' => '202'));
        $this->addElement($nombrecompleto);

        $nombre = new Zend_Form_Element_Text('nombre');
        $nombre->setLabel('nombre:')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->setAttribs(array('class' => 'span12 focused', 'name' => 'nombre', 'maxlength' => '100'));
        $this->addElement($nombre);

        $apellido = new Zend_Form_Element_Text('apellido');
        $apellido->setLabel('apellido:')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->setAttribs(array('class' => 'span12 focused', 'name' => 'apellido', 'maxlength' => '100'));
        $this->addElement($apellido);

        $email = new Zend_Form_Element_Text('email');
        $email->setLabel('email:')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->setAttribs(array('class' => 'span12 focused', 'name' => 'email', 'maxlength' => '100'));
        $this->addElement($email);
        
        $departamentos_id_departamento = new Zend_Form_Element_Select('departamentos_id_departamento');       
        $model = new Application_Model_Departamentos();
        $departamentos[]="";
        foreach ($model->getAll() as $departamento){
            $departamentos_id_departamento->addMultiOption($departamento->id_departamento, $departamento->departamento);
            $departamentos[]=$departamento->departamento;
        }
        $lista_departamentos = implode(",", $departamentos); 
                $departamentos_id_departamento->setLabel('Departamentos:')
                 ->setAttribs(array('maxlength' => '50',  'name' => 'departamentos_id_departamento', 'class' => 'span12 combobox', 'data-provide' => 'typeahead', 'data-items' => 4,
                    'data-source' => "[$lista_departamentos]"))
                ->addFilter('StripTags')
                ->addFilter('StringTrim');
        $this->addElement($departamentos_id_departamento);
        
        
        $id_aulas = new Zend_Form_Element_Multiselect('id_aulas');
        $model = new Application_Model_Aulas();
        $aulas[] = "";
        foreach ($model->getAll() as $aula) {
            $id_aulas->addMultiOption($aula->id_aula, $aula->aula);
            $aulas[] = $aula->aula;
        }
        $lista_aulas = implode(",", $aulas);
        $id_aulas->setLabel('Aulas:')
                ->setAttribs(array('maxlength' => '50', 'size' => '4', 'name' => 'id_aulas', 'class' => 'span12 combobox', 'data-provide' => 'typeahead', 'data-items' => 4,
                    'data-source' => "[$lista_aulas]"))
                ->addFilter('StripTags')
                ->addFilter('StringTrim');
        $this->addElement($id_aulas);
        
        $perfil = new Zend_Form_Element_Select('perfil');
        $perfil->setLabel('Perfil:')
                ->setMultiOptions(array('1' => 'Profesor', '2' => 'Jefe de Departamento', '3' => 'Secretario', '4' => 'Jefe de Estudios', '5' => 'Director'))
                ->setAttribs(array('maxlength' => '50', 'class' => 'span12 combobox', 'data-provide' => 'typeahead', 'data-items' => 4,
                    'data-source' => '["Profesor", "Jefe de Departamento", "Secretario", "Jefe de Estudios", "Director"]'));
        /* ->setValue('Zaragoza'); */
        $this->addElement($perfil);

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

