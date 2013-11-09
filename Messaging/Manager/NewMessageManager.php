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
use Miliooo\Messaging\Repository\ThreadRepositoryInterface;
use Miliooo\Messaging\Model\MessageInterface;

/**
 * The new message manager is responsible for saving new messages
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class NewMessageManager implements NewMessageManagerInterface
{
    /**
     * A message repository instance.
     *
     * @var MessageRepositoryInterface
     */
    protected $messageRepository;

    /**
     * A thread repository instance.
     *
     * @var ThreadRepositoryInterface
     */
    protected $threadRepository;

    /**
     * Constructor.
     *
     * @param MessageRepositoryInterface $messageRepository
     * @param ThreadRepositoryInterface $threadRepository
     */
    public function __construct(
        MessageRepositoryInterface $messageRepository,
        ThreadRepositoryInterface $threadRepository
    ) {
        $this->messageRepository = $messageRepository;
        $this->threadRepository = $threadRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function saveNewThread(MessageInterface $message)
    {
        $this->saveNewMessage($message);
    }

    /**
     * {@inheritdoc}
     */
    public function saveNewReply(MessageInterface $message)
    {
        $this->saveNewMessage($message);
    }

    /**
     * Saves a new message to the persistent storage
     *
     * @param MessageInterface $message
     */
    protected function saveNewMessage(MessageInterface $message)
    {
        $thread = $message->getThread();
        $this->messageRepository->save($message, false);
        $this->threadRepository->save($thread, true);
    }
}
