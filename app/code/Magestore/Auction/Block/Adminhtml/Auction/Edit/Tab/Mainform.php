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

namespace Magestore\Auction\Block\Adminhtml\Auction\Edit\Tab;

use Magestore\Auction\Model\Auction;
use Magestore\Auction\Model\Status;

/**
 * Auction Edit tab.
 * @category Magestore
 * @package  Magestore_Auction
 * @module   Auction
 * @author   Magestore Developer
 */
class Mainform extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * @var \Magento\Framework\DataObjectFactory
     */
    protected $_objectFactory;

    /**
     * value collection factory.
     *
     * @var \Magestore\Auction\Model\ResourceModel\Value\CollectionFactory
     */
    protected $_valueCollectionFactory;

    /**
     * auction factory.
     *
     * @var \Magestore\Auction\Model\AuctionFactory
     */
    protected $_auctionFactory;

    /**
     * @var \Magestore\Auction\Model\Auction
     */
    protected $_auction;

    /**
     * stdlib timezone.
     *
     * @var \Magento\Framework\Stdlib\DateTime\Timezone
     */
    protected $_stdTimezone;
    /**
     * constructor.
     *
     * @param \Magento\Backend\Block\Template\Context                        $context
     * @param \Magento\Framework\Registry                                    $registry
     * @param \Magento\Framework\Data\FormFactory                            $formFactory
     * @param \Magento\Framework\DataObjectFactory                               $objectFactory
     * @param \Magestore\Auction\Model\Auction                           $auction
     * @param \Magestore\Auction\Model\ResourceModel\Value\CollectionFactory $valueCollectionFactory
     * @param \Magestore\Auction\Model\AuctionFactory                    $auctionFactory
     * @param array                                                          $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Framework\DataObjectFactory $objectFactory,
        \Magestore\Auction\Model\Auction $auction,
        \Magestore\Auction\Model\ResourceModel\Value\CollectionFactory $valueCollectionFactory,
        \Magestore\Auction\Model\AuctionFactory $auctionFactory,
        \Magento\Framework\Stdlib\DateTime\Timezone $timezone,
        array $data = []
    ) {
        $this->_objectFactory = $objectFactory;
        $this->_auction = $auction;
        $this->_valueCollectionFactory = $valueCollectionFactory;
        $this->_auctionFactory = $auctionFactory;
        $this->_stdTimezone = $timezone;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * prepare layout.
     *
     * @return $this
     */
    protected function _prepareLayout()
    {
        $this->getLayout()->getBlock('page.title')->setPageTitle($this->getPageTitle());

        \Magento\Framework\Data\Form::setFieldsetElementRenderer(
            $this->getLayout()->createBlock(
                'Magestore\Auction\Block\Adminhtml\Form\Renderer\Fieldset\Element',
                $this->getNameInLayout().'_fieldset_element'
            )
        );

        return $this;
    }

    /**
     * Prepare form.
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        $auctionAttributes = $this->_auction->getStoreAttributes();
        $auctionAttributesInStores = ['store_id' => ''];

        foreach ($auctionAttributes as $auctionAttribute) {
            $auctionAttributesInStores[$auctionAttribute.'_in_store'] = '';
        }

        $dataObj = $this->_objectFactory->create(
            ['data' => $auctionAttributesInStores]
        );
        $model = $this->_coreRegistry->registry('auction');

        if ($auctionId = $this->getRequest()->getParam('current_auction_id')) {
            $model->setAuctionId($auctionId);
        }

        $dataObj->addData($model->getData());

        $storeViewId = $this->getRequest()->getParam('store');

        $attributesInStore = $this->_valueCollectionFactory
            ->create()
            ->addFieldToFilter('auction_id', $model->getId())
            ->addFieldToFilter('store_id', $storeViewId)
            ->getColumnValues('attribute_code');

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix($this->_auction->getFormFieldHtmlIdPrefix());

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Auction Information')]);

        if ($model->getId()) {
            $fieldset->addField('auction_id', 'hidden', ['name' => 'auction_id']);
        }
        $fieldset->addField('product_id', 'hidden', ['name' => 'product_id','id'=>'product_id']);

        $elements = [];
        $elements['name'] = $fieldset->addField(
            'name',
            'text',
            [
                'name' => 'name',
                'label' => __('Name'),
                'title' => __('Name'),
                'required' => true,
                'note'   => '<a id="product_edit_link" target="_blank" href="'.$this->getUrl('catalog/product/edit', array('id' => $dataObj->getProductId())).'">'.__('View product information.').'</a>'
            ]
        );
        $elements['init_price'] = $fieldset->addField(
            'init_price',
            'text',
            [
                'name' => 'init_price',
                'label' => __('Init Price'),
                'title' => __('Init Price'),
                'required' => true,
                'disabled' => $model->isReadOnly(),
                'note'=>__('The price that a product is given at the beginning of an auction.')
            ]
        );
        $elements['reserved_price'] = $fieldset->addField(
            'reserved_price',
            'text',
            [
                'name' => 'reserved_price',
                'label' => __('Reserve Price'),
                'title' => __('Reserve Price'),
                'required' => true,
                'disabled' => $model->isReadOnly(),
                'note'=> __('If the Closing Price is lower than the Reserve Price, there are no winning bidders.')
            ]
        );
        $elements['min_interval_price'] = $fieldset->addField(
            'min_interval_price',
            'text',
            [
                'name' => 'min_interval_price',
                'label' => __('Minimum Bid Increment'),
                'title' => __('Minimum Bid Increment'),
                'required' => true,
                'disabled' => $model->isReadOnly(),
                'note' => __('The minimum amount that customer need to place higher than current bid.')
            ]
        );
        $elements['max_interval_price'] = $fieldset->addField(
            'max_interval_price',
            'text',
            [
                'name' => 'max_interval_price',
                'label' => __('Max Bid Increment'),
                'title' => __('Max Bid Increment'),
                'disabled' => $model->isReadOnly(),
                'required' => false,
            ]
        );
        $dateFormat = $this->_localeDate->getDateFormat(\IntlDateFormatter::SHORT);
        $timeFormat = $this->_localeDate->getTimeFormat(\IntlDateFormatter::SHORT);

        if($dataObj->hasData('start_time')) {
            $datetime = new \DateTime($dataObj->getData('start_time'));
            $dataObj->setData('start_time', $datetime->setTimezone(new \DateTimeZone($this->_localeDate->getConfigTimezone())));
        }
        if($dataObj->hasData('end_time')) {
            $datetime = new \DateTime($dataObj->getData('end_time'));
            $dataObj->setData('end_time', $datetime->setTimezone(new \DateTimeZone($this->_localeDate->getConfigTimezone())));
        }
        $now = $this->_stdTimezone->date()->format("Y-m-d H:i");
        $style = 'color: #000;background-color: #fff; font-weight: bold; font-size: 13px;';
        $elements['start_time'] = $fieldset->addField(
            'start_time',
            'date',
            [
                'name' => 'start_time',
                'label' => __('Starting time'),
                'title' => __('Starting time'),
                'required' => true,
                'readonly' => true,
                'style' => $style,
                'class' => 'required-entry',
                'date_format' => $dateFormat,
                'time_format' => $timeFormat,
                'disabled' => $model->isReadOnly(),
                'note' => __('Current time server is: %1',$now),
            ]
        );

        $elements['end_time'] = $fieldset->addField(
            'end_time',
            'date',
            [
                'name' => 'end_time',
                'label' => __('Ending time'),
                'title' => __('Ending time'),
                'required' => true,
                'readonly' => true,
                'style' => $style,
                'class' => 'required-entry',
                'date_format' => $dateFormat,
                'time_format' => $timeFormat,
                'disabled' => $model->isReadOnly(),
                'note' => __('Current time server is: %1',$now)
            ]
        );

        $elements['limit_time'] = $fieldset->addField(
            'limit_time',
            'text',
            [
                'name' => 'limit_time',
                'label' => __('Extended Time(second)'),
                'title' => __('Extended Time(second)'),
                'disabled' => $model->isReadOnly(),
                'required' => true,
            ]
        );
        $elements['multi_winner'] = $fieldset->addField(
            'multi_winner',
            'text',
            [
                'name' => 'multi_winner',
                'label' => __('Multiple Winner'),
                'title' => __('Multiple Winner'),
                'disabled' => $model->isReadOnly(),
                'required' => false,
                'note' => 'The number of customer(s) who bids the greatest price for the product sold.'
            ]
        );

        $fieldMaps['allow_buyout'] = $fieldset->addField(
            'allow_buyout',
            'select',
            [
                'label' => __('Sell auctioned product normally'),
                'name' => 'allow_buyout',
//                'disabled' => $model->isReadOnly(),
                'values' => [
                    [
                        'value' => Auction::ALLOW_BUY_OUT,
                        'label' => __('Yes'),
                    ],
                    [
                        'value' => Auction::NOT_ALLOW_BUY_OUT,
                        'label' => __('No'),
                    ],
                ],
                'onchange' => 'changeIsApplyAuction();',
                'note' => 'If Yes, all customers can buy a product at actual price without auctioning.'
            ]
        );
        $elements['day_to_buy'] = $fieldset->addField(
            'day_to_buy',
            'text',
            [
                'name' => 'day_to_buy',
                'label' => __('Sell normally after'),
                'title' => __('Sell normally after'),
                'required' => false,
                'note' => 'A given time period of day that the winner(s) can buy a product. After this time, the option may no longer be applied & other customers can buy this product.'
            ]
        );

        $fieldMaps['featured'] = $fieldset->addField(
            'featured',
            'select',
            [
                'label' => __('Featured'),
                'name' => 'featured',
                'values' => [
                    [
                        'value' => Auction::IS_FEATURE,
                        'label' => __('Yes'),
                    ],
                    [
                        'value' => Auction::NOT_IS_FEATURE,
                        'label' => __('No'),
                    ],
                ],
            ]
        );

        $elements['status'] = $fieldset->addField(
            'status',
            'select',
            [
                'label' => __('Status'),
                'title' => __('Auction Status'),
                'name' => 'status',
                'options' => $model->getEditAbleStatuses(),
            ]
        );
        foreach ($attributesInStore as $attribute) {
            if (isset($elements[$attribute])) {
                $elements[$attribute]->setStoreViewId($storeViewId);
            }
        }
        $form->addValues($dataObj->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * @return mixed
     */
    public function getAuction()
    {
        return $this->_coreRegistry->registry('auction');
    }

    /**
     * @return \Magento\Framework\Phrase
     */
    public function getPageTitle()
    {
        return $this->getAuction()->getId()
            ? __("Edit Auction '%1'", $this->escapeHtml($this->getAuction()->getName())) : __('New Auction');
    }

    /**
     * Prepare label for tab.
     *
     * @return string
     */
    public function getTabLabel()
    {
        return __('Auction Information');
    }

    /**
     * Prepare title for tab.
     *
     * @return string
     */
    public function getTabTitle()
    {
        return __('Auction Information');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }
}
