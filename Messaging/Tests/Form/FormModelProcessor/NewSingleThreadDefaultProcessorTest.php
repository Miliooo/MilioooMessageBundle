<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Tests\Form\FormModelProcessor;

use Miliooo\Messaging\Form\FormModelProcessor\NewSingleThreadDefaultProcesser;
use Miliooo\Messaging\TestHelpers\ParticipantTestHelper;

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
     * @var NewSingleThreadDefaultProcesser
     */
    private $processor;
    private $newThreadBuilder;
    private $newMessageManager;
    private $formModel;

    public function setUp()
    {
        $this->newThreadBuilder = $this->getMockBuilder('Miliooo\Messaging\Builder\Thread\NewThread\NewThreadBuilder')->disableOriginalConstructor()->getMock();
        $this->newMessageManager = $this->getMockBuilder('Miliooo\Messaging\Manager\NewMessageManager')->disableOriginalConstructor()->getMock();

        $this->formModel = $this->getMock('Miliooo\Messaging\Form\FormModel\NewThreadFormModelInterface');

        $this->processor = new NewSingleThreadDefaultProcesser($this->newThreadBuilder, $this->newMessageManager);
    }

    public function testProcess()
    {
        $this->formModel->expects($this->once())->method('getBody')->will($this->returnValue('the body'));
        $this->newThreadBuilder->expects($this->once())->method('setBody')->with('the body');

        $createdAt = new \DateTime('now');
        $this->formModel->expects($this->once())->method('getCreatedAt')->will($this->returnValue($createdAt));
        $this->newThreadBuilder->expects($this->once())->method('setCreatedAt')->with($createdAt);

        $recipients = new ParticipantTestHelper(2);
        $this->formModel->expects($this->once())->method('getRecipients')->will($this->returnValue(array($recipients)));
        $this->newThreadBuilder->expects($this->once())->method('setRecipients')->with(array($recipients));

        $sender = new ParticipantTestHelper(1);
        $this->formModel->expects($this->once())->method('getSender')->will($this->returnValue($sender));
        $this->newThreadBuilder->expects($this->once())->method('setSender')->with($sender);

        $subject = 'the subject';
        $this->formModel->expects($this->once())->method('getSubject')->will($this->returnValue($subject));
        $this->newThreadBuilder->expects($this->once())->method('setSubject')->with($subject);

        $newThread = $this->getMock('Miliooo\Messaging\Model\ThreadInterface');
        $this->newThreadBuilder->expects($this->once())->method('build')->will($this->returnValue($newThread));

        $message = $this->getMock('Miliooo\Messaging\Model\MessageInterface');
        $newThread->expects($this->once())->method('getLastMessage')->will($this->returnValue($message));

        $this->newMessageManager->expects($this->once())->method('saveNewThread')->with($message, $newThread);
        $this->processor->process($this->formModel);
    }
}
