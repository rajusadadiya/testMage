<?php
class Bytes_Category_Block_Catalog_Product_List extends Mage_Catalog_Block_Product_List
{
    protected function _getProductCollection()
    {   

        /*$websiteCode = Mage::getStoreConfig('bytescategorytab/smallbusiness/code');
        $website = Mage::getModel( "core/website" )->load($websiteCode);

        $storeId = $website->getDefaultGroup()->getDefaultStore()->getId();*/

        if (is_null($this->_productCollection)) {
            $layer = $this->getLayer();
            /* @var $layer Mage_Catalog_Model_Layer */
            if ($this->getShowRootCategory()) {
                $this->setCategoryId(Mage::app()->getStore()->getRootCategoryId());
            }

            // if this is a product view page
            if (Mage::registry('product')) {
                // get collection of categories this product is associated with
                $categories = Mage::registry('product')->getCategoryCollection()
                    ->setPage(1, 1)
                    ->load();
                // if the product is associated with any category
                if ($categories->count()) {
                    // show products from this category
                    $this->setCategoryId(current($categories->getIterator()));
                }
            }

            $origCategory = null;
            if ($this->getCategoryId()) {
                $category = Mage::getModel('catalog/category')->load($this->getCategoryId());
                if ($category->getId()) {
                    $origCategory = $layer->getCurrentCategory();
                    $layer->setCurrentCategory($category);
                    $this->addModelTags($category);
                }
            }
            $this->_productCollection = $layer->getProductCollection();

            $this->prepareSortableFieldsByCategory($layer->getCurrentCategory());

            if ($origCategory) {
                $layer->setCurrentCategory($origCategory);
            }

            if(Mage::helper("bytescategory")->isCategoryFilter()){
                $pids = Mage::helper("bytescategory")->getProductIdsByStore();
                $this->_productCollection->addAttributeToFilter('entity_id', array('in' => $pids));
            }
        }
        
        
        
        /* if(Mage::getSingleton("customer/session")->isLoggedIn()){
            $pids = array();   

            $pids = Mage::helper("bytescategory")->getProductIds();
            
            $customer = Mage::getSingleton("customer/session")->getCustomer();
            $categoryState = Mage::getModel("bytescustomer/customer")->load($customer->getId(),"customer_id");     
            $categoryId = $categoryState->getCategoryId();
            $categoryModel = Mage::getModel('catalog/category');            
            if(is_array($pids)){
                $this->_productCollection->addAttributeToFilter('entity_id', array('in' => $pids));              
            }
        }      */ 

        if(Mage::getSingleton("customer/session")->isLoggedIn()){                        
            $customer = Mage::getSingleton("customer/session")->getCustomer();
            if(Mage::helper("bytescategory")->checkCustomerWebsite($customer)){
                $region = Mage::helper("bytescategory")->getCustomerRegion($customer);            

                $starr = [];
                $attribute = Mage::getSingleton('eav/config')
                ->getAttribute(Mage_Catalog_Model_Product::ENTITY, 'product_state');
                $options = $attribute->getSource()->getAllOptions(true);
                $selected = 0;
                foreach ($options as $reg) {
                    if($reg["label"] == $region){
                        $selected = $reg["value"];
                    }
                    $starr[$reg["value"]] = $reg["value"];
                }           

                if($region != ''){
                    $this->_productCollection->addAttributeToFilter("product_state",array('finset'=>array_search($selected,$starr)));   
                }    
            }
            
        }    
        return $this->_productCollection;        
    }
}