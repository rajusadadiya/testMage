<?php
/* Developed By USLegal */

class Bytes_Ustheme_Model_CatalogsearchLayer extends Mage_CatalogSearch_Model_Layer
{
   
    /**
     * Prepare product collection
     *
     * @param Mage_Catalog_Model_Resource_Eav_Resource_Product_Collection $collection
     * @return Mage_Catalog_Model_Layer
     */
    
    //core method
    /*public function prepareProductCollection($collection)
    {
        $collection->addAttributeToSelect(Mage::getSingleton('catalog/config')->getProductAttributes())
            ->addSearchFilter(Mage::helper('catalogsearch')->getQuery()->getQueryText())
            ->setStore(Mage::app()->getStore())
            ->addMinimalPrice()
            ->addFinalPrice()
            ->addTaxPercents()
            ->addStoreFilter()
            ->addUrlRewrite();

        Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($collection);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInSearchFilterToCollection($collection);

        return $this;
    }*/


    public function prepareProductCollection($collection)
    {
        //$starr = [];

        $attribute = Mage::getSingleton('eav/config')
        ->getAttribute(Mage_Catalog_Model_Product::ENTITY, 'product_state');
        $options = $attribute->getSource()->getAllOptions(true);
        $selected = 0;
        foreach ($options as $region) {
            if($region["label"] == Mage::helper("catalogsearch")->getSelectedState()){
                $selected = $region["value"];
            }
            //$starr[$region["value"]] = $region["value"];
        }
        if(Mage::helper('catalogsearch')->getSelectedState() != "")
        {
            $collection->addAttributeToSelect(Mage::getSingleton('catalog/config')->getProductAttributes())
            ->addAttributeToFilter("product_state",array('finset'=>$selected))
            ->addSearchFilter(Mage::helper('catalogsearch')->getQuery()->getQueryText())
            ->setStore(Mage::app()->getStore())
            ->addMinimalPrice()
            ->addFinalPrice()
            ->addTaxPercents()
            ->addStoreFilter()
            ->addUrlRewrite();
             //echo $collection->getSelect(); 
            //->addCategoryFilter(Mage::helper('catalogsearch')->getSelectedCategory())
        }
        else
        {
            $collection->addAttributeToSelect(Mage::getSingleton('catalog/config')->getProductAttributes())
            ->addSearchFilter(Mage::helper('catalogsearch')->getQuery()->getQueryText())
            ->setStore(Mage::app()->getStore())
            ->addMinimalPrice()
            ->addFinalPrice()
            ->addTaxPercents()
            ->addStoreFilter() 
            ->addUrlRewrite();
        } 
        
        Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($collection);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInSearchFilterToCollection($collection);
        return $this;
    }
}
