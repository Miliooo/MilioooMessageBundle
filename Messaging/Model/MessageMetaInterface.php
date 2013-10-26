<?php

/*
 * This file is part of the MilioooMessageBundle package.
 * 
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Model;

use Miliooo\Messaging\Model\ParticipantInterface;
use Miliooo\Messaging\Model\MessageInterface;

/**
 * Interface for the message meta
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
interface MessageMetaInterface
{
    /**
     * Sets the participant to the message meta
     *
     * @param ParticipantInterface $participant The participant we add to the meta
     */
    public function setParticipant(ParticipantInterface $participant);

    /**
     * Gets the participant from the meta
     *
     * @return ParticipantInterface The participant from the meta
     */
    public function getParticipant();

    /**
     * Sets if the message has been read for the given participant of this meta
     *
     * @param boolean $boolean true if the message is read, false otherwise
     */
    public function setIsRead($boolean);

    /**
     * Gets the read status of the message for the participant of this meta
     *
     * @return boolean true if it's read false otherwise
     */
    public function getIsRead();

    /**
     * Sets the message this message meta belongs to
     *
     * @param MessageInterface $message The message this meta belongs to
     */
    public function setMessage(MessageInterface $message);

    /**
     * Gets the message from the messagemeta
     *
     * @return MessageInterface The message this meta belongs to
     */
    public function getMessage();
}
