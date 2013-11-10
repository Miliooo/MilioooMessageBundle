<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\ThreadProvider\Folder;

use Miliooo\Messaging\User\ParticipantInterface;
use Miliooo\Messaging\Repository\ThreadRepositoryInterface;

/**
 * The inbox provider provides inbox threads for a given participant.
 *
 * This is an extra layer between the repository
 * If you need more logic or alter the logic in the repository you can override this service
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class InboxProvider implements InboxProviderInterface
{
    /**
     * A thread repository instance.
     *
     * @var ThreadRepositoryInterface
     */
    protected $threadRepository;

    /**
     * Constructor.
     *
     * @param ThreadRepositoryInterface $threadRepository A threadRepository instance
     */
    public function __construct(ThreadRepositoryInterface $threadRepository)
    {
        $this->threadRepository = $threadRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function getInboxThreads(ParticipantInterface $participant)
    {
       return $this->threadRepository->getInboxThreadsForParticipant($participant);
    }
}
