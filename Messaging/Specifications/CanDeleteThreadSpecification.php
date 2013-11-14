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
 * CanDeleteThreadSpecification.
 *
 * The can delete thread specification is responsible for deciding if a given participant can delete a thread.
 *
 * Since we don't want messages to suddenly disappear for a given user the default implementation
 * is to always return false. Also we are not aware about how to check a certain user role. So checking for role_admin
 * is something we can't rely on since it's not part of the participantInterface
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class CanDeleteThreadSpecification
{
    /**
     * Checks if the given participant can delete the given thread.
     *
     * @param ParticipantInterface $participant The user that we check for
     * @param ThreadInterface      $thread      The thread that we check
     *
     * @return boolean true if participant can delete the thread, false otherwise.
     */
    public function isSatisfiedBy(ParticipantInterface $participant, ThreadInterface $thread)
    {
        return false;
    }
}
