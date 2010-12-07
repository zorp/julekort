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

        $isEnglish = ($this->_getParam('send_card_en', false)) ? true : false;

        $data = array(
            'isEnglish'  => $isEnglish,
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
            'label'      => 'Til (navn)',
            'required'   => true,
            'belongsTo'  => 'Recipient[' . $gid . ']',
        ));
        $to_email = new Zend_Form_Element_Text('to_email_' . $gid, array(
            'label'      => 'Til (email)',
            'required'   => true,
            'belongsTo'  => 'Recipient[' . $gid . ']',
            'validators' => array('EmailAddress'),
            'class'      => 'email',
        ));

        $elements = $to_name->__toString() . $to_email->__toString();
        $this->view->assign(array(
            'recipient' => $elements
        ));
    }

    public function viewAction()
    {
        $model = Doctrine_Core::getTable('Default_Model_Greeting')
            ->findOneByHash($this->_getParam('hash', 0));

        if($model === false) {
            $this->_redirector->gotoUrl('/notfound/');
        }

        $model->seen = $model->seen+1;
        $model->save();

        $this->view->assign(array(
            'hash'      => $model->hash,
            'isEnglish' => $model->isEnglish,
            'greeting'  => $model->greeting,
        ));

    }

    public function sendAction()
    {
        $model = Doctrine_Core::getTable('Default_Model_Greeting')
            ->findOneByHash($this->_getParam('hash', 0));

        // if we for some reason should not be able to find the message, redirect
        if($model === false) {
            $this->_redirector->gotoUrl('/notfound/');
        }

        $options = $this->getInvokeArg('bootstrap')->getOption('mail');
        /*$tr = new Zend_Mail_Transport_Smtp(
            $options['outbound']['host'],
            $options['outbound']
        );*/

        foreach($model->Recipient as $recipient) {
            $mail = new Zend_Mail('utf-8');

            $mail->addTo($recipient->to_email, $recipient->to_name);
            $mail->setFrom($options['outbound']['email'], $options['outbound']['from']);


            $message_danish = '<h2>Kære ' . $recipient->to_name . '</h2>
<p>Du har fået en julehilsen fra Inspicos.</p>
<p>For at se kortet, <a href="http://' . $this->getRequest()->getServer('HTTP_HOST') . '/view/' . $model->hash . '">klik her</a>.</p>
<p>Virker linket ikke, så kopiér koden herunder, åben http://' . $this->getRequest()->getServer('HTTP_HOST') . ' i din browser og indsæt koden.<br />
Kode: <strong>' . $model->hash . '</strong></p>

<p>Venlig hilsen<br />
' . $model->from_name . '</p>';

            $message_english = '<h2>Dear ' . $recipient->to_name . '</h2>
<p>You have received a christmas card from Inspicos.</p>
<p>To see the card use this link, <a href="http://' . $this->getRequest()->getServer('HTTP_HOST') . '/view/' . $model->hash . '">click here</a>.</p>
<p>If the link doesnt work, open http://' . $this->getRequest()->getServer('HTTP_HOST') . ' in your browser and insert the code.<br />
Code: <strong>' . $model->hash . '</strong></p>

<p>Greetings from,<br />
' . $model->from_name . '</p>';


            // @todo move this to a view and render it that way
            if($model->isEnglish) {
                $mail->setSubject('Season\'s Greetings from Inspicos');
                $mail->setBodyHtml($message_english);
            } else {
                $mail->setSubject('Julekort fra Inspicos');
                $mail->setBodyHtml($message_danish);
            }

            $mail->send();
        }

        $this->view->assign(array(
            'recipients' => $model->Recipient,
            'hash'       => $model->hash
        ));

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