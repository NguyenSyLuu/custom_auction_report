<?php

namespace Magestore\Auction\Controller\Adminhtml\Auction;

use Magento\Framework\Controller\ResultFactory;

/**
 * Action Save
 */
class ProductsGrid extends \Magestore\Auction\Controller\Adminhtml\Auction
{
    /**
     * Execute action
     */
    public function execute()
    {
        $resultLayout = $this->resultFactory->create(ResultFactory::TYPE_LAYOUT);
        $resultLayout->getLayout()->getBlock('aucton.aucton.edit.tab.products');
        return $resultLayout;
    }
}

