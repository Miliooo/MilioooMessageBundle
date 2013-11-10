<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Builder\Model;

use Miliooo\Messaging\Form\FormModel\NewMessageInterface;
use Miliooo\Messaging\User\ParticipantInterface;

/**
 * Description of AbstractMessageBuilderModel
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
abstract class AbstractMessageBuilderModel
{
    const SENDER = 'sender';
    const ALL = 'all';
    const RECIPIENTS = 'recipients';

    private static $group = [
        'sender' => self::SENDER,
        'all' => self::ALL,
        'recipients' => self::RECIPIENTS
    ];

    /**
     * @var NewMessageInterface
     */
    protected $messageModel;

    /**
     * The array where we'll save all the data to
     *
     * @var array
     */
    protected $data = [];

    /**
     * Constructor.
     *
     * @param NewMessageInterface $messageModel
     */
    public function __construct(NewMessageInterface $messageModel)
    {
        $this->messageModel = $messageModel;
        $this->processDefaultMessageData();
    }

    /**
     * This processes the default message data.
     *
     * This method is called in the constructor and populates the default new message data.
     * It also calls a method process extra which classes who extend this class have to implement.
     *
     * There we process some more default data. See the threadbuildermodel for an example.
     *
     * If you want to overwrite this data (not recommended!!) you can call the same method with the same key.
     * This will overwrite that data in the array with your given value.
     */
    protected function processDefaultMessageData()
    {
        $this->addMessageData('body', $this->messageModel->getBody());
        $this->addMessageData('createdAt', $this->messageModel->getCreatedAt());
        $this->addMessageData('sender', $this->messageModel->getSender());

        $this->addMessageMeta(self::SENDER, 'isRead', true);
        $this->addMessageMeta(self::RECIPIENTS, 'isRead', false);

        $this->addThreadMeta(self::SENDER, 'lastParticipantMessageDate', $this->messageModel->getCreatedAt());
        $this->addThreadMeta(self::RECIPIENTS, 'lastMessageDate', $this->messageModel->getCreatedAt());

        $this->processExtra();
    }

    /**
     * Gets the sender of the message
     *
     * @return ParticipantInterface
     */
    public function getSender()
    {
        return $this->messageModel->getSender();
    }

    /**
     * Add message data to your message class.
     *
     * @param string $key   The name of your classes attribute, example ip => builder will call setIp
     * @param mixed  $value The value of the attribute you want to set
     */
    public function addMessageData($key, $value)
    {
        $this->addData('message', $key, $value);
    }

    /**
     * Returns the message data.
     *
     * The key is the attribute name and the value is the value.
     * Example data['subject' => 'The subject of the message, 'createdAt' => \Datetime object]
     *
     * @return array An array with message data needed to populate the message class.
     */
    public function getMessageData()
    {
        return $this->getData('message');
    }

    /**
     * Add thread data to your thread class.
     *
     * @param string $key   The name of your thread class attribute
     * @param mixed  $value The value for your attribute
     */
    public function addThreadData($key, $value)
    {
        $this->addData('thread', $key, $value);
    }

    /**
     * Gets the data for the thread class.
     *
     * This function is used by the builders to populate the thread object.
     *
     * @return array||null An array where the keys are the names of the attributes and the values the attribute values
     */
    public function getThreadData()
    {
        return $this->getData('thread');
    }

    /**
     * Adds message meta for a given participant group to your message meta class.
     *
     * @param string $participant One of the class constants SENDER ALL RECIPIENTS
     * @param string $key         The name of your message meta class attribute you want to add data for.
     * @param mixed  $value       The value for your attribute
     */
    public function addMessageMeta($participant, $key, $value)
    {
        $this->addMeta('messageMeta', $participant, $key, $value);
    }

    /**
     * Returns the message meta for the given group of participants.
     *
     * @param string $participant One of the class constants SENDER ALL RECIPIENTS
     *
     * @return array||null An array where the keys are the names of the attributes and the values the attribute values
     */
    public function getMessageMeta($participant)
    {
        return $this->getMeta('messageMeta', $participant);
    }

    /**
     * Adds thread meta for a given participant group to the thread meta class.
     *
     * @param string $participant One of the class constants SENDER ALL RECIPIENTS
     * @param string $key         The name of your thread meta class attribute
     * @param mixed  $value       The value of your thread meta class attribute
     */
    public function addThreadMeta($participant, $key, $value)
    {
        $this->addMeta('threadMeta', $participant, $key, $value);
    }

    /**
     * Returns the thread meta for the given group of participants.
     *
     * @param string $participant One of the class constants SENDER ALL RECIPIENTS
     *
     * @return array||null An array where the keys are the names of the attributes and the values the attribute values
     */
    public function getThreadMeta($participant)
    {
        return $this->getMeta('threadMeta', $participant);
    }

    /**
     * Helper function to populate the data array.
     *
     * @param string $name        Name of the meta one of threadMeta, messageMeta
     * @param string $participant One of the class constants SENDER ALL RECIPIENTS
     * @param string $key         The name of the class attribute
     * @param mixed  $value       The value of the class attribute
     */
    protected function addMeta($name, $participant, $key, $value)
    {
        $this->data[$name][self::$group[$participant]][$key] = $value;
    }

    /**
     * Helper function to get meta.
     *
     * @param string $name        Name of the meta one of threadMeta, messageMeta
     * @param string $participant One of the class constants SENDER ALL RECIPIENTS
     *
     * @return array||null An array where the keys are the names of the attributes and the values the attribute values
     */
    protected function getMeta($name, $participant)
    {
        return isset($this->data[$name][self::$group[$participant]]) ?
            $this->data[$name][self::$group[$participant]] : null;
    }

    /**
     * Helper function to add data to the array.
     *
     * @param string $name  One of message, thread
     * @param string $key   Name of the attribute for the given class
     * @param mixed  $value Value for that attribute
     */
    protected function addData($name, $key, $value)
    {
        $this->data[$name][$key] = $value;
    }

    /**
     * Helper function to get data from the array.
     *
     * @param string $name One of message, thread
     *
     * @return array||null An array where the keys are the names of the attributes and the values the attribute values
     */
    protected function getData($name)
    {
        return isset($this->data[$name]) ? $this->data[$name] : null;
    }

    /**
     * Processes more default data specific to a new thread or a new reply.     *
     */
    abstract protected function processExtra();
}
