<?php

declare(strict_types=1);

namespace Infrangible\CatalogSearchRedirect\Block\Adminhtml\Query;

use Exception;
use Infrangible\CatalogSearchRedirect\Helper\Data;
use Magento\Framework\Data\Collection;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Exception\FileSystemException;

/**
 * @author      Andreas Knollmann
 * @copyright   Copyright (c) 2014-2024 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class Grid
    extends \Infrangible\BackendWidget\Block\Grid
{
    /**
     * @throws FileSystemException
     */
    public function _construct(): void
    {
        parent::_construct();

        $this->setDefaultSort('position');
        $this->setDefaultDir(Collection::SORT_ORDER_ASC);
    }

    protected function prepareCollection(AbstractDb $collection): void
    {
    }

    /**
     * @throws Exception
     */
    protected function prepareFields(): void
    {
        $this->addTextColumn('text', __('Term')->render());
        $this->addStoreStructureColumn('store_id');
        $this->addOptionsColumn('mode', __('Mode')->render(), [
            Data::MODE_EQUALS => __('Equals'),
            Data::MODE_WORDS_ALL => __('All Words'),
            Data::MODE_WORDS_ANY => __('Any Word'),
            Data::MODE_PART_OF => __('Part Of')
        ]);
        $this->addYesNoColumn('case_sensitive', __('Case Sensitive')->render());
        $this->addTextColumn('redirect_url', __('Redirect Url')->render());
        $this->addNumberColumn('position', __('Position')->render());
        $this->addYesNoColumn('status', __('Active')->render());
    }

    /**
     * @return string[]
     */
    protected function getHiddenFieldNames(): array
    {
        return [];
    }
}
