<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Specifications;

use Miliooo\Messaging\Model\ThreadInterface;
use Miliooo\Messaging\User\ParticipantInterface;

/**
 * This checks if the given participant is participant of the given thread.
 *
 * This can be used when deciding if a participant can see a given thread.
 * Since in most cases it does not make sense to not allow a participant to see a thread where he is part of.
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class IsParticipantThreadSpecification
{
    /**
     * Checks if the given participant is part of the participants of the thread
     *
     * @param ParticipantInterface $participant The user that we check for
     * @param ThreadInterface      $thread       The thread that we check
     *
     * @return boolean true if participant can see the thread, false otherwise
     */
    public function isSatisfiedBy(ParticipantInterface $participant, ThreadInterface $thread)
    {
        if ($thread->isParticipant($participant)) {
            return true;
        }

        return false;
    }
}
