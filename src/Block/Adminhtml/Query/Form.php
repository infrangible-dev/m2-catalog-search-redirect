<?php

declare(strict_types=1);

namespace Infrangible\CatalogSearchRedirect\Block\Adminhtml\Query;

use Infrangible\CatalogSearchRedirect\Helper\Data;

/**
 * @author      Andreas Knollmann
 * @copyright   Copyright (c) 2014-2024 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class Form
    extends \Infrangible\BackendWidget\Block\Form
{
    /**
     * @param \Magento\Framework\Data\Form $form
     */
    protected function prepareFields(\Magento\Framework\Data\Form $form)
    {
        $fieldSet = $form->addFieldset('general', ['legend' => __('General')]);

        $this->addTextField($fieldSet, 'text', __('Term')->render(), true);
        $this->addStoreSelectField($fieldSet, 'store_id', null, false, false, false);
        $this->addOptionsField($fieldSet, 'mode', __('Mode')->render(), [
            Data::MODE_EQUALS => __('Equals'),
            Data::MODE_WORDS_ALL => __('All Words'),
            Data::MODE_WORDS_ANY => __('Any Word'),
            Data::MODE_PART_OF => __('Part Of')
        ], Data::MODE_EQUALS, true);
        $this->addYesNoField($fieldSet, 'case_sensitive', __('Case Sensitive')->render(), true);
        $this->addTextField($fieldSet, 'redirect_url', __('Redirect Url')->render(), true);
        $this->addTextField($fieldSet, 'position', __('Position')->render(), true);
        $this->addYesNoField($fieldSet, 'status', __('Active')->render(), true);
    }
}
