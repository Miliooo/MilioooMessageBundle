<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Manager;

use Doctrine\ORM\EntityManager;
use Miliooo\Messaging\Model\MessageInterface;

/**
 * The new message manager is responsible for saving new messages
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class NewMessageManager implements NewMessageManagerInterface
{
    protected $entityManager;

    /**
     * Constructor.
     *
     * @param EntityManager $entityManager An entity manager instance
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritdoc}
     */
    public function saveNewThread(MessageInterface $message)
    {
        $thread = $message->getThread();
        $this->entityManager->persist($message);
        $this->entityManager->persist($thread);
        $this->entityManager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function saveNewReply(MessageInterface $message)
    {
        $thread = $message->getThread();
        $this->entityManager->persist($message);
        $this->entityManager->persist($thread);
        $this->entityManager->flush();
    }
}
