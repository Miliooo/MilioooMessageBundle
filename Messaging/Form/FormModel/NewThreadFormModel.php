<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Form\FormModel;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Description of NewSingleThreadFormModel
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class NewThreadFormModel
{
    protected $body;
    protected $createdAt;
    protected $sender;
    protected $recipients;
    protected $subject;

    public function __construct()
    {
        $this->recipients = new ArrayCollection();
    }

    public function getBody()
    {
        return $this->body;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function getSender()
    {
        return $this->sender;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function setBody($body)
    {
        $this->body = $body;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function setSender($sender)
    {
        $this->sender = $sender;
    }

    /**
     * @return ArrayCollection
     */
    public function getRecipients()
    {
        return $this->recipients;
    }

    /**
     * Adds single recipient to collection
     *
     * @param ParticipantInterface $recipient
     *
     * @return null
     */
    public function addRecipient(ParticipantInterface $recipient)
    {
        if (!$this->recipients->contains($recipient)) {
            $this->recipients->add($recipient);
        }
    }

    /**
     * Removes recipient from collection
     *
     * @param ParticipantInterface $recipient
     *
     * @return null
     *
     */
    public function removeRecipient(ParticipantInterface $recipient)
    {
        $this->recipients->removeElement($recipient);
    }

    public function setRecipients($recipients)
    {
        $this->recipients = $recipients;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
    }
}
