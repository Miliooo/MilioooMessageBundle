<?php

/*
 * This file is part of the MilioooMessageBundle package.
 * 
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Tests\Model;

use Miliooo\Messaging\Model\Thread;

/**
 * Test file for the thread model
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class ThreadTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The class under test
     *
     * @var Thread
     */
    protected $thread;

    public function setUp()
    {
        $this->thread = $this->getMockForAbstractClass('Miliooo\Messaging\Model\Thread');
    }

    public function testInterface()
    {
        $this->assertInstanceOf('Miliooo\Messaging\Model\ThreadInterface', $this->thread);
    }

    public function testIdWorks()
    {
        $this->assertAttributeEquals(null, 'id', $this->thread);
        $this->assertNull($this->thread->getId());
    }

    public function testSubjectWorks()
    {
        $subject = 'this is the subject';
        $this->thread->setSubject($subject);
        $this->assertSame($subject, $this->thread->getSubject());
    }

    public function testCreatedByWorks()
    {
        $participant = $this->getMock('Miliooo\Messaging\Model\ParticipantInterface');
        $this->thread->setCreatedBy($participant);
        $this->assertEquals($participant, $this->thread->getCreatedBy());
    }

    public function testCreatedAtWorks()
    {
        $createdAt = new \DateTime('2013-10-12 00:00:00');
        $this->thread->setCreatedAt($createdAt);
        $this->assertSame($createdAt, $this->thread->getCreatedAt());
    }
}
