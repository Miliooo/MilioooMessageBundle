<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\MessagingBundle\Tests;

use Miliooo\MessagingBundle\MilioooMessagingBundle;

/**
 * Description of MilioooMessagingBundleTest
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class MilioooMessagingBundleTest extends \PHPUnit_Framework_TestCase
{
    protected $MilioooMessagingBundle;

    public function setUp()
    {
        $this->MilioooMessagingBundle = new MilioooMessagingBundle;
    }

    public function testInterface()
    {
        $this->assertInstanceOf('Symfony\Component\HttpKernel\Bundle\BundleInterface', $this->MilioooMessagingBundle);
    }
}
