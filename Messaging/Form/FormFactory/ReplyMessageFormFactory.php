<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Form\FormFactory;

use Miliooo\Messaging\Model\ThreadInterface;
use Miliooo\Messaging\User\ParticipantInterface;
use Miliooo\Messaging\Form\FormModel\ReplyMessageInterface;

/**
 * The form factory for reply messages.
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class ReplyMessageFormFactory extends AbstractMessageFormFactory
{
    /**
     * Creates a replyform from a form type with a form model set.
     *
     * It also sets some values on that form model.
     *
     * @param ThreadInterface      $thread The thread we answer to
     * @param ParticipantInterface $sender The sender of the reply
     *
     * @return Form
     */
    public function create(ThreadInterface $thread, ParticipantInterface $sender)
    {
        $formModel = $this->createNewFormModel();
        $formModel->setThread($thread);
        $formModel->setSender($sender);

        return $this->formFactory->createNamed($this->formName, $this->formType, $formModel);
    }

    /**
     * Creates a new form model object from the modelClassName
     *
     * @return ReplyMessageInterface
     */
    protected function createNewFormModel()
    {
        return new $this->modelClassName;
    }
}
