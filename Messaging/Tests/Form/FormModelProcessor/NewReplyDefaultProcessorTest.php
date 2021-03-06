<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Tests\Form\FormModelProcessor;

use Miliooo\Messaging\Form\FormModelProcessor\NewReplyDefaultProcessor;
use Miliooo\Messaging\TestHelpers\ParticipantTestHelper;

/**
 * Test file for Miliooo\Messaging\Form\FormModelProcessor\NewReplyDefaultProcessor.
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class NewReplyDefaultProcessorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The class under test
     *
     * @var NewSingleThreadDefaultProcesser
     */
    private $processor;
    private $replyBuilder;
    private $newMessageManager;
    private $replyMessageModel;

    public function setUp()
    {
        $this->replyBuilder = $this->getMockBuilder('Miliooo\Messaging\Builder\Message\ReplyBuilder')
                ->disableOriginalConstructor()->getMock();
        $this->newMessageManager = $this->getMock('Miliooo\Messaging\Manager\NewMessageManagerInterface');
        $this->replyMessageModel = $this->getMock('Miliooo\Messaging\Form\FormModel\ReplyMessageInterface');
        $this->processor = new NewReplyDefaultProcessor($this->replyBuilder, $this->newMessageManager);
    }

    public function testInterface()
    {
        $this->assertInstanceOf('Miliooo\Messaging\Form\FormModelProcessor\NewReplyFormProcessorInterface', $this->processor);
    }

    public function testProcess()
    {
        $newThread = $this->expectsNewThreadCreated();
        $this->expectsNewMessageManagerCall($newThread);
        $this->processor->process($this->replyMessageModel);
    }



    /**
     * Expects creation of thread by replybuilder.
     *
     * @return ThreadInterface
     */
    protected function expectsNewThreadCreated()
    {
        $newThread = $this->getMock('Miliooo\Messaging\Model\ThreadInterface');
        $this->replyBuilder->expects($this->once())->method('build')->will($this->returnValue($newThread));

        return $newThread;
    }

    /**
     * Expects saveNewReplyCall with right argument.
     *
     * @param ThreadInterface $newThread
     */
    protected function expectsNewMessageManagerCall($newThread)
    {
        $message = $this->getMock('Miliooo\Messaging\Model\MessageInterface');
        $newThread->expects($this->once())->method('getLastMessage')->will($this->returnValue($message));
        $this->newMessageManager->expects($this->once())->method('saveNewReply')->with($message);
    }
}
