<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\MessagingBundle\Tests\Entity;

/**
 * Test file for Miliooo\MessagingBundle\Entity\Message.
 *
 * Since this class is needed and used in doctrine mappings we need to test
 * that this class exists and extends the right class.
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class MessageTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $messageMeta = $this->getMockForAbstractClass('Miliooo\MessagingBundle\Entity\Message');
        $this->assertInstanceOf('Miliooo\Messaging\Model\Message', $messageMeta);
    }
}
