<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Tests\Builder\Reply;

use Miliooo\Messaging\Builder\Reply\ReplyBuilder;
use Miliooo\Messaging\TestHelpers\ThreadModelTestHelper;
use Miliooo\Messaging\TestHelpers\ParticipantTestHelper;

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

    public function setUp()
    {
        $this->builder = new ReplyBuilder();
        $this->builder->setMessageClass('\Miliooo\Messaging\TestHelpers\Model\Message');
        $this->builder->setThreadClass('\Miliooo\Messaging\TestHelpers\Model\Thread');
        $this->builder->setMessageMetaClass('\Miliooo\Messaging\TestHelpers\Model\MessageMeta');
        $this->builder->setThreadMetaClass('\Miliooo\Messaging\TestHelpers\Model\ThreadMeta');
    }

    public function testInstanceOfAbstractNewMessageBuilder()
    {
        $this->assertInstanceOf('Miliooo\Messaging\Builder\Message\AbstractNewMessageBuilder', $this->builder);
    }

    public function testBuildReturnsAthread()
    {
        $threadWeReplyTo = $this->getModelThread();
        $senderOfReply = new ParticipantTestHelper(ThreadModelTestHelper::RECIPIENT_ID);
        $this->builder->setThread($threadWeReplyTo);
        $this->builder->setSender($senderOfReply);
        $this->builder->setBody('new reply');
        $this->builder->setCreatedAt(new \DateTime('now'));
        $this->assertInstanceOf('Miliooo\Messaging\Model\ThreadInterface', $this->builder->build());
    }

    public function testBuilderWithOneReplyContains2Messages()
    {
        $threadWeReplyTo = $this->getModelThread();
        $senderOfReply = new ParticipantTestHelper(ThreadModelTestHelper::RECIPIENT_ID);
        $this->builder->setBody('this is a reply');
        $this->builder->setCreatedAt(new \DateTime('now'));
        $this->builder->setThread($threadWeReplyTo);
        $this->builder->setSender($senderOfReply);
        $thread = $this->builder->build();

        $this->assertEquals(2, count($thread->getMessages()));
    }

    public function testSenderWhoRepliesHasSeenHisReplyMessage()
    {
        $threadWeReplyTo = $this->getModelThread();
        $senderOfReply = new ParticipantTestHelper(ThreadModelTestHelper::RECIPIENT_ID);
        $this->builder->setBody('this is a reply');
        $this->builder->setCreatedAt(new \DateTime('now'));
        $this->builder->setThread($threadWeReplyTo);
        $this->builder->setSender($senderOfReply);
        $threadWithReply = $this->builder->build();
        $lastMessage = $threadWithReply->getLastMessage();
        $isRead = $lastMessage->getMessageMetaForParticipant($senderOfReply)->getIsRead();
        $this->assertTrue($isRead);
    }

    public function testRecipientsHasNotSeenNewReplyMessage()
    {
        $threadWeReplyTo = $this->getModelThread();
        $senderOfReply = new ParticipantTestHelper(ThreadModelTestHelper::RECIPIENT_ID);
        $recieverOfReply = new ParticipantTestHelper(ThreadModelTestHelper::SENDER_ID);
        $this->builder->setBody('this is a reply');
        $this->builder->setCreatedAt(new \DateTime('now'));
        $this->builder->setThread($threadWeReplyTo);
        $this->builder->setSender($senderOfReply);
        $threadWithReply = $this->builder->build();
        $lastMessage = $threadWithReply->getLastMessage();
        $messageMetaRecipients = $lastMessage->getMessageMetaForParticipant($recieverOfReply);
        $this->assertFalse($messageMetaRecipients->getIsRead());
    }

    /**
     * The recipient receives a new message so we want it in the inbox
     *
     * This requires a last message date set for the thread that is not from the participant
     */
    public function testNewReplyDoesUpdateLastMessageDateForRecipient()
    {
        $created = new \DateTime('now');
        $threadWeReplyTo = $this->getModelThread();
        $senderOfReply = new ParticipantTestHelper(ThreadModelTestHelper::RECIPIENT_ID);
        $recieverOfReply = new ParticipantTestHelper(ThreadModelTestHelper::SENDER_ID);
        $this->builder->setBody('this is a reply');
        $this->builder->setCreatedAt($created);
        $this->builder->setThread($threadWeReplyTo);
        $this->builder->setSender($senderOfReply);
        $threadWithReply = $this->builder->build();
        $threadMeta = $threadWithReply->getThreadMetaForParticipant($recieverOfReply);
        $this->assertEquals($created, $threadMeta->getLastMessageDate());
    }

    protected function getModelThread()
    {
        $threadModelHelper = new ThreadModelTestHelper();
        $modelThread = $threadModelHelper->getModelThread();
        $this->assertInstanceOf('Miliooo\Messaging\Model\ThreadInterface', $modelThread);

        return $modelThread;
    }
}
