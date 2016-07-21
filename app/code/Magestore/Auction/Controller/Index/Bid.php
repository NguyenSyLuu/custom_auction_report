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

/**
 *
 *
 * @category Magestore
 * @package  Magestore_Pdfinvoiceplus
 * @module   Pdfinvoiceplus
 * @author   Magestore Developer
 */
class Bid extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magestore\Auction\Model\AuctionFactory
     */
    protected $_auctionFactory;
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $_resultJsonFactory;

    /**
     * @var \Magestore\Auction\Model\BidderFactory
     */
    protected $_bidderFactory;
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
        \Magestore\Auction\Model\BidderFactory $bidderFactory
    ) {
        parent::__construct($context);
        $this->_auctionFactory = $auctionFactory;
        $this->_resultJsonFactory = $resultJsonFactory;
        $this->_bidderFactory = $bidderFactory;
    }

    /**
     * Execute action
     * @var \Magestore\Auction\Model\Auction $auction
     */
    public function execute()
    {
        $result = $this->_resultJsonFactory->create();
        /*
         * check is loged in
         */
        if (!$this->_bidderFactory->create()->isLoggedIn()) {
            $result->setData(['error'=>__('Please login to bid!')]);
            return $result;
        }
        /*
         * check customer created autoBid name
         */
        $bidder = $this->_bidderFactory->create()->getCurrentBidder();
        if (!$bidder->getBidderName()) {
            $result->setData(['error'=>__('Please enter your bidder name before bid!')]);
            return $result;
        }

        /*
         * check the auction exist
         */
        $auction = $this->_auctionFactory->create()->load($this->getRequest()->getParam('auction_id'));
        if(!$auction->getId()){
            $result->setData(['error'=>__('The auction is not exist!')]);
            return $result;
        }


        /* check the last customer bid */
        if($auction->getCustomerIdOfLastBid() == $bidder->getCustomerId()){
            $result->setData(['error'=>__('You are the hightest bid!')]);
            return $result;
        }

        /*
         * check the auction is in proccess
         * @var \Magestore\Auction\Model\Auction $auction
         */
        $auction->updateStatus();
        if($auction->getStatus()!=Auction::AUCTION_STATUS_PROCESSING){
            $result->setData(['error'=>__('The auction is not in proccess!')]);
            return $result;
        }
        $price = $this->getRequest()->getParam('price');
        /*
         * check the price is number
         */
        if(!is_numeric($price)){
            $result->setData(['error' => __('The price is not numberic.')]);
            return $result;
        }

        /*
         * check the price is number
         */
        if($price>=100000000||$price<=0){
            $result->setData(['error' => __('The price is invalid.')]);
            return $result;
        }

        /*
         * check the price is larger Min next price
         */
        if($price < $auction->getMinNextPrice()){
            $result->setData(['error' => __('The bid need greater than %1.', $auction->getMinNextPriceText())]);
            return $result;
        }

        /*
         * check the price is less Min next price
         */
        if($auction->getMaxNextPrice()!=null && $price > $auction->getMaxNextPrice()){
            $result->setData(['error' => __('The bid need less than %1.', $auction->getMaxNextPriceText())]);
            return $result;
        }
        /*
         * create aution bid
         */
        $bid = $auction->createNewBid($bidder,$price);
        /*
         * check the bid created successfully
         */
        if($bid->getId()!=$auction->getLastBid()->getId()){
            $result->setData(['error' => __('You took a bid with %1 plus. However, another customer had already taken an auto-bid which is greater than your bid.',$bid->getPriceText()),
                'current_price' => $auction->getCurrentPriceText(),
                'min_next_price' => $auction->getMinNextPrice(),
                'current_bidder_name' => $auction->getCurrentBidderName(),
                'total_bid' => __('%1 bids', $auction->getTotalBids()),
                'time_left' => $auction->getTimeLeft()
            ]);
            return $result;
        }
        /*
         * bid successfully
         */
        $result->setData(['success' => __('You have created a successful bid with price is %1',$auction->getCurrentPriceText()),
            'current_price' => $auction->getCurrentPriceText(),
            'min_next_price' => $auction->getMinNextPrice(),
            'current_bidder_name' => $auction->getCurrentBidderName(),
            'total_bid' => __('%1 bids', $auction->getTotalBids()),
            'time_left' => $auction->getTimeLeft(),
            'suggess' => $this->getSuggess($auction)
        ]);
        return $result;
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