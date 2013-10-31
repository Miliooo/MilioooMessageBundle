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

/**
 * Instanciates message forms
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 */
class ReplyMessageFormFactory extends AbstractMessageFormFactory
{
    /**
     * Creates a reply message
     *
     * @param ThreadInterface      $thread The thread we answer to
     * @param ParticipantInterface $sender The sender of the reply
     *
     * @return Form
     */
    public function create(ThreadInterface $thread, ParticipantInterface $sender)
    {
        $message = $this->createModelInstance();
        $message->setThread($thread);
        $message->setSender($sender);

        return $this->formFactory->createNamed($this->formName, $this->formType, $message);
    }
}
