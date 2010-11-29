<?php

class Default_Form_View extends Zend_Form
{

    public function init()
    {
        $this->setAction('/view/');
        $this->setMethod(Zend_Form::METHOD_GET);
        $this->setAttrib('id', 'view');

        $hash = new Zend_Form_Element_Text('hash', array(
            'label'       => 'Indtast din kode:',
            /*'description' => 'Har du modtaget en kode pr mail kan du indtaste den her for at få vist dit personlige kort',*/
            'required'    => true,
        ));
        $submit = new Zend_Form_Element_Submit('submit', array(
            'label'  => 'Vis kort',
            'ignore' => true,
            'class'  => 'submit'
        ));


        $this->addElements(array(
            $hash,
            $submit,
        ));
    }

}