<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Builder\Message;

use Miliooo\Messaging\Model\ThreadInterface;
use Miliooo\Messaging\User\ParticipantInterface;
use Miliooo\Messaging\Model\ThreadMetaInterface;
use Miliooo\Messaging\Model\MessageInterface;
use Miliooo\Messaging\Model\MessageMetaInterface;

/**
 * Description of AbstractMessageBuilder
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
abstract class AbstractMessageBuilder
{
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

    protected function buildNewMessage(ThreadInterface $thread)
    {
        $message = $this->createMessage();
        $this->setMessageData($message);
        $message->setThread($thread);
        $thread->addMessage($message);
        $this->createMessageMetaForNewMessage($message);
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

    protected function setMessageData(MessageInterface $message)
    {
        $this->processbuilderModel('getMessageData', null, $message);
    }

    protected function updateMessageMetaForSender(MessageMetaInterface $messageMeta)
    {
        $this->processBuilderModel('getMessageMeta', 'all', $messageMeta);
        $this->processBuilderModel('getMessageMeta', 'sender', $messageMeta);
    }

    protected function updateMessageMetaForRecipient(MessageMetaInterface $messageMeta)
    {
        $this->processBuilderModel('getMessageMeta', 'all', $messageMeta);
        $this->processBuilderModel('getMessageMeta', 'recipients', $messageMeta);
    }

    protected function processBuilderModel($callMethodName, $callMethodArgument, $object)
    {
        $data = $this->builderModel->$callMethodName($callMethodArgument);

        if (!$data) {
            return $object;
        }

        foreach ($data as $key => $value) {
            $setterMethod = $this->getSetter($key, $object);
            $object->$setterMethod($value);
        }

        return $object;
    }

    protected function getSetter($key, $object)
    {
        if (!is_string($key)) {
            throw new \InvalidArgumentException('could not create setter method, no string given');
        }

        //try with setMethodName
        $setMethodName = 'set' . ucFirst($key);

        if (method_exists($object, $setMethodName) && is_callable([$object, $setMethodName])) {
            return $setMethodName;
        }

        throw new \InvalidArgumentException(sprintf('could not find setter for %s', $key));
    }

    /**
     * creates a new message object
     *
     * @return MessageInterface
     */
    private function createMessage()
    {
        return new $this->messageClass;
    }

    /**
     * creates a new message meta object
     *
     * @return MessageMetaInterface
     */
    private function createMessageMeta()
    {
        return new $this->messageMetaClass;
    }

    private function createMessageMetaForNewMessage($message)
    {
        $messageMeta = $this->createNewMessageMetaForParticipant($message, $this->sender);
        $this->updateMessageMetaForSender($messageMeta);

        foreach ($this->recipients as $recipient) {
            $messageMeta = $this->createNewMessageMetaForParticipant($message, $recipient);
            $this->updateMessageMetaForRecipient($messageMeta);
        }
    }

    /**
     * Updates the thread meta with settings specific for the sender
     *
     * @param ThreadMetadata $threadMeta
     */
    protected function updateThreadMetaForSender(ThreadMetaInterface $threadMeta)
    {
        $this->processBuilderModel('getThreadMeta', 'all', $threadMeta);
        $this->processBuilderModel('getThreadMeta', 'sender', $threadMeta);
    }

    /**
     * Updates the thread meta with the settings specific for the recipient
     *
     * @param ThreadMetadata $threadMeta
     */
    protected function updateThreadMetaForRecipient(ThreadMetaInterface $threadMeta)
    {
        $this->processBuilderModel('getThreadMeta', 'all', $threadMeta);
        $this->processBuilderModel('getThreadMeta', 'recipients', $threadMeta);
    }
}
