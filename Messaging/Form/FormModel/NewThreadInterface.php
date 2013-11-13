<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Form\FormModel;

use Miliooo\Messaging\User\ParticipantInterface;

/**
 * Interface for form models which create a new thread.
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
interface NewThreadInterface extends NewMessageInterface
{
    /**
     * Sets the subject
     *
     * @param string $subject
     */
    public function setSubject($subject);

    /**
     * Gets the subject of the thread
     *
     * @return string
     */
    public function getSubject();

    /**
     * Gets the recipients of the thread
     *
     * @return ParticipantInterface[] An array of participant Interfaces
     */
    public function getRecipients();
}
