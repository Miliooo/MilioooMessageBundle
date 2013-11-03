<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\User;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Miliooo\Messaging\User\ParticipantInterface;

/**
 * Interface that participant providers have to implement.
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
interface ParticipantProviderInterface
{
    /**
     * Gets the authenticated participant.
     *
     * This method is responsible for returning the authenticated participant.
     *
     * If there is no authenticated participant or the participant does not implement
     * the participantInterface an AccessDeniedException should be thrown.
     *
     * @return ParticipantInterface
     *
     * @throws AccessDeniedException if no authenticated or valid participant
     */
    public function getAuthenticatedParticipant();
}
