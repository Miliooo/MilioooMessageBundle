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
     * An array collection of messages for this thread
     *
     * @var ArrayCollection
     */
    protected $messages;

    /**
     * An array collection of thread metas for this thread
     *
     * @var ArrayCollection
     */
    protected $threadMeta;

    /**
     * An array collection with participants
     *
     * @var ArrayCollection
     */
    protected $participants;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->messages = new ArrayCollection();
        $this->threadMeta = new ArrayCollection();
        $this->participants = new ArrayCollection();
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

    /**
     * {@inheritdoc}
     */
    public function getThreadMeta()
    {
        return $this->threadMeta;
    }

    /**
     * {@inheritdoc}
     */
    public function addThreadMeta(ThreadMetaInterface $threadMeta)
    {
        $threadMeta->setThread($this);
        $this->threadMeta->add($threadMeta);
    }

    /**
     * {@inheritdoc}
     */
    public function getThreadMetaForParticipant(ParticipantInterface $participant)
    {
        foreach ($this->threadMeta as $meta) {
            if ($meta->getParticipant()->getParticipantId() == $participant->getParticipantId()) {
                return $meta;
            }
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getParticipants()
    {
        return $this->getParticipantsCollection()->toArray();
    }

     /**
     * {@inheritdoc}
     */
    public function isParticipant(ParticipantInterface $participant)
    {
        return $this->getParticipantsCollection()->contains($participant);
    }

    /**
     * Returns an array collection of participants for the given thread.
     *
     * We do not map the participantscollection because the thread meta allready
     * gives us all the information needed. It contains the thread AND the participant
     *
     * So in order to get the participants we need to loop over the threadmeta
     * Get the participant and add it to the collection.
     *
     * if we want to add a participant to the thread collection we need to create that threadmeta.
     *
     * @return ArrayCollection
     */
    protected function getParticipantsCollection()
    {
        //returns an empty array collection
        if (!$this->threadMeta->count() > 0) {
            return $this->participants;
        }
        //there is thread meta in the collection so let's loop over it
        foreach ($this->threadMeta as $threadMeta) {
            $this->addParticipantFromThreadMeta($threadMeta);
        }

        return $this->participants;
    }

    private function addParticipantFromThreadMeta(ThreadMetaInterface $threadMeta)
    {
        $participant = $threadMeta->getParticipant();
        if (is_object($participant) && $participant instanceof ParticipantInterface) {
            $this->participants->add($participant);
        }
    }
}
