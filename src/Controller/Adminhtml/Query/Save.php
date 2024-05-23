<?php

declare(strict_types=1);

namespace Infrangible\CatalogSearchRedirect\Controller\Adminhtml\Query;

use Infrangible\BackendWidget\Model\Backend\Session;
use Infrangible\CatalogSearchRedirect\Traits\Query;
use Infrangible\Core\Helper\Instances;
use Infrangible\Core\Helper\Registry;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\Model\AbstractModel;
use Magento\PageCache\Model\Cache\Type;
use Magento\PageCache\Model\Config;
use Psr\Log\LoggerInterface;

/**
 * @author      Andreas Knollmann
 * @copyright   Copyright (c) 2014-2024 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class Save
    extends \Infrangible\BackendWidget\Controller\Backend\Object\Save
{
    use Query;

    /** @var Config */
    protected $config;

    /** @var TypeListInterface */
    protected $typeList;

    /**
     * @param Registry $registryHelper
     * @param Instances $instanceHelper
     * @param Context $context
     * @param LoggerInterface $logging
     * @param Session $session
     * @param Config $config
     * @param TypeListInterface $typeList
     */
    public function __construct(
        Registry          $registryHelper,
        Instances         $instanceHelper,
        Context           $context,
        LoggerInterface   $logging,
        Session           $session,
        Config            $config,
        TypeListInterface $typeList)
    {
        parent::__construct($registryHelper, $instanceHelper, $context, $logging, $session);

        $this->config = $config;
        $this->typeList = $typeList;
    }

    /**
     * @return string
     */
    protected function getObjectCreatedMessage(): string
    {
        return __('The term has been created.')->render();
    }

    /**
     * @return string
     */
    protected function getObjectUpdatedMessage(): string
    {
        return __('The term has been saved.')->render();
    }

    /**
     * @param AbstractModel $object
     */
    protected function afterSave(AbstractModel $object)
    {
        parent::afterSave($object);

        if ($this->config->isEnabled()) {
            $this->typeList->invalidate(Type::TYPE_IDENTIFIER);
        }
    }
}
