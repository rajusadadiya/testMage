<?php 

require_once(Mage::getModuleDir('controllers','MD_Membership').DS.'IndexController.php');
 
class Bytes_Customer_IndexController extends MD_Membership_IndexController{

	public function paymentAction() {

		/* @raju added code */
		/*if($this->getRequest()->getParam("cat_id") != '' && is_numeric($this->getRequest()->getParam("cat_id"))){
			$id = (int) $this->getRequest()->getParam("cat_id");
			Mage::getSingleton('core/session')->setCustomerStateCategory($id);			
		}
		else{
			Mage::getSingleton('core/session')->addError(Mage::helper('md_membership')->__('Please select plan state.'));
            $this->_redirect('membership.html');
		}*/
		/* @raju code end*/

        $params = $this->getRequest()->getParams();
        $planId = base64_decode($params['plan']);
        if ($planId):
            $plan = Mage::getSingleton('md_membership/plans')->load($planId);

            if ($plan->getData()) {
                if (isset($params['membership']['subscription_start_date']) && !is_null($params['membership']['subscription_start_date'])) {
                    $plan->setData('customer_subscription_Date', $params['membership']['subscription_start_date']);
                }
                Mage::register('current_plan', $plan);
                $this->loadLayout();
                $this->renderLayout();
            } else {
                Mage::getSingleton('core/session')->addError(Mage::helper('md_membership')->__('Please select plan.'));
                $this->_redirect('membership.html');
            }
        else:
            Mage::getSingleton('core/session')->addError(Mage::helper('md_membership')->__('Please select plan.'));
            $this->_redirect('membership.html');

        endif;
    }

