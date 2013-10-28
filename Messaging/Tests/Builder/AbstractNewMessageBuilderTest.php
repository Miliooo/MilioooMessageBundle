<?php

/*
 * This file is part of the MilioooMessageBundle package.
 * 
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Tests\Builder;

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
        $this->abstractNewMessageBuilder = $this->getMockForAbstractClass('Miliooo\Messaging\Builder\AbstractNewMessageBuilder');
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
}
