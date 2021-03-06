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
 * Interface ArchivedProviderInterface
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
interface ArchivedProviderInterface
{
    /**
     * Gets the inbox threads for a given participant
     *
     * @param ParticipantInterface $participant The participant
     *
     * @return ThreadInterface[]|null Array of thread interfaces or null when no threads
     */
    public function getArchivedThreads(ParticipantInterface $participant);
}
