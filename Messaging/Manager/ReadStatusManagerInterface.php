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

/**
 * The read status manager is responsible for changing the read statuses of messages.
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
interface ReadStatusManagerInterface
{
    /**
     * Marks a message collection as read.
     *
     * If the message has been already marked as read nothing happens.
     * We use a messageCollection as argument and not a thread since it's possible to paginate single threads.
     * That way we are sure only the seen messages are being updated.
     *
     * Marking messages as read needs to happen just before we show the thread to the user.
     *
     * @param ParticipantInterface $participant The participant for who we update the read status
     * @param MessageInterface[] $messages An array of messageInterfaces that ne
     *
     * @throws \InvalidArgumentException if no array given or array does not contain message interfaces
     * @throws \InvalidArgumentException if no message meta found for given participant
     */
    public function markMessageCollectionAsRead(ParticipantInterface $participant, $messages = []);
}
