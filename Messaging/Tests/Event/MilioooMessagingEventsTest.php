<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Tests\Event;

use Miliooo\Messaging\Event\MilioooMessagingEvents;

/**
 * Test file for MilioooMessagingEvents
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class MilioooMessagingEventsTest extends \PHPUnit_Framework_TestCase
{

    public function testConstants()
    {
        $this->assertEquals('miliooo_messaging.read_status_changed', MilioooMessagingEvents::READ_STATUS_CHANGED);
    }
}
