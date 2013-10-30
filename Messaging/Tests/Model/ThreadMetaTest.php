<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Tests\Model;

use Miliooo\Messaging\Model\ThreadMeta;
use Miliooo\Messaging\TestHelpers\ParticipantTestHelper;

/**
 * Description of ThreadMetaTest
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class ThreadMetaTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The class under test
     *
     * @var ThreadMeta
     */
    protected $threadMeta;

    public function setUp()
    {
        $this->threadMeta = $this->getMockForAbstractClass('Miliooo\Messaging\Model\ThreadMeta');
    }

    public function testInterface()
    {
        $this->assertInstanceOf('Miliooo\Messaging\Model\ThreadMetaInterface', $this->threadMeta);
    }

    public function testIdWorks()
    {
        $this->assertAttributeEquals(null, 'id', $this->threadMeta);
        $this->assertNull($this->threadMeta->getId());
    }

    public function testParticipantWorks()
    {
        $participant = new ParticipantTestHelper('participant');
        $this->threadMeta->setParticipant($participant);
        $this->assertSame($participant, $this->threadMeta->getParticipant());
    }

    public function testIsArchivedDefaultsToFalse()
    {
        $this->AssertAttributeEquals(false, 'isArchived', $this->threadMeta);
        $this->assertFalse($this->threadMeta->getIsArchived());
    }

    public function testIsArchivedWorks()
    {
        $this->threadMeta->setIsArchived(true);
        $this->assertTrue($this->threadMeta->getIsArchived());
        $this->threadMeta->setIsArchived(false);
        $this->assertFalse($this->threadMeta->getIsArchived());
    }

    public function testLastParticipantMessageDateWorks()
    {
        $dateTime = new \DateTime('2013-10-10 23:23:23');
        $this->threadMeta->setLastParticipantMessageDate($dateTime);
        $this->assertSame($dateTime, $this->threadMeta->getLastParticipantMessageDate());
    }

    public function testLastMessageDateWorks()
    {
        $dateTime = new \DateTime('2013-10-10 23:23:23');
        $this->threadMeta->setLastMessageDate($dateTime);
        $this->assertSame($dateTime, $this->threadMeta->getLastMessageDate());
    }

    public function testThreadWorks()
    {
        $thread = $this->getMock('Miliooo\Messaging\Model\ThreadInterface');
        $this->threadMeta->setThread($thread);
        $this->assertSame($thread, $this->threadMeta->getThread());
    }
}
