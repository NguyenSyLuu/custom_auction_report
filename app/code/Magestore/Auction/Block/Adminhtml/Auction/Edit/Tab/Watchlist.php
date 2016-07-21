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
use Magento\Backend\Block\Widget\Grid\Extended;
use Magento\Store\Model\Store;
use Magento\Backend\Block\Widget\Tab\TabInterface;

class Watchlist extends \Magento\Backend\Block\Widget\Grid\Extended implements TabInterface
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $_productFactory;
    protected $_customerFactory;
    protected $_auction;
    protected $moduleManager;
    protected $_auctionFactory;
    protected $_auctionProductsFactory;
    protected $_objectManager;
    protected $_status;
    protected $_visibility;
    protected $_setsFactory;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Magento\Catalog\Model\ProductFactory $productFactory
     * @param \Magento\Framework\Registry $coreRegistry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Magento\Framework\Registry $coreRegistry,
        \Magestore\Auction\Model\AuctionFactory $_auctionFactory,
        \Magestore\Auction\Model\Auction $_auction,
        \Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory $setsFactory,
        \Magento\Catalog\Model\Product\Attribute\Source\Status $status,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Catalog\Model\Product\Visibility $visibility,
        \Magento\Framework\Module\Manager $moduleManager,
        array $data = []
    ) {
        $this->_productFactory = $productFactory;
        $this->_customerFactory = $customerFactory;
        $this->_coreRegistry = $coreRegistry;
        $this->_auctionFactory = $_auctionFactory;
        $this->_objectManager = $objectManager;
        $this->_auction = $_auction;
        $this->_status = $status;
        $this->_visibility = $visibility;
        $this->_setsFactory = $setsFactory;
        $this->moduleManager = $moduleManager;
        $this->_objectManager = $objectManager;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('auction_watchlist');
        $this->setDefaultSort('entity_id');
        $this->setUseAjax(true);
    }

    protected function _prepareCollection() {
        $collection = $this->_customerFactory
            ->create()
            ->getCollection()
            ->addAttributeToSelect('*');
        $auction = $this->getAuction();
        $collection->addFieldToFilter('entity_id', ['in' =>  explode(',',$auction->getWatchList())]);
        $collection->getSelect()->joinLeft(['bidder' => $collection->getTable('magestore_auction_bidder')],
            'e.entity_id = bidder.customer_id',
            ['bidder_name' => 'bidder.bidder_name']);
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {
        $this->addColumn('entity_id', array(
            'header' => __('ID'),
            'sortable' => true,
            'width' => '60px',
            'index' => 'entity_id'
        ));
        $this->addColumn('firstname', array(
            'header' => __('First Name'),
            'index' => 'firstname'
        ));

        $this->addColumn('email', array(
            'header' => __('Email'),
            'index' => 'email'
        ));

        $this->addColumn('bidder_name', array(
            'header' => __('Bidder Name'),
            'index' => 'bidder_name'
        ));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row) {
        return $this->getUrl('customer/index/edit', array('id' => $row->getId()));
    }

    public function getGridUrl() {
        return $this->getData('grid_url') ? $this->getData('grid_url') : $this->getUrl('*/*/watchlistGrid', array('_current' => true));
    }

    public function getAuction() {
        return $this->_auction->load($this->getRequest()->getParam('auction_id'))
            ;
    }

    /*=======Required methods=========*/
    public function getTabLabel()
    {
        return __('Customer');
    }

    public function getTabTitle()
    {
        return __('Customer');
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
