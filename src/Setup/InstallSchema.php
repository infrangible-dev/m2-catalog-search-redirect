<?php

declare(strict_types=1);

namespace Infrangible\CatalogSearchRedirect\Setup;

use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Zend_Db_Exception;

/**
 * @author      Andreas Knollmann
 * @copyright   Copyright (c) 2014-2024 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class InstallSchema
    implements InstallSchemaInterface
{
    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     *
     * @return void
     * @throws Zend_Db_Exception
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $connection = $setup->getConnection();

        $queryTableName = $connection->getTableName('catalogsearch_query');

        if (!$connection->isTableExists($queryTableName)) {
            $queryTable = $connection->newTable($queryTableName);

            $queryTable->addColumn('query_id', Table::TYPE_SMALLINT, 6,
                ['identity' => true, 'primary' => true, 'nullable' => false, 'unsigned' => true]);
            $queryTable->addColumn('text', Table::TYPE_TEXT, 255, ['nullable' => false]);
            $queryTable->addColumn('store_id', Table::TYPE_SMALLINT, 5, ['nullable' => false, 'unsigned' => true]);
            $queryTable->addColumn('mode', Table::TYPE_TEXT, 255, ['nullable' => false]);
            $queryTable->addColumn('case_sensitive', Table::TYPE_SMALLINT, 6,
                ['nullable' => false, 'unsigned' => true, 'default' => 0]);
            $queryTable->addColumn('redirect_url', Table::TYPE_TEXT, 255, ['nullable' => false]);
            $queryTable->addColumn('position', Table::TYPE_INTEGER, 11,
                ['nullable' => false, 'unsigned' => true, 'default' => 0]);
            $queryTable->addColumn('status', Table::TYPE_SMALLINT, 6,
                ['nullable' => false, 'unsigned' => true, 'default' => 1]);
            $queryTable->addColumn('created_at', Table::TYPE_DATETIME, null, ['nullable' => false]);
            $queryTable->addColumn('updated_at', Table::TYPE_DATETIME, null, ['nullable' => false]);

            $queryTable->addIndex($connection->getIndexName($queryTableName, ['text', 'store_id']),
                ['text', 'store_id'], AdapterInterface::INDEX_TYPE_UNIQUE);

            $connection->createTable($queryTable);
        }

        $setup->endSetup();
    }
}
