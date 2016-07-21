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

class Product extends \Magento\Backend\Block\Widget\Grid\Extended implements TabInterface
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
        $this->setId('auction_products');
        $this->setDefaultSort('entity_id');
        $this->setUseAjax(true);
    }

    protected function _prepareCollection() {
        $collection = $this->_productFactory
            ->create()
            ->getCollection()
            ->addAttributeToSelect('*');
        $collection->addFieldToFilter('type_id', ['nin'=>['grouped', 'bundle']]);
        $collection->addFieldToFilter('visibility', ['nin'=>[1]]);
        $productIds = $this->_auction->getProductAuctionIds();
        if (count($productIds)) {
            $collection->addFieldToFilter('entity_id', array('nin' => $productIds));
        }
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {

        $this->addColumn('in_products', array(
            'header_css_class' => 'a-center',
            'type' => 'radio',
            'html_name' => 'aproducts[]',
            'align' => 'center',
            'index' => 'entity_id',
        ));


        $this->addColumn('entity_id', array(
            'header' => __('ID'),
            'sortable' => true,
            'width' => '60px',
            'index' => 'entity_id'
        ));
        $this->addColumn('name', array(
            'header' => __('Name'),
            'index' => 'name'
        ));

        $sets = $this->_setsFactory->create()->setEntityTypeFilter(
            $this->_productFactory->create()->getResource()->getTypeId()
        )->load()->toOptionHash();
        $this->addColumn(
            'set_name',
            [
                'header'           => __('Attribute Set'),
                'index'            => 'attribute_set_id',
                'type'             => 'options',
                'options'          => $sets,
                'header_css_class' => 'col-attr-name',
                'column_css_class' => 'col-attr-name',
            ]
        );

        $this->addColumn('status', array(
            'header' => __('Status'),
            'width' => '90px',
            'index' => 'status',
            'type' => 'options',
            'options' => $this->_status->getOptionArray(),
        ));

        $this->addColumn('visibility', array(
            'header' => __('Visibility'),
            'width' => '90px',
            'index' => 'visibility',
            'type' => 'options',
            'options' => $this->_visibility->getOptionArray(),
        ));

        $this->addColumn('sku', array(
            'header' => __('SKU'),
            'width' => '80px',
            'index' => 'sku'
        ));
        $this->addColumn('price', array(
            'header' => __('Price'),
            'type' => 'currency',
            'currency_code' => (string)$this->_scopeConfig->getValue(
                \Magento\Directory\Model\Currency::XML_PATH_CURRENCY_BASE,
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            ),
            'index' => 'price'
        ));

        return parent::_prepareColumns();
    }


    public function getSelectedRelatedProducts() {
        return [];
    }

    public function getRowUrl($row) {
        return $this->getUrl('adminhtml/catalog_product/edit', array('id' => $row->getId()));
    }

    public function getGridUrl() {
        return $this->getData('grid_url') ? $this->getData('grid_url') : $this->getUrl('*/*/productsGrid', array('_current' => true));
    }

    public function getAuction() {
        return $this->_auction->load($this->getRequest()->getParam('id'))
            ;
    }

    /*=======Required methods=========*/
    public function getTabLabel()
    {
        return __('Products');
    }

    public function getTabTitle()
    {
        return __('Products');
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
