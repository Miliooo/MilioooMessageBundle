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
use Miliooo\Messaging\Specifications\CanMessageRecipientInterface;

/**
 * This implementation uses the CanMessageRecipientSpecification to decide whether the current user can message a recipient
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class CanMessageRecipientManager implements CanMessageRecipientManagerInterface
{
    /**
     * A can message recipient instance.
     *
     * @var CanMessageRecipientInterface
     */
    protected $specification;

    /**
     * Constructor.
     *
     * @param CanMessageRecipientInterface $specification
     */
    public function __construct(CanMessageRecipientInterface $specification)
    {
        $this->specification = $specification;
    }

    /**
     * Decides whether the logged in user can send a message to the recipient.
     *
     * @param ParticipantInterface $loggedInUser The logged in user
     * @param ParticipantInterface $recipient    The recipient we check
     *
     * @return boolean true if the loggedInUser can send a message to the recipient, false otherwise.
     */
    public function canMessageRecipient(ParticipantInterface $loggedInUser, ParticipantInterface $recipient)
    {
        return $this->specification->isSatisfiedBy($loggedInUser, $recipient);
    }

    /**
     * Check CanMessageRecipientInterface in the specifications for more information.
     *
     * @return null|string
     */
    public function getErrorMessage()
    {
        return $this->specification->getErrorMessage();
    }
}
