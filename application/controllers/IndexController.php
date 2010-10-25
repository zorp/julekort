<?php

class IndexController extends Zend_Controller_Action
{

    protected $_form = null;

    public function getForm()
    {
        if($this->_form === null) {
            $this->_form = new Default_Form_Greeting();
        }
        return $this->_form;
    }

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }

    public function newAction()
    {
        // now that we are sure lets add the name to our hidden form element
        $form = $this->getForm();

        // if everything is good, lets go and save the record
        if($this->getRequest()->isPost() && $form->isValid($this->_getAllParams())) {
            $data  = $form->getValues();

            $model = new Default_Model_Entry();
            $model->fromArray(array(
                'from_name'  => $data['from_name'],
                'from_email' => $data['from_email'],
                'greeting'   => $data['greeting'],
                'recipients' => array() // @todo work in the multiple recipients here
            ));
            $model->save();

            // all good, now lets share this bad boy
            $this->_redirector->gotoUrl('/show/' . $model->hash);
        }

        $slicedName = explode(' ', $this->_getParam('name'));
        $this->view->assign(array(
            'form'      => $form,
        ));
    }

    public function showAction()
    {
        $greeting = Doctrine_Core::getTable('Default_Model_Greeting')
            ->findOneByHash($this->_getParam('hash', 0));

        if($greeting === false) {
            $this->_redirector->gotoUrl('/notfound/');
        }

        $this->view->assign(array(
            'hash'     => $greeting->hash,
        ));

    }

    public function sendAction()
    {
        // action body
    }

    public function notfoundAction()
    {}

    public function __call($action, $args)
    {
        $hash = str_replace('Action', '', $action);
        $lookup = Doctrine_Core::getTable('Default_Model_Greeting')
            ->findOneByHash($hash);
        if($lookup === false) {
            $this->_redirector->gotoUrl('/notfound/');
        }
        $this->_redirector->gotoUrl('/show/' . $hash);
    }

}