<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Notifications;

use Miliooo\Messaging\User\ParticipantInterface;

/**
 * Returns the unread message count for a given participant.
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
interface UnreadMessagesProviderInterface
{
    /**
     * Gets the unread message count for a given participant.
     *
     * @param ParticipantInterface $participant
     *
     * @return integer The unread message count.
     */
    public function getUnreadMessageCountForParticipant(ParticipantInterface $participant);
}
