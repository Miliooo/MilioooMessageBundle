<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Manager;

use Miliooo\Messaging\User\ParticipantInterface;
use Miliooo\Messaging\Model\ThreadInterface;
use Miliooo\Messaging\Repository\ThreadRepositoryInterface;
use Miliooo\Messaging\ValueObjects\ThreadStatus;
use Miliooo\Messaging\Repository\MessageRepositoryInterface;
use Miliooo\Messaging\Model\ThreadMetaInterface;
use Miliooo\Messaging\Model\MessageMetaInterface;
use Miliooo\Messaging\ValueObjects\ReadStatus;

/**
 * ThreadStatusManager.
 *
 * The thread status manager is responsible for updating the thread status and persisting the updated thread to
 * the storage engine.
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class ThreadStatusManager implements ThreadStatusManagerInterface
{
    /**
     * A thread repository instance.
     *
     * @var ThreadRepositoryInterface
     */
    private $threadRepository;

    /**
     * A message repository instance.
     *
     * @var MessageRepositoryInterface
     */
    private $messageRepository;

    /**
     * A read status manager instance.
     *
     * @var ReadStatusManagerInterface
     */
    private $readStatusManager;

    /**
     * @param ThreadRepositoryInterface  $threadRepository  A thread repository
     * @param MessageRepositoryInterface $messageRepository A message repository
     * @param ReadStatusManagerInterface $readStatusManager A read status manager
     */
    public function __construct(
        ThreadRepositoryInterface $threadRepository,
        MessageRepositoryInterface $messageRepository,
        ReadStatusManagerInterface $readStatusManager
    ) {
        $this->threadRepository = $threadRepository;
        $this->messageRepository = $messageRepository;
        $this->readStatusManager = $readStatusManager;
    }

    /**
     * {@inheritdoc}
     */
    public function updateThreadStatusForParticipant(
        ThreadStatus $newThreadStatus,
        ThreadInterface $thread,
        ParticipantInterface $participant
    ) {
        $threadMeta = $thread->getThreadMetaForParticipant($participant);

        //get the integer value of the thread status.
        $newThreadStatusInt = $newThreadStatus->getThreadStatus();
        $oldThreadStatusInt = $threadMeta->getStatus();


        //if no thread meta can happen if the current user is not participant of the thread
        if (!$threadMeta || $oldThreadStatusInt === $newThreadStatusInt) {
            return false;
        }

        //updates the status
        $threadMeta->setStatus($newThreadStatusInt);

        //saves the thread
        $this->threadRepository->save($thread);

        //create a value object from the old status integer
        $oldThreadStatus = new ThreadStatus($oldThreadStatusInt);
        $this->maybeMarkMessagesAsRead($thread, $participant, $oldThreadStatus, $newThreadStatus);

        return true;
    }

    /**
     * When archiving an active thread we want to mark all unread messages as read.
     *
     * Since this is a thread status update we do this here. We do use the read status manager for this should also
     * dispatch a new read status event.
     *
     * @param ThreadInterface      $thread          The current thread
     * @param ParticipantInterface $participant     The participant who wants to update the thread status
     * @param ThreadStatus         $oldThreadStatus The integer value of the old thread status
     * @param ThreadStatus         $newThreadStatus The integer value of the new thread status
     *
     * @return boolean false if no messages where marked as read, true if there were messages marked as read
     */
    protected function maybeMarkMessagesAsRead(
        ThreadInterface $thread,
        ParticipantInterface $participant,
        ThreadStatus $oldThreadStatus,
        ThreadStatus $newThreadStatus
    ) {
        if (!$this->updateActiveThreadToArchivedThread($oldThreadStatus, $newThreadStatus)) {
            return false;
        }

        //get the messages...
        $messages = $this->messageRepository->getUnreadMessagesFromThreadForParticipant($participant, $thread);
        // return if not array (should never happen) or if empty messages()
        if (!is_array($messages) || empty($messages)) {
            return false;
        }

        $readStatus = new ReadStatus(MessageMetaInterface::READ_STATUS_READ);
        $this->readStatusManager->updateReadStatusForMessageCollection($readStatus, $participant, $messages);

        return true;
    }

    /**
     * Checks whether we are updating the thread status from an active to an archived thread
     *
     * @param ThreadStatus $oldThreadStatus
     * @param ThreadStatus $newThreadStatus
     *
     * @return boolean true if we are updating an active thread to an archived thread false otherwise
     */
    protected function updateActiveThreadToArchivedThread(ThreadStatus $oldThreadStatus, ThreadStatus $newThreadStatus)
    {
        if ($oldThreadStatus->getThreadStatus() !== ThreadMetaInterface::STATUS_ACTIVE
            ||
            $newThreadStatus->getThreadStatus() !== ThreadMetaInterface::STATUS_ARCHIVED
        ) {
            return false;
        }

        return true;
    }
}
