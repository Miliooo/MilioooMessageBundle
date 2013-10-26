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
use Miliooo\Messaging\Model\MessageMetaInterface;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * The message model
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
abstract class Message implements MessageInterface
{
    /**
     * The unique id of the message
     * 
     * @var integer The unique id of the message
     */
    protected $id;

    /**
     * The creation time of the message
     *
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * The sender of the message
     *
     * @var ParticipantInterface
     */
    protected $sender;

    /**
     * The body of the message
     * 
     * @var string
     */
    protected $body;

    /**
     * A collection of message metas
     * @var ArrayCollection
     */
    protected $messageMeta;

    /**
     * Constructor.
     * 
     */
    public function __construct()
    {
        $this->messageMeta = new ArrayCollection();
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
    public function getBody()
    {
        return $this->body;
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
    public function setSender(ParticipantInterface $sender)
    {
        $this->sender = $sender;
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
    public function addMessageMeta(MessageMetaInterface $messageMeta)
    {
        $this->messageMeta->add($messageMeta);
    }

    /**
     * {@inheritdoc}
     */
    public function getMessageMeta()
    {
        return $this->messageMeta;
    }
}


