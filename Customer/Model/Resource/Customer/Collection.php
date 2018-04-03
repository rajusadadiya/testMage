<?php 
class Bytes_Customer_Model_Resource_Customer_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract {
    protected function _construct()
    {
        $this->_init('bytescustomer/customer');
    }
}