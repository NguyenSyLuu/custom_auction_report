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

namespace Magestore\Auction\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * Install schema
 * @category Magestore
 * @package  Magestore_Auction
 * @module   Auction
 * @author   Magestore Developer
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        /*
         * Drop tables if exists
         */
        $installer->getConnection()->dropTable($installer->getTable('magestore_auction'));
        $installer->getConnection()->dropTable($installer->getTable('magestore_auction_value'));
        $installer->getConnection()->dropTable($installer->getTable('magestore_auction_bid'));
        $installer->getConnection()->dropTable($installer->getTable('magestore_auction_autobid'));
        $installer->getConnection()->dropTable($installer->getTable('magestore_auction_bidder'));

        /*
         * Create table magestore_auction
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('magestore_auction')
        )->addColumn(
            'auction_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            10,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'Auction ID'
        )->addColumn(
            'product_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            10,
            ['nullable' => false],
            'Product Id'
        )->addColumn(
            'name',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => true],
            'Auction Name'
        )->addColumn(
            'init_price',
            \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
            '12,4',
            ['nullable' => false, 'default' => '0.00'],
            'Init Price'
        )->addColumn(
            'reserved_price',
            \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
            '12,4',
            ['nullable' => false, 'default' => '0.00'],
            'Reserved Price'
        )->addColumn(
            'min_interval_price',
            \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
            '12,4',
            ['nullable' => false, 'default' => '0.00'],
            'Min interval Price'
        )->addColumn(
            'max_interval_price',
            \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
            '12,4',
            ['nullable' => true, 'default' => '0.00'],
            'Max Interval Price'
        )->addColumn(
            'start_time',
            \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
            null,
            ['nullable' => true],
            'Auction starting time'
        )->addColumn(
            'end_time',
            \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
            null,
            ['nullable' => true],
            'Auction ending time'
        )->addColumn(
            'created_time',
            \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
            null,
            ['nullable' => true],
            'Created Time'
        )->addColumn(
            'updated_time',
            \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
            null,
            ['nullable' => true],
            'Updated Time'
        )->addColumn(
            'limit_time',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            10,
            ['nullable' => false, 'default' => '15'],
            'Limit Time'
        )->addColumn(
            'multi_winner',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            10,
            ['nullable' => false, 'default' => '1'],
            'Product Id'
        )->addColumn(
            'featured',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['nullable' => false, 'default' => '0'],
            'Featured'
        )->addColumn(
            'allow_buyout',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['nullable' => false, 'default' => '1'],
            'Allow Buyout'
        )->addColumn(
            'day_to_buy',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['nullable' => false, 'default' => '1'],
            'Day To Buy'
        )->addColumn(
            'watch_list',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            2047,
            ['nullable' => true, 'default' => ''],
            'Watch list'
        )->addColumn(
            'status',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['nullable' => false, 'default' => '1'],
            'Auction status'
        )->addIndex(
            $installer->getIdxName('magestore_auction', ['auction_id']),
            ['start_time']
        )->addIndex(
            $installer->getIdxName('magestore_auction', ['product_id']),
            ['start_time']
        )->addIndex(
            $installer->getIdxName('magestore_auction', ['status']),
            ['start_time']
        );
        $installer->getConnection()->createTable($table);
        /*
         * End create table magestore_auction
         */

        /*
         * Create table magestore_auction_value
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('magestore_auction_value')
        )->addColumn(
            'value_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            10,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'Value ID'
        )->addColumn(
            'auction_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            10,
            ['unsigned' => true, 'nullable' => false, 'default' => '0'],
            'Auction ID'
        )->addColumn(
            'store_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['unsigned' => true, 'nullable' => false, 'default' => '0'],
            'Store view ID'
        )->addColumn(
            'attribute_code',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            63,
            ['nullable' => false, 'default' => ''],
            'Attribute code'
        )->addColumn(
            'value',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            ['nullable' => false],
            'Value'
        )->addIndex(
            $installer->getIdxName(
                'magestore_auction_value',
                ['auction_id', 'store_id', 'attribute_code'],
                \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE
            ),
            ['auction_id', 'store_id', 'attribute_code'],
            ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE]
        )->addIndex(
            $installer->getIdxName('magestore_auction_value', ['auction_id']),
            ['auction_id']
        )->addIndex(
            $installer->getIdxName('magestore_auction_value', ['store_id']),
            ['store_id']
        )->addForeignKey(
            $installer->getFkName(
                'magestore_auction_value',
                'auction_id',
                'magestore_auction',
                'auction_id'
            ),
            'auction_id',
            $installer->getTable('magestore_auction'),
            'auction_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )->addForeignKey(
            $installer->getFkName(
                'magestore_auction_value',
                'store_id',
                'store',
                'store_id'
            ),
            'store_id',
            $installer->getTable('store'),
            'store_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        );
        $installer->getConnection()->createTable($table);
        /*
         * End create table magestore_auction_value
         */

        /*
         * Create table magestore_auction_bid
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('magestore_auction_bid')
        )->addColumn(
            'bid_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            10,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'Bid ID'
        )->addColumn(
            'auction_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            10,
            ['unsigned' => true, 'nullable' => false, 'default' => '0'],
            'Auction ID'
        )->addColumn(
            'customer_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            10,
            ['unsigned' => true, 'nullable' => false, 'default' => '0'],
            'Customer ID'
        )->addColumn(
            'order_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            10,
            ['unsigned' => true, 'nullable' => true],
            'Order ID'
        )->addColumn(
            'autobid_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            10,
            ['unsigned' => true, 'nullable' => false, 'default' => '0'],
            'Autobid ID'
        )->addColumn(
            'price',
            \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
            '16,4',
            ['nullable' => false, 'default' => '0.00'],
            'Bid Price'
        )->addColumn(
            'created_time',
            \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
            null,
            ['nullable' => true],
            'Created Time'
        )->addColumn(
            'status',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['nullable' => false, 'default' => '1'],
            'Bid status'
        )->addIndex(
            $installer->getIdxName('magestore_auction_bid', ['bid_id']),
            ['bid_id']
        )->addIndex(
            $installer->getIdxName('magestore_auction_bid', ['auction_id']),
            ['auction_id']
        )->addIndex(
            $installer->getIdxName('magestore_auction_bid', ['customer_id']),
            ['customer_id']
        )->addIndex(
            $installer->getIdxName('magestore_auction_bid', ['autobid_id']),
            ['autobid_id']
        )->addIndex(
            $installer->getIdxName('magestore_auction_bid', ['status']),
            ['status']
        )->addForeignKey(
            $installer->getFkName(
                'magestore_auction_bid',
                'auction_id',
                'magestore_auction',
                'auction_id'
            ),
            'auction_id',
            $installer->getTable('magestore_auction'),
            'auction_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        );
        $installer->getConnection()->createTable($table);
        /*
         * End create table magestore_auction_bid
         */

        /*
         * Create table magestore_auction_autobid
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('magestore_auction_autobid')
        )->addColumn(
            'autobid_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            10,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'Bid ID'
        )->addColumn(
            'auction_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            10,
            ['unsigned' => true, 'nullable' => false, 'default' => '0'],
            'Auction ID'
        )->addColumn(
            'customer_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            10,
            ['unsigned' => true, 'nullable' => false, 'default' => '0'],
            'Customer ID'
        )->addColumn(
            'price',
            \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
            '12,4',
            ['nullable' => false, 'default' => '0.00'],
            'Bid Price'
        )->addColumn(
            'created_time',
            \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
            null,
            ['nullable' => true],
            'Created Time'
        )->addColumn(
            'status',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['nullable' => false, 'default' => '1'],
            'Bid status'
        )->addIndex(
            $installer->getIdxName('magestore_auction_autobid', ['autobid_id']),
            ['autobid_id']
        )->addIndex(
            $installer->getIdxName('magestore_auction_autobid', ['auction_id']),
            ['auction_id']
        )->addIndex(
            $installer->getIdxName('magestore_auction_autobid', ['customer_id']),
            ['customer_id']
        )->addIndex(
            $installer->getIdxName('magestore_auction_autobid', ['status']),
            ['status']
        )->addForeignKey(
            $installer->getFkName(
                'magestore_auction_autobid',
                'auction_id',
                'magestore_auction',
                'auction_id'
            ),
            'auction_id',
            $installer->getTable('magestore_auction'),
            'auction_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        );
        $installer->getConnection()->createTable($table);
        /*
         * End create table magestore_auction_autobid
         */

        /*
         * Create table magestore_auction_bidder
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('magestore_auction_bidder')
        )->addColumn(
            'bidder_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            10,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'Bidder ID'
        )->addColumn(
            'customer_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            10,
            ['unsigned' => true, 'nullable' => false, 'default' => '0'],
            'Customer ID'
        )->addColumn(
            'bidder_name',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            ['nullable' => true, 'default' => ''],
            'Bidder Name'
        )->addColumn(
            'place_bid',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['nullable' => false, 'default' => '1'],
            'Send Email when place a new Bid'
        )->addColumn(
            'place_autobid',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['nullable' => false, 'default' => '1'],
            'Send Email when place a new autobid'
        )->addColumn(
            'over_bid',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['nullable' => false, 'default' => '1'],
            'Send Email when over bid'
        )->addColumn(
            'over_autobid',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['nullable' => false, 'default' => '1'],
            'Send Email when over auto bid'
        )->addColumn(
            'cancel_bid',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['nullable' => false, 'default' => '1'],
            'Send Email when cancel bid'
        )->addColumn(
            'highest_bid',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['highest_bid' => false, 'default' => '1'],
            'Send Email when is highest bid'
        )->addColumn(
            'status',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['nullable' => false, 'default' => '1'],
            'Bid status'
        )
            ->addIndex(
            $installer->getIdxName('magestore_auction_bidder', ['bidder_id']),
            ['bidder_id']
        )->addIndex(
            $installer->getIdxName('magestore_auction_bidder', ['customer_id']),
            ['customer_id']
        )->addIndex(
            $installer->getIdxName('magestore_auction_bidder', ['status']),
            ['status']
        );
        $installer->getConnection()->createTable($table);
        /*
         * End create table magestore_auction_bidder
         */

        $installer->endSetup();
    }
}