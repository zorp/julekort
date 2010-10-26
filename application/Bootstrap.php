<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initTranslation()
    {
        $path = APPLICATION_PATH . '/configs/lang/da-DK.php';
        $translate = new Zend_Translate('array', $path, 'da');
        Zend_Form::setDefaultTranslator($translate);
    }
}