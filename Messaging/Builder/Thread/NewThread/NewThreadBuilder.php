<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Builder\Thread\NewThread;

use Miliooo\Messaging\Builder\Message\AbstractNewMessageBuilder;
use Miliooo\Messaging\User\ParticipantInterface;
use Miliooo\Messaging\Model\ThreadInterface;
use Miliooo\Messaging\Model\ThreadMetaInterface;

/**
 * The builder for a new thread.
 *
 * This class is responsable for creating a new thread object with the message.
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
abstract class NewThreadBuilder extends AbstractNewMessageBuilder
{
    /**
     * The recipients
     *
     * @var ParticipantInterface[] array of Recipients
     */
    protected $recipients = array();

    /**
     * The subject of the thread
     *
     * @var string
     */
    protected $subject;

    /**
     * Sets the recipients of the thread
     *
     * We also validate here if it's really an array with participantinterfaces
     *
     * @param ParticipantInterface[] $recipients array of recipients
     *
     * @throws \InvalidArgumentException if not an array with participantinterfaces
     */
    public function setRecipients($recipients = array())
    {
        if (!is_array($recipients)) {
            throw new \InvalidArgumentException('SetRecipients requires an array as argument');
        }

        foreach ($recipients as $recipient) {
            if (!$recipient instanceof ParticipantInterface) {
                throw new \InvalidArgumentException('Recipients need to implement ParticipantInterface');
            }
        }

        $this->recipients = $recipients;
    }

    /**
     * Sets the subject of the thread
     *
     * @param string $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * Builds the thread object.
     *
     * Here the new thread object gets build
     *
     * @return ThreadInterface
     */
    public function build()
    {
        $thread = $this->buildNewThreadWithRequiredValues();
        $this->buildThreadMetaForSender($thread);

        foreach ($this->recipients as $recipient) {
            $this->buildThreadMetaForRecipient($thread, $recipient);
        }

        $this->buildNewMessage($thread);

        return $thread;
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
     * Builds a new thread and sets the required values
     *
     * @return ThreadInterface
     */
    private function buildNewThreadWithRequiredValues()
    {
        $thread = $this->createThread();
        $thread->setCreatedAt($this->createdAt);
        $thread->setCreatedBy($this->sender);
        $thread->setSubject($this->subject);

        return $thread;
    }

    /**
     * Creates new threadmeta for the participant
     *
     * This creates new thread meta for the participant with the required settings set
     * It sets the
     * participant
     * current thread
     *
     * @param ThreadInterface      $thread
     * @param ParticipantInterface $participant
     *
     * @return ThreadMeta
     */
    private function createThreadMetaForParticipant(ThreadInterface $thread, ParticipantInterface $participant)
    {
        //creation of the thread meta
        $threadMeta = $this->createThreadMeta();
        $threadMeta->setParticipant($participant);
        $threadMeta->setThread($thread);
        $thread->addThreadMeta($threadMeta);

        return $threadMeta;
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
}
