<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Form\FormModel;

use Miliooo\Messaging\User\ParticipantInterface;

/**
 * Description of NewSingleThreadFormModel
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class NewThreadFormModel
{
    /**
     * Body of the message
     * @var string
     */
    protected $body;

    /**
     * Creation time of the message
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * Sender of the message
     * @var ParticipantInterface
     */
    protected $sender;

    /**
     * Recipient of the message
     * @var ParticipantInterface
     */
    protected $recipient;

    /**
     * Subject of the message
     * @var string
     */
    protected $subject;

    /**
     * Gets the body of the message
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Gets the creation time of the thread and message
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Gets the sender of the message.
     *
     * If we create a new thread also the creator of the thread
     *
     * @return ParticipantInterface
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * Gets the subject of the thread
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Sets the body of the message
     *
     * @param string $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * Sets the creation date of the message
     *
     * If we create a new thread this is also the creation time of the thread
     *
     * @param \DateTime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Sets the sender of the message
     *
     * If we create a new thread this is also the creator of the thread
     *
     * @param ParticipantInterface $sender
     */
    public function setSender(ParticipantInterface $sender)
    {
        $this->sender = $sender;
    }

    /**
     * Gets the recipient of the message
     *
     * @return ParticipantInterface the recipient
     */
    public function getRecipient()
    {
        return $this->recipients;
    }

    /**
     * Sets the recipient of the message
     *
     * @param ParticipantInterface $recipient
     */
    public function setRecipient(ParticipantInterface $recipient)
    {
        $this->recipients = $recipient;
    }

    /**
     * Sets the subject
     *
     * @param string $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }
}
