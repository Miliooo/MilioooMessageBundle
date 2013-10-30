<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Tests\TestHelpers;

use Miliooo\Messaging\TestHelpers\ParticipantTestHelper;

/**
 * The test file for Miliooo\Messaging\TestHelpers\ParticipantTestHelper
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class ParticipantTestHelperTest extends \PHPUnit_Framework_TestCase
{
    public function testInstanceOfParticipantInterface()
    {
        $class = new ParticipantTestHelper(1);
        $this->assertInstanceOf('Miliooo\Messaging\User\ParticipantInterface', $class);
    }

    public function testConstructorArgumentIsGetParticipantIdResult()
    {
        $class1 = new ParticipantTestHelper(1);
        $this->assertEquals(1, $class1->getParticipantId());

        $class2 = new ParticipantTestHelper('foo');
        $this->assertEquals('foo', $class2->getParticipantId());
    }
}
