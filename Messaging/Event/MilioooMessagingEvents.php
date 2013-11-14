<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Event;

/**
 * The event constants
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class MilioooMessagingEvents
{

    /**
     * The READ_STATUS_CHANGED event occurs when a read status of a message has changed.
     *
     * The event listener method will receive a
     * /Miliooo/Messaging/Event/ReadStatusChangedMessageEvent instance.
     *
     * This event allows you to notify participants that a certain message has been read. Or do any other logic.
     * For the different read statuses see MessageMetaInterface
     *
     */
    const READ_STATUS_CHANGED = 'miliooo_messaging.read_status_changed';
}
