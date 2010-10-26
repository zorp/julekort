<?php

class Default_Form_Greeting extends Zend_Form
{

    public function init()
    {
        $this->setAction('/new/');
        $this->setMethod(Zend_Form::METHOD_POST);
        $this->setAttrib('id', 'greeting');

        $gid = new Zend_Form_Element_Hidden('gid', array(
            'value' => 1,
            'order' => 999,
        ));

        $from_name = new Zend_Form_Element_Text('from_name', array(
            'label'      => 'Fra:',
            'required'   => true,
            'order'      => 1,
        ));
        $from_email = new Zend_Form_Element_Text('from_email', array(
            'label'      => 'Fra email:',
            'required'   => true,
            'order'      => 2,
            'validators' => array('EmailAddress')
        ));


        /**
         * @todo it should be possible to extend this with multiple recipients
         * a subform might be the way to go by this
         */
        $to_name = new Zend_Form_Element_Text('to_name_0', array(
            'label'      => 'Til:',
            'required'   => true,
            'order'      => 3,
            'belongsTo'  => 'Recipient[0]',
        ));
        $to_email = new Zend_Form_Element_Text('to_email_0', array(
            'label'      => 'Til email:',
            'required'   => true,
            'order'      => 4,
            'belongsTo'  => 'Recipient[0]',
            'validators' => array('EmailAddress')
        ));
        $add_recipient = new Zend_Form_Element_Button('add_recipient', array(
            'label'  => 'Tilføj modtager',
            'ignore' => true,
            'order'  => 90,
            'class'  => 'add-recipient',
        ));


        $greeting = new Zend_Form_Element_Textarea('greeting', array(
            'label'      => 'Tekst:',
            'required'   => true,
            'order'      => 91,
            'validators' => array(
                array('StringLength', false, array(0, 450))
            ),
            'cols' => 40,
            'rows' => 5,
        ));


        $show_card = new Zend_Form_Element_Button('show_card', array(
            'label'  => 'Vis kort',
            'ignore' => true,
            'order'  => 92,
            'class'  => 'submit',
        ));
        $send_card = new Zend_Form_Element_Submit('send_card', array(
            'label'  => 'Send kort',
            'ignore' => true,
            'order'  => 93,
            'class'  => 'submit',
        ));


        $this->addElements(array(
            $from_name,
            $from_email,
            $to_name,
            $to_email,
            $add_recipient,
            $greeting,
            $show_card,
            $send_card,
            $gid,
        ));
    }

    public function getRecipients()
    {
        $recipients = array();

        foreach($this->getElements() as $key => $elm) {
            preg_match('/(Recipient\w*)/', $elm->getBelongsTo(), $match);
            if(count($match) > 0) {
                preg_match('/(to_\w*)_(\d*)/', $elm->getName(), $name);

                $gid = (int) $name[2];
                $key = $name[1];

                $recipients[$gid][$key] = $elm->getValue();
            }
        }

        return $recipients;
    }

    public function validateRecipient(array $fields)
    {
        if(count($fields) === 1) {
            return false;
        }

        $order = 5;

        function findFields($field) {
            foreach($field as $k => $v) {
                preg_match('/(to_\w*)_(\d*)/', $k, $matches);
                if(count($matches) > 0) {
                    return $field;
                }
            }
        }

        $filterFields = array_filter($fields, 'findFields');

        foreach($filterFields as $belongsTo => $field) {
            foreach($field as $name => $value) {
                $order++;
                $type = (strpos($name, 'name')) ? 'name' : 'email';
                $this->addRecipient($name, $value, $type, $belongsTo, $order);
            }
        }
        return true;
    }

    public function addRecipient($name, $value, $type, $belongsTo, $order)
    {
        $this->addElement('text', $name, array(
            'label'      => ($type == 'name') ? 'Til:' : 'Til email:',
            'validators' => ($type == 'name') ? array() : array('EmailAddress'),
            'value'      => $value,
            'required'   => true,
            'belongsTo'  => 'Recipient[' . $belongsTo . ']',
            'order'      => $order,
        ));
    }

}