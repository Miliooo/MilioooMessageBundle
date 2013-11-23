<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Specifications;

use Miliooo\Messaging\User\ParticipantInterface;

/**
 * This specification is responsible for deciding if a given user can send a message to a given recipient.
 *
 * The default implementation is to just return true.
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class CanMessageRecipientSpecification
{
    /**
     * Decides whether the current participant can send a message to the given recipient.
     *
     * @param ParticipantInterface $currentParticipant The current participant
     * @param ParticipantInterface $recipient          The recipient
     *
     * @return boolean true if the participant can send a message to the recipient, false otherwise
     */
    public function isSatisfiedBy(ParticipantInterface $currentParticipant, ParticipantInterface $recipient)
    {
        return true;
    }
}
