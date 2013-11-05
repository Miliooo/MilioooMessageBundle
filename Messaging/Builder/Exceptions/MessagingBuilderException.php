<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Builder\Exceptions;

/**
 * BuilderException.
 *
 * A builder exception happens when the builder is not able to build a valid
 * thread object.
 *
 * The controller should catch those exceptions, translate them and show them to the user
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class MessagingBuilderException extends \Exception
{
    /**
     * Constructor.
     *
     * @param string     $message
     * @param integer    $code
     * @param \Exception $previous
     */
    public function __construct($message, $code = 0, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
