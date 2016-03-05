<?php

class Application_Form_Inventario extends Twitter_Form {

    public function init() {
        $this->setName('formulario');
        $this->setAttrib("class", "form-horizontal");

        $id_inventario = new Zend_Form_Element_Text('id_inventario');
        $id_inventario->setAttribs(array('style' => 'display:none;'));
        $this->addElement($id_inventario);

        $modelo = new Zend_Form_Element_Text('modelo');
        $modelo->setLabel('Modelo:')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->setAttribs(array('class' => 'span12 focused', 'name' => 'modelo', 'maxlength' => '100'));
        $this->addElement($modelo);

        $codigo = new Zend_Form_Element_Text('codigo');
        $codigo->setLabel('codigo:')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->setAttribs(array('class' => 'span12 focused', 'name' => 'codigo', 'maxlength' => '100'));
        $this->addElement($codigo);
        

        $aulas_id_aula = new Zend_Form_Element_Select('aulas_id_aula');
        $model = new Application_Model_Aulas();
        $aulas[] = "";
        foreach ($model->getAll() as $aula) {
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
        
        $puesto = new Zend_Form_Element_Select('puesto');
        $puesto->addMultiOption('', ' ');
        for ($i=0; $i<31; $i++) {
            $puesto->addMultiOption($i, $i);
            //$aulas[] = $aula->aula;
        }
        /*añadimos otro puesto más, el vacío*/
        
        $puesto->setLabel('Puesto:')
                ->setAttribs(array('maxlength' => '52', 'size' => '1', 'name' => 'puesto', 'class' => 'span12 combobox', 'data-provide' => 'typeahead', 'data-items' => 4,
                    'data-source' => '[$lista_aulas]'))
                ->addFilter('StripTags')
                ->addFilter('StringTrim');
        $this->addElement($puesto);

        $tipos_id_tipo = new Zend_Form_Element_Select('tipos_id_tipo');
        $model = new Application_Model_Materiales();
        $materiales[] = "";
        foreach ($model->getAll() as $material) {
            $tipos_id_tipo->addMultiOption($material->id_tipo, $material->tipo);
            $materiales[] = $material->tipo;
        }
        $lista_materiales = implode(",", $materiales);
        $tipos_id_tipo->setLabel('Materiales:')
                ->setAttribs(array('maxlength' => '50', 'size' => '1', 'name' => 'tipos_id_tipo', 'class' => 'span12 combobox', 'data-provide' => 'typeahead', 'data-items' => 4,
                    'data-source' => "[$lista_materiales]"))
                ->addFilter('StripTags')
                ->addFilter('StringTrim');
        $this->addElement($tipos_id_tipo);

        $proveedores_id_proveedor = new Zend_Form_Element_Select('proveedores_id_proveedor');
        $model = new Application_Model_Proveedores();
        $proveedores[] = "";
        foreach ($model->getAll() as $proveedor) {
            $proveedores_id_proveedor->addMultiOption($proveedor->id_proveedor, $proveedor->proveedor);
            $proveedores[] = $proveedor->proveedor;
        }
        $lista_proveedores = implode(",", $proveedores);
        $proveedores_id_proveedor->setLabel('Proveedor:')
                ->setAttribs(array('maxlength' => '50', 'size' => '1', 'name' => 'proveedores_id_proveedor', 'class' => 'span12 combobox', 'data-provide' => 'typeahead', 'data-items' => 4,
                    'data-source' => "[$lista_proveedores]"))
                ->addFilter('StripTags')
                ->addFilter('StringTrim');
        $this->addElement($proveedores_id_proveedor);

        $departamentos_id_departamento = new Zend_Form_Element_Select('departamentos_id_departamento');
        $model = new Application_Model_Departamentos();
        $departamentos[] = "";
        foreach ($model->getAll() as $departamento) {
            $departamentos_id_departamento->addMultiOption($departamento->id_departamento, $departamento->departamento);
            $departamentos[] = $departamento->departamento;
        }
        $lista_departamentos = implode(",", $departamentos);
        $departamentos_id_departamento->setLabel('Departamento:')
                ->setAttribs(array('maxlength' => '50', 'size' => '1', 'name' => 'departamentos_id_departamento', 'class' => 'span12 combobox', 'data-provide' => 'typeahead', 'data-items' => 4,
                    'data-source' => "[$lista_departamentos]"))
                ->addFilter('StripTags')
                ->addFilter('StringTrim');
        $this->addElement($departamentos_id_departamento);


        $fecha_adquisicion = new Zend_Form_Element_Text('fecha_adquisicion');
        $fecha_adquisicion->setLabel('Fecha adquisición:')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->setAttribs(array('class' => 'span12 focused', 'name' => 'fecha_adquisicion', 'maxlength' => '100'));
        $this->addElement($fecha_adquisicion);


        $estado = new Zend_Form_Element_Select('estado');
        $estado->setLabel('Estado:')
                ->setMultiOptions(array('En uso' => 'En uso', 'Baja' => 'Baja', 'Averiado' => 'Averiado'))
                ->setAttribs(array('maxlength' => '50', 'class' => 'span12 combobox', 'data-provide' => 'typeahead', 'data-items' => 4,
                    'data-source' => '["En uso", "Baja", "Averiado"]'));
        /* ->setValue('Zaragoza'); */
        $this->addElement($estado);

        $obs = new Zend_Form_Element_Textarea('obs');
        $obs->setLabel('Observaciones:')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->setAttribs(array('class' => 'span12', 'name' => 'obs', 'maxlength' => '100'));
        $this->addElement($obs);



        $enviar = new Zend_Form_Element_Submit('guardar');
        $enviar->setLabel('Guardar')
                ->setAttribs(array('class' => 'btn btn-primary', 'title'=>'Guardar el material'));
        $this->addElement($enviar);

        $cancelar = new Zend_Form_Element_Reset('cancelar');
        $cancelar->setLabel('Cancelar')
                ->setAttribs(array('class' => 'btn', 'title'=>'Volver al listado de material'));
        $this->addElement($cancelar);
        
        $clonar = new Zend_Form_Element_Submit('clonar');
        $clonar->setLabel('Clonar')
                ->setAttribs(array('class' => 'btn btn-warning', 'title'=>'Guardar el material y clonar para la inserción posterior de un material similar'));
        $this->addElement($clonar);
    }

}

