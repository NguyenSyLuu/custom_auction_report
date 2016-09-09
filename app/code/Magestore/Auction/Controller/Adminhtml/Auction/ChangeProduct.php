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

namespace Magestore\Auction\Controller\Adminhtml\Auction;

/**
 * Edit Auction action.
 * @category Magestore
 * @package  Magestore_Auction
 * @module   Auction
 * @author   Magestore Developer
 */
class ChangeProduct extends \Magestore\Auction\Controller\Adminhtml\Auction
{
    /**
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        $product_id = $this->getRequest()->getParam('product_id');
        if ($product_id) {
            $array = [];
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $product = $objectManager->get('Magento\Catalog\Model\Product')->load($product_id);
            $product = $product->load($product_id);
            $array['id'] = $product->getId();
            $array['name'] = $product->getName();
            $array['url'] = $this->getUrl('catalog/product/edit', array('id' => $product_id));
            $objectManager->get('Magento\Backend\Model\Session')->setChooseProduct($product_id);
        }
        $result = $this->getJsonFactory()->create();
        $result->setData($array);

        return $result;
    }
}
