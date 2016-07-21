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
class MassDelete extends \Magestore\Auction\Controller\Adminhtml\Auction
{

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
        $auctionIds = $this->getRequest()->getParam('auction');
        if (!is_array($auctionIds) || empty($auctionIds)) {
            $this->messageManager->addError(__('Please select auction(s).'));
        } else {
            $auctionCollection = $this->_auctionFactory->create()->getCollection()
                ->addFieldToFilter('auction_id', ['in' => $auctionIds]);
            try {
                $i = 0;
                foreach ($auctionCollection as $aution) {
                    if(!in_array($aution->getStatus(),[Auction::AUCTION_STATUS_PROCESSING,Auction::AUCTION_STATUS_FINISHED_AND_WAIT_FOR_WINNER_BUY])) {
                        $aution->delete();
                        $i++;
                    }
                }
                if(count($auctionIds)-$i>0) {
                    $this->messageManager->addError(
                        __('A total of %1 "processing" or "wait to buy" auction(s) have not been deleted.', count($auctionIds) - $i)
                    );
                }
                $this->messageManager->addSuccess(
                    __('A total of %1 auction(s) have been deleted.', $i)
                );

            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }
        $resultRedirect = $this->resultRedirectFactory->create();

        return $resultRedirect->setPath('*/*/');
    }
}
