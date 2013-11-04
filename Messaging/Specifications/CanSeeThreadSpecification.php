<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Specifications;

use Miliooo\Messaging\User\ParticipantInterface;
use Miliooo\Messaging\Model\ThreadInterface;

/**
 * CanSeeThreadSpecification.
 *
 * The can see thread specification is responsible for deciding if the given
 * participant can see the given thread.
 *
 * The default implementation is that they have to be participant of that thread.
 * Since it would not make much sense to not let the participant see that thread
 *
 * One of the many reasons to  extend could be to check if the participant
 * has a certain role... (eg ROLE_SUPER_ADMIN)
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class CanSeeThreadSpecification
{
    /**
     * Checks if the given participant can see the given thread
     *
     * @param ParticipantInterface $participant The user that we check for
     * @param ThreadInterface      $thread      The thread that we check
     *
     * @return boolean true if participant (user) can see the thread, false otherwise
     */
    public function isSatisfiedBy(ParticipantInterface $participant, ThreadInterface $thread)
    {
        if ($thread->isParticipant($participant)) {
            return true;
        }

        return false;
    }
}
