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
    protected $isRead;

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
    public function getIsRead()
    {
        return $this->isRead;
    }
}
