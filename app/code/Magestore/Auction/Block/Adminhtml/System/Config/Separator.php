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
 * @package     Magestore_Bannerslider
 * @copyright   Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license     http://www.magestore.com/license-agreement.html
 */
namespace Magestore\Auction\Block\Adminhtml\System\Config;

/**
 * Image renderer.
 * @category Magestore
 * @package  Magestore_Shopbybrand
 * @module   Bannerslider
 * @author   Magestore Developer
 */
class Separator extends \Magento\Config\Block\System\Config\Form\Field
{
    public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $id = $element->getHtmlId();
        $html = '<tr id="row_' . $id . '">'
            . '<td class="label" colspan="3">';
        $marginTop = $element->getComment() ? $element->getComment() : '0px';
        $html .= '<div style="margin-top: ' . $marginTop
            . '; font-weight: bold; border-bottom: 1px solid #dfdfdf; text-align:left">';
        $html .= $element->getLabel();
        $html .= '</div></td></tr>';

        return $html;
    }
}