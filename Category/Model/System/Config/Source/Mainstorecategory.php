<?php
class Bytes_Category_Model_System_Config_Source_Mainstorecategory extends Mage_Adminhtml_Model_System_Config_Source_Category
{
    public function toOptionArray($addEmpty = true)
    {
        // get Main store root category Id
        $rootCategoryId = Mage::app()->getStore("default")->getRootCategoryId();
        // Load Main store category 
        $category = Mage::getModel('catalog/category')->load($rootCategoryId);
        // get Collection of main store category
        $collection = $category->getChildrenCategories();
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
