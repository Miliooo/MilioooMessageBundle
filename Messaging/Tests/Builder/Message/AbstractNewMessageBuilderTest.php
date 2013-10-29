<?php

/*
 * This file is part of the MilioooMessageBundle package.
 * 
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Tests\Builder\Message;

use Miliooo\Messaging\Builder\AbstractNewMessageBuilder;

/**
 * Test file for the abstract new message builder
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class AbstractNewMessageBuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The class under test
     *
     * @var AbstractNewMessageBuilder
     */
    protected $abstractNewMessageBuilder;

    public function setUp()
    {
        $this->abstractNewMessageBuilder = $this->getMockForAbstractClass('Miliooo\Messaging\Builder\Message\AbstractNewMessageBuilder');
    }

    public function testSetMessageClassWorks()
    {
        $this->abstractNewMessageBuilder->setMessageClass('\Acme\Demo\Domain\Model\Message');
        $this->assertAttributeEquals('\Acme\Demo\Domain\Model\Message', 'messageClass', $this->abstractNewMessageBuilder);
    }

    public function testSetMessageMetaClassWorks()
    {
        $this->abstractNewMessageBuilder->setMessageMetaClass('\Acme\Demo\Domain\Model\MessageMeta');
        $this->assertAttributeEquals('\Acme\Demo\Domain\Model\MessageMeta', 'messageMetaClass', $this->abstractNewMessageBuilder);
    }

    public function testSetThreadClassWorks()
    {
        $this->abstractNewMessageBuilder->setThreadClass('\Acme\Demo\Domain\Model\Thread');
        $this->assertAttributeEquals('\Acme\Demo\Domain\Model\Thread', 'threadClass', $this->abstractNewMessageBuilder);
    }

    public function testSetThreadMetaClassWorks()
    {
        $this->abstractNewMessageBuilder->setThreadMetaClass('\Acme\Demo\Domain\Model\ThreadMeta');
        $this->assertAttributeEquals('\Acme\Demo\Domain\Model\ThreadMeta', 'threadMetaClass', $this->abstractNewMessageBuilder);
    }

    public function testSetSenderWorks()
    {
        $sender = $this->getMock('Miliooo\Messaging\Model\ParticipantInterface');
        $this->abstractNewMessageBuilder->setSender($sender);
        $this->assertAttributeEquals($sender, 'sender', $this->abstractNewMessageBuilder);
    }

    public function testSetBodyWorks()
    {
        $body = 'message body';
        $this->abstractNewMessageBuilder->setBody($body);
        $this->assertAttributeEquals($body, 'body', $this->abstractNewMessageBuilder);
    }

    public function testSetCreatedAtWorks()
    {
        $createdAt = new \DateTime('2013-10-09 23:22:11');
        $this->abstractNewMessageBuilder->setCreatedAt($createdAt);
        $this->assertAttributeEquals($createdAt, 'createdAt', $this->abstractNewMessageBuilder);
    }
}
