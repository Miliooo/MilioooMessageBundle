<?php


namespace Miliooo\Messaging\ThreadProvider\Folder;

use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

/**
 * Abstract provider class for pagerfanta folder pagination.
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
abstract class AbstractProviderPagerFanta
{
    /**
     * Gets an adapter with the querybuilder.
     *
     * Helper function to be able to somewhat test this.
     *
     * @param QueryBuilder $queryBuilder
     *
     * @return DoctrineORMAdapter
     */
    protected function getAdapter(QueryBuilder $queryBuilder)
    {
        return new DoctrineORMAdapter($queryBuilder);
    }

    /**
     * Gets the pagerfanta object with the current adapter.
     *
     * Helper function to be able to somewhat test this.
     *
     * @param DoctrineORMAdapter $adapter
     *
     * @return Pagerfanta
     */
    protected function getPagerFanta(DoctrineORMAdapter $adapter)
    {
        return new Pagerfanta($adapter);
    }

    /**
     * Gets a pagerfanta object for the given page and the given queryBuilder
     *
     * @param QueryBuilder $queryBuilder A query builder instance
     * @param integer      $currentPage  The current page we are on
     * @param integer      $itemsPerPage How many items per page we show
     *
     * @return Pagerfanta
     */
    protected function getPagerfantaObject($queryBuilder, $currentPage, $itemsPerPage)
    {
        $adapter = $this->getAdapter($queryBuilder);
        $pagerfanta = $this->getPagerFanta($adapter);
        $pagerfanta->setMaxPerPage($itemsPerPage);
        $pagerfanta->setCurrentPage($currentPage);

        return $pagerfanta;
    }
}
