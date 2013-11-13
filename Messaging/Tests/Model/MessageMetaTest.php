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
use Miliooo\Messaging\TestHelpers\ParticipantTestHelper;
use Miliooo\Messaging\Model\MessageMetaInterface;
use Miliooo\Messaging\ValueObjects\ReadStatus;

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
        $participant = new ParticipantTestHelper('participant');
        $this->messageMeta->setParticipant($participant);
        $this->assertSame($participant, $this->messageMeta->getParticipant());
    }

    public function testSetReadStatusUpdatesOldStatus()
    {
        //we set the status to read
        $readStatus = new ReadStatus(MessageMetaInterface::READ_STATUS_READ);
        $this->messageMeta->setReadStatus($readStatus);
        //the getReadStatus should be read
        $this->assertSame(MessageMetaInterface::READ_STATUS_READ, $this->messageMeta->getReadStatus());
        //the previous read status should still  be never read
        $this->assertSame(MessageMetaInterface::READ_STATUS_NEVER_READ, $this->messageMeta->getPreviousReadStatus());

        //we set the new read status to marked_unread
        $readStatus = new ReadStatus(MessageMetaInterface::READ_STATUS_MARKED_UNREAD);
        $this->messageMeta->setReadStatus($readStatus);
        //the read status should be marked unread
        $this->assertSame(MessageMetaInterface::READ_STATUS_MARKED_UNREAD, $this->messageMeta->getReadStatus());
        //the previous status should now be read
        $this->assertSame(MessageMetaInterface::READ_STATUS_READ, $this->messageMeta->getPreviousReadStatus());
    }

    public function testMessageWorks()
    {
        $message = $this->getMock('Miliooo\Messaging\Model\MessageInterface');
        $this->messageMeta->setMessage($message);
        $this->assertSame($message, $this->messageMeta->getMessage());
    }

    public function testGetPreviousReadStatusDefautsToNeverRead()
    {
        $this->assertEquals($this->messageMeta->getReadStatus(), MessageMetaInterface::READ_STATUS_NEVER_READ);
    }
}
