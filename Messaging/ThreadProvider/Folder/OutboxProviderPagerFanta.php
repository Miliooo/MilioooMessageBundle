<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\ThreadProvider\Folder;

use Miliooo\Messaging\Repository\ThreadRepositoryInterface;
use Miliooo\Messaging\User\ParticipantInterface;

/**
 * Outbox provider for pagerfanta pagination
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class OutboxProviderPagerFanta extends AbstractProviderPagerFanta implements OutboxProviderPagerFantaInterface
{
    /**
     * A thread repository instance.
     *
     * @var ThreadRepositoryInterface
     */
    protected $threadRepository;

    /**
     * How many items we show per page
     *
     * @var integer
     */
    protected $itemsPerPage;

    /**
     * @param ThreadRepositoryInterface $threadRepository A thread repository instance
     * @param integer                   $itemsPerPage     Total items per page
     */
    public function __construct(ThreadrepositoryInterface $threadRepository, $itemsPerPage)
    {
        $this->threadRepository = $threadRepository;
        $this->itemsPerPage = $itemsPerPage;
    }

    /**
     * {@inheritdoc}
     */
    public function getOutboxThreadsPagerfanta(ParticipantInterface $participant, $currentPage)
    {
        $queryBuilder = $this->threadRepository->getOutboxThreadsForParticipantQueryBuilder($participant);

        return $this->getPagerfantaObject($queryBuilder, $currentPage, $this->itemsPerPage);
    }
}
