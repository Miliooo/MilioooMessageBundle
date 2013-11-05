<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Tests\Form\FormModel;

use Miliooo\Messaging\Form\FormModel\AbstractNewMessage;
use Miliooo\Messaging\TestHelpers\ParticipantTestHelper;

/**
 * Test file for Miliooo\Messaging\Form\FormModel\AbstractNewMessage
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class AbstractNewMessageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The class under test
     * @var AbstractNewMessage
     */
    private $formModel;

    public function setUp()
    {
        $this->formModel = $this->getMockForAbstractClass('Miliooo\Messaging\Form\FormModel\AbstractNewMessage');
    }

    public function testInterface()
    {
        $this->assertInstanceOf('Miliooo\Messaging\Form\FormModel\NewMessageInterface', $this->formModel);
    }

    public function testBodyWorks()
    {
        $body = "the  body";
        $this->formModel->setBody($body);
        $this->assertSame($body, $this->formModel->getBody());
    }

    public function testCreatedAtWorks()
    {
        $createdAt = new \DateTime('now');
        $this->formModel->setCreatedAt($createdAt);
        $this->assertSame($createdAt, $this->formModel->getCreatedAt());
    }

    public function testSenderWorks()
    {
        $sender = new ParticipantTestHelper(1);
        $this->formModel->setSender($sender);
        $this->assertSame($sender, $this->formModel->getSender());
    }
}
