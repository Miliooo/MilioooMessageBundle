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
class CanMessageRecipientSpecification implements CanMessageRecipientInterface
{

    protected $error;

    /**
     * {@inheritdoc}
     */
    public function isSatisfiedBy(ParticipantInterface $currentParticipant, ParticipantInterface $recipient)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getErrorMessage()
    {
        return $this->error;
    }
}
