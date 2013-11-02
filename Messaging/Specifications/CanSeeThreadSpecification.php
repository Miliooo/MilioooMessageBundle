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
 * Description of CanSeeThreadSpecification
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class CanSeeThreadSpecification
{
    public function isSatisfiedBy(ParticipantInterface $participant, ThreadInterface $thread)
    {
        if ($thread->isParticipant($participant)) {
            return true;
        } else {
            return false;
        }
    }
}
