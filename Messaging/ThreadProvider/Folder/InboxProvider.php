<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\ThreadProvider\Folder;

use Miliooo\Messaging\User\ParticipantInterface;
use Miliooo\Messaging\Repository\ThreadRepositoryInterface;

/**
 * Description of InboxProvider
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class InboxProvider
{
    protected $threadRepository;

    public function __construct(ThreadRepositoryInterface $threadRepository)
    {
        $this->threadRepository = $threadRepository;
    }

    public function getInboxThreadsForParticipant(ParticipantInterface $participant)
    {
       return $this->threadRepository->getInboxThreadsForParticipant($participant);
    }
}
