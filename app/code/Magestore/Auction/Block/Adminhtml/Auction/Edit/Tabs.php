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

namespace Magestore\Auction\Block\Adminhtml\Auction\Edit;
use \Magestore\Auction\Model\Auction;
/**
 * auction Tabs.
 * @category Magestore
 * @package  Magestore_Auction
 * @module   Auction
 * @author   Magestore Developer
 */
class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    /**
     * @var \Magestore\Auction\Model\AuctionFactory
     */
    protected $_autionFactory;
    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Json\EncoderInterface $jsonEncoder
     * @param \Magento\Backend\Model\Auth\Session $authSession
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\Backend\Model\Auth\Session $authSession,
        \Magestore\Auction\Model\AuctionFactory $auctionFactory,
        array $data = []
    ) {
        parent::__construct($context,$jsonEncoder,$authSession, $data);
        $this->_autionFactory = $auctionFactory;
    }

    /**
     * construct.
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('auction_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Auction Information'));
    }



    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if (!$this->getRequest()->getParam('auction_id')) {
            $this->_activeTab = 'product_section';
            $productBlock = $this->getChildBlock('auction_edit_tab_product');
            $this->addTab(
                'product_section',
                [
                    'label'   => 'Product',
                    'title'   => 'Product',
                    'content' => $this->getChildHtml('auction_edit_tab_product').
                        $this->getChildBlock('auction_edit_tabs.serilaze')->setGridBlock($productBlock)->toHtml(),
                ],
                'product_section'
            );
        }

        $this->addTab(
            'main_section',
            [
                'label'   => 'Information',
                'title'   => 'Information',
                'content' => $this->getChildBlock('auction_edit_tab_auction')->toHtml(),
            ],
            'main_section'
        );
        if ($this->getRequest()->getParam('auction_id')) {
            $this->addTab(
                'watchlist_section',
                [
                    'label'   => 'Watch List',
                    'title'   => 'Watch List',
                    'content' => $this->getChildHtml('auction_edit_tab_watchlist')
                ],
                'watchlist_section'
            );
            $this->addTab(
                'bids_section',
                [
                    'label'   => 'Bid List',
                    'title'   => 'Bid List',
                    'content' => $this->getChildHtml('auction_edit_tab_bid')
                ],
                'bids_section'
            );
            $this->addTab(
                'autobids_section',
                [
                    'label'   => 'Autobid List',
                    'title'   => 'Autobid List',
                    'content' => $this->getChildHtml('auction_edit_tab_autobid')
                ],
                'autobids_section'
            );
            $aution = $this->_autionFactory->create()->load($this->getRequest()->getParam('auction_id'));
            if(in_array($aution->getStatus(),[Auction::AUCTION_STATUS_FINISHED_AND_WAIT_FOR_WINNER_BUY,Auction::AUCTION_STATUS_CLOSED])){
                $this->addTab(
                    'winner_section',
                    [
                        'label'   => 'Winner(s)',
                        'title'   => 'Winner(s)',
                        'content' => $this->getChildHtml('auction_edit_tab_winners')
                    ],
                    'winner_section'
                );
            }
        }
        return $this;
    }
}
