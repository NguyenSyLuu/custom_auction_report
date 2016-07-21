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
 * @package     Magestore_Pdfinvoiceplus
 * @copyright   Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license     http://www.magestore.com/license-agreement.html
 */

namespace Magestore\Auction\Controller\Index;

use Magestore\Auction\Model\Auction;
use Magestore\Auction\Model\Bid;

/**
 *
 *
 * @category Magestore
 * @package  Magestore_Pdfinvoiceplus
 * @module   Pdfinvoiceplus
 * @author   Magestore Developer
 */
class Test extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magestore\Auction\Model\AuctionFactory
     */
    protected $_auctionFactory;
    /**
     * @var \Magestore\Auction\Model\AutobidFactory
     */
    protected $_autobidFactory;
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $_resultJsonFactory;

    /**
     * @var \Magestore\Auction\Model\BidderFactory
     */
    protected $_bidderFactory;

    /**
     * Execute action
     */
    protected $resultPageFactory;
    /**
     * @var Session
     */
    protected $_checkoutSession;

    /**
     * @var \Magestore\Auction\Model\BidderFactory
     */
    protected $_bidFactory;

    /**
     * @var \Magento\Customer\Model\CustomerFactory
     */
    protected $_customerFactory;
    /**
     * AutoBid constructor.
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magestore\Auction\Model\Auction $auctionFactory
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magestore\Auction\Model\AuctionFactory $auctionFactory,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magestore\Auction\Model\BidderFactory $bidderFactory,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Checkout\Model\Session $session,
        \Magestore\Auction\Model\BidFactory $bidFactory,
        \Magestore\Auction\Model\AutobidFactory $autobidFactory,
        \Magento\Customer\Model\CustomerFactory $customerFactory
    ) {
        parent::__construct($context);
        $this->_auctionFactory = $auctionFactory;
        $this->_bidFactory = $bidFactory;
        $this->_resultJsonFactory = $resultJsonFactory;
        $this->_bidderFactory = $bidderFactory;
        $this->resultPageFactory = $resultPageFactory;
        $this->_autobidFactory = $autobidFactory;
        $this->_checkoutSession = $session;
        $this->_customerFactory = $customerFactory;
    }

    /**
     * Execute action
     * @var \Magestore\Auction\Model\Auction $aution
     */
    public function execute()
    {


        exit;
        $resultPage = $this->resultPageFactory->create();
        return $resultPage;

    }
}