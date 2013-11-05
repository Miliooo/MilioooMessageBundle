<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Tests\TestHelpers\Model;

use Miliooo\Messaging\TestHelpers\Model\ThreadMeta;

/**
 * The test file for Miliooo\Messaging\TestHelpers\Model\ThreadMeta.
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class ThreadMetaTest extends \PHPUnit_Framework_TestCase
{
    public function testClassInstanceOfModelClass()
    {
        $class = new ThreadMeta;
        $this->assertInstanceOf('Miliooo\Messaging\Model\ThreadMeta', $class);
    }
}
