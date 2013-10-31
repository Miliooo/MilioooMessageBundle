<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Form\FormFactory;

use Miliooo\Messaging\Form\FormModel\NewThreadFormModel;
use Miliooo\Messaging\User\ParticipantInterface;

/**
 * The form factory for new thread messages
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class NewThreadMessageFormFactory extends AbstractMessageFormFactory
{
    /**
     * Creates a new thread message
     *
     * @param ParticipantInterface $sender The sender
     *
     * @return Form
     */
    public function create(ParticipantInterface $sender)
    {
        $message = $this->createNewMessage();
        $message->setSender($sender);

        return $this->formFactory->createNamed($this->formName, $this->formType, $message);
    }

    /**
     * Creates the new form model object we add to the form factory
     *
     * @return NewThreadFormModel
     */
    protected function createNewMessage()
    {
        return $this->createModelInstance();
    }
}
