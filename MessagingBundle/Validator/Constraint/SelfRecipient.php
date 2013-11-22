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
 * Class SelfRecipient
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class SelfRecipient extends Constraint
{
    public $message = 'validate.author_not_recipient';

    /**
     * {@inheritdoc}
     */
    public function validatedBy()
    {
        return 'miliooo_messaging.validator.self_recipient';
    }

    /**
     * {@inheritDoc}
     */
    public function getTargets()
    {
        return self::PROPERTY_CONSTRAINT;
    }
}
