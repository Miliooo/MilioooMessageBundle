<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Tests\Form\FormModelProcessor;

use Miliooo\Messaging\Form\FormModelProcessor\NewSingleThreadDefaultProcessor;

/**
 * Test file for Miliooo\Messaging\Form\FormModelProcessor\NewSingleThreadDefaultProcesser
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class NewSingleThreadDefaultProcessorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The class under test
     *
     * @var NewSingleThreadDefaultProcessor
     */
    private $processor;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $newThreadBuilder;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $newMessageManager;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $newThreadModel;

    public function setUp()
    {
        $this->newThreadBuilder = $this->getMockBuilder('Miliooo\Messaging\Builder\Message\NewThreadBuilder')->disableOriginalConstructor()->getMock();
        $this->newMessageManager = $this->getMock('Miliooo\Messaging\Manager\NewMessageManagerInterface');
        $this->newThreadModel = $this->getMock('Miliooo\Messaging\Form\FormModel\NewThreadInterface');
        $this->processor = new NewSingleThreadDefaultProcessor($this->newThreadBuilder, $this->newMessageManager);
    }

    public function testProcess()
    {
        $newThread = $this->getMock('Miliooo\Messaging\Model\ThreadInterface');
        $this->newThreadBuilder->expects($this->once())
            ->method('build')
            ->will($this->returnValue($newThread));

        $message = $this->getMock('Miliooo\Messaging\Model\MessageInterface');
        $newThread->expects($this->once())->method('getLastMessage')->will($this->returnValue($message));

        $this->newMessageManager->expects($this->once())->method('saveNewThread')->with($message);
        $this->processor->process($this->newThreadModel);
    }
}
