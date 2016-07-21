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
class Auction extends \Magestore\Auction\Block\AbstractBlock
{
    /**
     * @var string
     */
    protected $_template = 'Magestore_Auction::auctionbox.phtml';
    /**
     * @var \Magestore\Auction\Helper\Data
     */
    protected $_helper;
    /**
     * @var \Magestore\Auction\Model\BidderFactory
     */
    protected $_bidderFactory;
    /**
     * @var \Magestore\Auction\Model\AuctionFactory
     */
    protected $_auctionFactory;


    /**
     * @var Session
     */
    protected $session;

    /**
     * @var \Magestore\Auction\Model\SystemConfig
     */
    protected $_config;
    /**
     * View constructor.
     *
     * @param Context $context
     * @param \Magestore\Auction\Helper\Data $helper
     * @param Session $customerSession
     * @param array $data
     */
    public function __construct(
        \Magestore\Auction\Block\Context $context,
        \Magestore\Auction\Helper\Data $helper,
        \Magestore\Auction\Model\BidderFactory $bidFactory,
        \Magestore\Auction\Model\AuctionFactory $auctionFactory,
        \Magestore\Auction\Model\SystemConfig $config,
        Session $customerSession,
        array $data = []
    ) {
        $this->session = $customerSession;
        $this->_bidderFactory = $bidFactory;
        $this->_auctionFactory = $auctionFactory;
        $this->_helper = $helper;
        parent::__construct($context, $data);
        $this->_config = $config;
        $this->_isScopePrivate = false;
    }

    /**
     * @return \Magestore\Auction\Helper\Data
     */
    public function getHelper()
    {
        return $this->_helper;
    }

    public function getLoadedProductCollection()
    {
        return $this->_objectManager->get('Magento\Catalog\Block\Product\ListProduct')->getLoadedProductCollection();
    }


    /**
     * @return \Magestore\Auction\Model\Auction
     */
    public function getCurrentAuction(){
        if (!$this->hasData('current_auction')) {
            $auction = $this->_auctionFactory->create()->loadCurrentAuction();
            $this->setData('current_auction', $auction);
        }
        return $this->getData('current_auction');
    }



    /**
     * @return \Magestore\Auction\Model\Bidder
     */
    public function getCurrentBidder(){
        if (!$this->hasData('current_bidder')) {
            $auction = $this->_bidderFactory->create()->getCurrentBidder();
            $this->setData('current_bidder', $auction);
        }
        return $this->getData('current_bidder');
    }

    /**
     * @return mixed
     */
    public function getCurrentAuctionId(){
        return $this->getCurrentAuction()->getId();
    }

    /**
     * @return string
     */
    public function getViewBidUrl(){
        return $this->getUrl('auction/index/viewbids',array('auction_id'=>$this->getAuction()->getId()));
    }
    /**
     * @return bool
     */
    public function getAuctionLink()
    {
        $auction = $this->getAuction();
        if ($auction->getAuctionUrl()) {
            return $auction->getAuctionUrl();
        } else {
            return false;
        }
    }

    /**
     * @return bool
     */
    public function isLogedIn(){
        return $this->getCurrentBidder()->isLoggedIn();
    }
    /**
     * @return bool
     */
    public function hasBidName(){
        return !$this->getCurrentBidder()->getBidderName()=='';
    }

    /**
     * @return bool
     */
    public function getCurrentCustomer(){
        return $this->session->getCustomer();
    }
    /**
     * @return bool
     */
    public function getCurrentCustomerId(){
        return $this->session->getCustomerId();
    }

    /**
     * @return bool
     */
    public function enableBid(){
        return $this->isLogedIn()&&$this->hasBidName();
    }

    public function getPostBidderNameActionUrl(){
        return $this->getUrl('auction/index/postBidderName');
    }

    public function isWinner(){
        $auction = $this->getCurrentBidder()->getWonInfo($this->getAuction()->getProductId());
        return $auction->getId();
    }

    public function getWonMessage()
    {
        return $this->_config->wonMessage();
    }

    public function getUpdateTime(){
        return $this->_config->updateTime();
    }

    public function showPrice(){
        return $this->_config->showPrice();
    }
    public function autoBid(){
        return $this->_config->autobid();
    }

    /**
     * @param \Magestore\Auction\Model\Auction $auction
     * @return string
     */
    public function getSuggess($auction){
        if($auction->getTotalBids()){
            return $auction->getMaxNextPrice()?__('Your bid must be greater than %1 and less than %2',$auction->getMinNextPriceText(),$auction->getMaxNextPriceText()):__('Your bid must be greater than %1',$auction->getMinNextPriceText());
        }
        return $auction->getMaxNextPrice()?__('Your bid must be equal or greater than %1 and less than %2',$auction->getMinNextPriceText(),$auction->getMaxNextPriceText()):__('Your bid must be equal or greater than %1',$auction->getMinNextPriceText());;
    }
}