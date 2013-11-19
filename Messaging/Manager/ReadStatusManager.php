<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Manager;

use Miliooo\Messaging\Repository\MessageRepositoryInterface;
use Miliooo\Messaging\Model\MessageInterface;
use Miliooo\Messaging\User\ParticipantInterface;
use Miliooo\Messaging\ValueObjects\ReadStatus;
use Miliooo\Messaging\Model\MessageMetaInterface;

/**
 * The read status manager is responsible for changing the read statuses of messages.
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class ReadStatusManager implements ReadStatusManagerInterface
{
    /**
     * A message repository instance.
     *
     * @var MessageRepositoryInterface
     */
    protected $messageRepository;

    /**
     * Helper to only flush once.
     *
     * If there are updates this becomes true so we know we need to flush.
     * @var boolean defaults to false
     */
    private $needsUpdate = false;

    private $updatedMessages = [];

    /**
     * Constructor.
     *
     * @param MessageRepositoryInterface $messageRepository
     */
    public function __construct(MessageRepositoryInterface $messageRepository)
    {
        $this->messageRepository = $messageRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function updateReadStatusForMessageCollection(
        ReadStatus $updatedReadStatus,
        ParticipantInterface $participant,
        $messages = []
    ) {
        foreach ($messages as $message) {
            $updated = $this->maybeMarkMessageAs($updatedReadStatus, $participant, $message);

            if ($updated) {
                $this->messageRepository->save($message, false);
                $this->needsUpdate = true;
                $this->updatedMessages[] = $message;
            }
        }
        //helper to only flush once
        //if one message is updated the needsUpdate gets set to true, so we know we need to flush after the loop
        $this->maybeFlush();

        return $this->updatedMessages;
    }

    /**
     * Updates a message read status to a new read status.
     *
     * This function is protected because it does not persist the message.
     * We only want to flush once so this is just a helper function for updateReadStatusForMessageCollection
     *
     * @param ReadStatus           $newReadStatus The new read status
     * @param ParticipantInterface $participant   The participant where we check the read status for
     * @param MessageInterface     $message       The message where we check the read status for
     *
     * @throws \InvalidArgumentException When no message meta found for given participant
     *
     * @return boolean true if the message has been updated, false otherwise
     */
    protected function maybeMarkMessageAs(
        ReadStatus $newReadStatus,
        ParticipantInterface $participant,
        MessageInterface $message
    ) {
        $readStatusValue = $newReadStatus->getReadStatus();
        $messageMeta = $message->getMessageMetaForParticipant($participant);

        //if no message meta can happen if the logged in user is not participant of the thread
        if ($messageMeta === null || $messageMeta->getReadStatus() === $readStatusValue) {
            return false;
        }
                $messageMeta->setReadStatus($newReadStatus);
                $this->updateThreadMetaUnreadCount($message, $participant, $newReadStatus);

            return true;
    }

    /**
     * Helper function to only flush once
     */
    protected function maybeFlush()
    {
        if ($this->needsUpdate) {
            $this->messageRepository->flush();
            $this->needsUpdate = false;
        }
    }


    /**
     * Updates the thread meta unread count for the participant.
     *
     * @param MessageInterface     $message
     * @param ParticipantInterface $participant
     * @param ReadStatus           $newReadStatus
     */
    protected function updateThreadMetaUnreadCount(
        MessageInterface $message,
        ParticipantInterface $participant,
        ReadStatus $newReadStatus
    ) {
        //get the thread
        $thread = $message->getThread();

        //get the thread meta for the participant
        $threadMeta = $thread->getThreadMetaForParticipant($participant);
        //return if no thread meta, should not happen
        if (!$threadMeta) {
            return;
        }

        // gets the integer of the number of unread messages
        $unreadCount = $threadMeta->getUnreadMessageCount();

        //we marked a message as read so we lower the unread count with one
        if ($newReadStatus->getReadStatus() === MessageMetaInterface::READ_STATUS_READ) {
            $newUnreadCount = --$unreadCount;
        } else {
            $newUnreadCount = ++$unreadCount;
        }

        $threadMeta->setUnreadMessageCount($newUnreadCount);
    }
}
