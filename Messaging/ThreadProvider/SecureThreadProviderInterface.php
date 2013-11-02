<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\ThreadProvider;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Miliooo\Messaging\User\ParticipantInterface;
use Miliooo\Messaging\Model\ThreadInterface;

/**
 * Interfaces for secure thread providers
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
interface SecureThreadProviderInterface
{
    /**
     * Finds a thread for a given participant
     *
     * This method is responsible for returning a thread for a given participant.
     * When the participant has not enough rights to view the thread an
     * Symfony\Component\Security\Core\Exception\AccessDeniedException should be
     * thrown.
     *
     * @param ParticipantInterface $participant The current user
     * @param integer              $threadId    The threadId of the thread the user wants to see
     *
     * @return ThreadInterface|null
     *
     * @throws AccessDeniedException if not authorised to view the thread
     */
    public function findThreadForParticipant(ParticipantInterface $participant, $threadId);
}
