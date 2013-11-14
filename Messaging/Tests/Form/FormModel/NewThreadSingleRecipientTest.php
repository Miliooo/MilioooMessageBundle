<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Tests\Form\FormModel;

use Miliooo\Messaging\Form\FormModel\NewThreadSingleRecipient;
use Miliooo\Messaging\TestHelpers\ParticipantTestHelper;
use Miliooo\Messaging\User\ParticipantInterface;

/**
 * Test file for Miliooo\Messaging\Form\FormModel\NewThreadSingleRecipient
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class NewThreadSingleRecipientTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The class under test.
     *
     * @var NewThreadSingleRecipient
     */
    private $formModel;

    /**
     * A participant
     *
     * @var ParticipantInterface
     */
    private $recipient;


    public function setUp()
    {
        $this->formModel = new NewThreadSingleRecipient();
        $this->recipient = new ParticipantTestHelper(1);
    }

    public function testInterface()
    {
        $this->assertInstanceOf('Miliooo\Messaging\Form\FormModel\NewThreadInterface', $this->formModel);
    }

    public function testRecipientWorks()
    {
        $recipient = new ParticipantTestHelper(1);
        $this->formModel->setRecipient($recipient);
        $this->assertSame([$recipient], $this->formModel->getRecipients());
    }

    public function testSubjectWorks()
    {
        $subject = 'this is the subject';
        $this->formModel->setSubject($subject);
        $this->assertSame($subject, $this->formModel->getSubject());
    }

    /**
     * The form will call this function to get the recipient.
     */
    public function testGetRecipientWorks()
    {
        $this->formModel->setRecipient($this->recipient);
        $this->assertSame($this->recipient, $this->formModel->getRecipient());
    }

    public function testGetRecipientsReturnsArayWhenEmpty()
    {
        $this->assertEquals([], $this->formModel->getRecipients());
    }
}
