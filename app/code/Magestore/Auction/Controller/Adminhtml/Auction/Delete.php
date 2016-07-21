<?php

/**
 * Magestore
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Magestore.com license that is
 * available through the world-wide-web at this URL:
 * http://www.magestore.com/license-agreement.html
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Magestore
 * @package     Magestore_Auction
 * @copyright   Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license     http://www.magestore.com/license-agreement.html
 */

namespace Magestore\Auction\Controller\Adminhtml\Auction;
use \Magestore\Auction\Model\Auction;
/**
 *
 *
 * @category Magestore
 * @package  Magestore_Auction
 * @module   Pdfinvoiceplus
 * @author   Magestore Developer
 */
class Delete extends \Magestore\Auction\Controller\Adminhtml\Auction
{
    /**
     * Delete action.
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $id = $this->getRequest()->getParam('auction_id');
        if ($id) {
            try {
                /** @var \Magestore\Auction\Model\Auction $model */
                $model = $this->_objectManager->create('Magestore\Auction\Model\Auction');
                $model->load($id);
                if(in_array($model->getStatus(),[Auction::AUCTION_STATUS_PROCESSING,Auction::AUCTION_STATUS_FINISHED_AND_WAIT_FOR_WINNER_BUY])){
                    $this->messageManager->addError(__('You are unable to delete a %1 auction.',$model->getStatusLabel()));
                    return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
                }else {
                    $model->delete();
                    $this->messageManager->addSuccess(__('You deleted the item.'));
                    return $resultRedirect->setPath('*/*/');
                }
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
            }
        }
        $this->messageManager->addError(__('We can\'t find a item to delete.'));
        return $resultRedirect->setPath('*/*/');
    }
}
