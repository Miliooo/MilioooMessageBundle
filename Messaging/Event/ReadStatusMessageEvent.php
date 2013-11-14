<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Event;

use Miliooo\Messaging\Model\MessageInterface;
use Miliooo\Messaging\Model\MessageMetaInterface;
use Miliooo\Messaging\User\ParticipantInterface;
use Miliooo\Messaging\Model\ThreadInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Model for messages from whom the read status has changed.
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class ReadStatusMessageEvent extends Event implements ReadStatusMessageEventInterface
{
    /**
     * The message from whom the read status has changed.
     *
     * @var MessageInterface
     */
    protected $message;

    /**
     * The old status.
     *
     * @var int
     */
    protected $oldStatus;

    /**
     * The new status.
     *
     * @var int
     */
    protected $newStatus;

    /**
     * The participant from whom the read status has changed.
     *
     * @var ParticipantInterface
     */
    protected $participant;

    /**
     * Constructor.
     *
     * @param MessageInterface     $message
     * @param ParticipantInterface $participant
     * @param integer              $oldStatus
     * @param integer              $newStatus
     */
    public function __construct(MessageInterface $message, ParticipantInterface $participant, $oldStatus, $newStatus)
    {
        $this->message = $message;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }

    /**
     * Gets the message where the read status has changed.
     *
     * @return MessageInterface
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Gets the thread for the given message.
     *
     * @return ThreadInterface
     */
    public function getThread()
    {
        return $this->message->getThread();
    }

    /**
     * Gets the old read status.
     *
     * @return integer one of MessageInterface read status constants
     */
    public function getOldReadStatus()
    {
        return $this->oldStatus;
    }

    /**
     * Gets the new read status.
     *
     * @return integer one of MessageInterface read status constants
     */
    public function getNewStatus()
    {
        return $this->newStatus;
    }

    /**
     * Returns whether the message has been read for the first time by the given participant.
     *
     * @return boolean true if read for the first time, false otherwise
     */
    public function isFirstTimeRead()
    {
        if ($this->oldStatus === MessageMetaInterface::READ_STATUS_NEVER_READ
            && $this->newStatus === MessageMetaInterface::READ_STATUS_READ) {

            return true;
        }

        return false;
    }

    /**
     * Gets the participant for whom the read status of the message has changed.
     *
     * @return ParticipantInterface The participant for whom the message read status has changed.
     */
    public function getParticipant()
    {
        return $this->participant;
    }
}
