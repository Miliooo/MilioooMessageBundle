<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Manager;

use Miliooo\Messaging\Model\ThreadInterface;
use Miliooo\Messaging\User\ParticipantInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Interface for secure delete thread managers.
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
interface DeleteThreadManagerSecureInterface
{
    /**
     * Deletes a thread if the given participant has enough rights to do so. Else throws an exception.
     *
     * @param ParticipantInterface $participant The participant who wants to delete a thread
     * @param ThreadInterface      $thread      The given thread
     *
     * @throws AccessDeniedException if not enough rights to delete the thread.
     */
    public function deleteThread(ParticipantInterface $participant, ThreadInterface $thread);
}
