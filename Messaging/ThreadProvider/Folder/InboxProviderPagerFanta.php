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
 * {@inheritdoc}
 */
class InboxProviderPagerFanta extends AbstractProviderPagerFanta implements InboxProviderPagerFantaInterface
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
    public function __construct(ThreadRepositoryInterface $threadRepository, $itemsPerPage = 15)
    {
        $this->threadRepository = $threadRepository;
        $this->itemsPerPage = $itemsPerPage;
    }

    /**
     * {@inheritdoc}
     */
    public function getInboxThreadsPagerfanta(ParticipantInterface $participant, $currentPage)
    {
        $queryBuilder = $this->threadRepository->getInboxThreadsForParticipantQueryBuilder($participant);

        return $this->getPagerfantaObject($queryBuilder, $currentPage, $this->itemsPerPage);
    }
}
