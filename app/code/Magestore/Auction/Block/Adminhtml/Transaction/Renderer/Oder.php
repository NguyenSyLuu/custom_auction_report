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

namespace Magestore\Auction\Block\Adminhtml\Transaction\Renderer;

/**
 * Image renderer.
 * @category Magestore
 * @package  Magestore_Auction
 * @module   Auction
 * @author   Magestore Developer
 */
class Oder extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{
    /**
     * @param \Magento\Framework\DataObject $row
     * @return string
     */
    public function render(\Magento\Framework\DataObject $row){
        return sprintf('
			<a href="%s" title="%s">%s</a>',
            $this->getUrl('sales/order/view/', array('_current'=>true, 'order_id' => $row->getOrderId())),
            __('View Order Detail'),
            $row->getOrderNumber()
        );
    }
}
