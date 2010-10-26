<?php

class IndexController extends Zend_Controller_Action
{

    protected $_form = null;
    protected $_redirector = null;

    public function getForm()
    {
        if($this->_form === null) {
            $this->_form = new Default_Form_Greeting();
        }
        return $this->_form;
    }

    public function init()
    {
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext->addActionContext('addrecipient', 'html')
                    ->initContext();

        $this->_redirector = $this->getHelper('Redirector');
    }

    public function indexAction()
    {
        $form = new Default_Form_View();
        $this->view->form = $form;
    }

    public function newAction()
    {
        $form = $this->getForm();

        if(!$this->getRequest()->isPost()) {
            $this->view->form = $form;
            return;
        }

        $val = $form->validateRecipient($this->_getParam('Recipient'));

        if(!$form->isValid($this->_getAllParams())) {
            $this->view->form = $form;
            return;
        }

        $data = array(
            'from_name'  => $this->_getParam('from_name'),
            'from_email' => $this->_getParam('from_email'),
            'greeting'   => $this->_getParam('greeting'),
            'Recipient'  => $form->getRecipients(),
        );

        $model = new Default_Model_Greeting();
        $model->fromArray($data);
        $model->save();

        $this->_redirector->gotoUrl('/send/' . $model->hash);
    }

    public function addrecipientAction()
    {
        $gid = $this->_getParam('gid', null);

        $to_name = new Zend_Form_Element_Text('to_name_' . $gid, array(
            'label'      => 'Til:',
            'required'   => true,
            'belongsTo'  => 'Recipient[' . $gid . ']',
        ));
        $to_email = new Zend_Form_Element_Text('to_email_' . $gid, array(
            'label'      => 'Til email:',
            'required'   => true,
            'belongsTo'  => 'Recipient[' . $gid . ']',
            'validators' => array('EmailAddress')
        ));

        $elements = $to_name->__toString() . $to_email->__toString();
        $this->view->assign(array(
            'recipient' => $elements
        ));
    }

    public function viewAction()
    {
        $greeting = Doctrine_Core::getTable('Default_Model_Greeting')
            ->findOneByHash($this->_getParam('hash', 0));

        if($greeting === false) {
            $this->_redirector->gotoUrl('/notfound/');
        }

        $this->view->assign(array(
            'hash' => $greeting->hash,
        ));

    }

    public function sendAction()
    {
        /**
         * send email here...
         */
        var_dump($this->_getParam('hash', null));
        die('Sending...');
        //$this->_redirector->gotoUrl('/view/' . $this->_getParam('hash', null));
    }

    public function notfoundAction()
    {
        $this->getResponse()->setHttpResponseCode(404);
    }

    public function __call($action, $args)
    {
        $hash = str_replace('Action', '', $action);
        $lookup = Doctrine_Core::getTable('Default_Model_Greeting')
            ->findOneByHash($hash);

        if($lookup === false) {
            $this->_redirector->gotoUrl('/notfound/');
        }

        $this->_redirector->gotoUrl('/view/' . $hash);
    }

}