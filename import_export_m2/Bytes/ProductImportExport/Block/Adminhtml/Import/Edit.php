<?php
namespace Bytes\ProductImportExport\Block\Adminhtml\Import;
 
class Edit extends \Magento\Backend\Block\Widget\Form\Container
{ 
    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }
 
    /**
     * Initialize staff grid edit block
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId = 'entity_id';
        $this->_blockGroup = 'Bytes_ProductImportExport';
        $this->_controller = 'adminhtml_import';
 
        parent::_construct();
        $this->buttonList->remove('delete');
        $this->buttonList->remove('reset');
    }
 
    /**
     * Retrieve text for header element depending on loaded blocklist
     *
     * @return \Magento\Framework\Phrase
     */
    public function getHeaderText()
    { 
        return __('Import Product');
    }
 
    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }
 
    /**
     * Getter of url for "Save and Continue" button
     * tab_id will be replaced by desired by JS later
     *
     * @return string
     */
    protected function _getSaveAndContinueUrl()
    {
        return $this->getUrl('btimportexport/import/save', ['_current' => true, 'back' => 'edit', 'active_tab' => '']);
    }
}