<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\MessagingBundle\Tests\Validator\Constraint;

use Miliooo\MessagingBundle\Validator\Constraint\CanMessageRecipientValidator;
use Miliooo\MessagingBundle\Validator\Constraint\CanMessageRecipient;
use Miliooo\Messaging\TestHelpers\ParticipantTestHelper;
use Miliooo\Messaging\User\ParticipantInterface;

/**
 * Class CanMessageRecipientValidatorTest
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class CanMessageRecipientValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CanMessageRecipientValidator
     */
    private $validator;

    /**
     * @var CanMessageRecipient
     */
    private $constraint;

    /**
     * @var ParticipantInterface
     */
    private $loggedInUser;

    /**
     * @var ParticipantInterface
     */
    private $recipient;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $manager;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $participantProvider;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $context;

    public function setUp()
    {
        $this->loggedInUser = new ParticipantTestHelper('1');
        $this->recipient = new ParticipantTestHelper('2');
        $this->constraint = new CanMessageRecipient();
        $this->participantProvider = $this->getMock('Miliooo\Messaging\User\ParticipantProviderInterface');
        $this->manager = $this->getMock('Miliooo\Messaging\Manager\CanMessageRecipientManagerInterface');
        $this->context = $this->getMock('Symfony\Component\Validator\ExecutionContextInterface');
        $this->validator = new CanMessageRecipientValidator($this->manager, $this->participantProvider);
        $this->validator->initialize($this->context);
    }

    public function testValidateNoPermission()
    {
        $this->expectsLoggedInUser();
        $this->expectsManagerCallWillReturn(false);
        $this->context->expects($this->once())->method('addViolation')->with($this->constraint->message);

        $this->validator->validate($this->recipient, $this->constraint);
    }

    public function testValidatePermission()
    {
        $this->expectsLoggedInUser();
        $this->expectsManagerCallWillReturn(true);
        $this->context->expects($this->never())->method('addViolation');

        $this->validator->validate($this->recipient, $this->constraint);
    }

    /**
     * Expects call to the manager which will return...
     *
     * @param boolean $boolean
     */
    protected function expectsManagerCallWillReturn($boolean)
    {
        $this->manager->expects($this->once())->method('canMessageRecipient')
            ->with($this->loggedInUser, $this->recipient)
            ->will($this->returnValue($boolean));
    }

    protected function expectsLoggedInUser()
    {
        $this->participantProvider->expects($this->once())->method('getAuthenticatedParticipant')
            ->will($this->returnValue($this->loggedInUser));
    }
}
