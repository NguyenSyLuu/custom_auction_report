<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Product in category grid
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
namespace Magestore\Auction\Block\Adminhtml\Auction\Edit\Tab;

use Magento\Backend\Block\Widget\Grid;
use Magento\Backend\Block\Widget\Grid\Column;
use Magento\Store\Model\Store;
use Magento\Backend\Block\Widget\Tab\TabInterface;

class Autobid extends \Magento\Backend\Block\Widget\Grid\Extended implements TabInterface
{
    /**
     * @var \Magestore\Auction\Model\AutobidFactory
     */
    protected $_autobidFactory;
    /**
     * @var \Magestore\Auction\Model\AuctionFactory
     */
    protected $_auctionFactory;

    /**
     * Bid constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Magestore\Auction\Model\BidFactory $autobidFactory
     * @param \Magestore\Auction\Model\AuctionFactory $_auctionFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magestore\Auction\Model\AutobidFactory $autobidFactory,
        \Magestore\Auction\Model\AuctionFactory $_auctionFactory,
        array $data = []
    ) {
        $this->_autobidFactory = $autobidFactory;
        $this->_auctionFactory = $_auctionFactory;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('auction_autobid');
        $this->setDefaultSort('autobid_id');
        $this->setUseAjax(true);
    }

    protected function _prepareCollection() {
        $collection = $this->_autobidFactory
            ->create()
            ->getCollection();
//        $auction = $this->getAuction();
        $collection->addFieldToFilter('auction_id', $this->getRequest()->getParam('auction_id'))->joinBidderName();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {
        $this->addColumn('autobid_id', array(
            'header' => __('ID'),
            'sortable' => true,
            'width' => '60px',
            'index' => 'autobid_id'
        ));
        $this->addColumn('bidder_name', array(
            'header' => __('Bidder Name'),
            'index' => 'bidder_name'
        ));

        $this->addColumn('price', array(
            'header' => __('Price'),
            'index' => 'price'
        ));

        $this->addColumn('created_time', array(
            'header' => __('Created Time'),
            'index' => 'created_time'
        ));

        $this->addColumn('main_table.status', array(
            'header' => __('Status'),
            'width' => '90px',
            'index' => 'status',
            'type' => 'options',
            'options' => $this->_autobidFactory->create()->getStatusArray(),
        ));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row) {
        return null;//$this->getUrl('adminhtml/customer_index/edit', array('id' => $row->getId()));
    }

    public function getGridUrl() {
        return $this->getData('grid_url') ? $this->getData('grid_url') : $this->getUrl('*/*/autobidGrid', array('_current' => true));
    }

    public function getAuction() {
        return $this->_autobidFactory->create()->load($this->getRequest()->getParam('auction_id'))
            ;
    }

    /*=======Required methods=========*/
    public function getTabLabel()
    {
        return __('Autobid');
    }

    public function getTabTitle()
    {
        return __('Autobid');
    }

    public function canShowTab()
    {
        return true;
    }

    public function isHidden()
    {
        return false;
    }
}
