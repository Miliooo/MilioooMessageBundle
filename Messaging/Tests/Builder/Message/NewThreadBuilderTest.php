<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Tests\Builder\Message;

use Miliooo\Messaging\Builder\Message\NewThreadBuilder;
use Miliooo\Messaging\Builder\Model\ThreadBuilderModel;
use Miliooo\Messaging\Form\FormModel\NewThreadSingleRecipient;
use Miliooo\Messaging\TestHelpers\ParticipantTestHelper;

/**
 *
 * This test file is stupidly complex because we want to test
 * mock expectations for multiple calls to a function
 *
 * @link http://www.kreamer.org/phpunit-cookbook/1.0/mocks/set-mock-expectations-for-multiple-calls-to-a-function
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class ThreadBuilderModelTest extends \PHPUnit_Framework_TestCase
{
    private $builder;

    public function setUp()
    {
        $this->builder = new NewThreadBuilder();
        $this->builder->setMessageClass('Miliooo\Messaging\TestHelpers\Model\Message');
        $this->builder->setThreadClass('Miliooo\Messaging\TestHelpers\Model\Thread');
        $this->builder->setMessageMetaClass('Miliooo\Messaging\TestHelpers\Model\MessageMeta');
        $this->builder->setThreadMetaClass('Miliooo\Messaging\TestHelpers\Model\ThreadMeta');
    }

    public function testBuildReturnsThreadObject()
    {
        $sender = new ParticipantTestHelper(1);
        $recipient = new ParticipantTestHelper(2);

        $newThreadModel = new NewThreadSingleRecipient();
        $newThreadModel->setSender($sender);
        $newThreadModel->setRecipients($recipient);
        $newThreadModel->setSubject('subject');
        $newThreadModel->setBody('body');
        $newThreadModel->setCreatedAt(new \DateTime('2013-10-10 00:00:00'));

        $builderModel = new ThreadBuilderModel($newThreadModel);
        $build = $this->builder->build($builderModel);
        
        $this->assertInstanceOf('Miliooo\Messaging\Model\ThreadInterface', $build);
    }

    public function testBuildCallsTheBuilderModel()
    {
        $sender = new ParticipantTestHelper(1);
        $recipient = new ParticipantTestHelper(2);

        $builderModel = $this->getMockBuilder('Miliooo\Messaging\Builder\Model\ThreadBuilderModel')
            ->disableOriginalConstructor()
            ->getMock();

        $builderModel->expects($this->at(0))->method('getSender')
            ->will($this->returnValue($sender));

        $builderModel->expects($this->at(1))->method('getRecipients')
            ->will($this->returnValue(array($recipient)));

        $builderModel->expects($this->at(2))->method('getThreadData')
            ->with(null);

        $builderModel->expects($this->at(3))->method('getThreadMeta')
            ->with('all');

        $builderModel->expects($this->at(4))->method('getThreadMeta')
            ->with('sender');

        //the recipient update updates first the values that are happening for all...
        $builderModel->expects($this->at(5))->method('getThreadMeta')
            ->with('all');

        $builderModel->expects($this->at(6))->method('getThreadMeta')
            ->with('recipients');

        $builderModel->expects($this->at(7))->method('getMessageData')
            ->with(null);

        $builderModel->expects($this->at(8))->method('getMessageMeta')
            ->with('all');

        $builderModel->expects($this->at(9))->method('getMessageMeta')
            ->with('sender');

        $builderModel->expects($this->at(10))->method('getMessageMeta')
            ->with('all');

        $builderModel->expects($this->at(11))->method('getMessageMeta')
            ->with('recipients');

        //2 times for all, one time for sender, one time for recipient
        $builderModel->expects($this->exactly(4))->method('getMessageMeta');
        //2 times for all, one time for sender, one time for recipient
        $builderModel->expects($this->exactly(4))->method('getThreadMeta');

        $builderModel->expects($this->exactly(1))->method('getThreadData');
        $builderModel->expects($this->exactly(1))->method('getMessageData');

        $this->builder->build($builderModel);
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage could not find setter for foo
     */
    public function testBuilderThrowsExceptionWhenNotAbleToCreateSetter()
    {
        $builderModel = $this->getMockBuilder('Miliooo\Messaging\Builder\Model\ThreadBuilderModel')
            ->disableOriginalConstructor()
            ->getMock();

        $builderModel->expects($this->atLeastOnce())->method('getThreadData')
            ->will($this->returnValue(['foo' => 'bar']));
        $this->builder->build($builderModel);
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage could not create setter method, no string given
     */
    public function testBuilderThrowsExceptionWhenSetterKeyNotString()
    {
        $builderModel = $this->getMockBuilder('Miliooo\Messaging\Builder\Model\ThreadBuilderModel')
            ->disableOriginalConstructor()
            ->getMock();

        $builderModel->expects($this->atLeastOnce())->method('getThreadData')
            ->will($this->returnValue([['foo' => 'bar'], 'bar']));

        $this->builder->build($builderModel);
    }
}
