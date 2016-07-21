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

/**
 *
 *
 * @category Magestore
 * @package  Magestore_Auction
 * @module   Pdfinvoiceplus
 * @author   Magestore Developer
 */
class MassStatus extends \Magestore\Auction\Controller\Adminhtml\Auction
{

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
        $auctionIds = $this->getRequest()->getParam('auction');
        $status = $this->getRequest()->getParam('status');
        $storeViewId = $this->getRequest()->getParam('store');
        if (!is_array($auctionIds) || empty($auctionIds)) {
            $this->messageManager->addError(__('Please select auction(s).'));
        } else {
            $auctionCollection = $this->_auctionFactory->create()->getCollection()
                ->addFieldToFilter('auction_id', ['in' => $auctionIds]);
            try {
                foreach ($auctionCollection as $aution) {
                    $aution->setStoreViewId($storeViewId)
                        ->setStatus($status)
                        ->updateStatus()
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->messageManager->addSuccess(
                    __('A total of %1 record(s) have been updated.', count($auctionIds))
                );
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }
        $resultRedirect = $this->resultRedirectFactory->create();

        return $resultRedirect->setPath('*/*/');
    }
}
