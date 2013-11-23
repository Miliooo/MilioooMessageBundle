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

/**
 * Can message recipient constraint.
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class CanMessageRecipient extends Constraint
{
    public $message = 'validate.can_not_message_recipient';

    /**
     * {@inheritdoc}
     */
    public function validatedBy()
    {
        return 'miliooo_messaging.validator.can_message_recipient';
    }

    /**
     * {@inheritDoc}
     */
    public function getTargets()
    {
        return self::PROPERTY_CONSTRAINT;
    }
}
