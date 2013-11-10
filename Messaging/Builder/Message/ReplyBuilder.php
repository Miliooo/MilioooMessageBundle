<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Builder\Message;

use Miliooo\Messaging\Builder\Model\ReplyBuilderModel;
use Miliooo\Messaging\Model\ThreadInterface;

/**
 * Builder for reply messages
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class ReplyBuilder extends AbstractMessageBuilder implements ReplyBuilderInterface
{
    protected $builderModel;
    protected $sender;
    protected $recipients;

    /**
     * @var ThreadInterface
     */
    protected $thread;

    /**
     * Builds a new thread object with a reply message
     *
     * @param ReplyBuilderModel $builderModel
     *
     * @return ThreadInterface
     */
    public function build(ReplyBuilderModel $builderModel)
    {
        $this->builderModel = $builderModel;
        $this->sender = $this->builderModel->getSender();
        $this->thread = $this->builderModel->getThread();
        $this->recipients = $this->thread->getOtherParticipants($this->sender);
        $this->buildNewMessage($this->thread);
        $this->updateReplyThreadMeta();

        return $this->thread;
    }

    private function updateReplyThreadMeta()
    {
        //update the thread meta for the sender
        $senderThreadMeta = $this->thread->getThreadMetaForParticipant($this->sender);
        $this->updateThreadMetaForSender($senderThreadMeta);

        //update the thread meta for the recipients
        foreach ($this->recipients as $recipient) {

            $recipientThreadMeta = $this->thread->getThreadMetaForParticipant($recipient);
            $this->updateThreadMetaForRecipient($recipientThreadMeta);
        }
    }
}
