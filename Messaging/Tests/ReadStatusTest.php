<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Tests\User;

use Miliooo\Messaging\ValueObjects\ReadStatus;
use Miliooo\Messaging\Model\MessageMetaInterface;

/**
 * Test file for Read Status Value Object
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class ReadStatusTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider invalidReadStatusProvider
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Read status is not valid
     */
    public function testInvalidReadStatuses($status)
    {
        new ReadStatus($status);
    }

    /**
     * Invalid statuses provider.
     *
     * @return array
     */
    public function invalidReadStatusProvider()
    {
        return [
            ['foo'],
            [null],
            [100],
            [[]]
        ];
    }

    /**
     * Valid statuses provider.
     *
     * @return array
     */
    public function validReadStatusProvider()
    {
        return [
            [MessageMetaInterface::READ_STATUS_NEVER_READ],
            [MessageMetaInterface::READ_STATUS_READ],
            [MessageMetaInterface::READ_STATUS_READ]
        ];
    }

    /**
     * @dataProvider validReadStatusProvider
     */
    public function testValidReadStatuses($status)
    {
        $statusValueObject = new ReadStatus($status);
        $this->assertEquals($status, $statusValueObject->getReadStatus());
    }
}
