<?php

namespace Magestore\Auction\Block\Adminhtml\System\Config;

/**
 * Grid Container Brand
 */
class Implementcode extends \Magento\Config\Block\System\Config\Form\Field
{
    /**
     * {@inheritdoc}
     */
    public function returnlayout()
    {
        return '&nbsp;&lt;block name="featured_product" class="Magestore\Auction\Block\FeaturedList" template="Magestore_Auction::features.phtml"/&gt<br/>';
    }

    public function returnblock()
    {
        return '&nbsp;&nbsp{{block class="Magestore\Auction\Block\FeaturedList" template="Magestore_Auction::features.phtml"}}<br>';
    }

    public function returntext()
    {
        return 'Besides the Brand Listing page, you can show the Featured Auctions box in other places by using the following options (recommended for developers)';
    }

    public function returntemplate()
    {
        return "&nbsp;&nbsp;\$this->getLayout()->createBlock('Magestore\Auction\Block\FeaturedList')->setTemplate('Magestore_Auction::features.phtml')<br/>&nbsp;&nbsp;->tohtml();";
    }

    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $layout = $this->returnlayout();
        $block = $this->returnblock();
        $text = $this->returntext();
        $template = $this->returntemplate();

        return '
        <!-- <div class="entry-edit-head collapseable"><a onclick="Fieldset.toggleCollapse(\'auction_template\'); return false;" href="#" id="auction_template-head" class="open">Implement Code</a></div> -->
        <input id="auction_template-state" type="hidden" value="1" name="config_state[auction_template]">
        <fieldset id="auction_template" class="config collapseable" style="">
            <div id="messages" class="div-mess-auction">
                <ul class="messages mess-megamennu">
                    <li class="notice-msg notice-auction">
                        <ul>
                            <li>
                            ' . $text . '
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
            <br/>
            <div id="messages" class="div-mess-auction">
                <ul class="messages mess-megamennu">
                    <li class="notice-msg notice-auction">
                        <ul>
                            <li>
                            ' . __('Option 1: Add the code below to a CMS Page or a Static Block') . '
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
                <ul>
                    <li>
                        <code>
                        ' . $block . '
                        </code>
                    </li>
                </ul>
            <br/>
            <div id="messages" class="div-mess-auction">
               <ul class="messages mess-megamennu">
                    <li class="notice-msg notice-auction">
                        <ul>
                            <li>
                            ' . __('Option 2: Add the code below to a template file') . '
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
            <ul>
                <li>
                    <code>
                    &lt;?php echo' . $template . ' ?&gt;
                    </code>
                </li>
            </ul>
            <br/>
            <div id="messages" class="div-mess-auction">
                <ul class="messages mess-megamennu">
                    <li class="notice-msg notice-auction">
                        <ul>
                            <li>
                            ' . __('Option 3: Add the code below to a layout file') . '
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
            <ul>
                <li>
                    <code>
                    ' . $layout . '
                    </code>
                </li>
            </ul>
        </fieldset>';
    }
}
