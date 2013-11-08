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

    protected $messageModel;
    protected $data = array();

    public function __construct(NewMessageInterface $messageModel)
    {
        $this->messageModel = $messageModel;
        $this->processMessage();
    }

    public function processMessage()
    {
        $this->addMessageData('body', $this->messageModel->getBody());
        $this->addMessageData('createdAt', $this->messageModel->getCreatedAt());
        $this->addMessageData('sender', $this->messageModel->getSender());

        $this->addMessageMeta(self::SENDER, 'isRead', true);
        $this->addMessageMeta(self::RECIPIENTS, 'isRead', false);

        $this->addThreadMeta(self::SENDER, 'lastParticipantMessageDate', $this->messageModel->getCreatedAt());
        $this->addThreadMeta(self::RECIPIENTS, 'lastMessageDate', $this->messageModel->getCreatedAt());

        $this->addThreadMeta(self::ALL, 'isArchived', false);

        $this->processExtra();
    }

    public function getSender()
    {
        return $this->messageModel->getSender();
    }

    public function addMessageData($key, $value)
    {
        $this->addData('message', $key, $value);
    }

    public function getMessageData()
    {
        return $this->getData('message');
    }

    public function addThreadData($key, $value)
    {
        $this->addData('thread', $key, $value);
    }

    public function getThreadData()
    {
        return $this->getData('thread');
    }

    public function addMessageMeta($participant, $key, $value)
    {
        $this->addMeta('messageMeta', $participant, $key, $value);
    }

    public function getMessageMeta($participant)
    {
        return $this->getMeta('messageMeta', $participant);
    }

    public function addThreadMeta($participant, $key, $value)
    {
        $this->addMeta('threadMeta', $participant, $key, $value);
    }

    public function getThreadMeta($participant)
    {
        return $this->getMeta('threadMeta', $participant);
    }

    public function addMeta($name, $participant, $key, $value)
    {
        $this->data[$name][self::$group[$participant]][$key] = $value;
    }

    protected function getMeta($name, $participant)
    {
        return isset($this->data[$name][self::$group[$participant]]) ?
            $this->data[$name][self::$group[$participant]] : null;
    }

    protected function addData($name, $key, $value)
    {
        $this->data[$name][$key] = $value;
    }

    protected function getData($name)
    {
        return $this->data[$name];
    }

    abstract protected function processExtra();
}
