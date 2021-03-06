<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Builder\Message;

use Miliooo\Messaging\Builder\Model\ThreadBuilderModel;
use Miliooo\Messaging\Model\ThreadInterface;
use Miliooo\Messaging\User\ParticipantInterface;
use Miliooo\Messaging\Model\ThreadMetaInterface;

/**
 * Builder for new threads
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class NewThreadBuilder extends AbstractMessageBuilder
{
    protected $builderModel;
    protected $sender;
    protected $recipients = [];

    /**
     * Builds a new thread object with one new message
     *
     * @param ThreadBuilderModel $builderModel
     *
     * @return ThreadInterface
     */
    public function build(ThreadBuilderModel $builderModel)
    {
        $this->builderModel = $builderModel;
        $this->sender = $this->builderModel->getSender();
        $this->recipients = $this->builderModel->getRecipients();

        $thread = $this->createThread();
        $this->setThreadData($thread);
        $this->createThreadMetaForNewThread($thread);
        $this->buildNewMessage($thread);

        return $thread;
    }

    /**
     * We update the thread with the thread data from the builder
     *
     * @param ThreadInterface $thread
     */
    protected function setThreadData(ThreadInterface $thread)
    {
        $this->processBuilderModel('getThreadData', null, $thread);
    }

    /**
     * Creates new thread meta for a single participant
     *
     * @param ThreadInterface      $thread      The thread we are building
     * @param ParticipantInterface $participant The participant whom we create thread meta for
     *
     * @return ThreadMetaInterface
     */
    private function createThreadMetaForParticipant(ThreadInterface $thread, ParticipantInterface $participant)
    {
        //creates an empty thread meta object
        $threadMeta = $this->createThreadMeta();

        //adds the participant to the threadmeta
        $threadMeta->setParticipant($participant);

        //adds the thread to the thread meta
        $threadMeta->setThread($thread);

        //adds the thread meta to the thread
        $thread->addThreadMeta($threadMeta);

        return $threadMeta;
    }

    /**
     * Creates a new thread meta object from the config (user specified) thread meta class
     *
     * @return ThreadMetaInterface
     */
    private function createThreadMeta()
    {
        return new $this->threadMetaClass();
    }

    /**
     * Creates thread meta for a new thread.
     *
     * We need thread meta for each participant
     *
     * @param ThreadInterface $thread The thread we create thread meta for
     */
    private function createThreadMetaForNewThread(ThreadInterface $thread)
    {
        $this->buildThreadMetaForSender($thread);

        foreach ($this->recipients as $recipient) {
            $this->buildThreadMetaForRecipient($thread, $recipient);
        }
    }

    /**
     * Builds the thread meta for the sender
     *
     * @param ThreadInterface $thread
     */
    private function buildThreadMetaForSender(ThreadInterface $thread)
    {
        $threadMeta = $this->createThreadMetaForParticipant($thread, $this->sender);
        $this->updateThreadMetaForSender($threadMeta);
    }

    /**
     * Builds the thread meta for the recipient
     *
     * @param ThreadInterface      $thread
     * @param ParticipantInterface $recipient
     */
    private function buildThreadMetaForRecipient(ThreadInterface $thread, ParticipantInterface $recipient)
    {
        $threadMeta = $this->createThreadMetaForParticipant($thread, $recipient);
        $this->updateThreadMetaForRecipient($threadMeta);
    }

    /**
     * Creates a new thread object from the config (user specified) thread class
     *
     * @return ThreadInterface
     */
    private function createThread()
    {
        return new $this->threadClass();
    }
}
