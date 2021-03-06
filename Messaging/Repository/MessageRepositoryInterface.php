<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Repository;

use Miliooo\Messaging\Model\MessageInterface;
use Miliooo\Messaging\User\ParticipantInterface;
use Miliooo\Messaging\Model\ThreadInterface;

/**
 * Interface for threadRepository instances
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
interface MessageRepositoryInterface
{
    /**
     * @param ParticipantInterface $participant
     * @param ThreadInterface      $thread
     *
     * @return MessageInterface[] Array of messages
     */
    public function getUnreadMessagesFromThreadForParticipant(ParticipantInterface $participant, ThreadInterface $thread);
    /**
     * Saves a message to the storage engine
     *
     * @param MessageInterface $message The message we save
     * @param bool $flush Whether to flush or not defaults to true
     */
    public function save(MessageInterface $message, $flush = true);

    /**
     * Calls flush to the entityManager.
     *
     * If you want to persist multiple objects but only flush once you can call this method.
     * This calls flush to the entity manager and all persisted objects will be saved.
     */
    public function flush();
}
