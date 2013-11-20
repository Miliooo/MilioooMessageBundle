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
use Miliooo\Messaging\ValueObjects\ThreadStatus;

/**
 * The interface for the threadmeta class
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
interface ThreadStatusManagerInterface
{

    /**
     * Updates the thread status for a given participant.
     *
     * @param ThreadStatus         $threadStatus The new thread status
     * @param ThreadInterface      $thread       The thread for whom we update the status
     * @param ParticipantInterface $participant  The participant for whom we update the status
     */
    public function updateThreadStatusForParticipant(
        ThreadStatus $threadStatus,
        ThreadInterface $thread,
        ParticipantInterface $participant
    );
}
