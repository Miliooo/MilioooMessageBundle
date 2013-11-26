<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Notifications;

use Miliooo\Messaging\User\ParticipantInterface;
use Miliooo\Messaging\Repository\ThreadRepositoryInterface;

/**
 * Doctrine implementation of the unreadMessagesProviderInterface
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class UnreadMessagesProviderDoctrine implements UnreadMessagesProviderInterface
{
    /**
     * A thread repository instance.
     *
     * @var ThreadRepositoryInterface
     */
    private $threadRepository;

    /**
     * Constructor.
     *
     * @param ThreadRepositoryInterface $threadRepository
     */
    public function __construct(ThreadRepositoryInterface $threadRepository)
    {
        $this->threadRepository = $threadRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function getUnreadMessageCountForParticipant(ParticipantInterface $participant)
    {
        return $this->threadRepository->getUnreadMessageCountForParticipant($participant);
    }
}
