<?php

namespace Magestore\Auction\Controller\Adminhtml\Auction;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Controller\ResultFactory;

/**
 * Action ExportXml
 */
class ExportXml extends \Magestore\Auction\Controller\Adminhtml\Auction
{
    /**
     * Execute action
     */
    public function execute()
    {
        $fileName = 'Auctions.xml';

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $content = $resultPage->getLayout()
            ->createBlock('Magestore\Auction\Block\Adminhtml\Auction\Export')->getXml();

        /** @var \Magento\Framework\App\Response\Http\FileFactory $fileFactory */
        $fileFactory = $this->_objectManager->get('Magento\Framework\App\Response\Http\FileFactory');

        return $fileFactory->create($fileName, $content, DirectoryList::VAR_DIR);
    }
}
