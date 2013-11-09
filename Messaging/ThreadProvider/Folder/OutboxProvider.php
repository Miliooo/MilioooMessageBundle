<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\ThreadProvider\Folder;

use Miliooo\Messaging\Repository\ThreadRepositoryInterface;
use Miliooo\Messaging\User\ParticipantInterface;

/**
 * Class OutboxProvider
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class OutboxProvider implements OutboxProviderInterface
{
    /**
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
    public function getOutboxThreads(ParticipantInterface $participant)
    {
        return $this->threadRepository->getOutboxThreadsForParticipant($participant);
    }
}
