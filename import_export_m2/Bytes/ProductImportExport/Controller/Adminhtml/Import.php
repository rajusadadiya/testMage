<?php
namespace Bytes\ProductImportExport\Controller\Adminhtml;

abstract class Import extends \Bytes\ProductImportExport\Controller\Adminhtml\AbstractActionClass {

    const PARAM_CRUD_ID = 'entity_id';

    /**
     * Check if admin has permissions to visit related pages.
     *
     * @return bool
     */
    protected function _isAllowed() {
        return $this->_authorization->isAllowed('Bytes_ProductImportExport::import');
    }

}
