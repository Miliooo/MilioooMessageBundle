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
 * The abstract new message class.
 *
 * This abstract class should be used by all the form models.
 * Since it contains all the getters and setters for creating a message object.
 * Both replying of a message or creating a thread also adds a message.
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
abstract class AbstractNewMessage implements NewMessageInterface
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
     * Constructor.
     *
     */
    public function __construct()
    {
        //we will update this value in the form handler so we get the real value
        $this->createdAt = new \DateTime('now');
    }

    /**
     * {@inheritdoc}
     */
    public function getBody()
    {
        return $this->body;
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
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * {@inheritdoc}
     */
    public function setBody($body)
    {
        $this->body = $body;
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
    public function setSender(\Miliooo\Messaging\User\ParticipantInterface $sender)
    {
        $this->sender = $sender;
    }
}
