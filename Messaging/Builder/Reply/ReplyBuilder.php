<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Builder\Reply;

use Miliooo\Messaging\Builder\Message\AbstractNewMessageBuilder;
use Miliooo\Messaging\Model\ThreadInterface;

/**
 * Description of ReplyBuilder
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class ReplyBuilder extends AbstractNewMessageBuilder
{
    /**
     * The thread we reply to
     *
     * @var ThreadInterface
     */
    protected $thread;
    protected $recipients;


    /**
     * Sets the thread where we will reply to
     *
     * @param ThreadInterface $thread
     */
    public function setThread(ThreadInterface $thread)
    {
        $this->thread = $thread;
    }

    /**
     * Builds a new thread with the reply added
     *
     * @return ThreadInterface the new build thread
     */
    public function build()
    {
        $this->recipients = $this->thread->getOtherParticipants($this->sender);
        $this->buildNewMessage($this->thread);
        $senderThreadMeta = $this->thread->getThreadMetaForParticipant($this->sender);
        $this->updateThreadMetaForSender($senderThreadMeta);
        foreach ($this->recipients as $recipient) {
            $recipientThreadMeta = $this->thread->getThreadMetaForParticipant($recipient);
            $this->updateThreadMetaForRecipient($recipientThreadMeta);
        }

        return $this->thread;
    }
}
