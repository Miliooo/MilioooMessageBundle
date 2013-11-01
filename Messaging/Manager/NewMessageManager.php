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

/**
 * The new message manager is responsible for handling new messages
 *
 * Since messages are always part of a thread a new message is either a
 *
 * New thread with a new message
 * Reply to a thread
 *
 * In both cases updates are needed to the thread object that's why we need both
 * The message manager and the thread manager to update this object
 *
 * This would not be the case if we did persist all on the thread object, but that's
 * a performance cost I don't want to create.
 *
 */
class NewMessageManager
{

    public function __construct()
    {

    }

    public function saveNewThread(MessageInterface $message, ThreadInterface $thread)
    {
        //todo
    }

    public function saveNewReply(MessageInterface $message, threadInterface $thread)
    {
        //todo
    }
}
