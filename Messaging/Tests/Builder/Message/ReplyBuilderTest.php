<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Tests\Builder\Message;

use Miliooo\Messaging\Builder\Message\ReplyBuilder;
use Miliooo\Messaging\Builder\Model\ReplyBuilderModel;
use Miliooo\Messaging\Form\FormModel\ReplyMessage;
use Miliooo\Messaging\TestHelpers\ThreadModelTestHelper;

/**
 * Description of ReplyBuilderTest
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class ReplyBuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The class under test
     * @var type
     */
    private $builder;
    protected $threadModelHelper;

    public function setUp()
    {
        $this->builder = new ReplyBuilder();
        $this->builder->setMessageClass('\Miliooo\Messaging\TestHelpers\Model\Message');
        $this->builder->setThreadClass('\Miliooo\Messaging\TestHelpers\Model\Thread');
        $this->builder->setMessageMetaClass('\Miliooo\Messaging\TestHelpers\Model\MessageMeta');
        $this->builder->setThreadMetaClass('\Miliooo\Messaging\TestHelpers\Model\ThreadMeta');
        $this->threadModelHelper = new ThreadModelTestHelper();
    }

    public function testBuild()
    {
        $thread = $this->threadModelHelper->getModelThread();
        $sender = $this->threadModelHelper->getSender();

        $replyMessageModel = new ReplyMessage();
        $replyMessageModel->setBody('the body');
        $replyMessageModel->setSender($sender);
        $replyMessageModel->setThread($thread);
        $replyMessageModel->setCreatedAt(new \DateTime('now'));

        $replyBuilderModel = new ReplyBuilderModel($replyMessageModel);
        $build = $this->builder->build($replyBuilderModel);
        $this->assertInstanceOf('Miliooo\Messaging\Model\ThreadInterface', $build);
    }

    public function testBuildCallsTheBuilderModel()
    {

        $thread = $this->threadModelHelper->getModelThread();
        $sender = $this->threadModelHelper->getSender();
        $replyMessageModel = new ReplyMessage();
        $replyMessageModel->setBody('the body');
        $replyMessageModel->setSender($sender);
        $replyMessageModel->setThread($thread);
        $replyMessageModel->setCreatedAt(new \DateTime('now'));

        $replyBuilderModel = $this->getMockBuilder('Miliooo\Messaging\Builder\Model\ReplyBuilderModel')
            ->disableOriginalConstructor()
            ->getMock();

        $replyBuilderModel->expects($this->at(0))->method('getSender')
            ->will($this->returnValue($sender));
        $replyBuilderModel->expects($this->at(1))->method('getThread')
            ->will($this->returnValue($thread));
        $replyBuilderModel->expects($this->at(2))->method('getMessageData')
            ->with(null);
        $replyBuilderModel->expects($this->at(3))->method('getMessageMeta')
            ->with('all');
        $replyBuilderModel->expects($this->at(4))->method('getMessageMeta')
            ->with('sender');
        $replyBuilderModel->expects($this->at(5))->method('getMessageMeta')
            ->with('all');
        $replyBuilderModel->expects($this->at(6))->method('getMessageMeta')
            ->with('recipients');
        $replyBuilderModel->expects($this->at(7))->method('getThreadMeta')
            ->with($this->equalTo('all'));
        $replyBuilderModel->expects($this->at(8))->method('getThreadMeta')
            ->with('sender');
        $replyBuilderModel->expects($this->at(9))->method('getThreadMeta')
            ->with('all');
        $replyBuilderModel->expects($this->at(10))->method('getThreadMeta')
            ->with('recipients');
        //2 times for all, one time for sender, one time for recipient
        $replyBuilderModel->expects($this->exactly(4))->method('getMessageMeta');
        //2 times for all, one time for sender, one time for recipient
        $replyBuilderModel->expects($this->exactly(4))->method('getThreadMeta');

        $this->builder->build($replyBuilderModel);
    }
}
