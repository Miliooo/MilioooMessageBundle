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

    public function setUp()
    {
        $this->formModel = new NewThreadSingleRecipient();
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
}
