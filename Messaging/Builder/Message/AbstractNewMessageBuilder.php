<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Builder\Message;

use Miliooo\Messaging\User\ParticipantInterface;

/**
 * AbstractNewMessageBuilder.
 *
 * The AbstractNewMessagebuilder is responsible for helping to build new thread
 * and reply messages.
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
abstract class AbstractNewMessageBuilder
{
    /**
     * The sender of a new message
     *
     * @var ParticipantInterface
     */
    protected $sender;

    /**
     * The body of the message
     *
     * @var type
     */
    protected $body;

    /**
     * The creation date of the message
     * If we build a new thread also the creation date of the new thread
     *
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * Fully qualified namespace of the custom message class
     *
     * @var string
     */
    protected $messageClass;

    /**
     * Fully qualified names of the message meta class
     *
     * @var string
     */
    protected $messageMetaClass;

    /**
     * Fully qualified name of the thread class
     *
     * @var string
     */
    protected $threadClass;

    /**
     * Fully qualified name of the thread meta class
     *
     * @var string
     */
    protected $threadMetaClass;

    /**
     * Sets the message class
     *
     * @param string $messageClass Fully qualified name of message class
     */
    public function setMessageClass($messageClass)
    {
        $this->messageClass = $messageClass;
    }

    /**
     * Sets the message meta class
     *
     * @param string $messageMetaClass Fully qualified name the message meta class
     */
    public function setMessageMetaClass($messageMetaClass)
    {
        $this->messageMetaClass = $messageMetaClass;
    }

    /**
     * Sets the thread class
     *
     * @param string $threadClass Fully qualified name of the thread class
     */
    public function setThreadClass($threadClass)
    {
        $this->threadClass = $threadClass;
    }

    /**
     * Sets the thread meta class
     *
     * @param string $threadMetaClass Fully qualified name of the thread meta class
     */
    public function setThreadMetaClass($threadMetaClass)
    {
        $this->threadMetaClass = $threadMetaClass;
    }

    /**
     * Sets the sender of the thread and message
     *
     * @param ParticipantInterface $sender
     */
    public function setSender(ParticipantInterface $sender)
    {
        $this->sender = $sender;
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
     * Sets the creation date and time of the message
     * If a new thread this is also the creation date and time of the new thread
     *
     * @param \Datetime $dateTime
     */
    public function setCreatedAt(\Datetime $dateTime)
    {
        $this->createdAt = $dateTime;
    }
}
