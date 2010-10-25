<?php

class Default_Form_Greeting extends Zend_Form
{

    public function init()
    {
        $this->setAction('/send/');
        $this->setMethod(Zend_Form::METHOD_POST);
        $this->setAttrib('id', 'greeting');

        $from_name = new Zend_Form_Element_Text('from_name', array(
            'label' => 'Fra:',
            'validators' => array('NotEmpty')
        ));
        $from_email = new Zend_Form_Element_Text('from_email', array(
            'label' => 'Fra email:',
            'validators' => array('EmailAddress')
        ));


        /**
         * @todo it should be possible to extend this with multiple recipients
         * a subform might be the way to go by this
         */
        $to_name = new Zend_Form_Element_Text('to_name', array(
            'label' => 'Til:',
            'validators' => array('NotEmpty')
        ));
        $to_email = new Zend_Form_Element_Text('to_email', array(
            'label' => 'Fra:',
            'validators' => array('EmailAddress')
        ));


        $greeting = new Zend_Form_Element_Textarea('greeting', array(
            'label' => 'Tekst:',
            'validators' => array('NotEmpty') // @todo add max 450 length
        ));

        $show_card = new Zend_Form_Element_Submit('show_card', array(
            'label' => 'Vis kort',
            'ignore' => true,
        ));
        $send_card = new Zend_Form_Element_Submit('send_card', array(
            'label' => 'Send kort',
            'ignore' => true,
        ));


        $this->addElements(array(
            $from_name,
            $from_email,
            $to_name,
            $to_email,
            $greeting,
            $show_card,
            $send_card,
        ));
    }

}