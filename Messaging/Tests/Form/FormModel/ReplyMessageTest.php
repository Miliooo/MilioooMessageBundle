<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Tests\Form\FormModel;

use Miliooo\Messaging\Form\FormModel\ReplyMessage;

/**
 * Test file for Miliooo\Messaging\Form\FormModel\ReplyMessage
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class ReplyMessageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The class under test
     *
     * @var ReplyMessage
     */
    private $replyMessage;

    public function setUp()
    {
        $this->replyMessage = new ReplyMessage();
    }

    public function testInterface()
    {
        $this->assertInstanceOf('Miliooo\Messaging\Form\FormModel\ReplyMessageInterface', $this->replyMessage);
    }

    public function testThreadWorks()
    {
        $thread = $this->getMock('Miliooo\Messaging\Model\ThreadInterface');
        $this->replyMessage->setThread($thread);
        $this->assertSame($thread, $this->replyMessage->getThread());
    }
}
