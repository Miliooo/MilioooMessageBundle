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
use Miliooo\Messaging\Model\ThreadInterface;

/**
 * Interface OutboxProviderInterface
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
interface OutboxProviderInterface
{
    /**
     * Get the outbox threads for a given participant.
     *
     * @param ParticipantInterface $participant
     *
     * @return ThreadInterface[]|null An array of thread interfaces or null
     */
    public function getOutboxThreads(ParticipantInterface $participant);
}
