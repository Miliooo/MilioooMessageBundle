<?php

namespace Miliooo\Messaging\Event;

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

use Miliooo\Messaging\Model\MessageInterface;
use Miliooo\Messaging\Model\ThreadInterface;
use Miliooo\Messaging\User\ParticipantInterface;


/**
 * A model for messages from whom the read status has changed.
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
interface ReadStatusMessageEventInterface
{
    /**
     * Gets the message where the read status has changed.
     *
     * @return MessageInterface
     */
    public function getMessage();

    /**
     * Gets the participant for whom the read status of the message has changed.
     *
     * @return ParticipantInterface The participant for whom the message read status has changed.
     */
    public function getParticipant();

    /**
     * Gets the old read status.
     *
     * @return mixed
     */
    public function getOldReadStatus();

    /**
     * Gets the new read status.
     *
     * @return integer
     */
    public function getNewStatus();

    /**
     * Gets the thread for the given message.
     *
     * @return ThreadInterface
     */
    public function getThread();

    /**
     * Returns whether the message has been read for the first time by the given participant.
     *
     * @return boolean true if read for the first time, false otherwise
     */
    public function isFirstTimeRead();
}
