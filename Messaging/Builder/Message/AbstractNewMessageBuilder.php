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
use Miliooo\Messaging\Model\ThreadMetaInterface;
use Miliooo\Messaging\Model\MessageMetaInterface;
use Miliooo\Messaging\Model\MessageInterface;
use Miliooo\Messaging\Model\ThreadInterface;

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

    /**
     * Updates the thread meta with settings specific for the sender
     *
     * @param ThreadMetadata $threadMeta
     */
    protected function updateThreadMetaForSender(ThreadMetaInterface $threadMeta)
    {
        //we set the last message date for the sender, this makes it an inbox thread
        $threadMeta->setLastParticipantMessageDate($this->createdAt);
        $threadMeta->setIsArchived(false);
    }

    /**
     * Updates the thread meta with the settings specific for the recipient
     *
     * @param ThreadMetadata $threadMeta
     */
    protected function updateThreadMetaForRecipient(ThreadMetaInterface $threadMeta)
    {
        //we set the deleted to false this is debatable but else they don't see the new message appear in their inbox
        // but then again this is probably what some users would want
        $threadMeta->setIsArchived(false);
        //we set the last message date for the receivers, this makes those threads go to the top of their inbox
        $threadMeta->setLastMessageDate($this->createdAt);
    }

    /**
     * Builds a new message and adds it to the thread
     *
     * @param ThreadInterface $thread
     */
    final protected function buildNewMessage(ThreadInterface $thread)
    {
        $message = $this->createMessage();
        $message->setBody($this->body);
        $message->setThread($thread);
        $message->setCreatedAt($this->createdAt);
        $message->setSender($this->sender);

        $messageMeta = $this->createNewMessageMetaForParticipant($message, $this->sender);
        $this->updateMessageMetaForSender($messageMeta);

        foreach ($this->recipients as $recipient) {
            //setup the meta data for recipients
            $messageMeta = $this->createNewMessageMetaForParticipant($message, $recipient);
            $this->updateMessageMetaForRecipient($messageMeta);
        }

        $thread->addMessage($message);
    }

    /**
     * creates a new message object
     *
     * @return MessageInterface
     */
    protected function createMessage()
    {
        return new $this->messageClass;
    }

    /**
     * creates a new message meta object
     *
     * @return MessageMetaInterface
     */
    protected function createMessageMeta()
    {
        return new $this->messageMetaClass;
    }

    protected function updateMessageMetaForSender(MessageMetaInterface $messageMeta)
    {
        $messageMeta->setIsRead(true);
    }

    protected function updateMessageMetaForRecipient(MessageMetaInterface $messageMeta)
    {
        $messageMeta->setIsRead(false);
    }

    /**
     * Creates new message meta for the participant
     *
     * @param MessageInterface     $message     The message the meta belongs
     * @param ParticipantInterface $participant The participant in the thread this message belongs to
     *
     * @return MessageMetadata
     */
    private function createNewMessageMetaForParticipant(MessageInterface $message, ParticipantInterface $participant)
    {
        //set message meta data for sender
        $messageMeta = $this->createMessageMeta();
        $messageMeta->setMessage($message);
        $messageMeta->setParticipant($participant);
        $message->addMessageMeta($messageMeta);

        return $messageMeta;
    }
}
