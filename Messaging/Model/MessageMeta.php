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
     * Sets the read status of the message for the given participant
     *
     * @var boolean true if it's read by the given participant, false otherwise
     */
    protected $isRead = false;

    /**
     * Sets the new read status of the message.
     *
     * Since we update the read status just before we show the message to the user, we use this helper
     * function to check if it's the first time that the user has read this message.
     *
     * If the value is true it's the first time that the user sees this message. You could use this to style the message
     * in a different way.
     *
     * @var boolean true if the message has just been read, false otherwise
     */
    protected $newRead = false;

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
    public function setIsRead($boolean)
    {
        $this->isRead = (bool) $boolean;
    }

    /**
     * {@inheritdoc}
     */
    public function isRead()
    {
        return $this->isRead;
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
    public function setNewRead($boolean)
    {
        $this->newRead = $boolean;
    }

    /**
     * {@inheritdoc}
     */
    public function getNewRead()
    {
        return $this->newRead;
    }
}
