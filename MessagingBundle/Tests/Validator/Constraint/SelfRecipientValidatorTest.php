<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\MessagingBundle\Tests\Validator\Constraint;

use Miliooo\Messaging\User\ParticipantInterface;
use Miliooo\MessagingBundle\Validator\Constraint\SelfRecipient;
use Miliooo\MessagingBundle\Validator\Constraint\SelfRecipientValidator;
use Miliooo\Messaging\TestHelpers\ParticipantTestHelper;

/**
 * Test file for SelfRecipientValidator
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class SelfRecipientValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var SelfRecipient
     */
    private $constraint;

    /**
     * @var ParticipantInterface
     */
    private $sender;

    /**
     * @var ParticipantInterface
     */
    private $recipient;

    /**
     * @var SelfRecipientValidator
     */
    private $validator;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $context;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $participantProvider;

    public function setUp()
    {
        $this->sender = new ParticipantTestHelper('sender');
        $this->recipient = new ParticipantTestHelper('recipient');
        $this->constraint = new SelfRecipient();

        $this->context = $this->getMock('Symfony\Component\Validator\ExecutionContextInterface');
        $this->participantProvider = $this->getMock('Miliooo\Messaging\User\ParticipantProviderInterface');
        $this->validator = new SelfRecipientValidator($this->participantProvider);
        $this->validator->initialize($this->context);
    }

    public function testSenderIsRecipient()
    {
        $this->participantProvider->expects($this->once())->method('getAuthenticatedParticipant')
            ->will($this->returnValue($this->sender));
        $this->context->expects($this->once())->method('addViolation')->with($this->constraint->message);
        $this->validator->validate($this->sender, $this->constraint);
    }

    public function testSenderIsNotRecipient()
    {
        $this->participantProvider->expects($this->once())->method('getAuthenticatedParticipant')
            ->will($this->returnValue($this->sender));
        $this->context->expects($this->never())->method('addViolation');
        $this->validator->validate($this->recipient, $this->constraint);
    }
}
