<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\ThreadProvider;

use Miliooo\Messaging\Repository\ThreadRepositoryInterface;
use Miliooo\Messaging\User\ParticipantInterface;

/**
 * The thread provider is responsible for providing threads
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class ThreadProvider implements ThreadProviderInterface
{
    /**
     * A thread repository instance
     * @var ThreadRepositoryInterface
     */
    protected $threadRepository;

    /**
     * Constructor.
     *
     * @param ThreadRepositoryInterface $threadRepository
     */
    public function __construct(ThreadRepositoryInterface $threadRepository)
    {
        $this->threadRepository = $threadRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function findThreadById($threadId)
    {
        $thread = $this->threadRepository->findThread($threadId);

        return is_object($thread) ? $thread : null;
    }

    /**
     * {@inheritdoc}
     */
    public function findThreadForParticipant($threadId, ParticipantInterface $participant)
    {
        $thread = $this->threadRepository->findThreadForParticipant($threadId, $participant);

        return is_object($thread) ? $thread : null;
    }
}
