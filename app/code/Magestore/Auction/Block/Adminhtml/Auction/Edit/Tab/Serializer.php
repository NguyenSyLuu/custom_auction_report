<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magestore\Auction\Block\Adminhtml\Auction\Edit\Tab;

/**
 * @method string|array getInputNames()
 *
 * @author     Magento Core Team <core@magentocommerce.com>
 */
class Serializer extends \Magento\Backend\Block\Widget\Grid\Serializer
{
    /**
     * @var \Magento\Backend\Model\UrlInterface
     */
    protected $_backendUrl;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\Json\EncoderInterface $jsonEncoder
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\Backend\Model\UrlInterface $backendUrl,
        array $data = []
    ) {
        $this->_backendUrl = $backendUrl;
        parent::__construct($context,$jsonEncoder, $data);
    }

    public function _construct()
    {
        parent::_construct();
        $this->setUseAjax(true);
        $this->setTemplate('Magestore_Auction::serializer.phtml');
    }
    public function getChangeProductUrl()
    {
        return $this->_backendUrl->getUrl('*/*/changeProduct', ['_current' => true]);
    }

}