<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */


namespace Miliooo\MessagingBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Miliooo\Messaging\Repository\ThreadRepositoryInterface;

/**
 * Description of ThreadRepository
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class ThreadRepository extends EntityRepository implements ThreadRepositoryInterface
{
    public function getInboxThreadsForParticipant(ParticipantInterface $participant)
    {
        
    }
}
