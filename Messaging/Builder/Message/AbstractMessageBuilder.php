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

    /**
     * Builds a new message
     *
     * @param ThreadInterface $thread
     */
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
     * @return MessageMetaInterface
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

    /**
     * Updates the message with the message data in the builder model
     *
     * @param MessageInterface $message
     */
    protected function setMessageData(MessageInterface $message)
    {
        $this->processbuilderModel('getMessageData', null, $message);
    }

    /**
     * Updates the message meta for the sender.
     *
     * It processes first the message meta for all participants
     * Then it processes the message meta specific for the sender
     *
     * @param MessageMetaInterface $messageMeta
     */
    protected function updateMessageMetaForSender(MessageMetaInterface $messageMeta)
    {
        $this->processBuilderModel('getMessageMeta', 'all', $messageMeta);
        $this->processBuilderModel('getMessageMeta', 'sender', $messageMeta);
    }

    /**
     * Updates the message meta for the recipient.
     *
     * It processes first the message meta for all participants
     * Then it processes the message meta specific for the recipient.
     *
     * @param MessageMetaInterface $messageMeta
     */
    protected function updateMessageMetaForRecipient(MessageMetaInterface $messageMeta)
    {
        $this->processBuilderModel('getMessageMeta', 'all', $messageMeta);
        $this->processBuilderModel('getMessageMeta', 'recipients', $messageMeta);
    }

    /**
     * Updates the given object with the given methodName and argumentName
     *
     * @param string      $callMethodName     Calls the builder with this methodName
     * @param string|null $callMethodArgument Calls the builder with this argument for the given methodName
     * @param object      $object             The object which gets updated
     */
    protected function processBuilderModel($callMethodName, $callMethodArgument, $object)
    {
        $data = $this->builderModel->$callMethodName($callMethodArgument);

        if (!$data) {
            return;
        }

        foreach ($data as $key => $value) {
            $setterMethod = $this->getSetter($key, $object);
            $object->$setterMethod($value);
        }
    }

    /**
     * Gets the setter method for the given key.
     *
     * This function tries to find the setter for the given key. By convention this should be setKey.
     *
     * It checks the object if there is a method which is named setKey and if that method is callable (is public)
     * If not it throws an invalidArgumentException
     *
     * @param string $key
     * @param object $object
     *
     * @return string The callable setter method
     *
     * @throws \InvalidArgumentException If no callable setter found
     */
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

    /**
     * Creates the message meta for a new message.
     * @param $message
     */
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
     * @param ThreadMetaInterface $threadMeta
     */
    protected function updateThreadMetaForSender(ThreadMetaInterface $threadMeta)
    {
        $this->processBuilderModel('getThreadMeta', 'all', $threadMeta);
        $this->processBuilderModel('getThreadMeta', 'sender', $threadMeta);
    }

    /**
     * Updates the thread meta with the settings specific for the recipient
     *
     * @param ThreadMetaInterface $threadMeta
     */
    protected function updateThreadMetaForRecipient(ThreadMetaInterface $threadMeta)
    {
        $this->processBuilderModel('getThreadMeta', 'all', $threadMeta);
        $this->processBuilderModel('getThreadMeta', 'recipients', $threadMeta);
    }
}
