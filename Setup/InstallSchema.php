<?php

namespace Fastly\Cdn\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        /* @var $connection \Magento\Framework\DB\Adapter\AdapterInterface */
        $connection = $installer->getConnection();

        $installer->startSetup();

        /**
         * Create table 'fastly_statistics'
         */
        $table = $connection->newTable(
            $installer->getTable('fastly_statistics')
        )->addColumn(
            'stat_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'Stat id'
        )->addColumn(
            'action',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            30,
            ['nullable' => false],
            'Fastly action'
        )->addColumn(
            'sent',
            \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
            null,
            ['nullable' => false, 'default' => 0],
            '1 = Curl req. sent | 0 = Curl req. not sent'
        )->addColumn(
            'state',
            \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
            null,
            ['nullable' => false, 'default' => 0],
            '1 = configured | 0 = not_configured'
        )->addColumn(
            'created_at',
            \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
            null,
            [],
            'Action date'
        );
        $connection->createTable($table);
        $installer->endSetup();

    }
}
