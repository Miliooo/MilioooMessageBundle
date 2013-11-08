<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Tests\Builder\Message;

use Miliooo\Messaging\Builder\AbstractMessageBuilder;

/**
 * Test file for the abstract new message builder
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class AbstractMessageBuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The class under test
     *
     * @var AbstractMessageBuilder
     */
    protected $abstractMessageBuilder;

    public function setUp()
    {
        $this->abstractMessageBuilder = $this->getMockForAbstractClass('Miliooo\Messaging\Builder\Message\AbstractMessageBuilder');
    }

    public function testSetMessageClassWorks()
    {
        $this->abstractMessageBuilder->setMessageClass('\Acme\Demo\Domain\Model\Message');
        $this->assertAttributeEquals('\Acme\Demo\Domain\Model\Message', 'messageClass', $this->abstractMessageBuilder);
    }

    public function testSetMessageMetaClassWorks()
    {
        $this->abstractMessageBuilder->setMessageMetaClass('\Acme\Demo\Domain\Model\MessageMeta');
        $this->assertAttributeEquals('\Acme\Demo\Domain\Model\MessageMeta', 'messageMetaClass', $this->abstractMessageBuilder);
    }

    public function testSetThreadClassWorks()
    {
        $this->abstractMessageBuilder->setThreadClass('\Acme\Demo\Domain\Model\Thread');
        $this->assertAttributeEquals('\Acme\Demo\Domain\Model\Thread', 'threadClass', $this->abstractMessageBuilder);
    }

    public function testSetThreadMetaClassWorks()
    {
        $this->abstractMessageBuilder->setThreadMetaClass('\Acme\Demo\Domain\Model\ThreadMeta');
        $this->assertAttributeEquals('\Acme\Demo\Domain\Model\ThreadMeta', 'threadMetaClass', $this->abstractMessageBuilder);
    }
}
