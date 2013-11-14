<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Manager;

use Miliooo\Messaging\Model\MessageInterface;
use Miliooo\Messaging\User\ParticipantInterface;
use Miliooo\Messaging\ValueObjects\ReadStatus;

/**
 * The read status manager is responsible for changing the read statuses of messages.
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
interface ReadStatusManagerInterface
{
    /**
     * Updates the read status for a message collection for a given participant.
     *
     * If the old read status equals the new read status no updates happen
     * If the participant is not part of the messageMeta no updates happen
     *
     * @param ReadStatus           $updatedReadStatus The new read status
     * @param ParticipantInterface $participant       The participant for who we update the read status
     * @param MessageInterface[]   $messages          An array of messageInterfaces for whom we want to update the read status
     *
     * @return MessageInterface[]|[] Array with the updated messages, an empty array if no messages were updated
     */
    public function updateReadStatusForMessageCollection(
        ReadStatus $updatedReadStatus,
        ParticipantInterface $participant,
        $messages = []
    );
}
