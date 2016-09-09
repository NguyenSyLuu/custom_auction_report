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

use Magento\Framework\App\Filesystem\DirectoryList;

/**
 * Save Auction action.
 * @category Magestore
 * @package  Magestore_Auction
 * @module   Auction
 * @author   Magestore Developer
 */
class Save extends \Magestore\Auction\Controller\Adminhtml\Auction
{
    /**
     * @return $this|\Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($data = $this->getRequest()->getPostValue()) {
            $model = $this->_auctionFactory->create();
            $storeViewId = $this->getRequest()->getParam('store');

            if ($id = $this->getRequest()->getParam(static::PARAM_CRUD_ID)) {
                $model->load($id);
            }
            if(!isset($data['start_time'])){
                $data['start_time'] = date('Y-m-d H:i:s');
            }
            if(!isset($data['end_time'])){
                $data['end_time'] = date('Y-m-d H:i:s');
            }
            /** @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate */
            $localeDate = $this->_objectManager->get('Magento\Framework\Stdlib\DateTime\TimezoneInterface');
            $data['start_time'] = $localeDate->date($data['start_time'])->setTimezone(new \DateTimeZone('UTC'))->format('Y-m-d H:i');
            $data['end_time'] = $localeDate->date($data['end_time'])->setTimezone(new \DateTimeZone('UTC'))->format('Y-m-d H:i');

            if(!isset($data['multi_winner'])||$data['multi_winner']<1)
                $data['multi_winner']=1;
            if(isset($data['day_to_buy'])&&$data['day_to_buy']<1)
                $data['day_to_buy']=1;

            $data = $this->processData($data);

            if($data['product_id'] == null){
                $this->messageManager->addError(__('You have to choose the product first.'));
                return $resultRedirect->setPath('*/*/new');
            }

            $model->setData($data)
                ->updateStatus()
                ->setStoreViewId($storeViewId);

            try {
                $model->save();

                $this->messageManager->addSuccess(__('The auction has been saved.'));
                $this->_getSession()->setFormData(false);

                return $this->_getBackResultRedirect($resultRedirect, $model->getId());
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                $this->messageManager->addException($e, __('Something went wrong while saving the auction.'));
            }

            $this->_getSession()->setFormData($data);

            return $resultRedirect->setPath(
                '*/*/edit',
                [static::PARAM_CRUD_ID => $this->getRequest()->getParam(static::PARAM_CRUD_ID)]
            );
        }

        return $resultRedirect->setPath('*/*/');
    }

    public function processData($data){
        $data['init_price'] = abs($data['init_price']);
        $data['reserved_price'] = abs($data['reserved_price']);
        $data['min_interval_price'] = abs($data['min_interval_price']);
        $data['max_interval_price'] = abs($data['max_interval_price']);
        $data['limit_time'] = abs($data['limit_time']);
        $data['multi_winner'] = abs($data['multi_winner']);
        $data['day_to_buy'] = abs($data['day_to_buy']);
        $data['limit'] = abs($data['limit']);
        return $data;
    }

}
