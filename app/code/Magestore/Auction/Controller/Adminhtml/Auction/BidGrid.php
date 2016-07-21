<?php

namespace Magestore\Auction\Controller\Adminhtml\Auction;

use Magento\Framework\Controller\ResultFactory;

/**
 * Action Save
 */
class BidGrid extends \Magestore\Auction\Controller\Adminhtml\Auction
{
    /**
     * Execute action
     */
    public function execute()
    {
        $resultLayout = $this->resultFactory->create(ResultFactory::TYPE_LAYOUT);
        $resultLayout->getLayout()->getBlock('auction.edit.tab.bid');

        return $resultLayout;
    }
}

