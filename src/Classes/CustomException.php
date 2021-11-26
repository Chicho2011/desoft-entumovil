<?php

namespace Desoft\Classes;

use Exception;

class CustomException extends Exception{

    private $_data = '';

    public function __construct($message, $data)
    {
        $this->_data = $data;
        parent::__construct($message);
    }

    public function getData()
    {
        return $this->_data;
    }
}
