<?php
class Bytes_Category_Model_System_Config_Source_Category extends Mage_Adminhtml_Model_System_Config_Source_Category
{
    public function toOptionArray($addEmpty = true)
    {
        $collection = Mage::getModel('catalog/category')->getCollection()->addAttributeToSelect('*')
        ->addAttributeToFilter('level', 1)
        ->addAttributeToFilter('is_active', 1);

        $options = array();       
        foreach($collection as $category){            
            $options[] = array(
               'label' => $category->getName(),
               'value' => $category->getId()
            );            
        }
        return $options;
    }
}
