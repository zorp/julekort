<?php

return array(
    /*Zend_Validate_Alnum::STRING_EMPTY              => '',
    Zend_Validate_Alpha::NOT_ALPHA                 => '',
    Zend_Validate_Alpha::STRING_EMPTY              => '',
    Zend_Validate_Between::NOT_BETWEEN             => '',
    Zend_Validate_Between::NOT_BETWEEN_STRICT      => '',
    Zend_Validate_Date::INVALID                    => '',
    Zend_Validate_Digits::NOT_DIGITS               => '',
    Zend_Validate_Digits::STRING_EMPTY             => '',*/
    Zend_Validate_EmailAddress::INVALID            => 'Emailadressen ser forkert ud',
    Zend_Validate_EmailAddress::INVALID_FORMAT     => 'Emailadressen ser forkert ud',
    Zend_Validate_EmailAddress::INVALID_HOSTNAME   => 'Emailadressen ser forkert ud',
    Zend_Validate_EmailAddress::INVALID_MX_RECORD  => 'Emailadressen ser forkert ud',
    Zend_Validate_EmailAddress::DOT_ATOM           => 'Emailadressen ser forkert ud',
    Zend_Validate_EmailAddress::QUOTED_STRING      => 'Emailadressen ser forkert ud',
    Zend_Validate_EmailAddress::INVALID_LOCAL_PART => 'Emailadressen ser forkert ud',/*
    Zend_Validate_Float::NOT_FLOAT                 => '',
    Zend_Validate_InArray::NOT_IN_ARRAY            => '',
    Zend_Validate_Int::NOT_INT                     => '',
    Zend_Validate_LessThan::NOT_LESS               => '',*/
    Zend_Validate_NotEmpty::IS_EMPTY               => 'Dette felt er krævet',
    Zend_Validate_StringLength::TOO_SHORT          => 'Feltet indeholder mindre end det tilladte antal karakterer',
    Zend_Validate_StringLength::TOO_LONG           => 'Feltet indeholder mere end det tilladte antal karakterer',
);