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

/**
 * The can message recipient manager is responsible for deciding whether the current user can message the recipient.
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
interface CanMessageRecipientManagerInterface
{
    /**
     * Decides whether the logged in user can send a message to the recipient.
     *
     * @param ParticipantInterface $loggedInUser The logged in user
     * @param ParticipantInterface $recipient    The recipient we check
     *
     * @return boolean true if the loggedInUser can send a message to the recipient, false otherwise.
     */
    public function canMessageRecipient(ParticipantInterface $loggedInUser, ParticipantInterface $recipient);
}
