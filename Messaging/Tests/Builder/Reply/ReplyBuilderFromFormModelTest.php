<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Tests\Builder\Reply;

use Miliooo\Messaging\Builder\Reply\ReplyBuilderFromFormModel;
use Miliooo\Messaging\TestHelpers\ParticipantTestHelper;
use Miliooo\Messaging\TestHelpers\ThreadModelTestHelper;
use Miliooo\Messaging\Model\ThreadInterface;

/**
 * Test file for ReplyBuilderFromFormModel
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class ReplyBuilderFromFormModelTest extends \PHPUnit_Framework_TestCase
{
    private $builder;
    private $replyModel;
    private $thread;
    private $sender;

    public function setUp()
    {
        $this->sender = new ParticipantTestHelper(ThreadModelTestHelper::RECIPIENT_ID);
        $this->replyModel = $this->getMock('Miliooo\Messaging\Form\FormModel\ReplyMessageInterface');
        $this->thread = $this->getMock('Miliooo\Messaging\Model\ThreadInterface');
        $this->builder = new ReplyBuilderFromFormModel();
        $this->builder->setMessageClass('Miliooo\Messaging\TestHelpers\Model\Message');
        $this->builder->setThreadClass('Miliooo\Messaging\TestHelpers\Model\Thread');
        $this->builder->setMessageMetaClass('Miliooo\Messaging\TestHelpers\Model\MessageMeta');
        $this->builder->setThreadMetaClass('Miliooo\Messaging\TestHelpers\Model\ThreadMeta');
    }

    public function testBuildReply()
    {
        $this->thread = $this->createModelThread();
        $this->replyModel->expects($this->once())->method('getThread')
            ->will($this->returnValue($this->thread));
        $this->replyModel->expects($this->once())->method('getSender')
            ->will($this->returnValue($this->sender));
        $this->replyModel->expects($this->once())->method('getBody')
            ->will($this->returnValue('the body of the reply'));
        $this->replyModel->expects($this->once())->method('getCreatedAt')
            ->will($this->returnValue(new \DateTime('now')));
        $this->assertInstanceOf('Miliooo\Messaging\Model\ThreadInterface', $this->builder->buildReply($this->replyModel));
        }

    /**
     * Since we create a valid new thread we need a valid thread object with recipients
     * Else the builder fails over looping recipients...
     *
     * @return ThreadInterface
     */
    protected function createModelThread()
    {
        $threadHelper = new ThreadModelTestHelper();
        return $threadHelper->getModelThread();
    }
}
