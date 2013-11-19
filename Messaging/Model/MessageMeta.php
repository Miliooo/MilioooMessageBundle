<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Model;

use Miliooo\Messaging\User\ParticipantInterface;
use Miliooo\Messaging\ValueObjects\ReadStatus;

/**
 * The message meta model class
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
abstract class MessageMeta implements MessageMetaInterface
{
    /**
     * The participant from the message meta
     *
     * @var ParticipantInterface
     */
    protected $participant;

    /**
     * The read status of the message for the given participant
     *
     * @var integer
     */
    protected $readStatus;

    /**
     * The previous read status this will return null if the read status has not changed or an integer of the read
     * status has changed.
     *
     * @var integer|null
     */
    protected $previousReadStatus;

    protected $message;

    /**
     * {@inheritdoc}
     */
    public function setParticipant(ParticipantInterface $participant)
    {
        $this->participant = $participant;
    }

    /**
     * {@inheritdoc}
     */
    public function getParticipant()
    {
        return $this->participant;
    }

    /**
     * {@inheritdoc}
     */
    public function setReadStatus(ReadStatus $readStatus)
    {
        $this->previousReadStatus = $this->readStatus;
        $this->readStatus = $readStatus->getReadStatus();
    }

    /**
     * {@inheritdoc}
     */
    public function getReadStatus()
    {
        return $this->readStatus;
    }

    /**
     * {@inheritdoc}
     */
    public function setMessage(MessageInterface $message)
    {
        $this->message = $message;
    }

    /**
     * {@inheritdoc}
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * {@inheritdoc}
     */
    public function getPreviousReadStatus()
    {
        return $this->previousReadStatus;
    }
}
