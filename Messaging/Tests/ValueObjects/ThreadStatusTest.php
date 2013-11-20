<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Tests\ValueObjects;

use Miliooo\Messaging\ValueObjects\ThreadStatus;
use Miliooo\Messaging\Model\ThreadMetaInterface;

/**
 * Test file for Thread Status Value Object
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class ThreadStatusTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider invalidThreadStatusProvider
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid thread status
     */
    public function testInvalidThreadStatuses($status)
    {
        new ThreadStatus($status);
    }

    /**
     * Invalid statuses provider.
     *
     * @return array
     */
    public function invalidThreadStatusProvider()
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
    public function validThreadStatusProvider()
    {
        return [
            [ThreadMetaInterface::STATUS_ARCHIVED],
            [ThreadMetaInterface::STATUS_ACTIVE],
        ];
    }

    /**
     * @dataProvider validThreadStatusProvider
     */
    public function testValidReadStatuses($status)
    {
        $statusValueObject = new ThreadStatus($status);
        $this->assertEquals($status, $statusValueObject->getThreadStatus());
    }
}
