<?php

class Bytes_Category_Model_Observer extends Varien_Event_Observer
{
   public function saveCategoryObserve($observer)
   {
      $event = $observer->getEvent();
      $model = $event->getCategory();
	   if($model->getIsState()){
         $model->setIncludeInMenu(false);
      }
      return $this;
   }
}



