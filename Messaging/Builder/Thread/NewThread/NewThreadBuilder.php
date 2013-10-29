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

/**
 * The builder for a new thread.
 *
 * This class is responsable for creating a new thread object from the setters.
 *
 * Since this is the last step before saving the object to the database we want
 * to make sure it's a valid thread object.
 *
 * That's why this class is abstract and we use the guarded classes.
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
        $this->addParticipantsToThread($thread);

        return $thread;
    }

    protected function createThread()
    {
        return new $this->threadClass();
    }

    protected function createThreadMeta()
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
     * Adds participants to a thread
     *
     * @param ThreadInterface $thread The thread where we add the participants
     */
    private function addParticipantsToThread(ThreadInterface $thread)
    {
        $thread->addParticipant($this->sender);
        foreach ($this->recipients as $recipient) {
            $thread->addParticipant($recipient);
        }
    }
}
