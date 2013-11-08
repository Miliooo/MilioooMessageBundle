<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\ThreadProvider;

use Miliooo\Messaging\ThreadProvider\ThreadProviderInterface;
use Miliooo\Messaging\Specifications\CanSeeThreadSpecification as specification;
use Miliooo\Messaging\User\ParticipantInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * The SpecificationAware Thread Provider is a secure thread provider.
 *
 * This class uses the canseethreadspecification to decide whether or not to return a thread
 * or throw an exception. It implements the SecureThreadProviderinterface.
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class SpecificationAwareThreadProvider implements SecureThreadProviderInterface
{
    protected $threadProvider;
    protected $canSeeThread;

    /**
     * Constructor.
     *
     * @param ThreadProviderInterface   $threadProvider A thread provider instance
     * @param CanSeeThreadSpecification $canSeeThread   A canseethreadspecification
     */
    public function __construct(ThreadProviderInterface $threadProvider, specification $canSeeThread)
    {
        $this->threadProvider = $threadProvider;
        $this->canSeeThread = $canSeeThread;
    }

    /**
     * {@inheritdoc}
     */
    public function findThreadForParticipant(ParticipantInterface $participant, $threadId)
    {
        $thread = $this->threadProvider->findThreadById($threadId);
        if (!$thread) {
            return null;
        }

        if (!$this->canSeeThread->isSatisfiedBy($participant, $thread)) {
            throw new AccessDeniedException('Not allowed to see this thread');
        }

        return $thread;
    }
}
