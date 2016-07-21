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

namespace Magestore\Auction\Block\Product;

/**
 *
 *
 * @category Magestore
 * @package  Magestore_Auction
 * @module   Pdfinvoiceplus
 * @author   Magestore Developer
 */
class ListProduct extends \Magento\Catalog\Block\Product\ListProduct
{
    /**
     * Retrieve loaded category collection
     *
     * @return \Magento\Eav\Model\Entity\Collection\AbstractCollection
     */
    protected function _getProductCollection()
    {
        $productColection = parent::_getProductCollection();
        /** @var \Magestore\Auction\Model\Auction $auction */
        $auction = $this->_coreRegistry->registry('current_auction');
        if (is_array($auction)) {
            $productColection->addAttributeToFilter('entity_id', ['in' => array_keys($auction)]);
            $productColection->getSize();
        }

        return $productColection;
    }
}