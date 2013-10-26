<?php

/*
 * This file is part of the MilioooMessageBundle package.
 * 
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Milioo\Messaging\Tests\Model;

use Miliooo\Messaging\Model\MessageMeta;

/**
 * Test file for the message meta model
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class MessageMetaTest extends \PHPUnit_Framework_TestCase
{

    /**
     * The class under test
     * 
     * @var MessageMeta
     */
    private $messageMeta;

    public function setUp()
    {
        $this->messageMeta = $this->getMockForAbstractClass('Miliooo\Messaging\Model\MessageMeta');
    }

    public function testInterface()
    {
        $this->assertInstanceOf('Miliooo\Messaging\Model\MessageMetaInterface', $this->messageMeta);
    }

    public function testParticipantWorks()
    {
        $participant = $this->getMock('Miliooo\Messaging\Model\ParticipantInterface');
        $this->messageMeta->setParticipant($participant);
        $this->assertSame($participant, $this->messageMeta->getParticipant());
    }

    public function testIsReadWorks()
    {
        $this->messageMeta->setIsRead(true);
        $this->assertTrue($this->messageMeta->getIsread());
        $this->messageMeta->setIsRead(false);
        $this->assertFalse($this->messageMeta->getIsRead());
    }
}
