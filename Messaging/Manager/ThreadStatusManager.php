<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Manager;

use Miliooo\Messaging\User\ParticipantInterface;
use Miliooo\Messaging\Model\ThreadInterface;
use Miliooo\Messaging\Repository\ThreadRepositoryInterface;
use Miliooo\Messaging\ValueObjects\ThreadStatus;
/**
 * The interface for the threadmeta class
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class ThreadStatusManager implements ThreadStatusManagerInterface
{
    /**
     * A thread repository instance.
     *
     * @var ThreadRepositoryInterface
     */
    private $threadRepository;

    /**
     * Constructor.
     *
     */
    public function __construct(ThreadRepositoryInterface $threadRepository)
    {
        $this->threadRepository = $threadRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function updateThreadStatusForParticipant(ThreadStatus $threadStatus, ThreadInterface $thread, ParticipantInterface $participant)
    {
        $threadMeta = $thread->getThreadMetaForParticipant($participant);

        $newThreadStatus = $threadStatus->getThreadStatus();
        //if no thread meta can happen if the current user is not participant of the thread
        if (!$threadMeta || $threadMeta->getStatus() === $newThreadStatus) {
            return;
        }

       $threadMeta->setStatus($newThreadStatus);

        $this->threadRepository->save($thread);
    }
}
