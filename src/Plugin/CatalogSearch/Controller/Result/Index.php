<?php

declare(strict_types=1);

namespace Infrangible\CatalogSearchRedirect\Plugin\CatalogSearch\Controller\Result;

use Infrangible\CatalogSearchRedirect\Helper\Data;
use Infrangible\CatalogSearchRedirect\Model\ResourceModel\Query\CollectionFactory;
use Infrangible\Core\Helper\Stores;
use Infrangible\Core\Helper\Url;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\HTTP\PhpEnvironment\Response;
use Magento\Search\Model\PopularSearchTerms;
use Magento\Search\Model\Query;
use Magento\Search\Model\QueryFactory;

/**
 * @author      Andreas Knollmann
 * @copyright   Copyright (c) 2014-2024 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class Index
{
    /** @var Stores */
    protected $storeHelper;

    /** @var Url */
    protected $urlHelper;

    /** @var QueryFactory */
    protected $queryFactory;

    /** @var PopularSearchTerms */
    protected $popularSearchTerms;

    /** @var CollectionFactory */
    protected $queryCollectionFactory;

    /**
     * @param Stores $storeHelper
     * @param Url $urlHelper
     * @param QueryFactory $queryFactory
     * @param PopularSearchTerms $popularSearchTerms
     * @param CollectionFactory $queryCollectionFactory
     */
    public function __construct(
        Stores             $storeHelper,
        Url                $urlHelper,
        QueryFactory       $queryFactory,
        PopularSearchTerms $popularSearchTerms,
        CollectionFactory  $queryCollectionFactory)
    {
        $this->storeHelper = $storeHelper;
        $this->urlHelper = $urlHelper;

        $this->queryFactory = $queryFactory;
        $this->popularSearchTerms = $popularSearchTerms;
        $this->queryCollectionFactory = $queryCollectionFactory;
    }

    /**
     * @param \Magento\CatalogSearch\Controller\Result\Index $subject
     * @param callable $proceed
     *
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function aroundExecute(\Magento\CatalogSearch\Controller\Result\Index $subject, callable $proceed)
    {
        /* @var Query $query */
        $query = $this->queryFactory->get();

        $storeId = $this->storeHelper->getStore()->getId();

        if (is_string($storeId)) {
            $storeId = (int)$storeId;
        }

        $query->setStoreId($storeId);

        $queryText = $query->getQueryText();

        if ($queryText) {
            $queryCollection = $this->queryCollectionFactory->create();

            $queryCollection->addStoreFilter($storeId);
            $queryCollection->addActiveFilter();
            $queryCollection->orderByPosition();

            /** @var \Infrangible\CatalogSearchRedirect\Model\Query $redirectQuery */
            foreach ($queryCollection as $redirectQuery) {
                $redirectText = $redirectQuery->getText();
                $mode = $redirectQuery->getMode();
                $caseSensitive = $redirectQuery->getCaseSensitive() == 1;
                $redirectUrl = $redirectQuery->getRedirectUrl();

                if ($this->matches($queryText, $redirectText, $mode, $caseSensitive)) {
                    if (!filter_var($redirectUrl, FILTER_VALIDATE_URL)) {
                        $redirectUrl = $this->urlHelper->getDirectUrl($redirectUrl);
                    }

                    $response = $subject->getResponse();

                    if ($response instanceof Response) {
                        $response->setRedirect($redirectUrl);
                    }

                    if (!$this->isCacheable($queryText, $storeId)) {
                        $query->saveIncrementalPopularity();
                    }

                    return;
                }
            }
        }

        $proceed();
    }

    /**
     * @param string $queryText
     * @param string $redirectText
     * @param string $mode
     * @param bool $caseSensitive
     *
     * @return bool
     */
    protected function matches(string $queryText, string $redirectText, string $mode, bool $caseSensitive): bool
    {
        if ($mode === Data::MODE_EQUALS || $mode === Data::MODE_PART_OF) {
            $redirectText = preg_quote($redirectText, '/');
            $redirectText = str_replace(' ', '\s', $redirectText);
            $redirectText = str_replace('\*', '[a-zA-Z0-9]*?', $redirectText);

            $regularExpression = $mode === Data::MODE_EQUALS ?
                sprintf('/^%s$/', $redirectText) : sprintf('/%s/', $redirectText);

            if (!$caseSensitive) {
                $regularExpression .= 'i';
            }

            $result = preg_match($regularExpression, $queryText);

            return $result !== 0 && $result !== false;
        } else if ($mode === Data::MODE_WORDS_ALL || $mode === Data::MODE_WORDS_ANY) {
            $queryTextWords = explode(' ', $queryText);
            $redirectTextWords = explode(' ', $redirectText);

            if ($mode === Data::MODE_WORDS_ALL) {
                $result = true;

                foreach ($redirectTextWords as $redirectTextWord) {
                    $wordResult = false;

                    foreach ($queryTextWords as $queryTextWord) {
                        if ($this->matchesWord($queryTextWord, $redirectTextWord, $caseSensitive)) {
                            $wordResult = true;

                            break;
                        }
                    }

                    $result = $result && $wordResult;
                }

                return $result;
            } else {
                foreach ($redirectTextWords as $redirectTextWord) {
                    foreach ($queryTextWords as $queryTextWord) {
                        if ($this->matchesWord($queryTextWord, $redirectTextWord, $caseSensitive)) {
                            return true;
                        }
                    }
                }
            }

            return false;
        } else {
            return false;
        }
    }

    /**
     * @param string $queryTextWord
     * @param string $redirectTextWord
     * @param bool $caseSensitive
     *
     * @return bool
     */
    protected function matchesWord(string $queryTextWord, string $redirectTextWord, bool $caseSensitive): bool
    {
        if (strpos($redirectTextWord, '*') === false) {
            if ($caseSensitive && strcmp($queryTextWord, $redirectTextWord) === 0) {
                return true;
            }

            if (!$caseSensitive && strcasecmp($queryTextWord, $redirectTextWord) === 0) {
                return true;
            }
        } else {
            $redirectTextWord = preg_quote($redirectTextWord, '/');
            $redirectTextWord = str_replace(' ', '\s', $redirectTextWord);
            $redirectTextWord = str_replace('\*', '[a-zA-Z0-9]*?', $redirectTextWord);

            $regularExpression = sprintf('/^%s$/', $redirectTextWord);

            if (!$caseSensitive) {
                $regularExpression .= 'i';
            }

            $result = preg_match($regularExpression, $queryTextWord);

            return $result !== 0 && $result !== false;
        }

        return false;
    }

    /**
     * @param string $queryText
     * @param int $storeId
     *
     * @return bool
     */
    protected function isCacheable(string $queryText, int $storeId): bool
    {
        return $this->popularSearchTerms->isCacheable($queryText, $storeId);
    }
}
