<?php

class Verk_View_Helper_BodyClass extends Zend_View_Helper_Abstract {

    protected $_data = array();

    public function BodyClass($return = null)
    {
        if(is_array($return)) {
            foreach($return as $key) {
                $request = Zend_Controller_Front::getInstance()->getRequest();
                $params = $request->getParams();

                if(array_key_exists($key, $params)) {
                    array_push($this->_data, $params[$key]);
                }
            }
        }

        $output = implode(' ', $this->_data);
        return ($output) ? $output : 'index';
    }

}