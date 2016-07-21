<?php

/**
 * Magestore.
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

namespace Magestore\Auction\Block;
use Magento\Customer\Model\Session;

/**
 * @category Magestore
 * @package  Magestore_Auction
 * @module   Auction
 * @author   Magestore Developer
 */
class Mybids extends \Magestore\Auction\Block\AbstractBlock
{
    /**
     * @var string
     */
    protected $_template = 'Magestore_Auction::mybids.phtml';
    /**
     * @var \Magestore\Auction\Model\AuctionFactory
     */
    protected $_auctionFactory;
    /**
     * @var Session
     */
    protected $session;

    /**
     * @var \Magestore\Auction\Model\Bidder
     */
    protected $_bidder;

    /**
     * @var \Magestore\Auction\Model\SystemConfig
     */
    protected $_config;
    /**
     * View constructor.
     *
     * @param Context $context
     * @param \Magestore\Auction\Model\BidFactory $bidFactory
     * @param Session $customerSession
     * @param array $data
     */
    public function __construct(
        \Magestore\Auction\Block\Context $context,
        \Magestore\Auction\Model\AuctionFactory $_auctionFactory,
        \Magestore\Auction\Model\Bidder $bidder,
        \Magestore\Auction\Model\SystemConfig $systemConfig,
        Session $customerSession,
        array $data = []
    ) {
        $this->session = $customerSession;
        $this->_auctionFactory = $_auctionFactory;
        $this->_bidder = $bidder;
        $this->_config = $systemConfig;
        parent::__construct($context, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->pageConfig->getTitle()->set(__('My Bids'));
    }

    /**
     * @return \Magestore\Auction\Model\Bidder
     */
    public function getCurrentBidder(){
        return $this->_bidder->getCurrentBidder();
    }

    /**
     * @return mixed
     */
    public function getBids(){
        $collection = $this->getCurrentBidder()->getBidsCollection()->setPageSize(10)->setCurPage(1)->addOrder('status')->addOrder('created_time');
        if($this->getRequest()->getParam('p')){
            $collection->setCurPage((int)$this->getRequest()->getParam('p'));
        }
        return $collection;
    }

    /**
     * @return $this
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if ($this->getBids()) {
            $pager = $this->getLayout()->createBlock(
                'Magento\Theme\Block\Html\Pager',
                'sales.order.history.pager'
            )
            ->setTemplate('Magestore_Auction::html/pager.phtml')
            ->setCollection(
                $this->getBids()
            );
            $this->setChild('pager', $pager);
            $this->getBids()->load();
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    /**
     * @param object $bid
     * @return string
     */
    public function getCancelBid($bid)
    {
        return $this->getUrl('auction/index/cancel', ['id' => $bid]);
    }

    /**
     * @return mixed
     */
    public function isCancelAble(){
        return $this->_config->cancelBid();
    }
    /**
     * @return string
     */
    public function getBackUrl()
    {
        return $this->getUrl('customer/account/');
    }
}