<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\ThreadProvider;

use Miliooo\Messaging\Specifications\CanSeeThreadSpecification;
use Miliooo\Messaging\User\ParticipantInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * A thread provider which uses the can see thread specification to decide whether to allow access to a thread.
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class ThreadProviderSpecificationAware implements SecureThreadProviderInterface
{
    protected $threadProvider;
    protected $canSeeThread;

    /**
     * Constructor.
     *
     * @param ThreadProviderInterface   $threadProvider A thread provider instance
     * @param CanSeeThreadSpecification $canSeeThread   A canseethreadspecification
     */
    public function __construct(ThreadProviderInterface $threadProvider, CanSeeThreadSpecification $canSeeThread)
    {
        $this->threadProvider = $threadProvider;
        $this->canSeeThread = $canSeeThread;
    }

    /**
     * {@inheritdoc}
     */
    public function findThreadForParticipant(ParticipantInterface $participant, $threadId)
    {
        $thread = $this->threadProvider->findThreadForParticipant($threadId, $participant);
        if (!$thread) {
            return null;
        }

        if (!$this->canSeeThread->isSatisfiedBy($participant, $thread)) {
            throw new AccessDeniedException('Not allowed to see this thread');
        }

        return $thread;
    }
}
