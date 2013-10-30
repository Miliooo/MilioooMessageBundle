<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Tests\TestHelpers\Model;

use Miliooo\Messaging\TestHelpers\Model\Thread;

/**
 * Test file for Miliooo\Messaging\TestHelpers\Model\Thread.
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class ThreadTest extends \PHPUnit_Framework_TestCase
{

    public function testClassInstanceOfModelClass()
    {
        $class = new Thread;
        $this->assertInstanceOf('Miliooo\Messaging\Model\Thread', $class);
    }
}
