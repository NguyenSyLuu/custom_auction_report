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

namespace Magestore\Auction\Block\Adminhtml\Transaction;

use Magestore\Auction\Model\Auction;
use Magestore\Auction\Model\Status;

/**
 * Auction grid.
 * @category Magestore
 * @package  Magestore_Auction
 * @module   Auction
 * @author   Magestore Developer
 */
class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * auction collection factory.
     *
     * @var \Magestore\Auction\Model\ResourceModel\Bid\CollectionFactory
     */
    protected $_bidCollectionFactory;

    /**
     * [__construct description].
     *
     * @param \Magento\Backend\Block\Template\Context                         $context
     * @param \Magento\Backend\Helper\Data                                    $backendHelper
     * @param \Magestore\Auction\Model\ResourceModel\Auction\CollectionFactory $auctionCollectionFactory
     * @param \Magestore\Auction\Helper\Data                             $auctionHelper
     * @param array                                                           $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magestore\Auction\Model\ResourceModel\Bid\CollectionFactory $bidCollectionFactory,
        array $data = []
    ) {
        $this->_bidCollectionFactory = $bidCollectionFactory;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * [_construct description].
     *
     * @return [type] [description]
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('auctionGrid');
        $this->setDefaultSort('auction_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection.
     *
     * @return [type] [description]
     */
    protected function _prepareCollection()
    {
        $collection = $this->_bidCollectionFactory->create()
        ->getTransaction();
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * @return $this
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'auction_id',
            [
                'header' => __('Auction ID'),
                'type' => 'number',
                'index' => 'auction_id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id',
            ]
        );
        $this->addColumn(
            'name',
            [
                'header' => __('Auction Name'),
                'index' => 'name',
                'class' => 'xxx',
                'width' => '50px',
                'renderer' => 'Magestore\Auction\Block\Adminhtml\Transaction\Renderer\Name'
            ]
        );
        $this->addColumn(
            'order_id',
            [
                'header' => __('Order Id'),
                'index' => 'order_number',
                'class' => 'xxx',
                'width' => '50px',
                'renderer' => 'Magestore\Auction\Block\Adminhtml\Transaction\Renderer\Oder'
            ]
        );
        $this->addColumn(
            'price',
            [
                'header' => __('Transaction Price'),
                'index' => 'price',
                'class' => 'xxx',
                'width' => '50px',
                'type'  => 'price',
                'currency_code' => $this->_storeManager->getStore()->getBaseCurrencyCode()
            ]
        );
        $this->addColumn(
            'created_time',
            [
                'header' => __('Created Time'),
                'index' => 'created_at',
                'class' => 'xxx',
                'width' => '50px',
                'type'  => 'datetime'
            ]
        );

//        $this->addColumn(
//            'status',
//            [
//                'header' => __('Status'),
//                'index' => 'status',
//                'type' => 'options',
//                'options' => Auction::getAvailableStatuses(),
//            ]
//        );

//        $this->addColumn(
//            'action',
//            [
//                'header' => __('Action'),
//                'type' => 'action',
//                'getter' => 'getId',
//                'actions' => [
//                    [
//                        'caption' => __('Action'),
//                        'url' => [
//                            'base' => '*/*/viewtransaction',
//                        ],
//                        'field' => 'bid_id',
//                    ],
//                ],
//                'filter' => false,
//                'sortable' => false,
//                'index' => 'stores',
//                'header_css_class' => 'col-action',
//                'column_css_class' => 'col-action',
//            ]
//        );
        $this->addExportType('*/*/exportCsv', __('CSV'));
        $this->addExportType('*/*/exportXml', __('XML'));
        $this->addExportType('*/*/exportExcel', __('Excel'));

        return parent::_prepareColumns();
    }

    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current' => true));
    }

    /**
     * get row url
     * @param  object $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl(
            '*/*/viewtransaction',
            array('auction_id' => $row->getBidId())
        );
    }
}
