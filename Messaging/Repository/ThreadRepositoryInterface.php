<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Repository;

use Miliooo\Messaging\User\ParticipantInterface;
use Doctrine\Common\Persistence\ObjectRepository;

/**
 * Description of ThreadRepositoryInterface
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
interface ThreadRepositoryInterface extends ObjectRepository
{
    /**
     * Gets the inbox threads for a given participant
     *
     * @param ParticipantInterface $participant The participant
     *
     * @return ThreadInterface[]|null Array of threadinterfaces or null when no threads
     */
    public function getInboxThreadsForParticipant(ParticipantInterface $participant);

    /**
     * Finds a thread by it's unique id
     *
     * @param integer $id The unique id
     *
     * @return ThreadInterface|null
     */
    public function findThread($id);
}