    public function payAction() {

        if ($this->getRequest()->isXmlHttpRequest()) {
            $stateCategoryId = Mage::getSingleton('core/session')->getCustomerStateCategory();
            $posts = $this->getRequest()->getPost();
            $store = Mage::app()->getStore();
            //upgrade plan            
            if (Mage::getSingleton('customer/session')->isLoggedIn()) {                
                $helper = Mage::helper('md_membership');
                $customerData = Mage::getSingleton('customer/session')->getCustomer();
                $cusexitplanId = $helper->getcustomerPlan($customerData->getId());                
                   
                if (isset($cusexitplanId['plan_id']) && $cusexitplanId['plan_id'] != ''):
                    //Cancle customer existing plan
                    $canclecurrentPlan = $helper->canclePlan($customerData->getId(), $cusexitplanId['plan_id']);
                    $status = MD_Membership_Model_Subscribers::SUBSCRIPTION_STATUS_CANCELLED;
                                        
                    if ($canclecurrentPlan == $status):

                        $planId = $this->getRequest()->getParam('plan_id', null);
                        $billingAddressId = $posts['billing_address_id'] ? $posts['billing_address_id'] : null;
                        $billingaddress = Mage::getModel('customer/address')->load($billingAddressId);
                        $method = $posts['membership']['method'];
                        $paymentModelClass = (isset($this->_modelMap[$method])) ? $this->_modelMap[$method] : null;

                        Mage::getSingleton('md_membership/session')->setNewCustomerBillingInfo($newCustomer);

                        Mage::getSingleton('md_membership/session')->setCustomerBillingAddressId($billingAddressId);
                        Mage::getSingleton('md_membership/session')->setPlanId($planId);
                        Mage::getSingleton('md_membership/session')->setcustomerId($billingaddress->getParentId());
                        Mage::getSingleton('md_membership/session')->setSubscriptionStartDate($posts['subscription_date']);

                        if ($paymentModelClass && $planId) {
                            $payment = Mage::getModel($paymentModelClass)
                                    ->setMembershipPlanId($planId)
                                    ->setBillingAddressId($billingAddressId)
                                    ->setSubscriptionStartDate($posts['subscription_date']);

                            if (in_array($method, $this->_creditCardRequiredMethod)) {
                                $response = $payment->setCardDetails($cardDetails)->pay();
                                $result = array();
                                if (array_key_exists('profile_id', $response)) {
                                    $plan = Mage::getModel('md_membership/plans')->load($planId);
                                    $arrayData = $plan->getData();
                                    $arrayData['plan_url'] = $plan->getPlanUrl();
                                    $arrayData['image_url'] = $plan->getImageUrl();
                                    if (!array_key_exists('reference_id', $response)) {
                                        $response['reference_id'] = Mage::getModel('md_membership/subscribers')->getReservedIncrementId();
                                    }
                                    $response['plan_data'] = serialize($arrayData);
                                    $subscribers = Mage::getModel('md_membership/subscribers')
                                            ->setData($response);
                                    $subscribers->save();

                                    // check customer with category state
                                    /*Mage::helper('bytescustomer')->checkCustomer($customerData->getId(),$subscribers->getPlanId(),$stateCategoryId);*/

                                    Mage::helper('md_membership')->sendNewSubscriptionEmail($subscribers);
                                    Mage::getSingleton('core/session')->addSuccess(Mage::helper('md_membership')->__('You are subscribed for membership plan \'%s\'', $response['plan_title']));
                                    $result['redirect_url'] = Mage::getUrl('success');
                                } else {
                                    $result['error'] = $response['error'];
                                }
                            } else {
                                $result = $payment->callSetExpressCheckoutMethod();
                            }
                        }
                        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
                    endif;
                else:

                    $planId = $this->getRequest()->getParam('plan_id', null);
                    $billingAddressId = $posts['billing_address_id'] ? $posts['billing_address_id'] : null;
                    $billingaddress = Mage::getModel('customer/address')->load($billingAddressId);
                    $method = $posts['membership']['method'];
                    $paymentModelClass = (isset($this->_modelMap[$method])) ? $this->_modelMap[$method] : null;
                    
                    Mage::getSingleton('md_membership/session')->setNewCustomerBillingInfo($newCustomer);
                    Mage::getSingleton('md_membership/session')->setCustomerBillingAddressId($billingAddressId);
                    Mage::getSingleton('md_membership/session')->setPlanId($planId);
                    Mage::getSingleton('md_membership/session')->setcustomerId($billingaddress->getParentId());
                    Mage::getSingleton('md_membership/session')->setSubscriptionStartDate($posts['subscription_date']);

                    if ($paymentModelClass && $planId) {
                        $payment = Mage::getModel($paymentModelClass)
                                ->setMembershipPlanId($planId)
                                ->setBillingAddressId($billingAddressId)
                                ->setSubscriptionStartDate($posts['subscription_date']);

                        if (in_array($method, $this->_creditCardRequiredMethod)) {

                            $cardDetails = null;
                            if(isset($posts[$method])){
                                $cardDetails = $posts[$method];
                            }
                            $response = $payment->setCardDetails($cardDetails)->pay();
                            $result = array();
                            if (array_key_exists('profile_id', $response)) {
                                $plan = Mage::getModel('md_membership/plans')->load($planId);
                                $arrayData = $plan->getData();
                                $arrayData['plan_url'] = $plan->getPlanUrl();
                                $arrayData['image_url'] = $plan->getImageUrl();
                                if (!array_key_exists('reference_id', $response)) {
                                    $response['reference_id'] = Mage::getModel('md_membership/subscribers')->getReservedIncrementId();
                                }
                                $response['plan_data'] = serialize($arrayData);
                                $subscribers = Mage::getModel('md_membership/subscribers')
                                        ->setData($response);
                                $subscribers->save();

                                /*Mage::helper('bytescustomer')->checkCustomer($customerData->getId(),$subscribers->getPlanId(),$stateCategoryId);*/

                                Mage::helper('md_membership')->sendNewSubscriptionEmail($subscribers);
                                Mage::getSingleton('core/session')->addSuccess(Mage::helper('md_membership')->__('You are subscribed for membership plan \'%s\'', $response['plan_title']));
                                $result['redirect_url'] = Mage::getUrl('success');
                            } else {
                                $result['error'] = $response['error'];
                            }
                        } else {
                            $result = $payment->callSetExpressCheckoutMethod();
                        }
                    } else {
                        $plan = Mage::getModel('md_membership/plans')->load($planId);
                        $numdayAllow = $plan->getFreeAllowDays();
                        $allowDate = strtotime('+' . $numdayAllow . ' day');
                        $billingAddressId = $posts['billing_address_id'] ? $posts['billing_address_id'] : null;
                        $address = Mage::getModel('customer/address')->load($billingAddressId);
                        $customer = Mage::getModel('customer/customer')->load($address->getParentId());
                        $subscribers = Mage::getModel('md_membership/subscribers')
                                ->setPlanId($planId)
                                ->setReferenceId(mt_rand(100000, 999999))
                                ->setStoreId($store->getId())
                                ->setCustomerId($customer->getEntityId() ? $customer->getEntityId() : $customerData->getId())
                                ->setCustomerAddressId($billingAddressId)
                                ->setName($customer->getFirstname() && $customer->getLastname() ? $customer->getFirstname() . ' ' . $customer->getLastname() : $customerData->getFirstname() . ' ' . $customerData->getLastname())
                                ->setGroupId($customer->getGroupId() ? $customer->getGroupId() : $customerData->getGroupId())
                                ->setPaymentMethod('free')
                                ->setStatus(1)
                                ->setTelephone($address->getTelephone())
                                ->setPostcode($address->getPostcode())
                                ->setRegion($address->getRegion())
                                ->setCountryId($address->getCountryId())
                                ->setProfileStartDate(Mage::getModel('core/date')->date('Y-m-d'))
                                ->setProfileEndDate(Mage::getModel('core/date')->date('Y-m-d', $allowDate))
                                ->setCreatedAt(Mage::getModel('core/date')->date('Y-m-d H:i:s'))
                                ->setUpdatedAt(Mage::getModel('core/date')->date('Y-m-d H:i:s'))
                                ->setEmail($customer->getEmail() ? $customer->getEmail() : $customerData->getEmail());

                        $subscribers->save();
                        
                       /* Mage::helper('bytescustomer')->checkCustomer($customer->getId(),$subscribers->getPlanId(),$stateCategoryId);*/

                        Mage::helper('md_membership')->sendNewSubscriptionEmail($subscribers);

                        Mage::getSingleton('core/session')->addSuccess(Mage::helper('md_membership')->__('You are subscribed for Free Membership plan'));
                        $result['redirect_url'] = Mage::getUrl('success');
                    }
                    $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
                endif;
            } else {
            	
                //Create customer
                $websiteId = Mage::app()->getWebsite()->getId();
                $store = Mage::app()->getStore();

                //Create Customer
                $newCustomer = $posts['billing'];
                //Check Existing customer
                $customer = Mage::getModel("customer/customer");
                $customer->setWebsiteId($websiteId);
                $customer->loadByEmail($newCustomer['email']);

                //Check plan is exiting for this customer
                $planId = $this->getRequest()->getParam('plan_id', null);
                $plan = Mage::getModel('md_membership/plans')->load($planId);

                if ($customer->getId()):
                    $url = Mage::getUrl("customer/account/login");
                    $loginUrl = '<a href=' . $url . '>Click here.</a>';
                    Mage::getSingleton('core/session')->addError(Mage::helper('md_membership')->__('There is already an account with this email address. Please do login to access your account.'));
                    $result['error'] = 'There is already an account with this email address. Please do login to access your account.';
                else:
                    $customer = Mage::getModel("customer/customer");
                    $customer->setWebsiteId($websiteId)
                            ->setStore($store)
                            ->setGroupId($plan->getAssignedGroupId())
                            ->setFirstname($newCustomer['first_name'])
                            ->setLastname($newCustomer['last_name'])
                            ->setEmail($newCustomer['email'])
                            ->setPassword($newCustomer['customer_password']);
                    $customer->save();

                    //Save New Customer Address
                    $address = Mage::getModel("customer/address");
                    $address->setCustomerId($customer->getId())
                            ->setFirstname($customer->getFirstname())
                            ->setMiddleName($customer->getMiddlename())
                            ->setLastname($customer->getLastname())
                            ->setCountryId('US')
                            ->setRegionId($newCustomer['region']) //state/province, only needed if the country is USA
                            ->setPostcode($newCustomer['postcode'])
                            ->setCity($newCustomer['city'])
                            ->setTelephone($newCustomer['telephone'])
                            ->setStreet($newCustomer['address'] . ' ' . $newCustomer['address1'])
                            ->setIsDefaultBilling('1')
                            ->setIsDefaultShipping('1')
                            ->setSaveInAddressBook('1');

                    try {
                        $address->save();
                    } catch (Exception $e) {
                        Zend_Debug::dump($e->getMessage());
                    }

                    $billingAddressId = $address->getEntityId() ? $address->getEntityId() : null;

                    $planId = $this->getRequest()->getParam('plan_id', null);
                    $method = $posts['membership']['method'];
                    $cardDetails = $posts[$method];
                    if ($method == 'membership_method_free'):
                        $plan = Mage::getModel('md_membership/plans')->load($planId);
                        $numdayAllow = $plan->getFreeAllowDays();
                        $allowDate = strtotime('+' . $numdayAllow . ' day');
                        $subscribers = Mage::getModel('md_membership/subscribers')
                                ->setPlanId($planId)
                                ->setReferenceId(mt_rand(100000, 999999))
                                ->setStoreId($store->getId())
                                ->setCustomerId($customer->getEntityId() ? $customer->getEntityId() : $customer->getEntityId())
                                ->setCustomerAddressId($billingAddressId)
                                ->setName($customer->getFirstname() . ' ' . $customer->getLastname() ? $customer->getFirstname() . ' ' . $customer->getLastname() : $customer->getFirstname() . ' ' . $customer->getLastname())
                                ->setGroupId($customer->getGroupId() ? $customer->getGroupId() : $customer->getGroupId())
                                ->setPaymentMethod('free')
                                ->setStatus(1)
                                ->setTelephone($address->getTelephone())
                                ->setPostcode($address->getPostcode())
                                ->setRegion($address->getRegion())
                                ->setCountryId($address->getCountryId())
                                ->setProfileStartDate(Mage::getModel('core/date')->date('Y-m-d'))
                                ->setProfileEndDate(Mage::getModel('core/date')->date('Y-m-d', $allowDate))
                                ->setCreatedAt(Mage::getModel('core/date')->date('Y-m-d H:i:s'))
                                ->setUpdatedAt(Mage::getModel('core/date')->date('Y-m-d H:i:s'))
                                ->setEmail($customer->getEmail() ? $customer->getEmail() : $customer->getEmail());
                        $subscribers->save();

                        // check customer with category state
                        /*Mage::helper('bytescustomer')->checkCustomer($customer->getId(),$subscribers->getPlanId(),$stateCategoryId);
*/
                        Mage::helper('md_membership')->sendNewSubscriptionEmail($subscribers);
                        Mage::getSingleton('core/session')->addSuccess(Mage::helper('md_membership')->__('You are subscribed for Free Membership plan'));
                        $result['redirect_url'] = Mage::getUrl('success');
                    else:

                        $billingAddressId = $customer->getDefaultBilling() ? $customer->getDefaultBilling() : null;
                        $paymentModelClass = (isset($this->_modelMap[$method])) ? $this->_modelMap[$method] : null;
                        Mage::getSingleton('md_membership/session')->setNewCustomerBillingInfo($newCustomer);
                        $newCustomer = Mage::getSingleton('core/session')->getNewCustomerBillingInfo();

                        Mage::getSingleton('md_membership/session')->setCustomerBillingAddressId($billingAddressId);
                        Mage::getSingleton('md_membership/session')->setPlanId($planId);
                        Mage::getSingleton('md_membership/session')->setcustomerId($customer->getId());
                        Mage::getSingleton('md_membership/session')->setSubscriptionStartDate($posts['subscription_date']);

                        if ($paymentModelClass && $planId) {
                            $payment = Mage::getModel($paymentModelClass)
                                    ->setMembershipPlanId($planId)
                                    ->setBillingAddressId($billingAddressId)
                                    ->setSubscriptionStartDate($posts['subscription_date']);

                            if (in_array($method, $this->_creditCardRequiredMethod)) {
                                
                                $response = $payment->setCardDetails($cardDetails)->pay();
                                $result = array();
                                if (array_key_exists('profile_id', $response)) {
                                    $plan = Mage::getModel('md_membership/plans')->load($planId);
                                    $arrayData = $plan->getData();
                                    $arrayData['plan_url'] = $plan->getPlanUrl();
                                    $arrayData['image_url'] = $plan->getImageUrl();
                                    if (!array_key_exists('reference_id', $response)) {
                                        $response['reference_id'] = Mage::getModel('md_membership/subscribers')->getReservedIncrementId();
                                    }
                                    $response['plan_data'] = serialize($arrayData);
                                    $subscribers = Mage::getModel('md_membership/subscribers')
                                            ->setData($response);
                                    $subscribers->save();

                                    /*Mage::helper('bytescustomer')->checkCustomer($customer->getId(),$subscribers->getPlanId(),$stateCategoryId);*/

                                    Mage::helper('md_membership')->sendNewSubscriptionEmail($subscribers);
                                    Mage::getSingleton('core/session')->addSuccess(Mage::helper('md_membership')->__('You are subscribed for membership plan \'%s\'', $response['plan_title']));
                                    $result['redirect_url'] = Mage::getUrl('success');
                                } else {
                                    $result['error'] = $response['error'];
                                }
                            } else {
                                $result = $payment->callSetExpressCheckoutMethod();
                            }
                        }
                    endif;
                endif;

                $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
            }
        }
    }

}