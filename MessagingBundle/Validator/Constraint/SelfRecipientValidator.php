<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\MessagingBundle\Validator\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Miliooo\Messaging\User\ParticipantInterface;
use Miliooo\Messaging\User\ParticipantProviderInterface;

/**
 * Class SelfRecipientValidator
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class SelfRecipientValidator extends ConstraintValidator
{
    /**
     * A participant provider instance.
     *
     * @var ParticipantProviderInterface
     */
    private $participantProvider;

    /**
     * Constructor.
     *
     * @param ParticipantProviderInterface $participantProvider
     */
    public function __construct(ParticipantProviderInterface $participantProvider)
    {
        $this->participantProvider = $participantProvider;
    }

    /**
     * @param ParticipantInterface $recipient
     * @param Constraint           $constraint
     */
    public function validate($recipient, Constraint $constraint)
    {
        if ($recipient === $this->participantProvider->getAuthenticatedParticipant()) {
            $this->context->addViolation($constraint->message);
        }
    }
}
