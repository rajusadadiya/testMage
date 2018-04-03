<?php

class Bytes_Customer_Model_Customer extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('bytescustomer/customer');
    }
}
