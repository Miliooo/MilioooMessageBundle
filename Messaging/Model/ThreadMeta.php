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
use Miliooo\Messaging\Model\ThreadInterface;

/**
 * The thread meta model
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
abstract class ThreadMeta implements ThreadMetaInterface
{
    /**
     * The unique id of the thread
     * 
     * @var integer
     */
    protected $id;

    /**
     * The thread this meta belongs to
     *
     * @var ThreadInterface
     */
    protected $thread;

    /**
     * The participant of the thread meta
     *
     * @var ParticipantInterface
     */
    protected $participant;

    /**
     * The deleted value of the thread for the given participant
     * 
     * @var boolean
     */
    protected $isDeleted = false;

    /**
     * Datetime of the last message written by the participant
     * 
     * @var \DateTime
     */
    protected $lastParticipantMessageDate;

    /**
     * Datetime of the last message written by another participant
     * 
     * @var \DateTime 
     */
    protected $lastMessageDate;

    /**
     * Gets the unique id
     * 
     * @return integer|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function setThread(ThreadInterface $thread)
    {
        $this->thread = $thread;
    }

    /**
     * {@inheritdoc}
     */
    public function getThread()
    {
        return $this->thread;
    }

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
    public function getIsDeleted()
    {
        return $this->isDeleted;
    }

    /**
     * {@inheritdoc}
     */
    public function setIsDeleted($boolean)
    {
        $this->isDeleted = (bool) $boolean;
    }

    /**
     * {@inheritdoc}
     */
    public function setLastParticipantMessageDate(\DateTime $dateTime)
    {
        $this->lastParticipantMessageDate = $dateTime;
    }

    /**
     * {@inheritdoc}
     */
    public function getLastParticipantMessageDate()
    {
        return $this->lastParticipantMessageDate;
    }

    /**
     * {@inheritdoc}
     */
    public function getLastMessageDate()
    {
        return $this->lastMessageDate;
    }

    /**
     * {@inheritdoc}
     */
    public function setLastMessageDate(\DateTime $lastMessageDate)
    {
        $this->lastMessageDate = $lastMessageDate;
    }
}
