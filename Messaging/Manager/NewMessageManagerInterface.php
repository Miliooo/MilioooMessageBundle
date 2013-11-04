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
 * The new message manager is responsible for saving new messages
 *
 * The reason we need to persist the message and the thread is that we have no
 * cascade all on the messages array collection in our model thread.
 *
 * This means that only storing the thread object would result in a fatal error
 * See: http://docs.doctrine-project.org/en/latest/reference/working-with-associations.html#transitive-persistence-cascade-operations
 * The reason we did this is the performance cost.
 * But if it wasn't for doctrine and the performance cost we would ofcourse only store our new thread object
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
interface NewMessageManagerInterface
{
    /**
     * Saves a new thread to the storage engine.
     *
     * @param MessageInterface $message
     */
    public function saveNewThread(MessageInterface $message);

    /**
     * Saves a new reply to the storage engine.
     *
     * @param MessageInterface $message
     */
    public function saveNewReply(MessageInterface $message);
}
