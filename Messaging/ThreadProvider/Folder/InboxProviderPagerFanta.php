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
use Pagerfanta\Pagerfanta;

/**
 * Inbox provider for pagerfanta pagination
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class InboxProviderPagerFanta extends AbstractProviderPagerFanta
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
    public function __construct(ThreadrepositoryInterface $threadRepository, $itemsPerPage = 15)
    {
        $this->threadRepository = $threadRepository;
        $this->itemsPerPage = $itemsPerPage;
    }

    /**
     * Gets paginated inbox threads
     *
     * @param ParticipantInterface $participant The participant for whom we get the inbox threads
     * @param integer              $currentPage The page we are on
     *
     * @return Pagerfanta The pager fanta object with the current page and max per page items set.
     */
    public function getInboxThreadsPagerfanta(participantInterface $participant, $currentPage)
    {
        $queryBuilder = $this->threadRepository->getInboxThreadsForParticipantQueryBuilder($participant);

        return $this->getPagerfantaObject($queryBuilder, $currentPage, $this->itemsPerPage);
    }
}
