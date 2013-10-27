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
use Doctrine\Common\Collections\ArrayCollection;
use Miliooo\Messaging\Model\MessageInterface;

/**
 * The thread model
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
abstract class Thread implements ThreadInterface
{
    /**
     * The unique id of the thread
     * 
     * @var integer
     */
    protected $id;

    /**
     * The subject of the thread
     * 
     * @var string
     */
    protected $subject;

    /**
     * The participant who created the thread
     *
     * @var ParticipantInterface
     */
    protected $createdBy;

    /**
     * The datetime when the thread was created
     *
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * An array collection of messages who belong to this thread
     * 
     * @var ArrayCollection
     */
    protected $messages;

    /**
     * Constructor.
     * 
     */
    public function __construct()
    {
        $this->messages = new ArrayCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * {@inheritdoc}
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * {@inheritdoc}
     */
    public function setCreatedBy(ParticipantInterface $participant)
    {
        $this->createdBy = $participant;
    }

    /**
     * {@inheritdoc}
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * {@inheritdoc}
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * {@inheritdoc}
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * {@inheritdoc}
     */
    public function addMessage(MessageInterface $message)
    {
        $message->setThread($this);
        $this->messages->add($message);
    }

    /**
     * {@inheritdoc}
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * {@inheritdoc}
     */
    public function getFirstMessage()
    {
        return $this->messages->first();
    }

    /**
     * {@inheritdoc}
     */
    public function getLastMessage()
    {
        return $this->messages->last();
    }
}
