<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\MessagingBundle\Tests\Validator\Constraint;

use Miliooo\MessagingBundle\Validator\Constraint\SelfRecipient;

/**
 * Test file for SelfRecipient
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class SelfRecipientTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Class under test
     *
     * @var SelfRecipient
     */
    private $selfRecipient;

    public function setUp()
    {
        $this->selfRecipient = new SelfRecipient();
    }

    public function testValidatedBy()
    {
        $this->assertSame('miliooo_messaging.validator.self_recipient', $this->selfRecipient->validatedBy());
    }

    public function testTargets()
    {
        $this->assertSame(SelfRecipient::PROPERTY_CONSTRAINT, $this->selfRecipient->getTargets());
    }

    public function testMessage()
    {
        $this->assertAttributeEquals('validate.author_not_recipient', 'message', $this->selfRecipient);
    }
}
