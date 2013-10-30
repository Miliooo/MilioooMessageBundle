<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Tests\TestHelpers\Model;

use Miliooo\Messaging\TestHelpers\Model\Message;

/**
 * Test file for Miliooo\Messaging\TestHelpers\Model\Message.
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class MessageTest extends \PHPUnit_Framework_TestCase
{
    public function testClassInstanceOfModelClass()
    {
        $class = new Message;
        $this->assertInstanceOf('Miliooo\Messaging\Model\Message', $class);
    }
}
