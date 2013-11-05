<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Form\FormFactory;

use Miliooo\Messaging\Form\FormModel\NewThreadInterface;
use Miliooo\Messaging\User\ParticipantInterface;
use Symfony\Component\Form\Form;

/**
 * The form factory for new thread messages
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class NewThreadMessageFormFactory extends AbstractMessageFormFactory
{
    /**
     * Creates a new thread form from a form type with a form model set.
     *
     * @param ParticipantInterface $sender The sender
     *
     * @return Form
     */
    public function create(ParticipantInterface $sender)
    {
        $formModel = $this->createNewFormModel();
        $formModel->setSender($sender);

        return $this->formFactory->createNamed($this->formName, $this->formType, $formModel);
    }

    /**
     * Creates a new form model object from the modelClassName
     *
     * @return NewThreadInterface
     */
    protected function createNewFormModel()
    {
        return new $this->modelClassName;
    }
}
