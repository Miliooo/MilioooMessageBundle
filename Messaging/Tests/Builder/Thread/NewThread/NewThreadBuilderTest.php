<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Miliooo\Messaging\Tests\Builder\Thread\NewThread;

use Miliooo\Messaging\Builder\Thread\NewThread\NewThreadBuilder;
use Miliooo\Messaging\TestHelpers\ParticipantTestHelper;
use Miliooo\Messaging\Model\ThreadInterface;

/**
 * NewThreadBuilder test.
 *
 * Test file for Miliooo\Messaging\Builder\Thread\NewThread\NewThreadBuilder
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class NewThreadBuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The class under test
     *
     * @var NewThreadBuilder
     */
    protected $builder;

    public function setUp()
    {
        $this->builder = $this->getMockForAbstractClass('Miliooo\Messaging\Builder\Thread\NewThread\NewThreadBuilder');
        $this->builder->setMessageClass('Miliooo\Messaging\TestHelpers\Model\Message');
        $this->builder->setThreadClass('Miliooo\Messaging\TestHelpers\Model\Thread');
        $this->builder->setMessageMetaClass('Miliooo\Messaging\TestHelpers\Model\MessageMeta');
        $this->builder->setThreadMetaClass('Miliooo\Messaging\TestHelpers\Model\ThreadMeta');
    }

    public function testInstanceOfAbstractNewMessageBuilder()
    {
        $this->assertInstanceOf('Miliooo\Messaging\Builder\Message\AbstractNewMessageBuilder', $this->builder);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage SetRecipients requires an array as argument
     */
    public function testSetRecipientsThrowsExceptionWhenNotArray()
    {
        $this->builder->setRecipients('foo');
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Recipients need to implement ParticipantInterface
     */
    public function testSetRecipientsThrowsExceptionWhenNotArrayWithParticipantInterfaces()
    {
        $this->builder->setRecipients(array('foo'));
    }

    public function testSetRecipientsWorks()
    {
        $recipient1 = new ParticipantTestHelper(1);
        $recipient2 = new ParticipantTestHelper(2);
        $this->builder->setRecipients(array($recipient1, $recipient2));
        $this->assertAttributeEquals(array($recipient1, $recipient2), 'recipients', $this->builder);
    }

    public function testSetSubjectWorks()
    {
        $subject = 'the subject';
        $this->builder->setSubject($subject);
        $this->assertAttributeEquals($subject, 'subject', $this->builder);
    }

    public function testBuildReturnsThreadObject()
    {
        $sender = new ParticipantTestHelper('sender');
        $subject = 'the subject';
        $recipient = new ParticipantTestHelper('recipient');


        $this->builder->setRecipients(array($recipient));
        $this->builder->setSender($sender);
        $this->builder->setCreatedAt(new \DateTime(2013 - 10 - 10));
        $this->builder->setSubject($subject);
        $this->assertInstanceOf('Miliooo\Messaging\Model\ThreadInterface', $this->builder->build());
    }

    /*
     * --------------------------
     * thread object validation
     * --------------------------
     */

    public function testThreadHasCorrectSubject()
    {
        $thread = $this->buildModelThread();
        $this->assertEquals('the subject', $thread->getSubject());
    }

    public function testThreadHasCorrectCreatedAt()
    {
        $thread = $this->buildModelThread();
        $created = new \DateTime(2013 - 10 - 10);
        $this->assertEquals($created, $thread->getCreatedAt());
    }

    public function testThreadHasCorrectCreatedBy()
    {
        $thread = $this->buildModelThread();
        $this->assertEquals('sender', $thread->getCreatedBy()->getParticipantId());
    }


    /*
     * --------------------------
     * thread meta object validation
     * --------------------------
     */
    public function testGetThreadMeta()
    {
        $thread = $this->buildModelThread();
        $threadMetas = $thread->getThreadMeta();
        $this->assertCount(2, $threadMetas);
    }

    public function testGetThreadMetaForSenderReturnsCorrectValues()
    {
        $thread = $this->buildModelThread();
        $sender = new ParticipantTestHelper('sender');
        $created = new \DateTime(2013 - 10 - 10);
        $threadMetaSender = $thread->getThreadMetaForParticipant($sender);
        $this->assertFalse($threadMetaSender->getIsArchived());
        $this->assertEquals($created, $threadMetaSender->getLastParticipantMessageDate());
        $this->assertNull($threadMetaSender->getLastMessageDate());
    }

    public function testANewThreadContainsOneMessage()
    {
        $thread = $this->buildModelThread();
        $this->assertCount(1, $thread->getMessages());
    }

    /**
     *
     * @return ThreadInterface
     */
    protected function buildModelThread()
    {
        $sender = new ParticipantTestHelper('sender');
        $subject = 'the subject';
        $recipient = new ParticipantTestHelper('recipient');
        $this->builder->setRecipients(array($recipient));
        $this->builder->setSender($sender);
        $this->builder->setCreatedAt(new \DateTime(2013 - 10 - 10));
        $this->builder->setSubject($subject);

        return $this->builder->build();
    }
}
