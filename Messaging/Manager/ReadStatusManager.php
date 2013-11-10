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
    public function markMessageCollectionAsRead(ParticipantInterface $participant, $messages = [])
    {
        if (!is_array($messages)) {
            throw new \InvalidArgumentException('expects array with messages as second argument');
        }

        foreach ($messages as $message) {
            $updated = $this->maybeMarkMessageAsRead($participant, $message);

            if ($updated) {
                $this->messageRepository->save($message, false);
                $this->needsUpdate = true;
            }
        }
        //helper to only flush once
        //if one message is updated the needsUpdate gets set to true, so we know we need to flush after the loop
        $this->maybeFlush();
    }

    /**
     * Maybe marks a single message as read.
     *
     * This function is protected because it does not persist the message.
     * We only want to flush once so this is just a helper function for markMessageCollectionAsRead
     *
     * @param ParticipantInterface $participant The participant where we check the read status for
     * @param MessageInterface $message The message where we check the read status for
     *
     * @throws \InvalidArgumentException When no message meta found for given participant
     *
     * @return boolean true if the message has been marked as read (updated), false otherwise
     */
    protected function maybeMarkMessageAsRead(ParticipantInterface $participant, MessageInterface $message)
    {
        $messageMeta = $message->getMessageMetaForParticipant($participant);

        if ($messageMeta === null) {
            throw new \InvalidArgumentException('No message meta found for the given participant');
        }

        if ($messageMeta->isRead() === false) {
            $messageMeta->setIsRead(true);
            $messageMeta->setNewRead(true);

            return true;
        }

        return false;
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
}
