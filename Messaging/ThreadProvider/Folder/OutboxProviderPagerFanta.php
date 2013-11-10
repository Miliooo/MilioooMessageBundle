<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\ThreadProvider\Folder;

use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Miliooo\Messaging\Repository\ThreadRepositoryInterface;
use Miliooo\Messaging\User\ParticipantInterface;
use Doctrine\ORM\QueryBuilder;

/**
 * Outbox provider for pagerfanta pagination
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class OutboxProviderPagerFanta
{
    /**
     * A thread repository instance.
     *
     * @var ThreadRepositoryInterface
     */
    protected $threadRepository;

    /**
     * How many items we show on a page
     *
     * @var integer
     */
    protected $itemsPerPage;

    /**
     * @param ThreadRepositoryInterface $threadRepository A thread repository instance
     * @param integer                   $itemsPerPage     Total items per page
     */
    public function __construct(ThreadrepositoryInterface $threadRepository, $itemsPerPage = 15)
    {
        $this->threadRepository = $threadRepository;
        $this->itemsPerPage = $itemsPerPage;
    }

    /**
     * Gets paginated outboxThreads
     *
     * @param ParticipantInterface $participant The participant where we get the outbox threads for
     * @param integer              $currentPage The page we are on
     *
     * @return Pagerfanta The pager fanta object with the current page and max per page items set.
     */
    public function getOutboxThreadsPagerfanta(ParticipantInterface $participant, $currentPage)
    {
        $queryBuilder = $this->threadRepository->getOutboxThreadsForParticipantQueryBuilder($participant);
        $adapter = $this->getAdapter($queryBuilder);
        $pagerfanta = $this->getPagerFanta($adapter);
        $pagerfanta->setMaxPerPage($this->itemsPerPage);
        $pagerfanta->setCurrentPage($currentPage);

        return $pagerfanta;
    }

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
    protected function getPagerFanta(DoctrineOrmAdapter $adapter)
    {
        return new PagerFanta($adapter);
    }
}
