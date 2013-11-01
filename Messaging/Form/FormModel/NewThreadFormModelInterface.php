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
 * Description of NewThreadFormModelInterface
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
interface NewThreadFormModelInterface extends MessageFormModelInterface
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
     * @return ParticipantInterface[] An array of participant Interfaces
     */
    public function getRecipients();
}
