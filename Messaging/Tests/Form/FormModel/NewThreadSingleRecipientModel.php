<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Tests\Form\FormModel;

use Miliooo\Messaging\Form\FormModel\NewThreadSingleRecipientModel;
use Miliooo\Messaging\TestHelpers\ParticipantTestHelper;

/**
 * Test file for Miliooo\Messaging\Form\FormModel\NewThreadFormModel
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class NewThreadFormModelTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Class under test     *
     * @var NewThreadSingleRecipientModel
     */
    private $model;

    public function setUp()
    {
        $this->model = new NewThreadSingleRecipientModel();
    }

    public function testBodyWorks()
    {
        $body = "the  body";
        $this->model->setBody($body);
        $this->assertSame($body, $this->model->getBody());
    }

    public function testCreatedAtWorks()
    {
        $createdAt = new \DateTime('now');
        $this->model->setCreatedAt($createdAt);
        $this->assertSame($createdAt, $this->model->getCreatedAt());
    }

    public function testSenderWorks()
    {
        $sender = new ParticipantTestHelper(1);
        $this->model->setSender($sender);
        $this->assertSame($sender, $this->model->getSender());
    }

    public function testRecipientWorks()
    {
        $recipient = new ParticipantTestHelper(1);
        $this->model->setRecipient($recipient);
        $this->assertSame($recipient, $this->model->getRecipient());
    }

    public function testSubjectWorks()
    {
        $subject = 'this is the subject';
        $this->model->setSubject($subject);
        $this->assertSame($subject, $this->model->getSubject());
    }
}
