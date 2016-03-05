<?php

class Application_Form_Proveedor extends Twitter_Form {

    public function init() {
        $this->setName('formulario');
        $this->setAttrib("class", "form-horizontal");

        $id_proveedor = new Zend_Form_Element_Text('id_proveedor');
        $id_proveedor->setAttribs(array('style' => 'display:none;'));
        $this->addElement($id_proveedor);

        $proveedor = new Zend_Form_Element_Text('proveedor');
        $proveedor->setLabel('Proveedor:')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->setAttribs(array('class' => 'span12 focused', 'name' => 'proveedor', 'maxlength' => '100' ));
        $this->addElement($proveedor);

        $codigo = new Zend_Form_Element_Text('codigo');
        $codigo->setLabel('codigo:')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->setAttribs(array('class' => 'span12 focused', 'name' => 'codigo', 'maxlength' => '100'));
        $this->addElement($codigo);

        $descripcion = new Zend_Form_Element_Textarea('descripcion');
        $descripcion->setLabel('Descripción:')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->setAttribs(array('class' => 'span12', 'name' => 'descripcion', 'maxlength' => '100'));
        $this->addElement($descripcion);

        $material = new Zend_Form_Element_Text('material');
        $material->setLabel('material:')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->setAttribs(array('class' => 'span12 focused', 'name' => 'material', 'maxlength' => '100'));
        $this->addElement($material);

        $telefono = new Zend_Form_Element_Text('telefono');
        $telefono->setLabel('telefono:')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->setAttribs(array('class' => 'span12 focused', 'name' => 'telefono', 'maxlength' => '100'));
        $this->addElement($telefono);

        $movil = new Zend_Form_Element_Text('movil');
        $movil->setLabel('movil:')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->setAttribs(array('class' => 'span12 focused', 'name' => 'movil', 'maxlength' => '100'));
        $this->addElement($movil);

        $fax = new Zend_Form_Element_Text('fax');
        $fax->setLabel('fax:')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->setAttribs(array('class' => 'span12 focused', 'name' => 'fax', 'maxlength' => '100'));
        $this->addElement($fax);

        $nif = new Zend_Form_Element_Text('nif');
        $nif->setLabel('nif:')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->setAttribs(array('class' => 'span12 focused', 'name' => 'nif', 'maxlength' => '100'));
        $this->addElement($nif);

        $email = new Zend_Form_Element_Text('email');
        $email->setLabel('email:')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->setAttribs(array('class' => 'span12 focused', 'name' => 'email', 'maxlength' => '100'));
        $this->addElement($email);

        $direccion = new Zend_Form_Element_Text('direccion');
        $direccion->setLabel('direccion:')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->setAttribs(array('class' => 'span12 focused', 'name' => 'direccion', 'maxlength' => '100'));
        $this->addElement($direccion);

        $localidad = new Zend_Form_Element_Text('localidad');
        $localidad->setLabel('localidad:')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->setAttribs(array('class' => 'span12 focused', 'name' => 'localidad', 'maxlength' => '100'));
        $this->addElement($localidad);

        $provincia = new Zend_Form_Element_Select('provincia');
        $provincia->setLabel('Provincia:')
                ->setMultiOptions(array('Álava' => 'Álava', 'Albacete' => 'Albacete', 'Alicante' => 'Alicante', 'Almería' => 'Almería', 'Asturias' => 'Asturias', 'Ávila' => 'Ávila', 'Badajoz' => 'Badajoz', 'Baleares' => 'Baleares', 'Barcelona' => 'Barcelona', 'Burgos' => 'Burgos', 'Cáceres' => 'Cáceres', 'Cádiz' => 'Cádiz', 'Cantabria' => 'Cantabria', 'Castellón' => 'Castellón', 'Ceuta' => 'Ceuta', 'Ciudad Real' => 'Ciudad Real', 'Córdoba' => 'Córdoba', 'Coruña' => 'Coruña', 'Cuenca' => 'Cuenca', 'Gerona' => 'Gerona', 'Granada' => 'Granada', 'Guadalajara' => 'Guadalajara', 'Guipúzcoa' => 'Guipúzcoa', 'Huelva' => 'Huelva', 'Huesca' => 'Huesca', 'Jaén' => 'Jaén', 'León' => 'León', 'Lérida' => 'Lérida', 'La Rioja' => 'La Rioja', 'Lugo' => 'Lugo', 'Madrid' => 'Madrid', 'Málaga' => 'Málaga', 'Melilla' => 'Melilla', 'Murcia' => 'Murcia', 'Navarra' => 'Navarra', 'Orense' => 'Orense', 'Palencia' => 'Palencia', 'Las Palmas' => 'Las Palmas', 'Pontevedra' => 'Pontevedra', 'Salamanca' => 'Salamanca', 'Santa Cruz de Tenerife' => 'Santa Cruz de Tenerife', 'Segovia' => 'Segovia', 'Sevilla' => 'Sevilla', 'Soria' => 'Soria', 'Tarragona' => 'Tarragona', 'Teruel' => 'Teruel', 'Toledo' => 'Toledo', 'Valencia' => 'Valencia', 'Valladolid' => 'Valladolid', 'Vizcaya' => 'Vizcaya', 'Zamora' => 'Zamora', 'Zaragoza' => 'Zaragoza'))
                ->setAttribs(array('maxlength' => '50', 'class' => 'span12 focused combobox', 'name' => 'provincia', 'data-provide' => 'typeahead', 'data-items' => 4,
                    'data-source' => '["Álava", "Albacete", "Alicante","Almería", "Asturias","Ávila", "Badajoz", "Baleares", "Barcelona", "Burgos", "Cáceres", "Cádiz", "Cantabria","Castellón", "Ceuta", "Ciudad Real", "Córdoba", "Coruña", "Cuenca", "Gerona", "Granada", "Guadalajara", "Guipúzcoa", "Huelva", "Huesca", "Jaén", "León", "Lérida", "La Rioja", "Lugo", "Madrid", "Málaga",  "Melilla", "Murcia", "Navarra", "Orense", "Palencia", "Las Palmas", "Pontevedra", "Salamanca", "Santa Cruz de Tenerife",  "Segovia", "Sevilla", "Soria", "Tarragona", "Teruel", "Toledo", "Valencia", "Valladolid", "Vizcaya", "Zamora", "Zaragoza"]'))
         ->setValue('Zaragoza'); 
        $this->addElement($provincia);

        $cp = new Zend_Form_Element_Text('cp');
        $cp->setLabel('cp:')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->setAttribs(array('class' => 'span12 focused', 'name' => 'cp', 'maxlength' => '100'));
        $this->addElement($cp);
        
        $id_departamento = new Zend_Form_Element_Multiselect('id_departamento');       
        $model = new Application_Model_Departamentos();
        $departamentos[]="";
        foreach ($model->getAll() as $departamento){
            $id_departamento->addMultiOption($departamento->id_departamento, $departamento->departamento);
            $departamentos[]=$departamento->departamento;
        }
        $lista_departamentos = implode(",", $departamentos); 
                $id_departamento->setLabel('Departamentos:')
                 ->setAttribs(array('maxlength' => '50',  'size' => '4', 'name' => 'id_departamento', 'class' => 'span12 combobox', 'data-provide' => 'typeahead', 'data-items' => 4,
                    'data-source' => "[$lista_departamentos]"))
                ->addFilter('StripTags')
                ->addFilter('StringTrim');
        $this->addElement($id_departamento);



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

