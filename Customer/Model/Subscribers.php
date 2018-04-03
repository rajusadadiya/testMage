<?php

class Bytes_Customer_Model_Subscribers extends MD_Membership_Model_Subscribers {

    public function getReservedIncrementId() {
        $storeId = Mage::app()->getStore()->getId();
        $collection = Mage::getModel('md_membership/increments')->getCollection()
                ->addFieldToFilter('store_id', array('eq' => $storeId));

        $incrimentLastId = $storeId.'000000001';
        if($storeId > 9 && $storeId < 100){
            $incrimentLastId = $storeId.'00000001';
        }
        elseif($storeId > 99 && $storeId < 1000){
            $incrimentLastId = $storeId.'0000001';
        }

        if($collection->count() == 0){
            $storeIncrimentModel = Mage::getModel('md_membership/increments');
            $storeIncrimentModel->setData(array("store_id" => $storeId,"increment_last_id" => $incrimentLastId));
            $storeIncrimentModel->save();
            $collection = Mage::getModel('md_membership/increments')->getCollection()
                ->addFieldToFilter('store_id', array('eq' => $storeId));            
        }
        $incrementItem = $collection->getFirstItem();
        $incrementsIdFormat = (int)$incrimentLastId;
        if($incrementItem->getIncrementLastId() >= $incrementsIdFormat){
            $lastUsedIncrementId = $incrementItem->getIncrementLastId();
            $newIncrementId = $lastUsedIncrementId + 1;
        }
        else{
            $incrementItem->setIncrementLastId($incrementsIdFormat);
            $incrementItem->save();
        }
        return $newIncrementId;
    }
}
