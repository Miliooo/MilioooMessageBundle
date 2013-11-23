<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\MessagingBundle\Validator\Constraint;

use Miliooo\Messaging\User\ParticipantInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Miliooo\Messaging\Manager\CanMessageRecipientManagerInterface;
use Miliooo\Messaging\User\ParticipantProviderInterface;

/**
 * Can message recipient validator.
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class CanMessageRecipientValidator extends ConstraintValidator
{
    /**
     * A can message recipient manager instance.
     *
     * @var CanMessageRecipientManagerInterface
     */
    private $manager;

    /**
     * A participant provider instance.
     *
     * @var ParticipantProviderInterface
     */
    private $participantProvider;

    /**
     * Constructor.
     *
     * @param CanMessageRecipientManagerInterface $manager
     * @param ParticipantProviderInterface        $participantProvider
     */
    public function __construct(
        CanMessageRecipientManagerInterface $manager,
        ParticipantProviderInterface $participantProvider
    ) {
        $this->manager = $manager;
        $this->participantProvider = $participantProvider;
    }

    /**
     * Validate.
     *
     * @param ParticipantInterface $recipient  The recipient we check
     * @param Constraint           $constraint The CanMessageRecipient constraint
     */
    public function validate($recipient, Constraint $constraint)
    {
        $loggedInUser = $this->participantProvider->getAuthenticatedParticipant();

        if ($this->manager->canMessageRecipient($loggedInUser, $recipient) === false) {
            $this->context->addViolation($constraint->message);
        }
    }
}
