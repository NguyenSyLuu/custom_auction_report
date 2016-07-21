<?php

namespace Magestore\Auction\Block\Adminhtml\Auction;
use Magestore\Auction\Model\Auction;

/**
 * Grid Grid
 */
class Export extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_auctionCollection = null;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magestore\Auction\Model\ResourceModel\Auction\Collection $collection,
        array $data = []
    ) {
        parent::__construct($context, $backendHelper, $data);
        $this->_auctionCollection = $collection;
    }

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('Grid');
        $this->setDefaultSort('auction_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * {@inheritdoc}
     */
    protected function _prepareCollection()
    {
        $this->setCollection($this->_auctionCollection);
        return parent::_prepareCollection();
    }

    /**
     * {@inheritdoc}
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
                'header' => __('Name'),
                'index' => 'name',
                'class' => 'xxx',
                'width' => '50px',
            ]
        );
        $this->addColumn(
            'start_time',
            [
                'header' => __('Start Time'),
                'index' => 'start_time',
                'class' => 'xxx',
                'width' => '50px',
                'type'  => 'datetime'
            ]
        );
        $this->addColumn(
            'end_time',
            [
                'header' => __('End Time'),
                'index' => 'end_time',
                'class' => 'xxx',
                'width' => '50px',
                'type'  => 'datetime'
            ]
        );
        $this->addColumn(
            'multi_winner',
            [
                'header' => __('Multiple Winner'),
                'index' => 'multi_winner',
                'class' => 'xxx',
                'width' => '50px',
            ]
        );

        $this->addColumn(
            'status',
            [
                'header' => __('Status'),
                'index' => 'status',
                'type' => 'options',
                'options' => Auction::getAvailableStatuses(),
            ]
        );
        return parent::_prepareColumns();
    }
}
